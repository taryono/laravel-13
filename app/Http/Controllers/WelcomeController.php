<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Models\TrialRegistration;
use App\Services\SliderService;
use Models\EducationLevel;
use Models\Module;
use Models\Role;
use Models\User;
use Models\School;
use Models\Position;
use Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(SliderService $sliderService)
    {
        $sliders = $sliderService->getActive();
        return view('welcome', compact('sliders'));
    }
    public function trialSignup(Request $request): View
    {
        $token = $request->session()->get('trial_token');
        $registration = $token
            ? TrialRegistration::where('token', $token)->where('status', 'in_progress')->first()
            : null;

        if (!$registration) {
            $registration = TrialRegistration::create([
                'token' => (string) Str::uuid(),
                'current_step' => 1,
            ]);
            $request->session()->put('trial_token', $registration->token);
        }

        $modules = Module::where('is_active', true)->get(['id', 'name', 'code']);
        $student_ranges = \Models\StudentRange::where('is_active', true)->get(['id', 'name']);
        $roles = Role::where('is_active', true)->where('show_to_trial', 1)->get(['id', 'name', 'code']);
        $packages = Package::where('is_active', true)->get(['id', 'name']);
        $positions = Position::where('is_active', true)->get(['id', 'name', 'code']);
        $education_levels = EducationLevel::where('is_active', true)->get(['id', 'name', 'code']);

        return view('trial_signup', compact(
            'registration',
            'modules',
            'roles',
            'student_ranges',
            'packages',
            'positions',
            'education_levels'
        ));
    }

    public function saveStep(Request $request, int $step): JsonResponse
    {
        $registration = $this->currentRegistration($request);

        switch ($step) {
            case 1:
                $data = $request->validate([
                    'nama_sekolah' => 'required|string|max:255',
                    'npsn' => 'nullable|string|max:20',
                    'education_level_id' => 'required|array|min:1',
                    'education_level_id.*' => 'string|max:50',
                    'total_student' => 'required|string|max:100',
                    'address' => 'nullable|string|max:500',
                    'nama_user' => 'required|string|max:255',
                    'position_id' => 'required|string|max:100',
                ]);

                $registration->fill([
                    'nama_sekolah' => $data['nama_sekolah'],
                    'npsn' => $data['npsn'] ?? null,
                    'total_student' => $data['total_student'],
                    'address' => $data['address'] ?? null,
                    'nama_user' => $data['nama_user'],
                ]);

                if (is_numeric($data['position_id'])) {
                    $registration->position_id = (int)$data['position_id'];
                } else {
                    $pos = Position::where('code', $data['position_id'])->first();
                    $registration->position_id = $pos?->id;
                }

                $eduLevelCodes = (array)$data['education_level_id'];
                $eduLevelIds = [];
                foreach ($eduLevelCodes as $code) {
                    $cleanCode = strtoupper(trim($code));
                    if ($cleanCode === 'PAUD') $cleanCode = 'TK';
                    $level = EducationLevel::where('code', $cleanCode)
                        ->orWhere('code', $code)
                        ->first();
                    if ($level) {
                        $eduLevelIds[] = $level->id;
                    }
                }
                if (!empty($eduLevelIds)) {
                    $registration->education_level()->sync($eduLevelIds);
                }
                break;

            case 2:
                $data = $request->validate([
                    'modul' => 'required|array|min:1',
                    'modul.*' => 'string|max:50',
                ]);

                $moduleIds = [];
                foreach ($data['modul'] as $code) {
                    $cleanCode = strtolower(trim($code));
                    $module = Module::where('code', $cleanCode)->first();
                    if (!$module) {
                        $module = Module::firstOrCreate(
                            ['code' => $cleanCode],
                            [
                                'name' => ucwords(str_replace('_', ' ', $cleanCode)),
                                'is_active' => true,
                            ]
                        );
                    }
                    $moduleIds[] = $module->id;
                }
                $registration->modules()->sync($moduleIds);
                break;

            case 3:
                $data = $request->validate([
                    'role' => 'required|array|min:1',
                    'role.*' => 'string|max:50',
                ]);

                $roleIds = [];
                foreach ($data['role'] as $code) {
                    $roleCode = strtolower(trim($code));
                    $hyphenCode = str_replace('_', '-', $roleCode);
                    $role = Role::where('code', $roleCode)
                        ->orWhere('code', $hyphenCode)
                        ->orWhere('name', $roleCode)
                        ->orWhere('name', $hyphenCode)
                        ->first();

                    if (!$role) {
                        $role = Role::firstOrCreate(
                            ['code' => $hyphenCode, 'organization_id' => 1],
                            [
                                'name' => ucwords(str_replace('-', ' ', $hyphenCode)),
                                'is_active' => true,
                                'show_to_trial' => 1,
                            ]
                        );
                    }
                    $roleIds[] = $role->id;
                }
                $registration->roles()->sync($roleIds);
                break;

            case 4:
                $request->validate([
                    'notif_whatsapp' => 'nullable',
                    'notif_email' => 'nullable',
                    'notif_sms' => 'nullable',
                ]);

                $registration->notif_whatsapp = $request->boolean('notif_whatsapp');
                $registration->notif_email = $request->boolean('notif_email');
                $registration->notif_sms = $request->boolean('notif_sms');
                break;

            default:
                return response()->json(['message' => 'Step tidak valid'], 422);
        }

        $registration->current_step = max($registration->current_step, $step + 1);
        $registration->save();

        return response()->json(['success' => true, 'next_step' => $step + 1]);
    }

    public function completeSignup(Request $request): JsonResponse
    {
        $registration = $this->currentRegistration($request);

        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = DB::transaction(function () use ($registration, $data) {
            $org = \Models\Organization::first();
            $orgId = $org ? $org->id : 1;

            $school = School::create([
                'organization_id' => $orgId,
                'name' => $registration->nama_sekolah ?? 'Sekolah Trial',
                'npsn' => $registration->npsn,
                'total_student' => (string)($registration->total_student ?? ''),
                'address' => $registration->address,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('school_education_level')) {
                    $eduIds = $registration->education_level()->pluck('education_levels.id');
                    if ($eduIds->isNotEmpty()) {
                        $school->education_level()->sync($eduIds);
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('School education level sync failed: ' . $e->getMessage());
            }

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('school_modules')) {
                    $modIds = $registration->modules()->pluck('modules.id');
                    if ($modIds->isNotEmpty()) {
                        $school->modules()->sync(
                            $modIds->mapWithKeys(fn($id) => [$id => ['activated_at' => now()]])
                        );
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('School modules sync failed: ' . $e->getMessage());
            }

            $user = \App\Models\User::create([
                'name' => $registration->nama_user ?? 'Admin Trial',
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'current_organization_id' => $orgId,
            ]);

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('organization_user')) {
                    $user->organizations()->syncWithoutDetaching([
                        $orgId => ['is_default' => 1, 'is_active' => 1]
                    ]);
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('role_user')) {
                    $regRoleIds = $registration->roles()->pluck('roles.id');
                    if ($regRoleIds->isNotEmpty()) {
                        foreach ($regRoleIds as $rId) {
                            $user->roles()->syncWithoutDetaching([
                                $rId => ['organization_id' => $orgId, 'is_default' => 1]
                            ]);
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('User role/org attachment failed: ' . $e->getMessage());
            }

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('notification_preferences')) {
                    DB::table('notification_preferences')->updateOrInsert(
                        [
                            'user_id' => $user->id,
                            'category' => 'system',
                        ],
                        [
                            'organization_id' => $orgId,
                            'whatsapp_enabled' => (bool)$registration->notif_whatsapp,
                            'email_enabled' => (bool)$registration->notif_email,
                            'sms_enabled' => (bool)$registration->notif_sms,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Notification preferences sync failed: ' . $e->getMessage());
            }

            $registration->update([
                'email' => $data['email'],
                'no_hp' => $data['no_hp'],
                'school_id' => $school->id,
                'user_id' => $user->id,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            return $user;
        });

        Auth::login($result);
        session()->forget('trial_token');

        $redirectUrl = route('dashboard');
        return response()->json(['success' => true, 'redirect' => $redirectUrl]);
    }

    private function currentRegistration(Request $request): TrialRegistration
    {
        return TrialRegistration::where('token', $request->session()->get('trial_token'))
            ->where('status', 'in_progress')
            ->firstOrFail();
    }

    public function requestDemo()
    {
        $roles = Role::all();
        $positions = Position::where('is_active', true)->pluck('name', 'id')->toArray();
        $packages = Package::where('is_active', true)->pluck('name', 'id')->toArray();
        return view('request-demo', compact('roles', 'positions', 'packages'));
    }

    public function submitRequestDemo(Request $request)
    {
        $data = $request->validate([
            'school_name' => 'required|string|max:255',
            'person_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:25',
            'package' => 'required|string|max:100',
            'demo_datetime' => 'required|date',
            'message' => 'nullable|string|max:2000',
        ], [
            'school_name.required' => 'Nama sekolah wajib diisi.',
            'person_name.required' => 'Nama penanggung jawab wajib diisi.',
            'position.required' => 'Jabatan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'package.required' => 'Silakan pilih paket demo.',
            'demo_datetime.required' => 'Tanggal dan waktu demo wajib ditentukan.',
        ]);

        try {
            \Models\DemoRequest::create([
                'school_name' => $data['school_name'],
                'person_name' => $data['person_name'],
                'position' => $data['position'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'package' => $data['package'],
                'demo_datetime' => $data['demo_datetime'],
                'message' => $data['message'] ?? null,
                'status' => 'pending',
            ]);

            return redirect()->route('request_demo.form')
                ->with('success', 'Terima kasih! Jadwal demo Anda berhasil didaftarkan. Tim kami akan segera menghubungi Anda dalam waktu 1×24 jam.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Demo Request submission failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses permintaan demo. Silakan coba beberapa saat lagi.');
        }
    }
}
