<?php
// app/Jobs/GenerateCrudJob.php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class GenerateCrudJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $command;
    protected $userId;

    public function __construct(string $command, int $userId)
    {
        $this->command = $command;
        $this->userId = $userId;
    }

    public function handle()
    {
        try {
            Artisan::call($this->command);
            
            Log::info("CRUD Generated via Background Job", [
                'command' => $this->command,
                'user_id' => $this->userId,
            ]);

            // Notifikasi ke user via email atau broadcast
            // event(new CrudGenerated($this->userId, $this->command));

        } catch (\Exception $e) {
            Log::error("Background CRUD Generation Failed", [
                'command' => $this->command,
                'error' => $e->getMessage(),
            ]);
        }
    }
}