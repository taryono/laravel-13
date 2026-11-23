<?php

namespace App\Modules\AssetDisposal\Controllers;

use App\DataTables\AssetDisposalDataTable;
use App\Http\Controllers\MainController;
use Models\AssetDisposal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssetDisposalController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssetDisposal());
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
        AssetDisposalDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /asset-disposal?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssetDisposal::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assetDisposal = new AssetDisposal();
        $result = $this->getForm($assetDisposal);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetDisposal::create',
            compact('assetDisposal', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fixed_asset_id' => ['required', 'integer'],
            'disposal_number' => ['required', 'string', 'max:255'],
            'disposal_date' => ['required', 'string', 'max:255'],
            'disposal_type' => ['required', 'string', 'max:255'],
            'book_value' => ['required', 'string', 'max:255'],
            'proceeds_amount' => ['required', 'string', 'max:255'],
            'gain_or_loss' => ['required', 'string', 'max:255'],
            'disposal_reason' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssetDisposal::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetDisposal berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssetDisposal.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDisposal gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assetDisposal = $this->model->findOrFail($id);
        $result = $this->getForm($assetDisposal);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetDisposal::edit',
            compact('assetDisposal', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'fixed_asset_id' => ['required', 'integer'],
            'disposal_number' => ['required', 'string', 'max:255'],
            'disposal_date' => ['required', 'string', 'max:255'],
            'disposal_type' => ['required', 'string', 'max:255'],
            'book_value' => ['required', 'string', 'max:255'],
            'proceeds_amount' => ['required', 'string', 'max:255'],
            'gain_or_loss' => ['required', 'string', 'max:255'],
            'disposal_reason' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assetDisposal = $this->model->findOrFail($id);
            DB::transaction(function () use ($assetDisposal, $validated) {
                $assetDisposal->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetDisposal berhasil diperbarui.',
                $assetDisposal
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AssetDisposal.',
                [
                    'exception' => $e,
                    'AssetDisposal_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDisposal gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assetDisposal = $this->model->findOrFail($id);

            DB::transaction(function () use ($assetDisposal) {
                $assetDisposal->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssetDisposal berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $assetDisposal->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssetDisposal.',
                [
                    'exception' => $e,
                    'AssetDisposal_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDisposal gagal dihapus.'
            );
        }
    }
}
