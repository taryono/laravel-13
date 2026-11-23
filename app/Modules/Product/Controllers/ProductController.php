<?php

namespace App\Modules\Product\Controllers;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\MainController;
use Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ProductController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Product());
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
        ProductDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /product?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Product::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $product = new Product();
        $result = $this->getForm($product);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Product::create',
            compact('product', 'forms', 'groupedForms')
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
            'legal_name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Product::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Product berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Product.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Product gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $product = $this->model->findOrFail($id);
        $result = $this->getForm($product);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Product::edit',
            compact('product', 'forms', 'groupedForms')
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
            'legal_name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $product = $this->model->findOrFail($id);
            DB::transaction(function () use ($product, $validated) {
                $product->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Product berhasil diperbarui.',
                $product
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Product.',
                [
                    'exception' => $e,
                    'Product_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Product gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $product = $this->model->findOrFail($id);

            DB::transaction(function () use ($product) {
                $product->delete();
            });

            return $this->successResponse(
                $request,
                'Data Product berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $product->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Product.',
                [
                    'exception' => $e,
                    'Product_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Product gagal dihapus.'
            );
        }
    }
}
