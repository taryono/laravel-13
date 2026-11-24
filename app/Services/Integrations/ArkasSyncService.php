<?php

namespace App\Services\Integrations;

use Models\BosPeriod;
use Models\BosRealization;
use Models\BosReceipt;
use Models\RkasDocument;
use DomainException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ArkasSyncService
{
    /**
     * Mengekspor dan menyinkronkan data RKAS & Realisasi BOS satu periode ke format ARKAS.
     *
     * @param int $bosPeriodId
     * @param int $triggeredByUserId
     * @param string $syncMode 'API_PUSH' | 'EXPORT_ONLY'
     * @return array<string, mixed>
     * @throws Throwable
     */
    public function syncPeriod(int $bosPeriodId, int $triggeredByUserId, string $syncMode = 'API_PUSH'): array
    {
        $period = BosPeriod::with(['school', 'academicYear', 'fiscalPeriod'])
            ->findOrFail($bosPeriodId);

        // 1. Ambil dokumen RKAS aktif yang disetujui untuk tahun ajaran & sekolah terkait
        $rkasDocument = RkasDocument::with([
            'programs.standard',
            'programs.activities.subActivities.items.component',
            'programs.activities.subActivities.items.budgets.source',
        ])
            ->where('school_id', $period->school_id)
            ->where('academic_year_id', $period->academic_year_id)
            ->whereIn('status', ['APPROVED', 'ACTIVE'])
            ->where('is_active', true)
            ->latest('version')
            ->first();

        if (!$rkasDocument) {
            throw new DomainException(
                "Tidak ditemukan dokumen RKAS berstatus APPROVED/ACTIVE untuk sekolah {$period->school?->name} pada tahun ajaran ini."
            );
        }

        // 2. Bangun Payload Terstruktur Standar ARKAS (Kertas Kerja RKAS + BKU BOS)
        $arkasPayload = $this->buildArkasPayload($period, $rkasDocument);

        // 3. Simpan arsip paket JSON ke Storage (audit trail & fallback upload manual ke ARKAS)
        $exportPath = sprintf(
            'arkas-exports/%s/%s_%s.json',
            $period->school?->npsn ?? "school_{$period->school_id}",
            $period->code,
            now()->format('Ymd_His')
        );

        Storage::disk('local')->put(
            $exportPath,
            json_encode($arkasPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // 4. Eksekusi pengiriman ke endpoint API ARKAS / BOS Salur (jika mode API_PUSH)
        $remoteResponse = null;
        if ($syncMode === 'API_PUSH') {
            $remoteResponse = $this->pushToArkasGateway($period, $arkasPayload);
        }

        // 5. Perbarui status sinkronisasi pada bos_periods
        DB::transaction(function () use ($period, $triggeredByUserId, $syncMode) {
            $period->update([
                'arkas_sync_status' => $syncMode === 'API_PUSH' ? 'SYNCED' : 'EXPORTED',
                'arkas_synced_at' => now(),
                'updated_by' => $triggeredByUserId,
            ]);
        });

        return [
            'bos_period_id' => $period->id,
            'rkas_document' => $rkasDocument->code,
            'sync_mode' => $syncMode,
            'export_file' => $exportPath,
            'synced_at' => now()->toIso8601String(),
            'summary_counts' => [
                'kertas_kerja_items' => count($arkasPayload['kertas_kerja_rkas']),
                'bku_transactions' => count($arkasPayload['bku_realisasi']),
            ],
            'gateway_receipt' => $remoteResponse,
        ];
    }

    /**
     * Memetakan skema internal (RKAS & BOS) ke skema kode referensi ARKAS Kemendikbud.
     */
    protected function buildArkasPayload(BosPeriod $period, RkasDocument $rkasDocument): array
    {
        $kertasKerjaRows = [];

        // A. Mapping Kertas Kerja RKAS (8 SNP -> Kegiatan -> Sub Kegiatan -> Komponen BOS -> Rekening Belanja)
        foreach ($rkasDocument->programs as $program) {
            foreach ($program->activities as $activity) {
                foreach ($activity->subActivities as $subActivity) {
                    foreach ($subActivity->items as $item) {
                        // Filter hanya porsi anggaran item yang didanai oleh sumber BOS
                        $bosBudget = $item->budgets->first(
                            fn($b) => strtoupper((string) $b->source?->source_type) === 'BOS'
                        );

                        if (!$bosBudget) {
                            continue;
                        }

                        $kertasKerjaRows[] = [
                            'kode_snp' => $program->standard?->code,
                            'nama_snp' => $program->standard?->name,
                            'kode_kegiatan' => $subActivity->code,
                            'nama_kegiatan' => $subActivity->name,
                            'kode_rekening_bel' => $item->component?->account_code,
                            'kode_komponen_bos' => $item->component?->regulation_code,
                            'kode_barang' => $item->code,
                            'uraian_barang' => $item->name,
                            'spesifikasi' => $item->specification,
                            'satuan' => $item->unit,
                            'volume' => (float) $item->volume,
                            'harga_satuan' => (float) $item->unit_price,
                            'pajak' => (float) $item->tax_amount,
                            'jumlah_anggaran' => (float) $bosBudget->amount,
                            'bulan_pelaksanaan' => $item->execution_month,
                            'triwulan' => $item->execution_quarter,
                        ];
                    }
                }
            }
        }

        // B. Mapping Penerimaan Dana (Saldo & Penyaluran BOS Salur)
        $receipts = BosReceipt::where('bos_period_id', $period->id)
            ->where('is_active', true)
            ->orderBy('receipt_date')
            ->get()
            ->map(fn(BosReceipt $r) => [
                'tanggal_transaksi' => $r->receipt_date->toDateString(),
                'no_bukti' => $r->receipt_number,
                'jenis_transaksi' => 'PENERIMAAN_SALUR',
                'referensi_transfer' => $r->transfer_reference,
                'nominal_debit' => (float) $r->amount,
                'nominal_kredit' => 0.00,
                'uraian' => $r->notes ?? "Penyaluran Dana BOS {$period->name}",
            ])
            ->all();

        // C. Mapping BKU Pengeluaran (Realisasi Belanja & Pajak)
        $realizations = BosRealization::with([
            'vendor',
            'usagePlan.component',
            'rkasRealization.item.component',
            'rkasRealization.item.subActivity',
        ])
            ->where('bos_period_id', $period->id)
            ->where('status', '!=', 'VOID')
            ->where('is_active', true)
            ->orderBy('realization_date')
            ->orderBy('id')
            ->get()
            ->map(function (BosRealization $real) {
                $rkasItem = $real->rkasRealization?->item;

                return [
                    'tanggal_transaksi' => $real->realization_date->toDateString(),
                    'no_bukti' => $real->receipt_number,
                    'jenis_transaksi' => 'PENGELUARAN_BELANJA',
                    'kode_kegiatan' => $rkasItem?->subActivity?->code,
                    'kode_rekening_bel' => $rkasItem?->component?->account_code,
                    'kode_komponen_bos' => $real->usagePlan?->component?->code,
                    'uraian' => $real->description,
                    'nominal_debit' => 0.00,
                    'nominal_kredit' => (float) $real->amount,
                    'pajak' => [
                        'jenis_pajak' => $real->tax_type,
                        'status_pajak' => $real->tax_status,
                        'nominal' => (float) $real->tax_amount,
                    ],
                    'penyedia' => [
                        'is_siplah' => (bool) $real->is_siplah,
                        'nama' => $real->vendor?->name,
                        'npwp' => $real->vendor?->tax_id ?? null,
                    ],
                    'metode_pembayaran' => $real->payment_method,
                ];
            })
            ->all();

        return [
            'metadata' => [
                'schema_version' => 'ARKAS-BRIDGE-2026.1',
                'generated_at' => now()->toIso8601String(),
                'npsn' => $period->school?->npsn,
                'nama_sekolah' => $period->school?->name,
                'tahun_anggaran' => $period->academicYear?->name,
                'tahap_periode' => $period->period_number,
                'kode_periode' => $period->code,
                'versi_juknis' => $period->regulation_version,
                'versi_rkas' => $rkasDocument->version,
                'jenis_revisi_rkas' => $rkasDocument->revision_type,
            ],
            'ringkasan_pagu' => [
                'jumlah_siswa_dapodik' => $period->student_count,
                'satuan_biaya' => (float) ($period->unit_cost_per_student ?? 0),
                'silpa_tahun_lalu' => (float) ($period->carry_over_amount ?? 0),
                'total_alokasi' => (float) $period->total_allocation,
                'total_diterima' => (float) $period->total_received,
                'total_realisasi' => (float) $period->total_used,
                'sisa_saldo' => (float) $period->total_remaining,
            ],
            'kertas_kerja_rkas' => $kertasKerjaRows,
            'bku_penerimaan' => $receipts,
            'bku_realisasi' => $realizations,
        ];
    }

    /**
     * Mengirimkan payload ke Gateway API ARKAS / Dinas Pendidikan dengan retry otomatis.
     *
     * @throws RequestException
     */
    protected function pushToArkasGateway(BosPeriod $period, array $payload): array
    {
        $endpoint = config('services.arkas.endpoint');
        $apiToken = config('services.arkas.token');

        if (empty($endpoint)) {
            // Simulasi response sukses apabila endpoint belum dikonfigurasi pada environment dev
            Log::info("ARKAS Gateway endpoint belum dikonfigurasi, melewati HTTP push untuk periode #{$period->id}.");

            return [
                'status' => 'SIMULATED_ACCEPTED',
                'reference_id' => 'ARKAS-SIM-' . now()->format('YmdHis'),
            ];
        }

        $response = Http::withToken($apiToken)
            ->acceptJson()
            ->timeout(30)
            ->retry(3, 1000)
            ->post("{$endpoint}/v1/satdik/{$period->school?->npsn}/sync", $payload)
            ->throw();

        return $response->json();
    }
}