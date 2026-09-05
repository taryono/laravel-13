<?php

namespace App\Services\Attendance;

use Models\AttendanceLog;
use Models\AttendanceImport;
use Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Carbon\Carbon;
use Throwable;

class FingerprintImporter
{
    /**
     * Mapping field dari berbagai mesin fingerprint
     */
    protected array $fieldMappings = [
        // Format umum (Employee ID, Date, Time)
        'standard' => [
            'employee_id' => ['employee_id', 'nik', 'id_karyawan', 'pin', 'user_id', 'badge'],
            'date' => ['date', 'tanggal', 'tgl', 'scan_date', 'attendance_date'],
            'time' => ['time', 'jam', 'scan_time', 'attendance_time', 'check_time'],
            'datetime' => ['datetime', 'scan_datetime', 'attendance_datetime', 'timestamp'],
            'machine_id' => ['machine_id', 'device_id', 'terminal_id', 'mesin'],
            'status' => ['status', 'type', 'scan_type', 'mode'],
        ],
        
        // Format ZKTeco (default mesin fingerprint)
        'zkteco' => [
            'employee_id' => ['USERID', 'ID', 'EmpID', 'PIN'],
            'datetime' => ['DateTime', 'CheckTime', 'ScanTime'],
            'machine_id' => ['MachineNumber', 'DeviceID'],
            'status' => ['Status', 'VerifyMode'],
        ],
        
        // Format Sidik Jari (Fingerprint)
        'fingerprint' => [
            'employee_id' => ['NIP', 'NIK', 'NRP', 'EmpCode'],
            'date' => ['Tanggal', 'DATE'],
            'time' => ['Waktu', 'TIME'],
            'machine_id' => ['Device ID', 'Terminal'],
        ],
    ];

    /**
     * Import fingerprint data from file
     */
    public function import(AttendanceImport $batch, UploadedFile $file): array
    {
        try {
            // Update batch status
            $batch->update(['status' => 'processing', 'started_at' => now()]);

            // Read file
            $data = $this->readFile($file);
            
            // Process data
            $result = $this->processData($batch, $data);
            
            // Update batch
            $batch->update([
                'total_records' => $result['total'],
                'processed_records' => $result['processed'],
                'failed_records' => $result['failed'],
                'status' => $result['failed'] > 0 ? 'partial' : 'completed',
                'completed_at' => now(),
                'errors' => $result['errors'] ?? null,
            ]);

            return $result;
            
        } catch (Throwable $e) {
            Log::error('Gagal import data fingerprint.', [
                'batch_id' => $batch->id,
                'exception' => $e->getMessage(),
            ]);
            
            $batch->update([
                'status' => 'failed',
                'errors' => json_encode(['error' => $e->getMessage()]),
            ]);
            
            throw $e;
        }
    }

    /**
     * Preview import data (for UI)
     */
    public function preview(UploadedFile $file): array
    {
        try {
            $data = $this->readFile($file);
            
            // Take first 10 rows for preview
            $preview = array_slice($data, 0, 10);
            
            // Detect column mapping
            $headers = !empty($data) ? array_keys($data[0]) : [];
            $mapping = $this->detectColumnMapping($headers);
            
            return [
                'total_rows' => count($data),
                'headers' => $headers,
                'mapping' => $mapping,
                'preview' => $preview,
                'detected_format' => $this->detectFormat($headers),
            ];
            
        } catch (Throwable $e) {
            Log::error('Gagal preview data.', ['exception' => $e]);
            throw $e;
        }
    }

    /**
     * Read file based on extension
     */
    protected function readFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        return match ($extension) {
            'csv', 'txt' => $this->readCsv($file),
            'xlsx', 'xls' => $this->readExcel($file),
            'json' => $this->readJson($file),
            default => throw new \Exception('Format file tidak didukung. Gunakan CSV, Excel, atau JSON.'),
        };
    }

    /**
     * Read CSV file
     */
    protected function readCsv(UploadedFile $file): array
    {
        $reader = new Csv();
        $reader->setDelimiter($this->detectDelimiter($file));
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);
        $reader->setReadDataOnly(true);
        
        $spreadsheet = $reader->load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $rows = $worksheet->toArray();
        
        if (empty($rows)) {
            return [];
        }
        
        // First row as headers
        $headers = array_shift($rows);
        
        // Clean headers
        $headers = array_map(function ($header) {
            return trim(strtolower(str_replace([' ', '-', '_'], '_', $header)));
        }, $headers);
        
        $data = [];
        foreach ($rows as $row) {
            if (empty(array_filter($row))) {
                continue;
            }
            
            $item = [];
            foreach ($headers as $index => $header) {
                $item[$header] = $row[$index] ?? null;
            }
            $data[] = $item;
        }
        
        return $data;
    }

    /**
     * Read Excel file
     */
    protected function readExcel(UploadedFile $file): array
    {
        $reader = IOFactory::createReaderForFile($file->getPathname());
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $rows = $worksheet->toArray();
        
        if (empty($rows)) {
            return [];
        }
        
        // First row as headers
        $headers = array_shift($rows);
        $headers = array_map(function ($header) {
            return trim(strtolower(str_replace([' ', '-', '_'], '_', $header)));
        }, $headers);
        
        $data = [];
        foreach ($rows as $row) {
            if (empty(array_filter($row))) {
                continue;
            }
            
            $item = [];
            foreach ($headers as $index => $header) {
                $item[$header] = $row[$index] ?? null;
            }
            $data[] = $item;
        }
        
        return $data;
    }

    /**
     * Read JSON file
     */
    protected function readJson(UploadedFile $file): array
    {
        $content = file_get_contents($file->getPathname());
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Format JSON tidak valid: ' . json_last_error_msg());
        }
        
        // If data is object, convert to array
        if (isset($data['data']) && is_array($data['data'])) {
            $data = $data['data'];
        }
        
        // If first element is object, convert to associative array
        if (!empty($data) && is_object($data[0] ?? null)) {
            $data = array_map(function ($item) {
                return (array) $item;
            }, $data);
        }
        
        return $data;
    }

    /**
     * Process imported data
     */
    protected function processData(AttendanceImport $batch, array $data): array
    {
        $processed = 0;
        $failed = 0;
        $errors = [];
        
        // Detect column mapping
        $headers = !empty($data) ? array_keys($data[0]) : [];
        $mapping = $this->detectColumnMapping($headers);
        
        if (empty($mapping['employee_id']) || empty($mapping['datetime'])) {
            throw new \Exception('Tidak dapat mendeteksi kolom NIK dan Tanggal/Waktu. Pastikan file memiliki format yang benar.');
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($data as $index => $row) {
                try {
                    $this->processRow($batch, $row, $mapping, $index);
                    $processed++;
                } catch (Throwable $e) {
                    $failed++;
                    $errors[] = [
                        'row' => $index + 2, // +2 karena header di baris 1
                        'data' => $row,
                        'error' => $e->getMessage(),
                    ];
                    
                    Log::warning('Gagal proses baris data fingerprint.', [
                        'batch_id' => $batch->id,
                        'row' => $index,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
            DB::commit();
            
            return [
                'total' => count($data),
                'processed' => $processed,
                'failed' => $failed,
                'errors' => $errors,
            ];
            
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process single row of data
     */
    protected function processRow(AttendanceImport $batch, array $row, array $mapping, int $index): void
    {
        // Extract data
        $employeeId = $this->extractValue($row, $mapping['employee_id']);
        $datetime = $this->extractValue($row, $mapping['datetime']);
        $machineId = $this->extractValue($row, $mapping['machine_id'] ?? []);
        $status = $this->extractValue($row, $mapping['status'] ?? []);
        
        // Validate employee_id
        if (empty($employeeId)) {
            throw new \Exception('NIK karyawan tidak ditemukan.');
        }
        
        // Find employee
        $employee = Employee::where('employee_id', $employeeId)->first();
        if (!$employee) {
            throw new \Exception("Karyawan dengan NIK '{$employeeId}' tidak ditemukan.");
        }
        
        // Parse datetime
        $scanDateTime = $this->parseDateTime($datetime);
        if (!$scanDateTime) {
            throw new \Exception("Format tanggal/waktu tidak valid: '{$datetime}'.");
        }
        
        // Create attendance log
        AttendanceLog::create([
            'employee_id' => $employee->id,
            'attendance_date' => $scanDateTime->format('Y-m-d'),
            'scan_time' => $scanDateTime,
            'scan_type' => $this->detectScanType($status, $scanDateTime),
            'machine_id' => $machineId ?: $batch->machine_source,
            'import_batch' => $batch->batch_code,
            'raw_data' => json_encode($row),
            'is_processed' => false,
            'validation_status' => 'pending',
        ]);
    }

    /**
     * Detect column mapping from headers
     */
    protected function detectColumnMapping(array $headers): array
    {
        $mapping = [
            'employee_id' => null,
            'datetime' => null,
            'date' => null,
            'time' => null,
            'machine_id' => null,
            'status' => null,
        ];
        
        foreach ($headers as $header) {
            $headerLower = strtolower(trim($header));
            
            // Check each mapping type
            foreach ($this->fieldMappings as $format => $fields) {
                foreach ($fields as $field => $aliases) {
                    foreach ($aliases as $alias) {
                        $aliasLower = strtolower(trim($alias));
                        if ($headerLower === $aliasLower || 
                            strpos($headerLower, $aliasLower) !== false) {
                            
                            // Prefer datetime over separate date/time
                            if ($field === 'datetime' && !$mapping['datetime']) {
                                $mapping['datetime'] = $header;
                            }
                            
                            if ($field === 'date' && !$mapping['date'] && !$mapping['datetime']) {
                                $mapping['date'] = $header;
                            }
                            
                            if ($field === 'time' && !$mapping['time'] && !$mapping['datetime']) {
                                $mapping['time'] = $header;
                            }
                            
                            if ($field === 'employee_id' && !$mapping['employee_id']) {
                                $mapping['employee_id'] = $header;
                            }
                            
                            if ($field === 'machine_id' && !$mapping['machine_id']) {
                                $mapping['machine_id'] = $header;
                            }
                            
                            if ($field === 'status' && !$mapping['status']) {
                                $mapping['status'] = $header;
                            }
                        }
                    }
                }
            }
        }
        
        // If no datetime found, combine date and time
        if (!$mapping['datetime'] && $mapping['date'] && $mapping['time']) {
            $mapping['datetime'] = $mapping['date'] . '+' . $mapping['time'];
        }
        
        return $mapping;
    }

    /**
     * Detect file format from headers
     */
    protected function detectFormat(array $headers): string
    {
        $headerStr = implode(' ', array_map('strtolower', $headers));
        
        if (strpos($headerStr, 'userid') !== false || strpos($headerStr, 'checktime') !== false) {
            return 'zkteco';
        }
        
        if (strpos($headerStr, 'nip') !== false || strpos($headerStr, 'nik') !== false) {
            return 'fingerprint';
        }
        
        return 'standard';
    }

    /**
     * Detect CSV delimiter
     */
    protected function detectDelimiter(UploadedFile $file): string
    {
        $content = file_get_contents($file->getPathname());
        $lines = explode("\n", $content);
        
        if (empty($lines)) {
            return ',';
        }
        
        $firstLine = $lines[0];
        $delimiters = [',', ';', "\t", '|'];
        $counts = [];
        
        foreach ($delimiters as $delimiter) {
            $counts[$delimiter] = substr_count($firstLine, $delimiter);
        }
        
        arsort($counts);
        return key($counts) ?: ',';
    }

    /**
     * Extract value from row using possible fields
     */
    protected function extractValue(array $row, array $possibleFields): ?string
    {
        foreach ($possibleFields as $field) {
            if (isset($row[$field]) && !empty($row[$field])) {
                return trim($row[$field]);
            }
        }
        return null;
    }

    /**
     * Parse datetime from various formats
     */
    protected function parseDateTime($value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }
        
        $value = trim($value);
        
        // Try different formats
        $formats = [
            // Date and time formats
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'm/d/Y H:i:s',
            'm/d/Y H:i',
            'Y-m-d\TH:i:s',
            'Y-m-d\TH:i',
            'd-m-Y H:i:s',
            'd-m-Y H:i',
            
            // Excel datetime (numeric)
            'excel',
            
            // Just date
            'Y-m-d',
            'd/m/Y',
            'm/d/Y',
            'd-m-Y',
        ];
        
        // Try Excel datetime
        if (is_numeric($value) && strpos($value, '.') !== false) {
            $timestamp = ($value - 25569) * 86400;
            try {
                return Carbon::createFromTimestamp($timestamp);
            } catch (\Exception $e) {
                // Continue to next format
            }
        }
        
        // Try each format
        foreach ($formats as $format) {
            try {
                if ($format === 'excel') {
                    continue;
                }
                return Carbon::createFromFormat($format, $value);
            } catch (\Exception $e) {
                continue;
            }
        }
        
        // Try Carbon parse (autodetect)
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Detect scan type from status
     */
    protected function detectScanType(?string $status, Carbon $datetime): string
    {
        if (empty($status)) {
            // Default based on time
            $hour = $datetime->format('H');
            if ($hour < 9) {
                return 'check_in';
            } elseif ($hour >= 17) {
                return 'check_out';
            } else {
                return 'unknown';
            }
        }
        
        $statusLower = strtolower(trim($status));
        
        $checkInKeywords = ['in', 'checkin', 'check_in', 'masuk', 'scan in', '0', 'masuk', 'hadir'];
        $checkOutKeywords = ['out', 'checkout', 'check_out', 'pulang', 'scan out', '1', 'keluar'];
        $breakInKeywords = ['break_in', 'breakin', 'istirahat masuk'];
        $breakOutKeywords = ['break_out', 'breakout', 'istirahat keluar'];
        $overtimeKeywords = ['overtime_in', 'overtime_out', 'lembur'];
        
        if ($this->matchKeywords($statusLower, $checkInKeywords)) {
            return 'check_in';
        }
        
        if ($this->matchKeywords($statusLower, $checkOutKeywords)) {
            return 'check_out';
        }
        
        if ($this->matchKeywords($statusLower, $breakInKeywords)) {
            return 'break_in';
        }
        
        if ($this->matchKeywords($statusLower, $breakOutKeywords)) {
            return 'break_out';
        }
        
        if ($this->matchKeywords($statusLower, $overtimeKeywords)) {
            return 'overtime_in';
        }
        
        return 'unknown';
    }

    /**
     * Check if text matches any keyword
     */
    protected function matchKeywords(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (strpos($text, strtolower($keyword)) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Validate import file structure
     */
    public function validateFile(UploadedFile $file): array
    {
        try {
            $data = $this->readFile($file);
            $headers = !empty($data) ? array_keys($data[0]) : [];
            $mapping = $this->detectColumnMapping($headers);
            
            $requiredFields = ['employee_id', 'datetime'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (empty($mapping[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            return [
                'valid' => empty($missingFields),
                'headers' => $headers,
                'mapping' => $mapping,
                'missing_fields' => $missingFields,
                'total_rows' => count($data),
                'format' => $this->detectFormat($headers),
            ];
            
        } catch (Throwable $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}