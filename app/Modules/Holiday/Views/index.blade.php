@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Holiday</h5>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="ajax-modal" data-size="lg" data-method="GET"
                    data-title="Tambah Data Holiday Baru" data-url="{{ route('holiday.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formHoliday">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                    <i class="bi bi-plus-circle"></i> Tambah Baru
                </button>

            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-striped w-100']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. EVENT DELEGATION untuk Checkbox "isRange"
            // Mendengarkan event 'change' di seluruh document, tapi hanya bereaksi jika targetnya adalah #isRange
            document.addEventListener('change', function(e) {
                if (e.target && e.target.id === 'isRange') {
                    toggleEndDate(e.target);
                }
            });

            // 2. EVENT DELEGATION untuk Validasi Tanggal
            document.addEventListener('change', function(e) {
                if (e.target && (e.target.id === 'start_date' || e.target.id === 'end_date')) {
                    validateDates();
                }
            });

            // 3. Fungsi Helper: Toggle Tampilan
            function toggleEndDate(checkbox) {
                const endDateContainer = document.getElementById('end_date_container');
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                if (!endDateContainer || !startDateInput || !endDateInput) return;

                if (checkbox.checked) {
                    endDateContainer.style.display = 'block';
                    endDateInput.setAttribute('required', 'required');
                    if (!endDateInput.value) {
                        endDateInput.value = startDateInput.value;
                    }
                } else {
                    endDateContainer.style.display = 'none';
                    endDateInput.removeAttribute('required');
                    endDateInput.value = '';
                    endDateInput.setCustomValidity(''); // Reset validasi
                }
            }

            // 4. Fungsi Helper: Validasi Tanggal
            function validateDates() {
                const isRangeCheckbox = document.getElementById('isRange');
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                if (isRangeCheckbox && isRangeCheckbox.checked && startDateInput.value && endDateInput.value) {
                    if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                        endDateInput.setCustomValidity(
                            'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
                    } else {
                        endDateInput.setCustomValidity('');
                    }
                }
            }

            // 5. Fungsi Inisialisasi (Dipanggil saat halaman load ATAU saat modal muncul)
            function initFormState() {
                const isRangeCheckbox = document.getElementById('isRange');
                if (isRangeCheckbox && isRangeCheckbox.checked) {
                    toggleEndDate(isRangeCheckbox);
                    validateDates();
                }
            }

            // Jalankan saat halaman pertama kali dimuat (untuk mode full-page create.blade.php)
            initFormState();

            // Jalankan SETIAP KALI modal Bootstrap selesai ditampilkan (untuk mode AJAX modal)
            // Ini adalah kunci agar script jalan di modal.js Anda!
            document.addEventListener('shown.bs.modal', function() {
                initFormState();
            });
        });
    </script>
@endpush
