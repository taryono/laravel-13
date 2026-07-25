<?php
// app/Helpers/PdfHelper.php
namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PdfHelper
{
    /**
     * Convert PDF to JPG image
     * 
     * @param string $pdfPath - Path relative to storage/app/public
     * @return string|null - Return image path or null if failed
     */
    public static function convertToImage($pdfPath)
    {
        $fullPdfPath = storage_path('app/public/' . $pdfPath);
        
        // Check if file exists
        if (!file_exists($fullPdfPath)) {
            Log::error("PDF file not found: {$fullPdfPath}");
            return null;
        }
        
        // Get file extension
        $extension = pathinfo($fullPdfPath, PATHINFO_EXTENSION);
        
        // If already an image, return the path
        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
            return $pdfPath;
        }
        
        // If not PDF, return null
        if (strtolower($extension) !== 'pdf') {
            return null;
        }
        
        // Create converted images directory
        $convertedDir = storage_path('app/public');
        if (!file_exists($convertedDir)) {
            mkdir($convertedDir, 0755, true);
        }
        
        // Generate output path
        $fileName = pathinfo($pdfPath, PATHINFO_FILENAME);
        $outputFileName = $fileName . '_' . time() . '.jpg';
        $outputPath = $convertedDir . '/' . $outputFileName;
        
        // Detect OS and use appropriate Ghostscript command
        $gsCommand = self::getGhostscriptCommand();
        
        // Ghostscript command to convert PDF to JPG
        $command = sprintf(
            '%s -sDEVICE=jpeg -o "%s" -r150 -dFirstPage=1 -dLastPage=1 -dJPEGQ=90 "%s" 2>&1',
            $gsCommand,
            $outputPath,
            $fullPdfPath
        );
        
        // Execute command
        exec($command, $output, $returnVar);
        
        // Check if conversion successful
        if ($returnVar === 0 && file_exists($outputPath)) {
            return $outputFileName;
        }
        
        // Log error
        Log::error('PDF to Image conversion failed', [
            'pdf_path' => $fullPdfPath,
            'command' => $command,
            'output' => implode("\n", $output),
            'return_code' => $returnVar
        ]);
        
        return null;
    }
    
    /**
     * Get Ghostscript command based on OS
     */
    private static function getGhostscriptCommand()
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