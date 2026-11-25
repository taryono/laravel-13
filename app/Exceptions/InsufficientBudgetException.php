<?php

namespace App\Exceptions;

use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InsufficientBudgetException extends DomainException
{
    /**
     * Otomatis mengembalikan response HTTP 422 Unprocessable Entity
     * jika exception ini tidak di-catch di controller.
     */
    public function render(Request $request): JsonResponse|bool
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'error' => 'INSUFFICIENT_BUDGET',
            ], 422);
        }

        return false;
    }
}