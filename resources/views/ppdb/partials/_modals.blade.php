{{-- resources/views/ppdb/partials/_modals.blade.php --}}

{{-- ===== MODAL: KONFIRMASI SUBMIT ===== --}}
<div class="modal fade" id="modalConfirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-doc-preview">
            <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between gap-3"
                style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                <div class="d-flex align-items-center gap-3 min-w-0">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-3"
                        style="width:2.5rem;height:2.5rem;background:var(--bs-primary-fixed); color:var(--bs-primary); box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                        <span class="material-symbols-outlined" style="font-size:1.4rem;">assignment_turned_in</span>
                    </div>
                    <div class="min-w-0">
                        <h4 class="mb-0 text-truncate" style="font-size:1rem; font-weight:700;">
                            Konfirmasi Pengajuan Pendaftaran
                        </h4>
                        <p class="mb-0 text-truncate" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                            Mohon periksa kembali kelengkapan dan kebenaran data Anda sebelum dikirim permanen.
                        </p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm rounded-circle p-2" data-bs-dismiss="modal"
                    aria-label="Tutup Modal">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-4 overflow-y-auto" style="background:rgba(239,244,255,0.4);">
                {{-- Ringkasan --}}
                <div class="ppdb-card mb-3">
                    <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom"
                        style="border-color:var(--bs-outline-variant) !important;">
                        <span class="fw-bold d-flex align-items-center gap-2"
                            style="font-size:0.8rem; color:var(--bs-primary);">
                            <span class="material-symbols-outlined" style="font-size:1.1rem;">person</span>
                            Ringkasan Data Calon Peserta Didik
                        </span>
                        <span class="badge-soft badge-success-soft" style="font-size:0.6rem;">
                            <span class="material-symbols-outlined" style="font-size:0.7rem;">verified</span>
                            Dapodik Terverifikasi
                        </span>
                    </div>
                    <div class="row g-2" style="font-size:0.75rem;">
                        <div class="col-12 col-sm-6">
                            <div class="p-2 rounded-2 h-100" style="background:var(--bs-surface-container-low);">
                                <span class="d-block"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Nama Lengkap
                                    Siswa</span>
                                <strong style="font-size:0.8rem;"
                                    id="confirm-name">{{ $applicant->name ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-2 rounded-2 h-100" style="background:var(--bs-surface-container-low);">
                                <span class="d-block"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">NISN / NIK</span>
                                <span class="font-monospace fw-bold" style="font-size:0.8rem;" id="confirm-nisn">
                                    {{ $applicant->nisn ?? '-' }} / {{ $applicant->nik ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-2 rounded-2 h-100" style="background:var(--bs-surface-container-low);">
                                <span class="d-block"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Jalur
                                    Pendaftaran</span>
                                <strong style="font-size:0.8rem; color:var(--bs-primary);" id="confirm-path">
                                    {{ $applicant->path->name ?? '-' }}
                                </strong>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="p-2 rounded-2 h-100" style="background:var(--bs-surface-container-low);">
                                <span class="d-block"
                                    style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Kelengkapan
                                    Berkas</span>
                                <span class="fw-semibold d-flex align-items-center gap-1"
                                    style="font-size:0.8rem; color:var(--bs-success-dark);">
                                    <span class="material-symbols-outlined"
                                        style="font-size:0.95rem; color:var(--bs-success);">task_alt</span>
                                    {{ $applicant?->documents->count() ?? 0 }} Berkas Terunggah
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pakta Integritas --}}
                <div class="p-3 rounded-3 mb-3"
                    style="background:var(--bs-warning-light); border:1px solid var(--bs-warning-border);">
                    <label class="d-flex align-items-start gap-2" style="cursor:pointer;">
                        <input type="checkbox" id="agree-integritas" class="form-check-input mt-1">
                        <span style="font-size:0.75rem; line-height:1.6;">
                            <strong>Pakta Integritas Pendaftar:</strong> Saya menyatakan dengan sesungguhnya bahwa
                            seluruh data dan dokumen yang saya unggah adalah benar dan sah. Apabila ditemukan
                            pemalsuan data di kemudian hari, saya bersedia menerima sanksi pembatalan kelulusan dan
                            proses hukum sesuai regulasi PPDB.
                        </span>
                    </label>
                </div>

                {{-- Success Alert --}}
                <div id="submit-alert-success" class="d-none p-3 rounded-3"
                    style="background:var(--bs-success-light); border:1px solid var(--bs-success-border);">
                    <div class="d-flex align-items-start gap-2">
                        <span class="material-symbols-outlined mt-1"
                            style="color:var(--bs-success); font-size:1.1rem;">check_circle</span>
                        <div>
                            <strong class="d-block" style="font-size:0.8rem; color:var(--bs-success-dark);">Pendaftaran
                                Berhasil Dikirim!</strong>
                            <p class="mb-0 mt-1" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                Tanda bukti pendaftaran dan kode verifikasi telah dikirimkan ke nomor WhatsApp
                                <span class="font-monospace fw-bold">{{ $applicant->parent_phone ?? '-' }}</span>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 border-top d-flex flex-column-reverse flex-sm-row align-items-center justify-content-end gap-2"
                style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                <button type="button" class="btn-outline-custom w-100 w-sm-auto px-4" data-bs-dismiss="modal"
                    style="height:2.75rem; font-size:0.8rem;">
                    Periksa Kembali
                </button>
                <button type="button" id="btn-final-submit" onclick="submitRegistration()"
                    class="btn-primary-custom w-100 w-sm-auto px-5" style="height:2.75rem; font-size:0.8rem;">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">send</span>
                    Ya, Kirim Pendaftaran
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL PREVIEW DOKUMEN (Dinamis) ===== --}}
@if ($applicant && $applicant->documents->count() > 0)
    @foreach ($applicant->documents as $doc)
        <div class="modal fade" id="modalPreview{{ ucfirst($doc->document_type) }}" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content modal-doc-preview">
                    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between gap-3"
                        style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                        <div class="d-flex align-items-center gap-2 min-w-0">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                                style="width:2.25rem;height:2.25rem;background:rgba(16,185,129,0.1); color:var(--bs-success);">
                                <span class="material-symbols-outlined" style="font-size:1.2rem;">description</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="mb-0 text-truncate" style="font-size:1rem; font-weight:700;">
                                    Preview: {{ $doc->file_name }}
                                </h4>
                                <p class="mb-0 text-truncate"
                                    style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                    {{ number_format($doc->file_size / 1024 / 1024, 2) }} MB • Diunggah
                                    {{ $doc->uploaded_at?->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm rounded-circle p-2" data-bs-dismiss="modal">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <div class="doc-preview-content" style="min-height:400px;">
                        @if (Str::endsWith($doc->file_name, ['.jpg', '.jpeg', '.png']))
                            <img src="{{ $doc->document_url }}" alt="{{ $doc->file_name }}"
                                class="img-fluid rounded-3">
                        @else
                            <iframe src="{{ $doc->document_url }}"
                                style="width:100%;height:500px;border:none;"></iframe>
                        @endif
                    </div>

                    <div class="px-4 py-3 border-top d-flex justify-content-end gap-2"
                        style="border-color:var(--bs-outline-variant) !important; background:var(--bs-surface);">
                        <a href="{{ $doc->document_url }}" download class="btn-outline-custom"
                            style="height:2.5rem; font-size:0.75rem;">
                            <span class="material-symbols-outlined" style="font-size:1rem;">download</span>
                            Unduh
                        </a>
                        <button type="button" class="btn-primary-custom px-4" data-bs-dismiss="modal"
                            style="height:2.5rem; font-size:0.75rem;">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
