<?php

namespace App\Modules\Menu\Controllers;

use App\DataTables\MenuDataTable;
use App\Http\Controllers\MainController;
use Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MenuController extends MainController
{
    public function __construct(){
        parent::__construct(new Menu());
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
        MenuDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /menu?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Menu::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {   $model = new Menu();
        
        return view(
            'Menu::create',
            compact('model')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Menu::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Menu berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Menu.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Menu gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $menu = Menu::find($id);
        $forms = $this->getForm($menu);
        return view(
            'Menu::edit',
            compact('menu', 'forms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(
        Request $request,
        Menu $menu
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        try {

            DB::transaction(function () use (
                $menu,
                $validated
            ) {
                $menu->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Menu berhasil diperbarui.',
                $menu
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal memperbarui Menu.',
                [
                    'exception' => $e,
                    'Menu_id' => $menu->getKey(),
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Menu gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(
        Request $request,
        Menu $menu
    ): JsonResponse|RedirectResponse {
        try {

            DB::transaction(function () use ($menu) {
                $menu->delete();
            });

            return $this->successResponse(
                $request,
                'Data Menu berhasil dihapus.'
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menghapus Menu.',
                [
                    'exception' => $e,
                    'Menu_id' => $menu->getKey(),
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Menu gagal dihapus.'
            );
        }
    }
 
}