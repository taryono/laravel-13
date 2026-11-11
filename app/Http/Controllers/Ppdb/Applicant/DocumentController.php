<?php
// app/Http/Controllers/Ppdb/DocumentController.php

namespace App\Http\Controllers\Ppdb\Applicant;

use App\Http\Controllers\Controller;
use Models\Applicant;
use Models\ApplicationDocument;
use Models\AdmissionDocumentVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Upload dokumen
     */
    public function upload(Request $request, Applicant $applicant)
    {
        $request->validate([
            'document_type' => 'required|string|max:30',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store("ppdb/documents/{$applicant->id}", 'public');

        // Simpan / update dokumen
        $document = ApplicationDocument::updateOrCreate(
            [
                'applicant_id'  => $applicant->id,
                'document_type' => $request->document_type,
            ],
            [
                'document_url'  => Storage::url($path),
                'file_name'     => $file->getClientOriginalName(),
                'file_size'     => $file->getSize(),
                'uploaded_at'   => now(),
                'verified'      => 0,
                'is_active'     => 1,
                'created_by'    => Auth::id(),
            ]
        );

        // Buat record verifikasi
        AdmissionDocumentVerification::updateOrCreate(
            [
                'applicant_id'            => $applicant->id,
                'application_document_id' => $document->id,
            ],
            [
                'admission_id'        => $applicant->admission_id,
                'organization_id'     => $applicant->school_id,
                'document_type'       => $request->document_type,
                'file_path'           => $path,
                'verification_status' => 'pending',
                'verification_method' => 'online',
                'is_mandatory'        => in_array($request->document_type, ['akta_kelahiran', 'kartu_keluarga', 'rapor']),
                'created_by'          => Auth::id(),
            ]
        );

        return response()->json([
            'success'  => true,
            'document' => $document,
            'message'  => 'Dokumen berhasil diunggah',
        ]);
    }

    /**
     * Hapus dokumen
     */
    public function destroy(ApplicationDocument $document)
    {
        if (Auth::guard('web')->check() && $document->applicant && $document->applicant->created_by && $document->applicant->created_by !== Auth::guard('web')->id()) {
            $webUser = Auth::guard('web')->user();
            $isSuper = method_exists($webUser, 'isSuperUser') && $webUser->isSuperUser();
            $isAdmin = method_exists($webUser, 'hasAnyRole') && $webUser->hasAnyRole(['admin', 'admin-ppdb', 'super-admin']);
            if (!$isSuper && !$isAdmin) {
                abort(403);
            }
        }

        if (!empty($document->document_url)) {
            $urlPath = parse_url($document->document_url, PHP_URL_PATH) ?? $document->document_url;
            $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');
            Storage::disk('public')->delete($relativePath);
        }

        AdmissionDocumentVerification::where('application_document_id', $document->id)->delete();
        $document->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Preview dokumen
     */
    public function preview(ApplicationDocument $document)
    {
        $urlPath = parse_url($document->document_url ?? '', PHP_URL_PATH) ?? ($document->document_url ?? '');
        $relativePath = ltrim(str_replace('/storage/', '', $urlPath), '/');
        $path = storage_path('app/public/' . $relativePath);

        if (!file_exists($path) && !empty($document->document_url)) {
            $path = storage_path('app/public/' . ltrim($document->document_url, '/'));
        }

        if (!file_exists($path) || is_dir($path)) {
            $verification = AdmissionDocumentVerification::where('application_document_id', $document->id)->first();
            if ($verification && !empty($verification->file_path)) {
                $altPath = storage_path('app/public/' . ltrim($verification->file_path, '/'));
                if (file_exists($altPath)) {
                    $path = $altPath;
                }
            }
        }

        if (!file_exists($path) || is_dir($path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return response()->file($path);
    }
}
