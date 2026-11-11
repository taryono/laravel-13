{{-- resources/views/ppdb/partials/_scripts.blade.php --}}
<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
    let APPLICANT_ID = {{ $applicant->id ?? 'null' }};
    const ADMISSION_ID = {{ $admission->id }};

    // ===== Jalur Chip =====
    document.querySelectorAll('input[name="admission_path_id_radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('admission_path_id').value = this.value;
            document.querySelectorAll('input[name="admission_path_id_radio"]').forEach(r => {
                r.closest('label').classList.toggle('active', r.checked);
            });
        });
    });

    // ===== Gender Chip =====
    document.querySelectorAll('input[name="gender"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="gender"]').forEach(r => {
                r.closest('label').classList.toggle('active', r.checked);
            });
        });
    });

    // ===== Checkbox Pill =====
    document.querySelectorAll('.check-pill input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', function() {
            this.closest('.check-pill').classList.toggle('active', this.checked);
        });
    });

    // ===== Load Programs (AJAX) =====
    async function loadPrograms(selectEl, choiceIndex) {
        const schoolId = selectEl.value;
        const programSelect = document.querySelector(`.program-select[data-choice="${choiceIndex}"]`);
        programSelect.innerHTML = '<option value="">Loading...</option>';

        if (!schoolId) {
            programSelect.innerHTML = '<option value="">-- Pilih Program --</option>';
            return;
        }

        try {
            const url = `{{ route('ppdb.api.programs', ['school' => '__ID__']) }}`.replace('__ID__', schoolId);
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            const programs = await res.json();

            programSelect.innerHTML = '<option value="">-- Pilih Program --</option>';
            programs.forEach(p => {
                programSelect.innerHTML += `<option value="${p.id}">${p.name}</option>`;
            });
        } catch (e) {
            console.error(e);
            programSelect.innerHTML = '<option value="">Error memuat data</option>';
        }
    }

    // ===== Deteksi Lokasi =====
    function detectLocation() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolokasi.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('geo-display').textContent =
                    `${lat.toFixed(6)}, ${lng.toFixed(6)} (Akurasi ${position.coords.accuracy.toFixed(0)} Meter)`;
            },
            error => alert('Gagal mendeteksi lokasi: ' + error.message), {
                enableHighAccuracy: true
            }
        );
    }

    // ===== Upload Dokumen (AJAX) =====
    async function uploadDocument(input, type, applicantId) {
        let targetId = applicantId || APPLICANT_ID;
        if (!targetId) {
            const draft = await saveDraft();
            if (draft && draft.applicant_id) {
                targetId = draft.applicant_id;
            }
        }
        if (!targetId) {
            alert('Simpan data pendaftaran terlebih dahulu sebelum upload dokumen.');
            return;
        }

        const file = input.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB.');
            return;
        }

        const formData = new FormData();
        formData.append('document_type', type);
        formData.append('file', file);

        const box = document.getElementById(`file-box-${type}`);
        const filenameEl = document.getElementById(`filename-${type}`);
        const filesizeEl = document.getElementById(`filesize-${type}`);

        filenameEl.textContent = 'Mengunggah...';

        try {
            const uploadUrl = `{{ route('ppdb.document.upload', ['applicant' => '__ID__']) }}`.replace('__ID__', targetId);
            const res = await fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                box.classList.add('file-box-uploaded');
                filenameEl.textContent = data.document.file_name;
                filesizeEl.textContent =
                    `${(data.document.file_size / 1024 / 1024).toFixed(2)} MB • Baru saja diunggah`;
                alert('Dokumen berhasil diunggah!');
            } else {
                alert('Gagal upload: ' + (data.message || 'Unknown error'));
                filenameEl.textContent = 'Belum ada file';
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan saat upload.');
            filenameEl.textContent = 'Belum ada file';
        }
    }

    // ===== Submit Final =====
    async function submitRegistration() {
        const checkbox = document.getElementById('agree-integritas');
        if (!checkbox || !checkbox.checked) {
            alert('Silakan setujui Pakta Integritas terlebih dahulu.');
            return;
        }

        if (!APPLICANT_ID) {
            const draft = await saveDraft();
            if (!draft || !draft.applicant_id) {
                alert('Silakan simpan data pendaftaran terlebih dahulu.');
                return;
            }
        }

        const submitUrl = `{{ route('ppdb.submit', ['applicant' => '__ID__']) }}`.replace('__ID__', APPLICANT_ID);

        // Buat form dinamis untuk submit
        const submitForm = document.createElement('form');
        submitForm.method = 'POST';
        submitForm.action = submitUrl;
        submitForm.innerHTML = `
            <input type="hidden" name="_token" value="${CSRF_TOKEN}">
            <input type="hidden" name="agree_integritas" value="1">
        `;
        document.body.appendChild(submitForm);
        submitForm.submit();
    }

    // ===== Auto-save Draft =====
    let autoSaveTimer;
    document.querySelectorAll('#ppdb-form input, #ppdb-form select, #ppdb-form textarea').forEach(el => {
        el.addEventListener('change', () => {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(saveDraft, 3000);
        });
    });

    async function saveDraft() {
        const form = document.getElementById('ppdb-form');
        const formData = new FormData(form);
        formData.append('_is_draft', '1');
        formData.append('admission_id', ADMISSION_ID);

        try {
            const draftUrl = `{{ route('ppdb.draft') }}` + (APPLICANT_ID ? `/${APPLICANT_ID}` : '');
            const res = await fetch(draftUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await res.json();
            if (data.success && data.applicant_id) {
                APPLICANT_ID = data.applicant_id;
            }
            console.log('Draft tersimpan', data);
            return data;
        } catch (e) {
            console.error('Gagal simpan draft', e);
            return null;
        }
    }
</script>
