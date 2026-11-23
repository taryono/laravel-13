<?php

namespace App\Modules\Dictionary\Controllers;

use App\DataTables\DictionaryDataTable;
use App\Http\Controllers\MainController;
use Models\Dictionary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DictionaryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Dictionary());
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
        DictionaryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /dictionary?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Dictionary::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dictionary = new Dictionary();
        $result = $this->getForm($dictionary);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Dictionary::create',
            compact('dictionary', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255'],
            'group' => ['required', 'string', 'max:255'],
            'locale' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Dictionary::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Dictionary berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Dictionary.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Dictionary gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dictionary = $this->model->findOrFail($id);
        $result = $this->getForm($dictionary);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Dictionary::edit',
            compact('dictionary', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255'],
            'group' => ['required', 'string', 'max:255'],
            'locale' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        try {
            $dictionary = $this->model->findOrFail($id);

            DB::transaction(function () use ($dictionary, $validated) {
                $dictionary->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Dictionary berhasil diperbarui.',
                $dictionary
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $dictionary->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $dictionary belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui Dictionary.',
                [
                    'exception' => $e,
                    'Dictionary_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Dictionary gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $dictionary = $this->model->findOrFail($id);

            DB::transaction(function () use ($dictionary) {
                $dictionary->delete();
            });

            return $this->successResponse(
                $request,
                'Data Dictionary berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $dictionary->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Dictionary.',
                [
                    'exception' => $e,
                    'Dictionary_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Dictionary gagal dihapus.'
            );
        }
    }
}
