<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Pendaftaran Berhasil & Tagihan Pembayaran PPDB</title>
    <!--[if mso]>
    <style>
        * { font-family: 'Segoe UI', Arial, sans-serif !important; }
    </style>
    <![endif]-->
    <style>
        /* CSS Reset & Base */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; display: block; }
        body { margin: 0; padding: 0; width: 100% !important; background-color: #f1f5f9; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155; line-height: 1.6; }

        /* Links */
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* Responsive */
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .mobile-p-15 { padding-left: 16px !important; padding-right: 16px !important; }
            .mobile-p-20 { padding: 20px 16px !important; }
            .mobile-stack { display: block !important; width: 100% !important; box-sizing: border-box !important; }
            .mobile-mb-15 { margin-bottom: 15px !important; }
            .va-number { font-size: 20px !important; letter-spacing: 2px !important; }
            .heading-title { font-size: 20px !important; line-height: 28px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9;">

    <!-- Preview Text (Hidden in body, visible in inbox preview) -->
    <div style="display: none; font-size: 1px; color: #f1f5f9; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        Selamat! Pendaftaran PPDB Anda telah berhasil. Silakan selesaikan pembayaran tagihan via Virtual Account dan simpan akun login portal pendaftar Anda.
        &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy; &#847; &zwnj; &nbsp; &#8199; &shy;
    </div>

    <!-- Main Wrapper Table -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; padding: 25px 10px;">
        <tr>
            <td align="center">

                <!-- Email Container (Max 600px) -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
                    
                    <!-- 1. HEADER SECTION -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%); padding: 36px 30px 30px; text-align: center;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <!-- Logo / Brand Icon -->
                                        @if(!empty($schoolLogoUrl ?? null))
                                            <img src="{{ $schoolLogoUrl }}" alt="Logo Sekolah" width="70" height="70" style="max-width: 70px; margin-bottom: 14px; border-radius: 50%; background: #ffffff; padding: 4px;">
                                        @else
                                            <div style="display: inline-block; width: 64px; height: 64px; line-height: 64px; border-radius: 16px; background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); margin-bottom: 14px; text-align: center;">
                                                <span style="font-size: 32px; color: #ffffff;">🎓</span>
                                            </div>
                                        @endif
                                        
                                        <div style="font-size: 13px; font-weight: 700; color: #93c5fd; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;">
                                            {{ $schoolName ?? config('app.name', 'Sistem PPDB Online') }}
                                        </div>
                                        
                                        <h1 class="heading-title" style="margin: 0 0 10px; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                            Pendaftaran Berhasil Diterima!
                                        </h1>
                                        
                                        <!-- Badge Status -->
                                        <div style="display: inline-block; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 9999px; padding: 4px 14px; font-size: 12px; font-weight: 700; color: #065f46;">
                                            ✓ Tahap 1: Registrasi Selesai
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- 2. GREETING & SUMMARY -->
                    <tr>
                        <td class="mobile-p-20" style="padding: 30px 32px 20px;">
                            <p style="margin: 0 0 14px; font-size: 16px; font-weight: 600; color: #0f172a;">
                                Yth. Sdr/i {{ $applicant->name ?? 'Calon Peserta Didik' }},
                            </p>
                            <p style="margin: 0 0 18px; font-size: 14px; color: #475569; line-height: 1.7;">
                                Terima kasih telah menyelesaikan pengisian formulir pendaftaran <strong>PPDB Tahun Ajaran {{ $academicYear ?? date('Y') . '/' . (date('Y') + 1) }}</strong>. Data pendaftaran Anda telah tersimpan di sistem kami dengan rincian sebagai berikut:
                            </p>

                            <!-- Biodata Singkat Table -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; margin-bottom: 24px;">
                                <tr>
                                    <td width="38%" style="padding: 6px 0; font-size: 13px; color: #64748b;">No. Registrasi</td>
                                    <td width="4%" style="padding: 6px 0; font-size: 13px; color: #64748b;">:</td>
                                    <td width="58%" style="padding: 6px 0; font-size: 13px; font-weight: 700; color: #0f172a;">
                                        <code style="background-color: #e0e7ff; color: #3730a3; padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 13px;">
                                            {{ $applicant->registration_number ?? '-' }}
                                        </code>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">Nama Pendaftar</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; font-size: 13px; font-weight: 600; color: #1e293b;">
                                        {{ $applicant->name ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">Jalur Pendaftaran</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; font-size: 13px; font-weight: 600; color: #1e293b;">
                                        {{ $applicant->_path->name ?? $applicant->admission->name ?? 'Reguler' }}
                                    </td>
                                </tr>
                                @if(!empty($studyProgramName ?? null))
                                <tr>
                                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">Pilihan Jurusan</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; font-size: 13px; font-weight: 600; color: #1e293b;">
                                        {{ $studyProgramName }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">Waktu Pendaftaran</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #64748b;">:</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #475569;">
                                        {{ !empty($applicant->registered_at) ? \Carbon\Carbon::parse($applicant->registered_at)->translatedFormat('d F Y, H:i') . ' WIB' : now()->translatedFormat('d F Y, H:i') . ' WIB' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- 3. KREDENSIAL AKUN LOGIN SISTEM -->
                    <tr>
                        <td class="mobile-p-20" style="padding: 0 32px 24px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 14px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 18px 20px 14px; border-bottom: 1px solid #dbeafe;">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="30" valign="middle">
                                                    <span style="font-size: 20px; line-height: 1;">🔐</span>
                                                </td>
                                                <td valign="middle">
                                                    <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #1e3a8a;">
                                                        Informasi Akun Portal Pendaftar
                                                    </h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <p style="margin: 0 0 14px; font-size: 13px; color: #1e40af; line-height: 1.5;">
                                            Gunakan akun berikut untuk masuk ke portal pendaftar guna melihat status seleksi, mengunggah dokumen, dan mencetak kartu ujian:
                                        </p>

                                        <!-- Credential Box -->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px;">
                                            <tr>
                                                <td width="40%" style="padding: 5px 0; font-size: 13px; color: #64748b;">
                                                    <strong>Username / ID</strong>
                                                </td>
                                                <td width="60%" style="padding: 5px 0; font-size: 13px; font-weight: 700; color: #0f172a;">
                                                    {{ $loginUsername ?? $applicant->registration_number ?? $applicant->nik }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0; font-size: 13px; color: #64748b;">
                                                    <strong>Kata Sandi</strong>
                                                </td>
                                                <td style="padding: 5px 0; font-size: 13px; font-weight: 700; color: #2563eb;">
                                                    {{ $loginPassword ?? (!empty($applicant->birth_date) ? \Carbon\Carbon::parse($applicant->birth_date)->format('dmY') : 'Tgl Lahir (HHBBTTTT)') }}
                                                    <span style="display: block; font-size: 11px; font-weight: normal; color: #94a3b8; margin-top: 2px;">
                                                        (Format tgl lahir pendaftar: HHBBTTTT)
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- CTA Button -->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td align="center">
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td align="center" style="border-radius: 8px; background: #2563eb;">
                                                                <a href="{{ $loginUrl ?? route('ppdb.login') }}" target="_blank" style="display: inline-block; padding: 11px 24px; font-size: 14px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px; border: 1px solid #1d4ed8;">
                                                                    Masuk ke Portal Pendaftar &rarr;
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- 4. RINCIAN TAGIHAN & VIRTUAL ACCOUNT -->
                    <tr>
                        <td class="mobile-p-20" style="padding: 0 32px 24px;">
                            
                            <!-- Header Tagihan -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 12px;">
                                <tr>
                                    <td>
                                        <h3 style="margin: 0 0 4px; font-size: 16px; font-weight: 700; color: #0f172a;">
                                            💳 Tagihan Biaya Pendaftaran
                                        </h3>
                                        <p style="margin: 0; font-size: 13px; color: #64748b;">
                                            Silakan lakukan pembayaran sebelum batas waktu berakhir.
                                        </p>
                                    </td>
                                    <td align="right" valign="top">
                                        <span style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; text-transform: uppercase;">
                                            {{ strtoupper($invoice->status ?? 'MENUNGGU PEMBAYARAN') }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Kotak Virtual Account (High-Contrast Hero Box) -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 14px; color: #ffffff; padding: 22px 24px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);">
                                <tr>
                                    <td>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">
                                                    Bank Pembayaran
                                                </td>
                                                <td align="right" style="font-size: 12px; font-weight: 700; color: #38bdf8;">
                                                    {{ $bankName ?? 'Bank BNI / Mandiri / BRI / BCA' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding-top: 14px; padding-bottom: 6px;">
                                                    <span style="font-size: 11px; font-weight: 600; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.5px;">
                                                        Nomor Virtual Account (VA):
                                                    </span>
                                                    <div class="va-number" style="font-size: 26px; font-weight: 800; font-family: 'Courier New', Courier, monospace; letter-spacing: 3px; color: #38bdf8; margin: 4px 0 2px;">
                                                        {{ $vaNumber ?? '8808' . str_pad($applicant->id ?? 1, 10, '0', STR_PAD_LEFT) }}
                                                    </div>
                                                    <div style="font-size: 12px; color: #94a3b8;">
                                                        a.n. <strong style="color: #f1f5f9;">{{ $vaAccountName ?? 'PPDB - ' . ($applicant->name ?? 'CALON SISWA') }}</strong>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-top: 1px dashed #334155; padding-top: 14px; margin-top: 10px;">
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 11px; color: #94a3b8;">Total Tagihan:</span>
                                                                <div style="font-size: 20px; font-weight: 800; color: #34d399;">
                                                                    Rp {{ number_format($invoice->final_amount ?? ($applicant->admission->registration_fee ?? 250000), 0, ',', '.') }}
                                                                </div>
                                                            </td>
                                                            <td align="right" valign="bottom">
                                                                <span style="font-size: 11px; color: #fb7185;">Batas Waktu:</span>
                                                                <div style="font-size: 12px; font-weight: 700; color: #fecdd3;">
                                                                    {{ !empty($invoice->due_date) ? \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d M Y, H:i') . ' WIB' : now()->addDays(3)->translatedFormat('d M Y, H:i') . ' WIB' }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Rincian Biaya Table -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 24px;">
                                <thead>
                                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                        <th align="left" style="padding: 10px 16px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Deskripsi Item</th>
                                        <th align="right" style="padding: 10px 16px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($invoice->items) && count($invoice->items) > 0)
                                        @foreach($invoice->items as $item)
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 10px 16px; font-size: 13px; color: #334155;">
                                                {{ $item->name ?? 'Biaya Formulir & Seleksi PPDB' }}
                                            </td>
                                            <td align="right" style="padding: 10px 16px; font-size: 13px; font-weight: 600; color: #0f172a;">
                                                Rp {{ number_format($item->amount ?? 0, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 10px 16px; font-size: 13px; color: #334155;">
                                                Biaya Pendaftaran & Verifikasi Berkas
                                            </td>
                                            <td align="right" style="padding: 10px 16px; font-size: 13px; font-weight: 600; color: #0f172a;">
                                                Rp {{ number_format($invoice->final_amount ?? 250000, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if(!empty($invoice->discount_amount) && $invoice->discount_amount > 0)
                                    <tr style="border-bottom: 1px solid #f1f5f9; background-color: #f0fdf4;">
                                        <td style="padding: 10px 16px; font-size: 13px; color: #166534;">
                                            Potongan / Diskon
                                        </td>
                                        <td align="right" style="padding: 10px 16px; font-size: 13px; font-weight: 600; color: #166534;">
                                            - Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endif

                                    <!-- Total Row -->
                                    <tr style="background-color: #f8fafc;">
                                        <td style="padding: 12px 16px; font-size: 14px; font-weight: 700; color: #0f172a;">
                                            Total Pembayaran
                                        </td>
                                        <td align="right" style="padding: 12px 16px; font-size: 16px; font-weight: 800; color: #2563eb;">
                                            Rp {{ number_format($invoice->final_amount ?? ($applicant->admission->registration_fee ?? 250000), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </td>
                    </tr>

                    <!-- 5. PETUNJUK CARA PEMBAYARAN VIRTUAL ACCOUNT -->
                    <tr>
                        <td class="mobile-p-20" style="padding: 0 32px 28px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
                                <tr>
                                    <td>
                                        <h4 style="margin: 0 0 12px; font-size: 14px; font-weight: 700; color: #0f172a;">
                                            📖 Panduan Cara Pembayaran VA:
                                        </h4>

                                        <!-- Langkah Mobile Banking -->
                                        <div style="margin-bottom: 12px;">
                                            <strong style="font-size: 13px; color: #1e40af;">A. Melalui Mobile Banking (BCA / Mandiri / BNI / BRI / Lainnya)</strong>
                                            <ol style="margin: 6px 0 0 18px; padding: 0; font-size: 12px; color: #475569; line-height: 1.6;">
                                                <li>Buka aplikasi Mobile Banking pilihan Anda & login.</li>
                                                <li>Pilih menu <strong>Transfer</strong> &rarr; pilih <strong>Virtual Account</strong>.</li>
                                                <li>Masukkan Nomor Virtual Account: <strong>{{ $vaNumber ?? '8808' . str_pad($applicant->id ?? 1, 10, '0', STR_PAD_LEFT) }}</strong>.</li>
                                                <li>Pastikan nama siswa (<strong>{{ $applicant->name ?? '-' }}</strong>) dan nominal tagihan sudah sesuai.</li>
                                                <li>Konfirmasi dan masukkan PIN transaksi Anda hingga bukti pembayaran berhasil.</li>
                                            </ol>
                                        </div>

                                        <!-- Langkah ATM -->
                                        <div>
                                            <strong style="font-size: 13px; color: #1e40af;">B. Melalui Mesin ATM</strong>
                                            <ol style="margin: 6px 0 0 18px; padding: 0; font-size: 12px; color: #475569; line-height: 1.6;">
                                                <li>Masukkan Kartu ATM dan PIN Anda.</li>
                                                <li>Pilih Menu <strong>Transaksi Lainnya</strong> &rarr; <strong>Transfer / Pembayaran</strong> &rarr; <strong>Virtual Account</strong>.</li>
                                                <li>Ketik nomor VA dan selesaikan transaksi. Simpan struk bukti transaksi Anda.</li>
                                            </ol>
                                        </div>

                                        <div style="margin-top: 14px; padding: 10px 14px; background-color: #ecfdf5; border-radius: 8px; border: 1px solid #a7f3d0; font-size: 12px; color: #065f46;">
                                            ⚡ <strong>Verifikasi Otomatis:</strong> Pembayaran menggunakan Virtual Account akan terverifikasi secara otomatis oleh sistem dalam 1 - 5 menit tanpa perlu konfirmasi manual.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- 6. INFORMASI BANTUAN & CONTACT -->
                    <tr>
                        <td class="mobile-p-20" style="padding: 0 32px 30px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #0f172a;">
                                            Butuh Bantuan atau Ada Pertanyaan?
                                        </p>
                                        <p style="margin: 0 0 12px; font-size: 12px; color: #64748b; line-height: 1.6;">
                                            Jika mengalami kendala terkait pembayaran atau verifikasi data, tim panitia PPDB kami siap membantu melalui saluran resmi:
                                        </p>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding-right: 12px;">
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $helpdeskWhatsapp ?? '6281234567890') }}" target="_blank" style="display: inline-block; background-color: #25d366; color: #ffffff; font-size: 12px; font-weight: 700; padding: 7px 14px; border-radius: 6px; text-decoration: none;">
                                                        💬 WhatsApp Helpdesk
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="mailto:{{ $helpdeskEmail ?? 'ppdb@sekolah.sch.id' }}" style="display: inline-block; background-color: #f1f5f9; color: #334155; font-size: 12px; font-weight: 600; padding: 7px 14px; border-radius: 6px; border: 1px solid #cbd5e1; text-decoration: none;">
                                                        ✉️ Email Panitia
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- 7. FOOTER SECTION -->
                    <tr>
                        <td align="center" style="background-color: #0f172a; padding: 26px 30px; text-align: center; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                            <p style="margin: 0 0 6px; font-size: 13px; font-weight: 700; color: #f8fafc;">
                                {{ $schoolName ?? 'Panitia Penerimaan Peserta Didik Baru (PPDB)' }}
                            </p>
                            <p style="margin: 0 0 14px; font-size: 11px; color: #94a3b8; line-height: 1.5; max-width: 480px;">
                                {{ $schoolAddress ?? 'Jl. Pendidikan No. 123, Kota Bandung, Jawa Barat' }}<br>
                                Website: <a href="{{ config('app.url') }}" target="_blank" style="color: #38bdf8; text-decoration: none;">{{ config('app.url') }}</a> | Jam Layanan: Senin - Jumat (08.00 - 15.00 WIB)
                            </p>
                            <div style="border-top: 1px solid #1e293b; padding-top: 12px; font-size: 10px; color: #64748b; line-height: 1.5;">
                                Email ini dibuat secara otomatis oleh sistem. Mohon jangan membalas langsung ke alamat email ini.<br>
                                &copy; {{ date('Y') }} {{ $schoolName ?? config('app.name') }}. Hak Cipta Dilindungi Undang-Undang.
                            </div>
                        </td>
                    </tr>

                </table>
                <!-- /Email Container -->

            </td>
        </tr>
    </table>
    <!-- /Main Wrapper Table -->

</body>
</html>
