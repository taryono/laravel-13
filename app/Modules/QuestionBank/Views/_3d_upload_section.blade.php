{{--
    resources/views/admin/question_banks/partials/_3d_upload_section.blade.php

    Cara integrasi ke form.blade.php yang sudah dibuat sebelumnya:
    1. Tambahkan @push('scripts') <script src="{{ asset('js/network-quality-checker.js') }}"></script> @endpush
    2. Sisipkan blok ini di dalam card "Lampiran Media Soal", atau jadikan card terpisah.
    3. Di dalam x-data induk form, tambahkan properti berikut ke fungsi questionForm():
         networkStatus: null,
         has3DFile: false,
       lalu di init() tambahkan:
         this.initNetworkChecker();
       dan tambahkan method:
         initNetworkChecker() {
             this.netChecker = new NetworkQualityChecker({
                 testFileUrl: '{{ route('admin.network-probe') }}',
                 testFileSizeKB: 300,
                 checkInterval: 15000,
                 onUpdate: (result) => { this.networkStatus = result; },
             });
             this.netChecker.start();
         },
         onSelect3DFile(event) {
             this.has3DFile = event.target.files.length > 0;
         },
         async confirmSubmitIfNeeded(event) {
             if (this.has3DFile && this.networkStatus?.status === 'poor') {
                 event.preventDefault();
                 const result = await this.netChecker.checkNow(); // cek ulang, jangan andalkan data lama
                 if (result.status === 'poor') {
                     if (confirm('Koneksi Anda tidak stabil. Upload model 3D berisiko gagal di tengah jalan. Tetap lanjutkan?')) {
                         event.target.closest('form').submit();
                     }
                     return;
                 }
             }
             event.target.closest('form').submit();
         }
    4. Ubah tombol submit form menjadi:
         <button type="button" class="btn btn-primary" @click="confirmSubmitIfNeeded($event)">
             <i class="fa fa-save"></i> Simpan Soal
         </button>
       (dari <button type="submit"> menjadi type="button" + handler manual,
       supaya bisa disisipi pengecekan sebelum form benar-benar submit)
--}}

<div class="card mb-3">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span>Model 3D (opsional)</span>

        {{-- Badge status koneksi, warna berubah otomatis via Alpine --}}
        <span class="badge"
            :class="{
                'bg-success': networkStatus?.status === 'stable',
                'bg-warning text-dark': networkStatus?.status === 'unstable',
                'bg-danger': networkStatus?.status === 'poor',
                'bg-secondary': !networkStatus,
            }">
            <i class="fa fa-wifi"></i>
            <span x-text="networkStatus?.label ?? 'Mengecek koneksi...'"></span>
        </span>
    </div>

    <div class="card-body">

        {{-- Banner peringatan, hanya tampil kalau status bukan stabil --}}
        <div class="alert alert-warning d-flex align-items-start gap-2 mb-3"
            x-show="networkStatus && networkStatus.status !== 'stable'" x-cloak>
            <i class="fa fa-triangle-exclamation mt-1"></i>
            <div>
                <div class="fw-bold" x-text="networkStatus?.label"></div>
                <div class="small" x-text="networkStatus?.message"></div>
                <div class="small text-muted mt-1">
                    Estimasi kecepatan: <span x-text="networkStatus?.downlinkMbps"></span> Mbps
                </div>
            </div>
        </div>

        <label class="form-label">Upload Model 3D (.glb)</label>
        <input type="file" name="question_media_3d" class="form-control" accept=".glb"
            @change="onSelect3DFile($event)">
        <small class="text-muted d-block mt-1">
            Format wajib .glb (sudah dikompresi Draco), maksimal 10MB. Format lain (.gltf, .obj, .fbx)
            harus dikonversi dulu sebelum upload.
        </small>

        {{-- Preview ringan pakai model-viewer, hanya kalau ada file lama (mode edit) --}}
        @if (isset($question) && $question->media->where('media_type', 'model_3d')->first())
            @php($model3d = $question->media->where('media_type', 'model_3d')->first())
            <div class="mt-3">
                <label class="form-label d-block">Model Saat Ini</label>
                <model-viewer src="{{ Storage::url($model3d->file_path) }}"
                    style="width: 100%; max-width: 400px; height: 300px;" camera-controls auto-rotate>
                </model-viewer>
            </div>
        @endif

    </div>
</div>

{{-- Load <model-viewer> web component untuk preview (self-host jika target deploy
     memungkinkan offline; untuk cloud-based boleh dari CDN seperti contoh ini) --}}
@push('scripts')
    <script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
@endpush
