<?php

namespace App\Support;

use Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RuntimeException;

class EmployeeNumberGenerator
{
    /**
     * Format default: {PREFIX}-{YYMM}-{SEQ}
     * Contoh: EMP-2511-0001
     *
     * Bisa di-override per organisasi lewat kolom organizations.employee_number_format
     * Contoh format kustom: "KRY/{org_code}/{YY}/{SEQ:5}"
     */
    public const DEFAULT_FORMAT = '{PREFIX}-{YYMM}-{SEQ:4}';

    /**
     * Generate employee number unik per organisasi.
     *
     * @param  int         $organizationId
     * @param  string|null $format         Override format (opsional)
     * @param  string      $prefix         Prefix default jika tidak ada di format
     * @param  int         $padLength      Panjang padding SEQ default
     * @return string
     *
     * @throws RuntimeException
     */
    public static function generate(
        int $organizationId,
        ?string $format = null,
        string $prefix = 'EMP',
        int $padLength = 4
    ): string {
        $format = $format ?? self::resolveFormat($organizationId);
        $prefix = self::resolvePrefix($organizationId, $prefix);

        // Lock per organisasi supaya tidak ada race condition
        $lockKey = "employee_number_lock:org:{$organizationId}";

        return Cache::lock($lockKey, 10)->block(5, function () use (
            $organizationId, $format, $prefix, $padLength
        ) {
            return DB::transaction(function () use (
                $organizationId, $format, $prefix, $padLength
            ) {
                $nextSeq = self::nextSequence($organizationId);
                $number  = self::renderFormat($format, [
                    'PREFIX' => $prefix,
                    'YY'     => now()->format('y'),
                    'YYYY'   => now()->format('Y'),
                    'MM'     => now()->format('m'),
                    'YYMM'   => now()->format('ym'),
                    'YYYYMM' => now()->format('Ym'),
                    'DD'     => now()->format('d'),
                    'SEQ'    => str_pad((string) $nextSeq, $padLength, '0', STR_PAD_LEFT),
                ]);

                // Safety net: kalau ternyata sudah ada (mis. data lama), increment sampai unik
                $attempts = 0;
                while (self::exists($organizationId, $number)) {
                    if (++$attempts > 50) {
                        throw new RuntimeException(
                            "Gagal generate employee number unik untuk organisasi {$organizationId} setelah 50 percobaan."
                        );
                    }
                    $nextSeq++;
                    $number = self::renderFormat($format, [
                        'PREFIX' => $prefix,
                        'YY'     => now()->format('y'),
                        'YYYY'   => now()->format('Y'),
                        'MM'     => now()->format('m'),
                        'YYMM'   => now()->format('ym'),
                        'YYYYMM' => now()->format('Ym'),
                        'DD'     => now()->format('d'),
                        'SEQ'    => str_pad((string) $nextSeq, $padLength, '0', STR_PAD_LEFT),
                    ]);
                }

                // Simpan sequence terakhir yang dipakai
                self::persistSequence($organizationId, $nextSeq);

                return $number;
            });
        });
    }

    /**
     * Cek apakah employee_number sudah dipakai di organisasi tsb.
     */
    protected static function exists(int $organizationId, string $number): bool
    {
        return Employee::withTrashed()
            ->where('organization_id', $organizationId)
            ->where('employee_number', $number)
            ->exists();
    }

    /**
     * Ambil sequence berikutnya.
     *
     * Prioritas:
     * 1. Dari tabel `employee_number_sequences` (paling akurat, atomic)
     * 2. Fallback: MAX(employee_number) terakhir + 1 (parse SEQ dari string)
     * 3. Fallback terakhir: 1
     */
    protected static function nextSequence(int $organizationId): int
    {
        // === Opsi 1: pakai tabel sequence (disarankan) ===
        if (self::hasSequenceTable()) {
            $row = DB::table('employee_number_sequences')
                ->where('organization_id', $organizationId)
                ->lockForUpdate()
                ->first();

            if ($row) {
                $next = (int) $row->last_number + 1;

                DB::table('employee_number_sequences')
                    ->where('organization_id', $organizationId)
                    ->update([
                        'last_number' => $next,
                        'updated_at'  => now(),
                    ]);

                return $next;
            }

            // Belum ada row → insert dengan nilai 1
            DB::table('employee_number_sequences')->insert([
                'organization_id' => $organizationId,
                'last_number'     => 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return 1;
        }

        // === Opsi 2: fallback parse dari data terakhir ===
        $lastNumber = Employee::withTrashed()
            ->where('organization_id', $organizationId)
            ->orderByDesc('id')
            ->value('employee_number');

        if (!$lastNumber) {
            return 1;
        }

        // Ambil digit terakhir dari string (mis. "EMP-2511-0007" → 7)
        if (preg_match('/(\d+)$/', $lastNumber, $m)) {
            return (int) $m[1] + 1;
        }

        return 1;
    }

    /**
     * Simpan sequence terakhir (dipakai jika tidak pakai tabel sequence).
     * Kalau pakai tabel sequence, method ini no-op.
     */
    protected static function persistSequence(int $organizationId, int $number): void
    {
        if (self::hasSequenceTable()) {
            // Sudah di-handle di nextSequence()
            return;
        }

        // Fallback: simpan di cache (tidak persisten, hanya untuk mempercepat)
        Cache::put("employee_number_last:org:{$organizationId}", $number, now()->addDay());
    }

    /**
     * Resolve format dari kolom organizations.employee_number_format (jika ada).
     */
    protected static function resolveFormat(int $organizationId): string
    {
        $org = DB::table('organizations')->where('id', $organizationId)->first();

        if ($org && !empty($org->employee_number_format)) {
            return $org->employee_number_format;
        }

        return self::DEFAULT_FORMAT;
    }

    /**
     * Resolve prefix dari organizations.code / organizations.employee_prefix (jika ada).
     */
    protected static function resolvePrefix(int $organizationId, string $default): string
    {
        $org = DB::table('organizations')->where('id', $organizationId)->first();

        if ($org) {
            if (!empty($org->employee_prefix)) {
                return $org->employee_prefix;
            }
            if (!empty($org->code)) {
                return strtoupper($org->code);
            }
        }

        return $default;
    }

    /**
     * Render format dengan placeholder.
     * Format: "EMP/{YY}/{SEQ:4}" → "EMP/25/0001"
     */
    protected static function renderFormat(string $format, array $vars): string
    {
        return preg_replace_callback('/\{([A-Z]+)(?::(\d+))?\}/', function ($m) use ($vars) {
            $key    = $m[1];
            $padLen = isset($m[2]) ? (int) $m[2] : null;
            $value  = $vars[$key] ?? '';

            if ($padLen && $key === 'SEQ') {
                return str_pad((string) $value, $padLen, '0', STR_PAD_LEFT);
            }

            return (string) $value;
        }, $format);
    }

    /**
     * Cek apakah tabel sequence tersedia (di-cache per request).
     */
    protected static function hasSequenceTable(): bool
    {
        static $has = null;

        if ($has === null) {
            try {
                $has = \Illuminate\Support\Facades\Schema::hasTable('employee_number_sequences');
            } catch (\Throwable $e) {
                $has = false;
            }
        }

        return $has;
    }
}