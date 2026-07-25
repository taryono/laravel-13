<?php
namespace App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UploadHelper
{
    public static function validateUploadEnterprise(
        Request $request,
        array $rules,
        array $messages = [],
        array $options = []
    ) {
        $field = array_key_first($rules);
        $files = $request->file($field);

        if (!$files) {
            throw ValidationException::withMessages([
                $field => ['File tidak ditemukan']
            ]);
        }

        // Support single & multiple
        $files = is_array($files) ? $files : [$files];

        $result = [];

        foreach ($files as $file) {

            // ===============================
            // 1. VALIDASI LEVEL PHP
            // ===============================
            if (!$file->isValid()) {

                $errorCode = $file->getError();

                $msg = match ($errorCode) {
                    UPLOAD_ERR_INI_SIZE =>
                        "Ukuran file melebihi batas server (" . ini_get('upload_max_filesize') . ")",

                    UPLOAD_ERR_FORM_SIZE =>
                        "Ukuran file melebihi batas form",

                    UPLOAD_ERR_PARTIAL =>
                        "File hanya terupload sebagian",

                    UPLOAD_ERR_NO_FILE =>
                        "File tidak ditemukan",

                    default =>
                        $file->getErrorMessage() ?? "Gagal upload file",
                };

                Log::warning("UPLOAD_FAIL_PHP", [
                    'name' => $file->getClientOriginalName(),
                    'error' => $msg
                ]);

                throw ValidationException::withMessages([
                    $field => [$msg]
                ]);
            }

            // ===============================
            // 2. VALIDASI LARAVEL
            // ===============================
            $validator = Validator::make(
                [$field => $file],
                [$field => $rules[$field]],
                $messages
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // ===============================
            // 3. VALIDASI MIME ASLI (ANTI FAKE)
            // ===============================
            $realMime = (new \finfo(FILEINFO_MIME_TYPE))
                ->file($file->getRealPath());

            $allowedMime = $options['allowed_mime'] ?? null;

            if ($allowedMime && !in_array($realMime, $allowedMime)) {

                Log::warning("UPLOAD_FAKE_MIME", [
                    'name' => $file->getClientOriginalName(),
                    'mime' => $realMime
                ]);

                throw ValidationException::withMessages([
                    $field => ['Tipe file tidak valid']
                ]);
            }

            // ===============================
            // 4. SANITIZE NAMA FILE
            // ===============================
            $original = $file->getClientOriginalName();

            $cleanName = self::sanitizeFileName(
                pathinfo($original, PATHINFO_FILENAME)
            );

            $ext = strtolower($file->getClientOriginalExtension());

            // anti double extension
            $cleanName = preg_replace('/\.(php|exe|sh|bat)$/i', '', $cleanName);

            $finalName = $cleanName . '.' . $ext;

            // ===============================
            // 5. BUILD RESULT
            // ===============================
            $result[] = (object)[
                'original_name' => $original,
                'safe_name'     => $finalName,
                'extension'     => $ext,
                'mime'          => $realMime,
                'size'          => $file->getSize(),
                'size_human'    => self::humanFileSize($file->getSize()),
                'file'          => $file
            ];
        }

        return is_array($request->file($field))
            ? $result
            : $result[0];
    }

    // =====================================
    // HELPER PENDUKUNG
    // =====================================

    public static function sanitizeFileName($name)
    {
        $name = strip_tags($name);

        // ganti karakter aneh jadi _
        $name = preg_replace('/[^A-Za-z0-9_\- ]/', '_', $name);

        // hapus multiple underscore
        $name = preg_replace('/_+/', '_', $name);

        return trim($name, '_ ');
    }

    public static function humanFileSize($size)
    {
        $units = ['B','KB','MB','GB','TB'];

        for ($i = 0; $size > 1024; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    public static function humanFileSizeBigger($size)
    {
        if ($size <= 0) return "0 B";

        $units = ['B','KB','MB','GB','TB'];

        $i = floor(log($size, 1024));

        return round($size / pow(1024, $i), 2) . ' ' . $units[$i];
    }

}
