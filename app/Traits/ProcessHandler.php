<?php
namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;


trait ProcessHandler
{
    /**
     * Handle database transaction process with callback
     *
     * @param callable $callback
     * @param array $options
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public $errorMessage;
    public $successMessage;

    protected function handleProcess(callable $callback, array $options = [])
    {
        $options = array_merge([
            'successMessage' => 'Proses berhasil.',
            'errorMessage' => 'Proses gagal.',
            'redirectTo' => null,
            'redirectRoute' => null,
            'redirectParams' => [],
            'view' => null,
            'viewParams' => [],
            'save' => false,   // bungkus DB::transaction
            'audit' => false,   // tulis ke tabel Log
            'logContext' => [],      // tambahan konteks log error
        ], $options);

        $request = request();

        try {
            $result = $options['save']
                ? DB::transaction(function () use ($callback, $options, $request) {
                    $result = $callback();

                    if ($options['audit']) {

                    }

                    return $result;
                })
                : $callback();

            $successMessage = $options['successMessage'];
            $errorMessage = $options['errorMessage'];
            if ($options['activity']) {
                $this->logProcessActivity($options['activity'], 'success');
            }

            // Override pesan dari callback
            if (is_array($result)) {
                $successMessage = $result['successMessage'] ?? $successMessage;
            }

            // Response view
            if ($options['view']) {
                return view(
                    $options['view'],
                    is_array($result) ? $result : $options['viewParams']
                );
            }

            $redirectUrl = $options['redirectTo']
                ?? ($options['redirectRoute']
                    ? route($options['redirectRoute'], $options['redirectParams'])
                    : null);

            return $this->successResponse(
                $request,
                say($successMessage),
                $result,
                $redirectUrl
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(
                $request,
                say('Data tidak ditemukan.'),
                404
            );
        } catch (ValidationException $e) {
            throw $e; // biarkan Laravel menangani validasi (422 + error bag)

        } catch (Throwable $e) {
            if ($options['activity']) {
                $this->logProcessActivity($options['activity'], 'failed', $e->getMessage());
            }
            Log::error($options['errorMessage'], array_merge([
                'exception' => $e,
                'user_id' => Auth::id(),
                'input' => $request->except(['password', 'password_confirmation', '_token']),
            ], $options['logContext']));

            // Kasus khusus MySQL 3988
            if (
                $e instanceof \Illuminate\Database\QueryException
                && ($e->errorInfo[1] ?? null) == 3988
            ) {
                return $this->errorResponse(
                    $request,
                    say('Maaf, teks yang Anda input mengandung karakter yang belum didukung oleh sistem kami.'),
                    422
                );
            }

            return $this->errorResponse(
                $request,
                config('app.debug') ? $e->getMessage() : say($options['errorMessage'])
            );
        }
    }

    /** End of handleProcess */

    /** Handle Process to save Document Here */
    public function handleDocumentProcess($certification_request, $properties, $new_filename, $path, $object, $object_id, $code)
    {
        try {
            if (in_array($properties->ext, ['doc', 'docx'])) {
                /** convert doc to pdf */
                $ext = 'pdf';
            } else if (in_array($properties->ext, ['zip'])) {
                $ext = 'zip';
            } else {
                $ext = $properties->ext;
            }

            if (request()->input('id_file')) {
                $document = \Models\Document::where('id_file', request()->input('id_file'))
                    ->where('user_id', Auth::id())
                    ->first();
                if ($document) {
                    $document->deleteFile();
                }
                $document = \Models\Document::updateOrCreate([
                    'user_id' => Auth::id(),
                    'certification_request_id' => $certification_request?->id,
                    'id_file' => request()->input('id_file'),
                ], [
                    'filename' => $new_filename, // new filename
                    'form_type_id' => $certification_request?->step?->form_type_id,
                    'original_path' => $path,
                    'object' => $object,
                    'object_id' => $object_id,
                    'size' => $properties->size,
                    'mime_type' => $properties->mime,
                    'ext' => $ext,
                    'original_extension' => $properties->ext,
                    'code' => $code,
                    'name' => $properties->filename, // original file name
                    'document_category_id' => request()->input('document_category_id'),
                ]);
            } else {

                if (!empty($code)) {
                    if ($code == "facilities") {
                        $document = \Models\Document::updateOrCreate([
                            'user_id' => Auth::id(),
                            'certification_request_id' => $certification_request?->id,
                            'code' => $code, // new filename
                            'object' => $object,
                            'object_id' => $object_id,
                        ], [
                            'form_type_id' => $certification_request?->step?->form_type_id,
                            'original_path' => $path,
                            'size' => $properties->size,
                            'mime_type' => $properties->mime,
                            'ext' => $ext,
                            'original_extension' => $properties->ext,
                            'name' => $properties->filename, // original file name
                            'document_category_id' => request()->input('document_category_id'),
                        ]);
                    } else {
                        $document = \Models\Document::updateOrCreate([
                            'user_id' => Auth::id(),
                            'certification_request_id' => $certification_request?->id,
                            'code' => $code, // new filename
                        ], [
                            'form_type_id' => $certification_request?->step?->form_type_id,
                            'original_path' => $path,
                            'object' => $object,
                            'object_id' => $object_id,
                            'size' => $properties->size,
                            'mime_type' => $properties->mime,
                            'ext' => $ext,
                            'original_extension' => $properties->ext,
                            'name' => $properties->filename, // original file name
                            'document_category_id' => request()->input('document_category_id'),
                        ]);
                    }
                } else {
                    $document = \Models\Document::updateOrCreate([
                        'user_id' => Auth::id(),
                        'certification_request_id' => $certification_request?->id,
                        'filename' => $new_filename, // new filename
                    ], [
                        'form_type_id' => $certification_request?->step?->form_type_id,
                        'original_path' => $path,
                        'object' => $object,
                        'object_id' => $object_id,
                        'size' => $properties->size,
                        'mime_type' => $properties->mime,
                        'ext' => $ext,
                        'original_extension' => $properties->ext,
                        'code' => $code,
                        'name' => $properties->filename, // original file name
                        'document_category_id' => request()->input('document_category_id'),
                    ]);
                }
            }
            return $document;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            throw $th;
        }
        return null;
    }

    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
        return;
    }

    public function setSuccessMessage($message)
    {
        $this->successMessage = $message;
        return;
    }

    public function area()
    {
        try {
            $area_ids = request()->input('area_ids');
            $areas = request()->input('area');
            if (!is_null($area_ids) && !is_null($areas)) {

                if ($area_ids['country_id'] == null) {
                    $country = \Models\Country::updateOrCreate(
                        [
                            'name' => $areas['country_id'],
                        ],
                        [
                            'name' => $areas['country_id'],
                        ]
                    );
                } else {
                    $country = \Models\Country::find($area_ids['country_id']);
                }

                if ($area_ids['region_id'] == null) {
                    $region = \Models\Region::updateOrCreate(
                        [
                            'name' => $areas['region_id'],
                            'country_id' => $country->id,
                        ],
                        [
                            'name' => $areas['region_id'],

                        ]
                    );
                } else {
                    $region = \Models\Region::find($area_ids['region_id']);
                }

                if ($area_ids['city_id'] == null) {
                    $city = \Models\City::updateOrCreate(
                        [
                            'name' => $areas['city_id'],
                            'region_id' => $region->id,
                        ],
                        [
                            'name' => $areas['city_id'],
                        ]
                    );
                } else {
                    $city = \Models\City::find($area_ids['city_id']);
                }

                if ($area_ids['district_id'] == null) {
                    $district = \Models\District::updateOrCreate(
                        [
                            'name' => $areas['district_id'],
                            'city_id' => $city->id,
                        ],
                        [
                            'name' => $areas['district_id'],
                        ]
                    );
                } else {
                    $district = \Models\District::find($area_ids['district_id']);
                }

                if ($area_ids['subdistrict_id'] == null) {
                    $subdistrict = \Models\Subdistrict::updateOrCreate(
                        [
                            'name' => $areas['subdistrict_id'],
                            'district_id' => $district->id,
                        ],
                        [
                            'name' => $areas['subdistrict_id'],
                        ]
                    );
                } else {
                    $subdistrict = \Models\Subdistrict::find($area_ids['subdistrict_id']);
                }

                return [
                    'country_id' => $country?->id,
                    'region_id' => $region?->id,
                    'city_id' => $city?->id,
                    'district_id' => $district?->id,
                    'subdistrict_id' => $subdistrict?->id,
                ];
            }
            return [];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            throw $th;
        }
        return [];
    }

    protected function logProcessActivity(string $action, string $status, ?string $error = null): void
    {
        try {
            $request = request();
            $user = auth()->user();

            \Models\ActivityLog::create([
                'user_id' => $user?->getAuthIdentifier(),
                'user_role' => $user?->role,
                'action' => $action,
                'module' => class_basename($this),   // nama controller
                'status' => $status,
                'new_values' => $error ? ['error' => $error] : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Gagal menulis ActivityLog proses.', ['exception' => $e->getMessage()]);
        }
    }
}

/** How to use it in controller */

// public function store(Request $request)
// {
//     return $this->handleProcess(function () use ($request) {
//         $this->model->create($request->all());
//     }, [
//         'successMessage' => 'Data berhasil disimpan',
//         'redirectRoute'  => 'dashboard.index',
//     ]);
// }
/** or like this */

// public function store(Request $request)
// {
// return $this->handleProcess(
//     callback: function () use ($request) {
//         $certification = Certification::create($request->validated());
//         // Proses lainnya
//         return $certification;
//     },
//     options: [
//         'successMessage' => 'Proses Pengajuan Permohonan Sertifikasi Berhasil',
//         'errorMessage'   => 'Proses Pengajuan Permohonan Sertifikasi Error',
//         'redirectRoute'  => 'dashboard.timeline',
//         'redirectParams' => ['certification_id' => request()->input('certification_id')],
//         // Atau bisa juga menggunakan redirectTo langsung:
//         // 'redirectTo' => route('dashboard.timeline', request()->input('certification_id'))
//     ]
// );

// }

// public function show(Request $request)
// {
//     return $this->handleProcess(
//         callback: function() use ($request) {
//             // Data ini yang akan menjadi $result di dalam trait
//             $certification_request = CertificationRequest::find($request->id);

//             // Data yang di-return dari callback ini yang bakal di kirim ke view
//             return [
//                 'certification_request' => $certification_request,
//                 'certification'        => $certification_request->certification,
//                 'asesi'                => $certification_request->asesi,
//                 'custom_data'          => 'Contoh data tambahan'
//             ];
//         },
//         options: [
//             'view' => 'Dashboard::timeline',
//             'errorMessage' => 'Gagal memuat data sertifikasi',
//             // viewParams ini akan digabung dengan $result jika diperlukan
//             'viewParams' => [
//                 'title' => 'Timeline Sertifikasi',
//                 'menu'  => 'certification'
//             ]
//         ]
//     );
// }

// Pemakaian di controller:

// return $this->handleProcess(
//     callback: fn () => $this->importNilai($request),
//     options: [
//         'save'     => true,
//         'activity' => 'import_nilai', // ini akan diubah menjadi 'Import Nilai' di activity log berdasarkan nama method menandakan bahwa proses ini disimpan di logs
//     ]
// );