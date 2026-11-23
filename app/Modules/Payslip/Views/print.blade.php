<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji_{{ $payslip->payslip_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }

        .slip-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 24px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        @media print {
            .slip-container {
                border: none;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="container slip-container bg-white">
        <div class="no-print mb-3 text-end">
            <button onclick="window.print()" class="btn btn-primary btn-sm">Cetak / Simpan PDF</button>
            <button onclick="window.close()" class="btn btn-secondary btn-sm">Tutup</button>
        </div>

        <div class="text-center border-bottom pb-3 mb-4">
            <h4 class="fw-bold mb-1">SLIP GAJI KARYAWAN</h4>
            <p class="text-muted mb-0">Nomor: {{ $payslip->payslip_number }} | Periode:
                {{ $payslip->payrollPeriod?->period ?? '-' }}</p>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:130px;">Nama Karyawan</td>
                        <td>:
                            <strong>{{ $payslip->employee?->name ?? ($payslip->payrollDetail?->employee_name_snapshot ?? '-') }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. Karyawan</td>
                        <td>:
                            {{ $payslip->employee?->employee_number ?? ($payslip->payrollDetail?->employee_number_snapshot ?? '-') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">NIK / Identitas</td>
                        <td>: {{ $payslip->employee?->nik ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:130px;">Departemen</td>
                        <td>:
                            {{ $payslip->employee?->department?->name ?? ($payslip->payrollDetail?->department_name_snapshot ?? '-') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Posisi / Jabatan</td>
                        <td>:
                            {{ $payslip->employee?->position?->name ?? ($payslip->payrollDetail?->position_name_snapshot ?? '-') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Cetak</td>
                        <td>:
                            {{ $payslip->generated_at ? $payslip->generated_at->translatedFormat('d F Y') : date('d F Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-6">
                <h6 class="fw-bold pb-2 border-bottom text-uppercase" style="font-size:12px;">Penerimaan / Penghasilan
                </h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td>Gaji Pokok / Kotor</td>
                        <td class="text-end">Rp {{ number_format((float) ($payslip->gross_salary ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Tunjangan</td>
                        <td class="text-end">Rp
                            {{ number_format((float) ($payslip->total_allowance ?? 0), 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-top fw-bold">
                        <td>Total Penerimaan</td>
                        <td class="text-end">Rp
                            {{ number_format((float) (($payslip->gross_salary ?? 0) + ($payslip->total_allowance ?? 0)), 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <h6 class="fw-bold pb-2 border-bottom text-uppercase" style="font-size:12px;">Potongan</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td>Total Potongan</td>
                        <td class="text-end">Rp
                            {{ number_format((float) ($payslip->total_deduction ?? 0), 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-top fw-bold">
                        <td>Total Potongan</td>
                        <td class="text-end">Rp
                            {{ number_format((float) ($payslip->total_deduction ?? 0), 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="p-3 bg-light border rounded-3 mb-5 d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-6">GAJI BERSIH (TAKE HOME PAY)</span>
            <span class="fw-bold fs-5 text-primary">Rp
                {{ number_format((float) ($payslip->net_salary ?? 0), 0, ',', '.') }}</span>
        </div>

        <div class="row text-center mt-5">
            <div class="col-6">
                <p class="mb-5">Diterima oleh,</p>
                <p class="fw-bold mb-0">({{ $payslip->employee?->name ?? 'Karyawan' }})</p>
            </div>
            <div class="col-6">
                <p class="mb-5">Bagian Keuangan / Payroll,</p>
                <p class="fw-bold mb-0">( _______________________ )</p>
            </div>
        </div>
    </div>
</body>

</html>
