<div class="card border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <div>
            <h5 class="mb-0 fw-bold">Slip Gaji: {{ $payslip->payslip_number }}</h5>
            <small class="opacity-75">Periode: {{ $payslip->payrollPeriod?->period ?? '-' }}</small>
        </div>
        <div>
            {!! $payslip->status_label !!}
        </div>
    </div>
    <div class="card-body p-4">
        {{-- Info Karyawan & Perusahaan --}}
        <div class="row mb-4 pb-3 border-bottom">
            <div class="col-sm-6">
                <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size:11px; letter-spacing:1px;">Informasi Karyawan</h6>
                <div class="d-flex flex-column gap-1">
                    <span class="fs-5 fw-bold text-dark">{{ $payslip->employee?->name ?? ($payslip->payrollDetail?->employee_name_snapshot ?? '-') }}</span>
                    <span class="text-muted small">No. Karyawan: <strong>{{ $payslip->employee?->employee_number ?? ($payslip->payrollDetail?->employee_number_snapshot ?? '-') }}</strong></span>
                    <span class="text-muted small">Jabatan / Posisi: {{ $payslip->employee?->position?->name ?? ($payslip->payrollDetail?->position_name_snapshot ?? '-') }}</span>
                    <span class="text-muted small">Departemen: {{ $payslip->employee?->department?->name ?? ($payslip->payrollDetail?->department_name_snapshot ?? '-') }}</span>
                </div>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size:11px; letter-spacing:1px;">Detail Dokumen</h6>
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted small">Tanggal Cetak: <strong>{{ $payslip->generated_at ? $payslip->generated_at->translatedFormat('d F Y H:i') : '-' }}</strong></span>
                    <span class="text-muted small">Mata Uang: <strong>{{ $payslip->currency ?? 'IDR' }}</strong></span>
                    <span class="text-muted small">Status Pengiriman:
                        @if ($payslip->is_sent)
                            <span class="text-success fw-semibold">Terkirim ({{ $payslip->sent_at?->format('d/m/Y H:i') }})</span>
                        @else
                            <span class="text-warning fw-semibold">Belum Terkirim</span>
                        @endif
                    </span>
                    <span class="text-muted small">Status Unduhan:
                        @if ($payslip->is_downloaded)
                            <span class="text-info fw-semibold">Diunduh ({{ $payslip->downloaded_at?->format('d/m/Y H:i') }})</span>
                        @else
                            <span class="text-muted">Belum Diunduh</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Rincian Penghasilan & Potongan --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="border rounded-3 p-3 h-100 bg-light">
                    <h6 class="fw-bold text-success mb-3 pb-2 border-bottom">Penghasilan (Earnings)</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>Gaji Pokok / Kotor</td>
                                <td class="text-end fw-semibold">Rp {{ number_format((float) ($payslip->gross_salary ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Total Tunjangan</td>
                                <td class="text-end fw-semibold">Rp {{ number_format((float) ($payslip->total_allowance ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded-3 p-3 h-100 bg-light">
                    <h6 class="fw-bold text-danger mb-3 pb-2 border-bottom">Potongan (Deductions)</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>Total Potongan</td>
                                <td class="text-end fw-semibold text-danger">Rp {{ number_format((float) ($payslip->total_deduction ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Total Take Home Pay --}}
        <div class="bg-primary-subtle border border-primary-subtle rounded-3 p-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold text-primary mb-0">GAJI BERSIH (TAKE HOME PAY)</h6>
                <small class="text-muted">Total penghasilan setelah dikurangi seluruh potongan</small>
            </div>
            <div class="text-end">
                <span class="fs-4 fw-bolder text-primary">Rp {{ number_format((float) ($payslip->net_salary ?? 0), 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
        <a href="{{ route('payslip.print', $payslip->id) }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
            <span class="material-symbols-outlined" style="font-size:16px;">print</span> Cetak Slip
        </a>
    </div>
</div>

