<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\PurchaseException;
use App\Http\Controllers\Controller;
use App\Services\PurchaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Route (routes/api.php):
 *
 *   Route::middleware('auth:sanctum')->post(
 *       '/canteen/{tenant}/purchase',
 *       [CanteenPurchaseController::class, 'store']
 *   );
 *
 * Header wajib dari aplikasi kasir: Idempotency-Key (UUID baru per transaksi,
 * dipakai ulang HANYA saat mengirim ulang transaksi yang sama).
 */
class CanteenPurchaseController extends Controller
{
    public function store(Request $request, int $tenant, PurchaseService $service): JsonResponse
    {
        $data = $request->validate([
            'card_uid'         => ['required', 'string', 'max:64'],
            'items'            => ['required', 'array', 'min:1', 'max:50'],
            'items.*.menu_id'  => ['required', 'integer', 'min:1'],
            'items.*.qty'      => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $key = (string) $request->header('Idempotency-Key');
        if ($key === '' || strlen($key) > 64) {
            return response()->json([
                'error'   => 'idempotency_key_required',
                'message' => 'Header Idempotency-Key wajib diisi (maks 64 karakter).',
            ], 422);
        }

        try {
            $result = $service->purchase(
                cardUid: $data['card_uid'],
                tenantId: $tenant,
                items: $data['items'],
                idempotencyKey: $key,
                cashierId: $request->user()->id,
            );
        } catch (PurchaseException $e) {
            return response()->json([
                'error'   => $e->errorCode,
                'message' => $e->getMessage(),
            ], $e->httpStatus);
        }

        return response()->json($result, $result['replayed'] ? 200 : 201);
    }
}