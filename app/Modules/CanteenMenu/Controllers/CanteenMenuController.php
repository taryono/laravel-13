<?php

namespace App\Modules\CanteenMenu\Controllers;

use App\DataTables\CanteenMenuDataTable;
use App\Http\Controllers\MainController;
use Models\CanteenMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class CanteenMenuController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CanteenMenu());
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
        CanteenMenuDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /canteen-menu?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CanteenMenu::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $canteen_menu = new CanteenMenu();
        $tenants = \Models\CanteenTenant::orderBy('name')->pluck('name', 'id');

        return view(
            'CanteenMenu::create',
            compact('canteen_menu', 'tenants')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'canteen_tenant_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'cost_price' => ['required', 'string', 'max:255'],
            'photo_url' => ['required', 'string', 'max:255'],
            'is_available' => ['required', 'string', 'max:255'],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('canteen_menus', 'public');
            $validated['photo_url'] = Storage::disk('public')->url($path);
        }

        try {
            $model = DB::transaction(function () use ($validated) {
                return CanteenMenu::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenMenu berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CanteenMenu.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenMenu gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $canteen_menu = $this->model->findOrFail($id);
        $tenants = \Models\CanteenTenant::orderBy('name')->pluck('name', 'id');
        return view(
            'CanteenMenu::edit',
            compact('canteen_menu', 'tenants')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'canteen_tenant_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'cost_price' => ['required', 'string', 'max:255'],
            'photo_url' => ['required', 'string', 'max:255'],
            'is_available' => ['required', 'string', 'max:255'],
        ]);




        try {
            $canteenMenu = $this->model->findOrFail($id);
            if ($request->hasFile('photo')) {
                $this->deletePhoto($canteenMenu->photo_url);
                $path = $request->file('photo')->store('canteen_menus', 'public');
                $validated['photo_url'] = Storage::disk('public')->url($path);
            } elseif ($request->boolean('remove_photo')) {
                $this->deletePhoto($canteenMenu->photo_url);
                $validated['photo_url'] = null;
            }
            DB::transaction(function () use ($canteenMenu, $validated) {
                $canteenMenu->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenMenu berhasil diperbarui.',
                $canteenMenu
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CanteenMenu.',
                [
                    'exception' => $e,
                    'CanteenMenu_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenMenu gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $canteenMenu = $this->model->findOrFail($id);

            DB::transaction(function () use ($canteenMenu) {
                $canteenMenu->delete();
            });

            return $this->successResponse(
                $request,
                'Data CanteenMenu berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $canteenMenu->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CanteenMenu.',
                [
                    'exception' => $e,
                    'CanteenMenu_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenMenu gagal dihapus.'
            );
        }
    }

    private function deletePhoto(?string $url): void
    {
        if (!$url) {
            return;
        }

        $relative = ltrim(str_replace(Storage::disk('public')->url(''), '', $url), '/');
        Storage::disk('public')->delete($relative);
    }
}
