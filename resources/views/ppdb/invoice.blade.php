{{-- resources/views/ppdb/invoice.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Pendaftaran PPDB - {{ $applicant->registration_number }}</title>

    {{-- Fonts & Bootstrap --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --brand-primary: #00288e;
            --brand-primary-light: #1e40af;
            --brand-surface: #f8faff;
            --brand-navy: #09122c;
            --brand-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--brand-surface);
            color: #1e293b;
            min-height: 100vh;
        }

        .invoice-card {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 40, 142, 0.06);
            border: 1px solid var(--brand-border);
            overflow: hidden;
        }

        .invoice-header-strip {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-primary-light) 100%);
            color: #fff;
            padding: 2rem;
        }

        .badge-status {
            font-size: 0.85rem;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .payment-box {
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 0.75rem;
            padding: 1.25rem;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #fff;
            }
            .invoice-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body class="py-4 py-md-5">

    <div class="container" style="max-width: 900px;">

        {{-- Top Navigation / Back --}}
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="{{ route('ppdb.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.85rem;">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda PPDB
            </a>
            <button onclick="window.print()" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2 fw-bold" style="background: var(--brand-primary); font-size: 0.85rem;">
                <i class="fa-solid fa-print"></i> Cetak Invoice
            </button>
        </div>

        {{-- Flash Success Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="fa-solid fa-circle-check fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Flash Errors Alert --}}
        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center gap-2 fw-bold mb-1">
                    <i class="fa-solid fa-triangle-exclamation"></i> Terjadi kesalahan:
                </div>
                <ul class="mb-0 ps-3" style="font-size: 0.88rem;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- MAIN INVOICE CARD --}}
        <div class="invoice-card mb-4">
            {{-- Header Strip --}}
            <div class="invoice-header-strip d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fa-solid fa-graduation-cap fs-4"></i>
                        <span class="fw-bold tracking-wide" style="font-size: 1.1rem;">PORTAL RESMI PPDB ONLINE</span>
                    </div>
                    <p class="mb-0 text-white-50" style="font-size: 0.85rem;">
                        {{ $applicant->admission?->name ?? 'Penerimaan Peserta Didik Baru' }} &bull; {{ $applicant->school?->name ?? 'SMAN 3 Bandung' }}
                    </p>
                </div>

                <div class="text-md-end">
                    <span class="d-block text-white-50" style="font-size: 0.75rem;">STATUS TAGIHAN</span>
                    @php
                        $invStatus = strtolower($invoice->status ?? 'unpaid');
                        $badgeBg = match ($invStatus) {
                            'paid' => 'bg-success text-white',
                            'pending' => 'bg-warning text-dark',
                            default => 'bg-danger text-white'
                        };
                        $statusLabel = match ($invStatus) {
                            'paid' => 'LUNAS / TERVERIFIKASI',
                            'pending' => 'MENUNGGU KONFIRMASI',
                            default => 'BELUM DIBAYAR'
                        };
                    @endphp
                    <span class="badge {{ $badgeBg }} badge-status mt-1">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            {{-- Invoice Body --}}
            <div class="p-4 p-md-5">
                <div class="row g-4 mb-4">
                    <div class="col-sm-6">
                        <span class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Ditagihkan Kepada:</span>
                        <h5 class="fw-bold text-dark mt-1 mb-1">{{ $applicant->name }}</h5>
                        <div class="text-muted" style="font-size: 0.85rem; line-height: 1.5;">
                            <div>No. Registrasi: <strong>{{ $applicant->registration_number }}</strong></div>
                            <div>NIK: {{ $applicant->nik }}</div>
                            <div>Asal Sekolah: {{ $applicant->previous_school ?? '-' }}</div>
                            <div>No. HP / WA: {{ $applicant->phone ?? $applicant->parent_phone ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-sm-6 text-sm-end">
                        <span class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Detail Tagihan:</span>
                        <div class="mt-1" style="font-size: 0.85rem; line-height: 1.6;">
                            <div>Nomor Invoice: <strong class="text-primary">{{ $invoice->invoice_number }}</strong></div>
                            <div>Tanggal Terbit: {{ \Carbon\Carbon::parse($invoice->created_at)->translatedFormat('d F Y') }}</div>
                            <div>Batas Pembayaran: <strong class="text-danger">{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d F Y, H:i') }} WIB</strong></div>
                        </div>
                    </div>
                </div>

                {{-- Item Table --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;" class="text-center">No.</th>
                                <th>Rincian Biaya</th>
                                <th class="text-end" style="width: 200px;">Jumlah (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->items as $idx => $item)
                                <tr>
                                    <td class="text-center">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->name ?? 'Biaya Administrasi & Seleksi Pendaftaran' }}</div>
                                        <small class="text-muted">Biaya registrasi peserta didik baru gelombang aktif</small>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        Rp {{ number_format($item->amount ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        <div class="fw-semibold">Biaya Pendaftaran PPDB {{ $applicant->admission?->code ?? '' }}</div>
                                        <small class="text-muted">Biaya registrasi peserta didik baru gelombang reguler</small>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        Rp {{ number_format($invoice->total_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end fw-bold">TOTAL TAGIHAN:</th>
                                <th class="text-end fw-bold text-primary fs-5">
                                    Rp {{ number_format($invoice->final_amount ?? $invoice->total_amount ?? 0, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Payment Instructions --}}
                <div class="payment-box mb-4">
                    <h6 class="fw-bold mb-2 text-dark"><i class="fa-solid fa-building-columns text-primary me-2"></i>Petunjuk Pembayaran Transfer Bank:</h6>
                    <div class="row g-3" style="font-size: 0.85rem;">
                        <div class="col-sm-6">
                            <span class="text-muted d-block">Bank Tujuan:</span>
                            <strong class="fs-6 text-dark">BANK BJB / BRI (Virtual Account)</strong>
                            <div class="mt-2">
                                <span class="text-muted d-block">Nomor Rekening / VA:</span>
                                <span class="badge bg-dark text-white px-3 py-2 fs-6 font-monospace">1098 7726 {{ substr($applicant->nik, -6) }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block">Nama Rekening:</span>
                            <strong class="text-dark">PPDB {{ $applicant->school?->name ?? 'SMAN 3 BANDUNG' }}</strong>
                            <div class="mt-2 text-muted">
                                Sertakan berita transfer: <code>{{ $applicant->registration_number }}</code>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Confirmation Form --}}
                @if ($invStatus !== 'paid')
                    <div class="border rounded-3 p-4 no-print" style="background: #fafcff;">
                        <h5 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-receipt text-success me-2"></i>Konfirmasi Bukti Pembayaran</h5>
                        <p class="text-muted mb-3" style="font-size: 0.85rem;">
                            Setelah melakukan pembayaran, unggah bukti transfer di bawah ini agar panitia segera memverifikasi akun Anda.
                        </p>

                        <form action="{{ route('ppdb.payment.store', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem;">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select form-select-sm" required>
                                        <option value="bank_transfer" selected>Transfer Bank / M-Banking</option>
                                        <option value="virtual_account">Virtual Account</option>
                                        <option value="ewallet">E-Wallet (QRIS / GoPay / OVO)</option>
                                        <option value="cash">Tunai di Loket Sekolah</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nominal yang Ditransfer (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control form-control-sm"
                                        value="{{ old('amount', $invoice->final_amount ?? $invoice->total_amount ?? 0) }}" required min="0">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem;">Bank Pengirim / Asal</label>
                                    <input type="text" name="bank_name" class="form-control form-control-sm"
                                        placeholder="Contoh: BCA / Mandiri / BRI / BJB" value="{{ old('bank_name') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem;">Nomor Referensi / Kode Transaksi</label>
                                    <input type="text" name="reference_code" class="form-control form-control-sm"
                                        placeholder="Contoh: TRX-99281726" value="{{ old('reference_code') }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold" style="font-size: 0.85rem;">Unggah Bukti Struk / Resi Transfer (Foto / PDF)</label>
                                    <input type="file" name="attachment" class="form-control form-control-sm" accept="image/*,application/pdf">
                                    <div class="form-text" style="font-size: 0.78rem;">Maksimal ukuran file: 2 MB (.jpg, .png, .pdf)</div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-2 px-4 py-2 fw-bold" style="font-size: 0.9rem;">
                                        <i class="fa-solid fa-paper-plane"></i> Kirim Konfirmasi Pembayaran
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-success d-flex align-items-center gap-3 p-3 rounded-3 mb-0">
                        <i class="fa-solid fa-circle-check fs-2"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Pembayaran Telah Dikonfirmasi</h6>
                            <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                Terima kasih, transaksi Anda telah diverifikasi oleh bendahara panitia PPDB.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="p-3 bg-light text-center border-top text-muted" style="font-size: 0.78rem;">
                Invoice ini sah dan digenerate otomatis oleh Sistem Informasi PPDB Online Kemendikbudristek &bull; Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
