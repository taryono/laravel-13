<?php
// app/Http/Controllers/Ppdb/PaymentController.php

namespace App\Http\Controllers\Ppdb\Applicant;

use App\Http\Controllers\Controller;
use Models\Applicant;
use Models\ApplicantInvoice;
use Models\ApplicantPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function invoice(Applicant $applicant)
    {
        $invoice = $applicant->invoices()->with('items')->latest()->firstOrFail();
        return view('ppdb.invoice', compact('applicant', 'invoice'));
    }

    public function store(Request $request, ApplicantInvoice $invoice)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,debit_card,virtual_account,ewallet,check,other',
            'amount'         => 'required|numeric|min:0',
            'bank_name'      => 'nullable|string|max:100',
            'reference_code' => 'nullable|string|max:100',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $path = null;
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store("ppdb/payments/{$invoice->id}", 'public');
            }

            $payment = ApplicantPayment::create([
                'invoice_id'         => $invoice->id,
                'applicant_id'       => $invoice->applicant_id,
                'payment_method'     => $request->payment_method,
                'amount'             => $request->amount,
                'payment_date'       => now(),
                'bank_name'          => $request->bank_name,
                'reference_number'   => $request->reference_code ?? $request->reference_number,
                'payment_proof_url'  => $path ? \Illuminate\Support\Facades\Storage::url($path) : null,
                'status'             => 'pending',
                'created_by'         => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('ppdb.invoice', $invoice->applicant_id)
                ->with('success', 'Pembayaran berhasil dikirim, menunggu verifikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
