<?php

namespace App\Modules\DigitalLibraryItem\Controllers;

use App\DataTables\DigitalLibraryItemDataTable;
use App\Http\Controllers\MainController;
use Models\DigitalLibraryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DigitalLibraryItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DigitalLibraryItem());
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
        DigitalLibraryItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /digital-library-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DigitalLibraryItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $digitalLibraryItem = new DigitalLibraryItem();
        $result = $this->getForm($digitalLibraryItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DigitalLibraryItem::create',
            compact('digitalLibraryItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'library_book_id' => ['required', 'integer'],
            'book_category_id' => ['required', 'integer'],
            'book_publisher_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'item_type' => ['required', 'string', 'max:255'],
            'format' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:255'],
            'issn' => ['required', 'string', 'max:255'],
            'doi' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'editor' => ['required', 'string', 'max:255'],
            'translator' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'edition' => ['required', 'string', 'max:255'],
            'volume' => ['required', 'string', 'max:255'],
            'issue' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'string', 'max:255'],
            'publication_date' => ['required', 'string', 'max:255'],
            'page_count' => ['required', 'string', 'max:255'],
            'duration_seconds' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'abstract' => ['required', 'string', 'max:255'],
            'keywords' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'cover_path' => ['required', 'string', 'max:255'],
            'external_url' => ['required', 'string', 'max:255'],
            'access_type' => ['required', 'string', 'max:255'],
            'access_level' => ['required', 'string', 'max:255'],
            'license_type' => ['required', 'string', 'max:255'],
            'license_expires_at' => ['required', 'string', 'max:255'],
            'max_concurrent_access' => ['required', 'string', 'max:255'],
            'download_allowed' => ['required', 'string', 'max:255'],
            'print_allowed' => ['required', 'string', 'max:255'],
            'copy_allowed' => ['required', 'string', 'max:255'],
            'is_featured' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'total_views' => ['required', 'string', 'max:255'],
            'total_downloads' => ['required', 'string', 'max:255'],
            'rating_average' => ['required', 'string', 'max:255'],
            'rating_count' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DigitalLibraryItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DigitalLibraryItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DigitalLibraryItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalLibraryItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $digitalLibraryItem = $this->model->findOrFail($id);
        $result = $this->getForm($digitalLibraryItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DigitalLibraryItem::edit',
            compact('digitalLibraryItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'library_book_id' => ['required', 'integer'],
            'book_category_id' => ['required', 'integer'],
            'book_publisher_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'item_type' => ['required', 'string', 'max:255'],
            'format' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:255'],
            'issn' => ['required', 'string', 'max:255'],
            'doi' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'editor' => ['required', 'string', 'max:255'],
            'translator' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'edition' => ['required', 'string', 'max:255'],
            'volume' => ['required', 'string', 'max:255'],
            'issue' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'string', 'max:255'],
            'publication_date' => ['required', 'string', 'max:255'],
            'page_count' => ['required', 'string', 'max:255'],
            'duration_seconds' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'abstract' => ['required', 'string', 'max:255'],
            'keywords' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'cover_path' => ['required', 'string', 'max:255'],
            'external_url' => ['required', 'string', 'max:255'],
            'access_type' => ['required', 'string', 'max:255'],
            'access_level' => ['required', 'string', 'max:255'],
            'license_type' => ['required', 'string', 'max:255'],
            'license_expires_at' => ['required', 'string', 'max:255'],
            'max_concurrent_access' => ['required', 'string', 'max:255'],
            'download_allowed' => ['required', 'string', 'max:255'],
            'print_allowed' => ['required', 'string', 'max:255'],
            'copy_allowed' => ['required', 'string', 'max:255'],
            'is_featured' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'total_views' => ['required', 'string', 'max:255'],
            'total_downloads' => ['required', 'string', 'max:255'],
            'rating_average' => ['required', 'string', 'max:255'],
            'rating_count' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $digitalLibraryItem = $this->model->findOrFail($id);
            DB::transaction(function () use ($digitalLibraryItem, $validated) {
                $digitalLibraryItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DigitalLibraryItem berhasil diperbarui.',
                $digitalLibraryItem
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DigitalLibraryItem.',
                [
                    'exception' => $e,
                    'DigitalLibraryItem_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalLibraryItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $digitalLibraryItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($digitalLibraryItem) {
                $digitalLibraryItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data DigitalLibraryItem berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $digitalLibraryItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DigitalLibraryItem.',
                [
                    'exception' => $e,
                    'DigitalLibraryItem_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DigitalLibraryItem gagal dihapus.'
            );
        }
    }
}
