<?php
// app/Http/Controllers/Ppdb/RegistrationController.php

namespace App\Http\Controllers\Ppdb\Applicant;

use App\Http\Controllers\Controller;
use Models\Admission;
use Models\Applicant;
use Models\ApplicantAchievement;
use Models\ApplicantCategory;
use Models\ApplicantChoice;
use Models\ApplicantDomicile;
use Models\ApplicantParent;
use Models\ApplicantStatus;
use Models\ApplicantStatusHistory;
use Models\ApplicantSupportProgram;
use Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{

    public function index()
    {
        // show all admission schedules before show registration form 
        $admissions = Admission::where('is_active', 1)
            ->with([
                'academic_year',
                'school',
                'paths' => fn($q) => $q->where('is_active', 1)->orderBy('sort_order'),
                'timelines'
            ])
            ->orderBy('id', 'desc')
            ->get();

        // Ambil data jalur pendaftaran aktif (utamakan dari admission pertama atau seluruh jalur aktif)
        $firstAdmission = $admissions->first();
        $admissionPaths = $firstAdmission && $firstAdmission->paths->isNotEmpty()
            ? $firstAdmission->paths
            : \Models\AdmissionPath::where('is_active', 1)->orderBy('sort_order')->get();

        return view('ppdb.index', compact('admissions', 'admissionPaths'));
    }
    /**
     * Tampilkan form registrasi
     */
    public function create($code)
    {
        $admission = $code instanceof Admission ? $code : Admission::where('code', $code)->first();
        if (!$admission) {
            abort(404, 'Pendaftaran tidak ditemukan.');
        }

        $admission->load([
            'paths' => fn($q) => $q->where('is_active', 1)->orderBy('sort_order'),
            'school',
            'academic_year',
            'timelines',
            'quota_settings.study_program',
        ]);

        // Get data applicant
        $applicant = null;
        if (auth()->check()) {
            $applicant = Applicant::with([
                'domicile',
                'parents',
                'achievements',
                'applicant_support_programs',
                'applicant_choices.study_program',
                'documents',
                'categories',
                '_path'
            ])->where('user_id', auth()->id())
                ->where('admission_id', $admission->id)
                ->first();
        }

        // Master data
        $categories = ApplicantCategory::where('is_active', 1)
            ->where(fn($q) => $q->whereNull('admission_id')->orWhere('admission_id', $admission->id))
            ->orderBy('sort_order')->get();

        $statuses = ApplicantStatus::where('is_active', 1)->orderBy('stage_order')->get();

        // ⭐ FALLBACK: Jika timeline kosong, pakai default
        $timelines = $admission->timelines->count() > 0
            ? $admission->timelines
            : collect($admission->default_timelines)->map(fn($t) => (object) $t);

        return view('ppdb.form_registrasi', compact(
            'admission',
            'applicant',
            'categories',
            'statuses',
            'timelines'
        ));
    }


    /**
     * Simpan pendaftaran baru
     */
    public function store(Request $request, $admission)
    {
        if (!$admission instanceof Admission) {
            $admission = Admission::where('code', $admission)->firstOrFail();
        }

        $validated = $this->validateRegistration($request);

        DB::beginTransaction();
        try {
            // 1. Buat applicant
            $applicant = Applicant::create([
                'school_id'           => $admission->school_id,
                'admission_id'        => $admission->id,
                'admission_path_id'   => $validated['admission_path_id'] ?? null,
                'name'                => strtoupper($validated['name']),
                'nik'                 => $validated['nik'],
                'nisn'                => $validated['nisn'] ?? null,
                'birth_place'         => $validated['birth_place'],
                'birth_date'          => $validated['birth_date'],
                'gender'              => $validated['gender'],
                'address'             => $validated['address'],
                'phone'               => $validated['phone'] ?? null,
                'email'               => $validated['email'] ?? null,
                'parent_name'         => $validated['parent_name'],
                'parent_phone'        => $validated['parent_phone'],
                'parent_email'        => $validated['parent_email'] ?? null,
                'previous_school'     => $validated['previous_school'],
                'extracurriculars'    => $validated['extracurriculars'] ?? null,
                'foreign_language'    => $validated['foreign_language'] ?? null,
                'special_skill'       => $validated['special_skill'] ?? null,
                'agree_test'          => $request->boolean('agree_test'),
                'registration_number' => $this->generateRegistrationNumber($admission),
                'status'              => 'DRAFT',
                'registered_at'       => now(),
                'created_by'          => Auth::id(),
            ]);

            // 2. Simpan domicile
            ApplicantDomicile::create([
                'applicant_id'          => $applicant->id,
                'address'               => $validated['address'],
                'province_id'           => $validated['province_id'] ?? null,
                'regency_id'            => $validated['regency_id'] ?? null,
                'district_id'           => $validated['district_id'] ?? null,
                'village_id'            => $validated['village_id'] ?? null,
                'latitude'              => $validated['latitude'] ?? null,
                'longitude'             => $validated['longitude'] ?? null,
                'distance_to_school_m'  => $validated['distance'] ?? null,
                'is_primary'            => 1,
                'created_by'            => Auth::id(),
            ]);

            // 3. Simpan orang tua
            foreach ($validated['parents'] ?? [] as $parent) {
                ApplicantParent::create([
                    'applicant_id'        => $applicant->id,
                    'relation_type'       => $parent['relation_type'],
                    'name'                => $parent['name'],
                    'nik'                 => $parent['nik'] ?? null,
                    'education_level'     => $parent['education_level'] ?? null,
                    'job'                 => $parent['job'] ?? null,
                    'income_range'        => $parent['income_range'] ?? null,
                    'phone'               => $parent['phone'] ?? null,
                    'email'               => $parent['email'] ?? null,
                    'is_primary_contact'  => $parent['is_primary_contact'] ?? 0,
                    'created_by'          => Auth::id(),
                ]);
            }

            // 4. Simpan pilihan sekolah (applicant_choices)
            foreach ($validated['choices'] ?? [] as $order => $choice) {
                if (!empty($choice['study_program_id'])) {
                    ApplicantChoice::create([
                        'applicant_id'     => $applicant->id,
                        'study_program_id' => $choice['study_program_id'],
                        'choice_order'     => $order + 1,
                    ]);
                }
            }

            // 5. Simpan kategori (pivot)
            if (!empty($validated['category_ids'])) {
                $applicant->categories()->sync($validated['category_ids']);
            }

            // 6. Simpan prestasi (opsional)
            if (!empty($validated['achievements'])) {
                foreach ($validated['achievements'] as $ach) {
                    ApplicantAchievement::create([
                        'applicant_id'     => $applicant->id,
                        'achievement_type' => $ach['type'] ?? 'academic',
                        'title'            => $ach['title'],
                        'level'            => $ach['level'],
                        'rank'             => $ach['rank'] ?? null,
                        'year'             => $ach['year'] ?? null,
                        'certificate_url'  => $ach['certificate_url'] ?? null,
                        'created_by'       => Auth::id(),
                    ]);
                }
            }

            // 7. Simpan program pendukung (KIP, PIP, dll)
            if (!empty($validated['applicant_support_programs'])) {
                foreach ($validated['applicant_support_programs'] as $prog) {
                    ApplicantSupportProgram::create([
                        'applicant_id'  => $applicant->id,
                        'program_type'  => $prog['type'],
                        'card_number'   => $prog['card_number'] ?? null,
                        'program_name'  => $prog['program_name'] ?? null,
                        'created_by'    => Auth::id(),
                    ]);
                }
            }

            // 8. Set status awal + history
            $defaultStatus = ApplicantStatus::where('is_default', 1)
                ->where(fn($q) => $q->whereNull('school_id')->orWhere('school_id', $admission->school_id))
                ->first();

            if ($defaultStatus) {
                $applicant->status = $defaultStatus->code;
                $applicant->save();

                ApplicantStatusHistory::create([
                    'applicant_id'        => $applicant->id,
                    'from_status_id'      => null,
                    'to_status_id'        => $defaultStatus->id,
                    'changed_by'          => Auth::id(),
                    'changed_by_system'   => 0,
                    'reason'              => 'Pendaftaran awal dibuat',
                ]);
            }

            DB::commit();

            return redirect()
                ->route('ppdb.register.edit', $applicant->registration_number)
                ->with('success', 'Pendaftaran berhasil dibuat! Silakan lengkapi dokumen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Tampilkan form registrasi untuk pendaftar yang sudah ada
     */
    public function edit($registrationNumber)
    {
        $applicant = Applicant::where('registration_number', $registrationNumber)->firstOrFail();
        $admission = $applicant->admission ?? Admission::firstOrFail();

        $admission->load([
            'paths' => fn($q) => $q->where('is_active', 1)->orderBy('sort_order'),
            'school',
            'academic_year',
            'timelines',
            'quota_settings.study_program',
        ]);

        $applicant->load([
            'domicile',
            'parents',
            'achievements',
            'applicant_support_programs',
            'applicant_choices.study_program',
            'documents',
            'categories',
            '_path'
        ]);

        $categories = ApplicantCategory::where('is_active', 1)
            ->where(fn($q) => $q->whereNull('admission_id')->orWhere('admission_id', $admission->id))
            ->orderBy('sort_order')->get();

        $statuses = ApplicantStatus::where('is_active', 1)->orderBy('stage_order')->get();

        $timelines = $admission->timelines && $admission->timelines->count() > 0
            ? $admission->timelines
            : collect($admission->default_timelines)->map(fn($t) => (object) $t);

        return view('ppdb.form_registrasi', compact(
            'admission',
            'applicant',
            'categories',
            'statuses',
            'timelines'
        ));
    }

    /**
     * Simpan / Autosave Draft Pendaftaran
     */
    public function saveDraft(Request $request, $applicant = null)
    {
        $applicantModel = null;
        if ($applicant instanceof Applicant) {
            $applicantModel = $applicant;
        } elseif (!empty($applicant)) {
            $applicantModel = Applicant::find($applicant) ?? Applicant::where('registration_number', $applicant)->first();
        } elseif ($request->filled('applicant_id')) {
            $applicantModel = Applicant::find($request->input('applicant_id'));
        }

        $admissionId = $request->input('admission_id');
        $admission = $admissionId ? Admission::find($admissionId) : (Admission::where('is_active', 1)->first() ?? Admission::latest()->first());

        DB::beginTransaction();
        try {
            $data = [
                'name'                => $request->filled('name') ? strtoupper($request->input('name')) : ($applicantModel?->name ?? 'DRAFT PENDAFTAR'),
                'nik'                 => $request->input('nik') ?? $applicantModel?->nik,
                'nisn'                => $request->input('nisn') ?? $applicantModel?->nisn,
                'birth_place'         => $request->input('birth_place') ?? $applicantModel?->birth_place,
                'birth_date'          => $request->input('birth_date') ?? $applicantModel?->birth_date,
                'gender'              => $request->input('gender') ?? $applicantModel?->gender,
                'address'             => $request->input('address') ?? $applicantModel?->address,
                'phone'               => $request->input('phone') ?? $applicantModel?->phone,
                'email'               => $request->input('email') ?? $applicantModel?->email,
                'parent_name'         => $request->input('parent_name') ?? $applicantModel?->parent_name,
                'parent_phone'        => $request->input('parent_phone') ?? $applicantModel?->parent_phone,
                'parent_email'        => $request->input('parent_email') ?? $applicantModel?->parent_email,
                'previous_school'     => $request->input('previous_school') ?? $applicantModel?->previous_school,
                'admission_path_id'   => $request->input('admission_path_id') ?? $applicantModel?->admission_path_id,
                'extracurriculars'    => $request->input('extracurriculars') ?? $applicantModel?->extracurriculars,
                'foreign_language'    => $request->input('foreign_language') ?? $applicantModel?->foreign_language,
                'special_skill'       => $request->input('special_skill') ?? $applicantModel?->special_skill,
                'agree_test'          => $request->boolean('agree_test'),
            ];

            if ($applicantModel) {
                $applicantModel->update($data);
            } else {
                $data['school_id'] = $admission ? $admission->school_id : 1;
                $data['admission_id'] = $admission ? $admission->id : 1;
                $data['registration_number'] = $this->generateRegistrationNumber($admission ?? Admission::first());
                $data['status'] = 'DRAFT';
                $data['registered_at'] = now();
                $data['created_by'] = Auth::id();
                $applicantModel = Applicant::create($data);
            }

            // Simpan / update domisili
            if ($request->filled('address') || $request->filled('latitude')) {
                ApplicantDomicile::updateOrCreate(
                    ['applicant_id' => $applicantModel->id],
                    [
                        'address'              => $request->input('address') ?? $applicantModel->address,
                        'latitude'             => $request->input('latitude'),
                        'longitude'            => $request->input('longitude'),
                        'distance_to_school_m' => $request->input('distance'),
                        'is_primary'           => 1,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success'             => true,
                'message'             => 'Draft berhasil disimpan.',
                'applicant_id'        => $applicantModel->id,
                'registration_number' => $applicantModel->registration_number,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan draft: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update data pendaftar (draft / revisi)
     */
    public function update(Request $request, Applicant $applicant)
    {
        // Cek otorisasi jika user sedang login
        if (Auth::check() && $applicant->created_by && $applicant->created_by !== Auth::id()) {
            if (!Auth::user()->isSuperUser() && !Auth::user()->hasAnyRole(['admin', 'admin-ppdb', 'super-admin'])) {
                abort(403);
            }
        }

        $validated = $this->validateRegistration($request);

        DB::beginTransaction();
        try {
            $applicant->update([
                'name'              => strtoupper($validated['name']),
                'nik'               => $validated['nik'],
                'nisn'              => $validated['nisn'] ?? $applicant->nisn,
                'admission_path_id' => $validated['admission_path_id'] ?? $applicant->admission_path_id,
                'birth_place'       => $validated['birth_place'],
                'birth_date'        => $validated['birth_date'],
                'gender'            => $validated['gender'],
                'address'           => $validated['address'],
                'phone'             => $validated['phone'] ?? null,
                'email'             => $validated['email'] ?? null,
                'parent_name'       => $validated['parent_name'],
                'parent_phone'      => $validated['parent_phone'],
                'parent_email'      => $validated['parent_email'] ?? $applicant->parent_email,
                'previous_school'   => $validated['previous_school'],
                'extracurriculars'  => $validated['extracurriculars'] ?? $applicant->extracurriculars,
                'foreign_language'  => $validated['foreign_language'] ?? $applicant->foreign_language,
                'special_skill'     => $validated['special_skill'] ?? $applicant->special_skill,
                'agree_test'        => $request->boolean('agree_test'),
                'updated_by'        => Auth::id(),
            ]);

            // Update domicile
            $applicant->domicile()->updateOrCreate(
                ['applicant_id' => $applicant->id],
                [
                    'address'              => $validated['address'],
                    'latitude'             => $validated['latitude'] ?? null,
                    'longitude'            => $validated['longitude'] ?? null,
                    'distance_to_school_m' => $validated['distance'] ?? null,
                    'updated_by'           => Auth::id(),
                ]
            );

            // Sync kategori
            if (isset($validated['category_ids'])) {
                $applicant->categories()->sync($validated['category_ids']);
            }

            // Sync choices
            $applicant->choices()->delete();
            foreach ($validated['choices'] ?? [] as $order => $choice) {
                if (!empty($choice['study_program_id'])) {
                    ApplicantChoice::create([
                        'applicant_id'     => $applicant->id,
                        'study_program_id' => $choice['study_program_id'],
                        'choice_order'     => $order + 1,
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Submit final (kunci data)
     */
    public function submit(Request $request, Applicant $applicant)
    {
        // Validasi pakta integritas
        $request->validate([
            'agree_integritas' => 'accepted',
        ]);

        // Cek kelengkapan dokumen
        $mandatoryDocs = ['akta_kelahiran', 'kartu_keluarga', 'rapor'];
        $uploadedDocs = $applicant->documents()
            ->whereIn('document_type', $mandatoryDocs)
            ->where('verified', '!=', 2)
            ->pluck('document_type')->toArray();

        $missing = array_diff($mandatoryDocs, $uploadedDocs);
        if (!empty($missing)) {
            return back()->withErrors(['documents' => 'Dokumen belum lengkap: ' . implode(', ', $missing)]);
        }

        DB::beginTransaction();
        try {
            // Update status
            $submittedStatus = ApplicantStatus::where('code', 'SUBMITTED')
                ->where(fn($q) => $q->whereNull('school_id')->orWhere('school_id', $applicant->school_id))
                ->first();

            if ($submittedStatus) {
                $oldStatus = $applicant->status;
                $applicant->update([
                    'status'     => $submittedStatus->code,
                    'updated_by' => Auth::id(),
                ]);

                ApplicantStatusHistory::create([
                    'applicant_id'      => $applicant->id,
                    'from_status_id'    => optional(ApplicantStatus::where('code', $oldStatus)->first())->id,
                    'to_status_id'      => $submittedStatus->id,
                    'changed_by'        => Auth::id(),
                    'reason'            => 'Pendaftaran disubmit oleh pendaftar',
                    'metadata'          => ['ip' => $request->ip(), 'user_agent' => $request->userAgent()],
                ]);
            }

            // Generate invoice
            $this->generateInvoice($applicant);

            DB::commit();

            if (($applicant->admission->registration_fee ?? 0) > 0) {
                return redirect()->route('ppdb.invoice', $applicant->id)
                    ->with('success', 'Pendaftaran berhasil dikirim! Silakan selesaikan pembayaran biaya pendaftaran.');
            }

            return redirect()->route('ppdb.status')
                ->with('success', 'Pendaftaran berhasil dikirim! Kode verifikasi telah dikirim ke WhatsApp.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Generate nomor registrasi unik
     */
    private function generateRegistrationNumber(Admission $admission): string
    {
        $year = date('Y');
        $prefix = strtoupper($admission->code) . $year;

        $last = Applicant::where('admission_id', $admission->id)
            ->whereYear('created_at', $year)
            ->lockForUpdate()
            ->count();

        return $prefix . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate invoice untuk pendaftar
     */
    private function generateInvoice(Applicant $applicant): void
    {
        $invoiceNumber = 'INV-' . $applicant->registration_number;

        $invoice = \Models\ApplicantInvoice::updateOrCreate(
            ['applicant_id' => $applicant->id],
            [
                'school_id'       => $applicant->school_id,
                'admission_id'    => $applicant->admission_id,
                'invoice_number'  => $invoiceNumber,
                'invoice_type'    => 'registration',
                'due_date'        => now()->addDays(3),
                'total_amount'    => $applicant->admission->registration_fee ?? 0,
                'final_amount'    => $applicant->admission->registration_fee ?? 0,
                'balance_amount'  => $applicant->admission->registration_fee ?? 0,
                'status'          => 'unpaid',
                'created_by'      => Auth::id(),
            ]
        );

        // Item invoice
        \Models\ApplicantInvoiceItem::updateOrCreate(
            [
                'applicant_invoice_id' => $invoice->id,
                'name'                 => 'Biaya Pendaftaran PPDB',
            ],
            [
                'amount' => $applicant->admission->registration_fee ?? 0,
            ]
        );
    }

    /**
     * Validasi input pendaftaran
     */
    private function validateRegistration(Request $request): array
    {
        return $request->validate([
            // Data pribadi
            'nik'              => ['required', 'digits:16'],
            'nisn'             => ['required', 'digits:10'],
            'name'             => ['required', 'string', 'max:150'],
            'birth_place'      => ['required', 'string', 'max:100'],
            'birth_date'       => ['required', 'date', 'before:today'],
            'gender'           => ['required', Rule::in(['L', 'P'])],
            'address'          => ['required', 'string'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'email'            => ['nullable', 'email', 'max:100'],

            // Domisili
            'province_id'      => ['nullable', 'integer'],
            'regency_id'       => ['nullable', 'integer'],
            'district_id'      => ['nullable', 'integer'],
            'village_id'       => ['nullable', 'integer'],
            'latitude'         => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'        => ['nullable', 'numeric', 'between:-180,180'],
            'distance'         => ['nullable', 'numeric'],

            // Asal sekolah
            'previous_school'  => ['required', 'string', 'max:150'],

            // Jalur & pilihan
            'admission_path_id' => ['required', 'exists:admission_paths,id'],
            'category_ids'     => ['nullable', 'array'],
            'category_ids.*'   => ['exists:applicant_categories,id'],
            'choices'          => ['required', 'array', 'min:1', 'max:2'],
            'choices.*.study_program_id' => ['required', 'exists:study_programs,id'],

            // Minat bakat
            'extracurriculars' => ['nullable', 'array'],
            'foreign_language' => ['nullable', 'string', 'max:50'],
            'special_skill'    => ['nullable', 'string', 'max:200'],
            'agree_test'       => ['nullable', 'boolean'],

            // Orang tua
            'parent_name'      => ['required', 'string', 'max:150'],
            'parent_phone'     => ['required', 'string', 'max:20'],
            'parent_email'     => ['nullable', 'email', 'max:100'],
            'parents'          => ['nullable', 'array'],
            'parents.*.relation_type' => ['required_with:parents', Rule::in(['father', 'mother', 'guardian'])],
            'parents.*.name'   => ['required_with:parents', 'string', 'max:150'],
            'parents.*.nik'    => ['nullable', 'digits:16'],
            'parents.*.education_level' => ['nullable', 'string', 'max:50'],
            'parents.*.job'    => ['nullable', 'string', 'max:100'],
            'parents.*.income_range' => ['nullable', Rule::in(['below_1m', '1m_2m', '2m_5m', '5m_10m', 'above_10m', 'unknown'])],

            // Prestasi & program pendukung
            'achievements'     => ['nullable', 'array'],
            'achievements.*.title' => ['required_with:achievements', 'string', 'max:200'],
            'achievements.*.level' => ['required_with:achievements', Rule::in(['school', 'district', 'regency', 'province', 'national', 'international'])],
            'achievements.*.type'  => ['nullable', Rule::in(['academic', 'non_academic', 'sport', 'art', 'science', 'other'])],

            'applicant_support_programs' => ['nullable', 'array'],
            'applicant_support_programs.*.type' => ['required_with:applicant_support_programs', Rule::in(['kip', 'pip', 'ketm', 'abk', 'yatim', 'lainnya'])],
            'applicant_support_programs.*.card_number' => ['nullable', 'string', 'max:100'],
        ]);
    }

    /**
     * AJAX: Get schools by admission
     */
    public function getSchools(Admission $admission)
    {
        $schools = \Models\School::whereHas('admissions', fn($q) => $q->where('id', $admission->id))
            ->select('id', 'name', 'npsn')->get();
        return response()->json($schools);
    }

    /**
     * AJAX: Get paths by admission
     */
    public function getPaths(Admission $admission)
    {
        $paths = $admission->paths()->where('is_active', 1)
            ->select('id', 'code', 'name', 'quota', 'description')->get();
        return response()->json($paths);
    }

    /**
     * AJAX: Get study programs by school
     */
    public function getPrograms(\Models\School $school)
    {
        $programs = StudyProgram::where('school_id', $school->id)
            ->orWhereNull('school_id')
            ->select('id', 'name', 'code', 'degree_level')->get();
        return response()->json($programs);
    }

    /**
     * AJAX: Geocode address
     */
    public function geocode(Request $request)
    {
        $request->validate(['address' => 'required|string']);
        // Implementasi geocoding (Google Maps / OpenStreetMap)
        // ...
        return response()->json(['latitude' => null, 'longitude' => null]);
    }

    /**
     * Tampilkan form cek status (redirect ke section cek status di landing page)
     */
    public function statusForm()
    {
        return redirect()->to(route('ppdb.index') . '#cek-status');
    }

    /**
     * Proses pencarian status pendaftar
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
        ]);

        $applicant = Applicant::with([
            'statusRel',
            'admission',
            'applicant_choices.study_program',
            '_path'
        ])
            ->where('registration_number', trim($request->registration_number))
            ->orWhere('nik', trim($request->registration_number))
            ->first();

        if (!$applicant) {
            return redirect()->to(route('ppdb.index') . '#cek-status')
                ->withInput()
                ->with('status_error', 'Data tidak ditemukan! Mohon periksa kembali Nomor Registrasi atau NIK Anda.');
        }

        return redirect()->to(route('ppdb.index') . '#cek-status')
            ->withInput()
            ->with('applicant_result', $applicant);
    }

    /**
     * Cek status pendaftaran
     */
    public function status(Request $request)
    {
        $request->validate(['registration_number' => 'required|string']);

        $applicant = Applicant::with(['status', 'admission', 'applicant_choices.study_program'])
            ->where('registration_number', $request->registration_number)
            ->firstOrFail();

        return view('ppdb.status', compact('applicant'));
    }
}
