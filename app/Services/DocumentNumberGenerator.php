<?php

namespace App\Services;

use Models\Organization;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class DocumentNumberGenerator
{
    /**
     * Generate nomor dokumen baru yang unik per organisasi.
     *
     * @param  int    $organizationId
     * @param  string $documentType   Key di config/document_numbers.php, mis. 'contract', 'loan', dst.
     * @param  \DateTimeInterface|null $date  Tanggal acuan untuk periode (default: now()). Berguna untuk
     *                                        proses backdate/off-cycle payroll.
     * @return string  Nomor dokumen yang sudah terformat, mis. "CTR/YYP/2026/0001"
     *
     * @throws InvalidArgumentException jika documentType tidak dikenal
     */
    public function generate(int $organizationId, string $documentType, ?\DateTimeInterface $date = null): string
    {
        $config = config("document_numbers.{$documentType}");

        if (! $config) {
            throw new InvalidArgumentException(
                "Jenis dokumen '{$documentType}' belum terdaftar di config/document_numbers.php"
            );
        }

        $date = $date ?? now();
        $periodKey = $this->resolvePeriodKey($config['reset_period'], $date);

        // Transaksi + row lock: memastikan dua request bersamaan untuk
        // organisasi & periode yang sama tidak pernah mendapat angka yang sama.
        $nextNumber = DB::transaction(function () use ($organizationId, $documentType, $periodKey) {
            // Coba ambil baris dengan lock. Kalau belum ada, insert dulu.
            $row = DB::table('document_number_sequences')
                ->where('organization_id', $organizationId)
                ->where('document_type', $documentType)
                ->where('period_key', $periodKey)
                ->lockForUpdate()
                ->first();

            if (! $row) {
                // insertOrIgnore mencegah error jika ada race condition di
                // percobaan pertama (dua proses insert bersamaan).
                DB::table('document_number_sequences')->insertOrIgnore([
                    'organization_id' => $organizationId,
                    'document_type' => $documentType,
                    'period_key' => $periodKey,
                    'last_number' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $row = DB::table('document_number_sequences')
                    ->where('organization_id', $organizationId)
                    ->where('document_type', $documentType)
                    ->where('period_key', $periodKey)
                    ->lockForUpdate()
                    ->first();
            }

            if (! $row) {
                // Seharusnya tidak pernah terjadi; jaga-jaga saja.
                throw new RuntimeException('Gagal membuat/mengunci baris document_number_sequences.');
            }

            $next = $row->last_number + 1;

            DB::table('document_number_sequences')
                ->where('id', $row->id)
                ->update([
                    'last_number' => $next,
                    'updated_at' => now(),
                ]);

            return $next;
        });

        return $this->format($config, $organizationId, $date, $nextNumber);
    }

    /**
     * Tentukan period_key berdasarkan reset_period yang dikonfigurasi.
     */
    protected function resolvePeriodKey(string $resetPeriod, \DateTimeInterface $date): string
    {
        return match ($resetPeriod) {
            'yearly' => $date->format('Y'),
            'monthly' => $date->format('Y-m'),
            'none' => 'ALL',
            default => throw new InvalidArgumentException("reset_period '{$resetPeriod}' tidak dikenal"),
        };
    }

    /**
     * Susun string nomor akhir sesuai template format di config.
     */
    protected function format(array $config, int $organizationId, \DateTimeInterface $date, int $number): string
    {
        $orgCode = $this->getOrganizationCode($organizationId);

        $padded = str_pad((string) $number, $config['pad_length'], '0', STR_PAD_LEFT);

        $replacements = [
            '{prefix}' => $config['prefix'],
            '{org_code}' => $orgCode,
            '{year}' => $date->format('Y'),
            '{month}' => $date->format('m'),
            '{number}' => $padded,
        ];

        return strtr($config['format'], $replacements);
    }

    /**
     * Ambil kode organisasi. Di-cache per-request lewat static array
     * sederhana supaya tidak query berulang saat generate banyak nomor
     * sekaligus (mis. saat membuat banyak payslip dalam satu payroll run).
     */
    protected function getOrganizationCode(int $organizationId): string
    {
        static $cache = [];

        if (! array_key_exists($organizationId, $cache)) {
            $code = Organization::query()
                ->where('id', $organizationId)
                ->value('code');

            if (! $code) {
                throw new RuntimeException("Organization dengan id {$organizationId} tidak ditemukan.");
            }

            $cache[$organizationId] = $code;
        }

        return $cache[$organizationId];
    }
}
