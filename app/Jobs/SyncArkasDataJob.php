<?php

namespace App\Jobs;

use Models\BosPeriod;
use App\Services\Integrations\ArkasSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncArkasDataJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah percobaan ulang jika terjadi timeout jaringan ke server pusat.
     */
    public int $tries = 3;

    /**
     * Jeda waktu (detik) antar percobaan ulang (exponential backoff).
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [15, 60, 180];
    }

    /**
     * Durasi kunci unik (detik) agar periode yang sama tidak diproses paralel.
     */
    public int $uniqueFor = 300;

    public function __construct(
        public readonly int $bosPeriodId,
        public readonly int $userId,
        public readonly string $syncMode = 'API_PUSH'
    ) {
    }

    public function uniqueId(): string
    {
        return "arkas-sync-period-{$this->bosPeriodId}";
    }

    public function handle(ArkasSyncService $arkasSyncService): void
    {
        BosPeriod::whereKey($this->bosPeriodId)->update([
            'arkas_sync_status' => 'SYNCING',
        ]);

        $result = $arkasSyncService->syncPeriod(
            bosPeriodId: $this->bosPeriodId,
            triggeredByUserId: $this->userId,
            syncMode: $this->syncMode
        );

        Log::info('Sinkronisasi ARKAS berhasil diselesaikan.', $result);
    }

    public function failed(?Throwable $exception): void
    {
        BosPeriod::whereKey($this->bosPeriodId)->update([
            'arkas_sync_status' => 'FAILED',
        ]);

        Log::error("Sinkronisasi ARKAS gagal untuk BosPeriod #{$this->bosPeriodId}: {$exception?->getMessage()}", [
            'exception' => $exception,
        ]);
    }
}