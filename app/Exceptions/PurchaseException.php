<?php

namespace App\Exceptions;

use RuntimeException;

class PurchaseException extends RuntimeException
{
    private function __construct(
        string $message,
        public readonly string $errorCode,
        public readonly int $httpStatus,
    ) {
        parent::__construct($message);
    }

    public static function invalidItems(string $detail = 'Daftar item tidak valid.'): self
    {
        return new self($detail, 'invalid_items', 422);
    }

    public static function tenantInactive(): self
    {
        return new self('Kantin tidak ditemukan atau tidak aktif.', 'tenant_inactive', 404);
    }

    public static function cashierForbidden(): self
    {
        return new self('Pengguna tidak terdaftar pada kantin ini.', 'cashier_forbidden', 403);
    }

    public static function cardNotFound(): self
    {
        return new self('Kartu tidak dikenali.', 'card_not_found', 404);
    }

    public static function cardNotActive(string $status): self
    {
        return new self("Kartu tidak aktif (status: {$status}).", 'card_not_active', 422);
    }

    public static function walletLocked(?string $reason): self
    {
        $msg = 'Dompet sedang dikunci' . ($reason ? ": {$reason}" : '.');
        return new self($msg, 'wallet_locked', 422);
    }

    public static function organizationMismatch(): self
    {
        return new self('Dompet tidak berlaku untuk kantin ini.', 'organization_mismatch', 422);
    }

    public static function menuUnavailable(): self
    {
        return new self('Ada menu yang tidak tersedia atau bukan milik kantin ini.', 'menu_unavailable', 422);
    }

    public static function insufficientBalance(): self
    {
        return new self('Saldo tidak cukup.', 'insufficient_balance', 422);
    }

    public static function dailyLimitExceeded(string $limit): self
    {
        return new self("Melebihi batas belanja harian (Rp {$limit}).", 'daily_limit_exceeded', 422);
    }

    public static function concurrentUpdate(): self
    {
        return new self('Saldo berubah saat diproses, silakan coba lagi.', 'concurrent_update', 409);
    }

    public static function idempotencyConflict(): self
    {
        return new self('Idempotency-Key sudah dipakai untuk transaksi lain.', 'idempotency_conflict', 409);
    }
}