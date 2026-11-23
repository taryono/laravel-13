<?php

namespace App\Modules\PesantrenRule\Controllers;

use App\DataTables\PesantrenRuleDataTable;
use App\Http\Controllers\MainController;
use Models\PesantrenRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PesantrenRuleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PesantrenRule());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        PesantrenRuleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /pesantren-rule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PesantrenRule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pesantrenRule = new PesantrenRule();
        $result = $this->getForm($pesantrenRule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PesantrenRule::create',
            compact('pesantrenRule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'dormitory_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'legal_basis' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', 'max:255'],
            'sanction_type' => ['required', 'string', 'max:255'],
            'sanction_points' => ['required', 'string', 'max:255'],
            'effective_date' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'document_path' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PesantrenRule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PesantrenRule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PesantrenRule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenRule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pesantrenRule = $this->model->findOrFail($id);
        $result = $this->getForm($pesantrenRule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PesantrenRule::edit',
            compact('pesantrenRule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'dormitory_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'legal_basis' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', 'max:255'],
            'sanction_type' => ['required', 'string', 'max:255'],
            'sanction_points' => ['required', 'string', 'max:255'],
            'effective_date' => ['required', 'string', 'max:255'],
            'expiry_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'document_path' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $pesantrenRule = $this->model->findOrFail($id);
            DB::transaction(function () use ($pesantrenRule, $validated) {
                $pesantrenRule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PesantrenRule berhasil diperbarui.',
                $pesantrenRule
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PesantrenRule.',
                [
                    'exception' => $e,
                    'PesantrenRule_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenRule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $pesantrenRule = $this->model->findOrFail($id);

            DB::transaction(function () use ($pesantrenRule) {
                $pesantrenRule->delete();
            });

            return $this->successResponse(
                $request,
                'Data PesantrenRule berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $pesantrenRule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PesantrenRule.',
                [
                    'exception' => $e,
                    'PesantrenRule_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PesantrenRule gagal dihapus.'
            );
        }
    }
}
