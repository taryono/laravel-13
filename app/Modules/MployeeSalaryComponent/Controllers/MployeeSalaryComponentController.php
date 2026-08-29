<?php

namespace App\Modules\MployeeSalaryComponent\Controllers;

use App\DataTables\MployeeSalaryComponentDataTable;
use App\Http\Controllers\MainController;
use Models\MployeeSalaryComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MployeeSalaryComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new MployeeSalaryComponent());
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
        MployeeSalaryComponentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /mployee-salary-component?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'MployeeSalaryComponent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $mployeeSalaryComponent = new MployeeSalaryComponent();
        $result = $this->getForm($mployeeSalaryComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MployeeSalaryComponent::create',
            compact('mployeeSalaryComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return MployeeSalaryComponent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data MployeeSalaryComponent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan MployeeSalaryComponent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MployeeSalaryComponent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $mployeeSalaryComponent = $this->model->findOrFail($id);
        $result = $this->getForm($mployeeSalaryComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MployeeSalaryComponent::edit',
            compact('mployeeSalaryComponent', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $mployeeSalaryComponent = $this->model->findOrFail($id);

            DB::transaction(function () use (
                $mployeeSalaryComponent,
                $validated
            ) {
                $mployeeSalaryComponent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data MployeeSalaryComponent berhasil diperbarui.',
                $mployeeSalaryComponent
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $mployeeSalaryComponent->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $mployeeSalaryComponent belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui MployeeSalaryComponent.',
                [
                    'exception' => $e,
                    'MployeeSalaryComponent_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MployeeSalaryComponent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $mployeeSalaryComponent = $this->model->findOrFail($id);

            DB::transaction(function () use ($mployeeSalaryComponent) {
                $mployeeSalaryComponent->delete();
            });

            return $this->successResponse(
                $request,
                'Data MployeeSalaryComponent berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $mployeeSalaryComponent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus MployeeSalaryComponent.',
                [
                    'exception' => $e,
                    'MployeeSalaryComponent_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MployeeSalaryComponent gagal dihapus.'
            );
        }
    } 
}
