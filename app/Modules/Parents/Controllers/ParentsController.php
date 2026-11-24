<?php

namespace App\Modules\Parents\Controllers;

use App\DataTables\ParentsDataTable;
use App\Http\Controllers\MainController;
use Models\Parents;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ParentsController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Parents());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        ParentsDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /parents?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Parents::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $parents = new Parents();
        $result = $this->getForm($parents);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Parents::create',
            compact('parents', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $this->validated($request);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Parents::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Parents berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Parents.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Parents gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $parents = $this->model->findOrFail($id);
        $result = $this->getForm($parents);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Parents::edit',
            compact('parents', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validated($request, $id);

        try {
            $parents = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $parents,
                $validated
            ) {
                $parents->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Parents berhasil diperbarui.',
                $parents
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Parents.',
                [
                    'exception' => $e,
                    'Parents_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Parents gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $parents = $this->model->findOrFail($id);

            DB::transaction(function () use ($parents) {
                $parents->delete();
            });

            return $this->successResponse(
                $request,
                'Data Parents berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $parents->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Parents.',
                [
                    'exception' => $e,
                    'Parents_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Parents gagal dihapus.'
            );
        }
    } 

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        $orgId = $request->input('organization_id') ?? auth()->user()?->organization_id;

        $data = $request->validate([
            'organization_id'    => ['required', 'exists:organizations,id'],
            'name'               => ['required', 'string', 'max:150'],
            'nik'                => ['nullable', 'digits:16',
                Rule::unique('parents', 'nik')->where('organization_id', $orgId)->ignore($ignoreId)
            ],
            'gender'             => ['nullable', Rule::in(['L', 'P'])],
            'birth_place'        => ['nullable', 'string', 'max:100'],
            'birth_year'         => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'birth_date'         => ['nullable', 'date'],
            'religion'           => ['nullable', 'string', 'max:20'],
            'nationality'        => ['nullable', 'string', 'max:3'],
            'relation'           => ['required', Rule::in(['father', 'mother', 'guardian', 'other'])],
            'relation_detail'    => ['nullable', 'string', 'max:50'],
            'phone'              => ['required', 'string', 'max:20'],
            'home_phone'         => ['nullable', 'string', 'max:20'],
            'whatsapp'           => ['nullable', 'string', 'max:20'],
            'email'              => ['nullable', 'email', 'max:100'],
            'address'            => ['nullable', 'string'],
            'address_line'       => ['nullable', 'string'],
            'rt'                 => ['nullable', 'string', 'max:5'],
            'rw'                 => ['nullable', 'string', 'max:5'],
            'village'            => ['nullable', 'string', 'max:100'],
            'district'           => ['nullable', 'string', 'max:100'],
            'city'               => ['nullable', 'string', 'max:100'],
            'province'           => ['nullable', 'string', 'max:100'],
            'postal_code'        => ['nullable', 'string', 'max:10'],
            'education_level'    => ['nullable', Rule::in(['Tidak Sekolah','SD','SMP','SMA/SMK','D1-D3','S1','S2','S3'])],
            'occupation'         => ['nullable', 'string', 'max:100'],
            'income_range'       => ['nullable', Rule::in(['< 1 juta','1 - 3 juta','3 - 5 juta','5 - 10 juta','> 10 juta'])],
            'workplace'          => ['nullable', 'string', 'max:150'],
            'position'           => ['nullable', 'string', 'max:100'],
            'special_needs'      => ['nullable', 'string'],
            'death_date'         => ['nullable', 'required_if:is_alive,0', 'date'],
            'family_card_number' => ['nullable', 'digits:16'],
            'notes'              => ['nullable', 'string'],
        ]);

        // Normalisasi boolean (checkbox)
        foreach (['is_biological','is_primary_contact','lives_with_student','is_alive','is_active'] as $f) {
            $data[$f] = $request->boolean($f);
        }

        return $data;
    }
}
