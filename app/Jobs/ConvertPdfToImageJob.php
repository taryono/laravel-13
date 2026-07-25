<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use \Models\Document;

class ConvertPdfToImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pathPdf;
    public $documentId;

    public $tries = 3;
    public $timeout = 180;

    public function __construct($pathPdf, $documentId)
    {
        $this->pathPdf = $pathPdf;
        $this->documentId = $documentId;
    }

    public function handle()
    {
        $document = Document::find($this->documentId);

        if (!$document) {
            Log::error("Document tidak ditemukan ID: " . $this->documentId);
            return;
        }

        // 1. Ambil file dari SFTP
        $storage = getStorage();

        if (!$storage->exists($this->pathPdf)) {
            throw new \Exception("File SFTP tidak ditemukan: " . $this->pathPdf);
        }

        // 2. Simpan sementara di local
        $tempPdf = storage_path('app/temp_' . uniqid() . '.pdf');

        file_put_contents(
            $tempPdf,
            $storage->get($this->pathPdf)
        );

        // 3. Convert pakai Ghostscript
        $outputImage = storage_path('app/temp_' . uniqid() . '.jpg');
        $gsCommand = $this->getGhostscriptCommand();
        $fullPdfPath = escapeshellarg($tempPdf);
        $outputPath = escapeshellarg($outputImage);

        // Ghostscript command to convert PDF to JPG
        $command = sprintf(
                '%s -sDEVICE=jpeg -o %s -r150 -dFirstPage=1 -dLastPage=1 -dJPEGQ=90 %s 2>&1',
                $gsCommand,
                $outputPath,
                $fullPdfPath
            );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0 || !file_exists($outputImage)) {
            Log::error("GS Error", [
                'cmd' => $command,
                'out' => $output
            ]);

            throw new \Exception("Gagal convert PDF ke image");
        }

        // 4. Upload hasil ke SFTP
        $imgName = 'img/' . pathinfo($this->pathPdf, PATHINFO_FILENAME)
                 . '_' . time() . '.jpg';


        // 👉 pastikan folder ada
        if (!$storage->exists('img')) {
            $storage->makeDirectory('img');
        }

        $storage->put(
            $imgName,
            file_get_contents($outputImage)
        );

        // 5. Update tabel documents
        $document->img_path = $imgName;
        $document->save();

        // 6. Hapus file temp
        @unlink($tempPdf);
        @unlink($outputImage);

        Log::info("Convert sukses", [
            'pdf' => $this->pathPdf,
            'img' => $imgName
        ]);
    }

    protected function getGhostscriptCommand()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows - try different possible commands
            $possibleCommands = ['gswin64c', 'gswin32c', 'gs'];
            
            foreach ($possibleCommands as $cmd) {
                exec("where $cmd 2>nul", $output, $returnVar);
                if ($returnVar === 0) {
                    return $cmd;
                }
            }
            
            // If not in PATH, try common installation paths
            $commonPaths = [
                'C:\Program Files\gs\gs10.06.0\bin\gswin64c.exe', 
            ];
            
            foreach ($commonPaths as $path) {
                if (file_exists($path)) {
                    return '"' . $path . '"';
                }
            }
            
            return 'gswin64c'; // Default fallback
        }
        
        return 'gs'; // Linux/Unix
    }
}
