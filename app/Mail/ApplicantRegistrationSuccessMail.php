<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Models\Applicant;
use Models\ApplicantInvoice;

class ApplicantRegistrationSuccessMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Applicant $applicant;
    public ApplicantInvoice $invoice;
    public array $extra;

    /**
     * Create a new message instance.
     */
    public function __construct(Applicant $applicant, ApplicantInvoice $invoice, array $extra = [])
    {
        $this->applicant = $applicant;
        $this->invoice   = $invoice;
        $this->extra     = $extra;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $schoolName = $this->extra['schoolName'] ?? ($this->applicant->school->name ?? config('app.name', 'PPDB Online'));
        $regNumber  = $this->applicant->registration_number;

        return new Envelope(
            subject: "Pendaftaran Berhasil & Tagihan Pembayaran PPDB [{$regNumber}] - {$schoolName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $school = $this->applicant->school ?? null;
        $admission = $this->applicant->admission ?? null;

        // Tentukan jurusan jika ada
        $choice = $this->applicant->applicant_choices->first();
        $studyProgramName = $choice?->study_program?->name ?? null;

        // Default Virtual Account (bisa disesuaikan dengan integrasi Payment Gateway seperti Midtrans/Xendit/Duitku)
        $defaultVa = '8808' . str_pad($this->applicant->id, 10, '0', STR_PAD_LEFT);

        return new Content(
            view: 'emails.ppdb.registration_success',
            with: array_merge([
                'applicant'         => $this->applicant,
                'invoice'           => $this->invoice,
                'schoolName'        => $this->extra['schoolName'] ?? ($school->name ?? config('app.name', 'PPDB Online')),
                'schoolLogoUrl'     => $this->extra['schoolLogoUrl'] ?? ($school->logo_url ?? null),
                'schoolAddress'     => $this->extra['schoolAddress'] ?? ($school->address ?? null),
                'academicYear'      => $this->extra['academicYear'] ?? ($admission->academic_year->name ?? date('Y') . '/' . (date('Y') + 1)),
                'studyProgramName'  => $this->extra['studyProgramName'] ?? $studyProgramName,
                
                // Kredensial Login
                'loginUrl'          => $this->extra['loginUrl'] ?? route('ppdb.login'),
                'loginUsername'     => $this->extra['loginUsername'] ?? ($this->applicant->registration_number ?? $this->applicant->nik),
                'loginPassword'     => $this->extra['loginPassword'] ?? (!empty($this->applicant->birth_date) ? \Carbon\Carbon::parse($this->applicant->birth_date)->format('dmY') : 'Tanggal Lahir (HHBBTTTT)'),
                
                // Rincian VA & Pembayaran
                'bankName'          => $this->extra['bankName'] ?? 'Bank BNI / Mandiri / BRI / BCA',
                'vaNumber'          => $this->extra['vaNumber'] ?? ($this->invoice->va_number ?? $defaultVa),
                'vaAccountName'     => $this->extra['vaAccountName'] ?? ('PPDB - ' . $this->applicant->name),
                'helpdeskWhatsapp'  => $this->extra['helpdeskWhatsapp'] ?? '081234567890',
                'helpdeskEmail'     => $this->extra['helpdeskEmail'] ?? 'ppdb@sekolah.sch.id',
            ], $this->extra)
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
