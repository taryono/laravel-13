<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CanteenMenuRequest;
use App\Http\Resources\CanteenMenuResource;
use Models\CanteenMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CanteenMenuController extends Controller
{
    /** Daftar menu: pencarian nama/barcode, filter kategori & ketersediaan. */
    public function index(Request $request, int $tenant)
    {
        $menus = CanteenMenu::where('canteen_tenant_id', $tenant)
            ->when($request->query('q'), function ($query, $s) {
                $query->where(fn ($w) => $w
                    ->where('name', 'like', "%{$s}%")
                    ->orWhere('barcode', 'like', "{$s}%"));
            })
            ->when($request->query('category'), fn ($q, $c) => $q->where('category', $c))
            ->when($request->has('available'), fn ($q) => $q->where('is_available', $request->boolean('available')))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return CanteenMenuResource::collection($menus);
    }

    /** Dipanggil kolom scan di layar kasir. */
    public function byBarcode(int $tenant, string $code): JsonResponse
    {
        $menu = CanteenMenu::where('canteen_tenant_id', $tenant)
            ->where('barcode', trim($code))
            ->where('is_available', true)
            ->first();

        if (! $menu) {
            return response()->json([
                'error'   => 'barcode_not_found',
                'message' => 'Barcode tidak dikenal atau menu sedang tidak tersedia.',
            ], 404);
        }

        return (new CanteenMenuResource($menu))->response();
    }

    public function show(int $tenant, int $menu): CanteenMenuResource
    {
        return new CanteenMenuResource($this->findOrFail($tenant, $menu));
    }

    public function store(CanteenMenuRequest $request, int $tenant): JsonResponse
    {
        $menu = CanteenMenu::create([
            ...$request->validated(),
            'cost_price'        => $request->validated('cost_price') ?? 0,
            'canteen_tenant_id' => $tenant, // selalu dari URL, bukan dari input
        ]);

        return (new CanteenMenuResource($menu))->response()->setStatusCode(201);
    }

    public function update(CanteenMenuRequest $request, int $tenant, int $menu): CanteenMenuResource
    {
        $model = $this->findOrFail($tenant, $menu);
        $model->update($request->validated());

        return new CanteenMenuResource($model->refresh());
    }

    /** Tombol cepat "habis / tersedia" tanpa membuka form edit. */
    public function toggleAvailability(int $tenant, int $menu): CanteenMenuResource
    {
        $model = $this->findOrFail($tenant, $menu);
        $model->update(['is_available' => ! $model->is_available]);

        return new CanteenMenuResource($model);
    }

    /**
     * Soft delete. Menu yang pernah terjual tidak boleh dihapus permanen
     * (FK dari canteen_order_items bersifat RESTRICT), jadi riwayat tetap utuh.
     * Barcode dikosongkan agar bisa dipakai lagi oleh menu baru.
     */
    public function destroy(int $tenant, int $menu): JsonResponse
    {
        $model = $this->findOrFail($tenant, $menu);

        DB::transaction(function () use ($model) {
            $model->update(['barcode' => null, 'is_available' => false]);
            $model->delete();
        });

        return response()->json(null, 204);
    }

    private function findOrFail(int $tenant, int $menu): CanteenMenu
    {
        return CanteenMenu::where('canteen_tenant_id', $tenant)->findOrFail($menu);
    }
}