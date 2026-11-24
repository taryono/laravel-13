<?php

namespace App\Services\Finance;

use App\Exceptions\InsufficientBudgetException;
use Models\BosPeriod;
use Models\BosRealization;
use Models\BosUsagePlan;
use Models\JournalEntry;
use Models\RkasBudget;
use Models\RkasItem;
use Models\RkasRealization;
use Models\RkasSource;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class RealizationService
{
    /**
     * Mencatat realisasi belanja RKAS secara atomik.
     *
     * @param array<string, mixed> $payload
     * @param int $userId
     * @return RkasRealization
     * @throws Throwable
     */
    public function record(array $payload, int $userId): RkasRealization
    {
        return DB::transaction(function () use ($payload, $userId) {
            $rkasItem = RkasItem::with([
                'component',
                'subActivity.activity.program.document',
            ])->lockForUpdate()->findOrFail($payload['rkas_item_id']);

            $rkasSource = RkasSource::lockForUpdate()->findOrFail($payload['rkas_source_id']);

            $rkasBudget = RkasBudget::where('rkas_item_id', $rkasItem->id)
                ->where('rkas_source_id', $rkasSource->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$rkasBudget) {
                throw new InvalidArgumentException(
                    "Item '{$rkasItem->name}' tidak memiliki alokasi anggaran dari sumber '{$rkasSource->name}'."
                );
            }

            $amount = (float) $payload['amount'];
            $taxAmount = (float) ($payload['tax_amount'] ?? 0);
            $grossUsed = $amount + $taxAmount;

            $alreadyRealized = (float) RkasRealization::where('rkas_item_id', $rkasItem->id)
                ->where('rkas_source_id', $rkasSource->id)
                ->where('status', '!=', 'VOID')
                ->sum(DB::raw('amount + tax_amount'));

            $remainingBudget = (float) $rkasBudget->amount - $alreadyRealized;

            if ($grossUsed > $remainingBudget) {
                throw new InsufficientBudgetException(
                    sprintf(
                        'Pagu anggaran tidak mencukupi. Sisa pagu: Rp %s, Nominal realisasi: Rp %s.',
                        number_format($remainingBudget, 2, ',', '.'),
                        number_format($grossUsed, 2, ',', '.')
                    )
                );
            }

            $isBosSource = strtoupper($rkasSource->source_type) === 'BOS';
            $bosPeriod = null;
            $bosUsagePlan = null;

            if ($isBosSource) {
                if (empty($payload['bos_period_id'])) {
                    throw new InvalidArgumentException('Parameter bos_period_id wajib diisi untuk sumber dana BOS.');
                }

                $bosPeriod = BosPeriod::lockForUpdate()->findOrFail($payload['bos_period_id']);

                if ((float) $bosPeriod->total_remaining < $grossUsed) {
                    throw new InsufficientBudgetException(
                        sprintf(
                            'Saldo kas periode BOS (%s) tidak mencukupi. Sisa kas: Rp %s.',
                            $bosPeriod->name,
                            number_format((float) $bosPeriod->total_remaining, 2, ',', '.')
                        )
                    );
                }

                $bosUsagePlan = BosUsagePlan::where('bos_period_id', $bosPeriod->id)
                    ->where('rkas_item_id', $rkasItem->id)
                    ->where('is_active', true)
                    ->first();
            }

            $journalEntry = $this->createJournalEntry(
                rkasItem: $rkasItem,
                rkasSource: $rkasSource,
                payload: $payload,
                amount: $amount,
                taxAmount: $taxAmount,
                userId: $userId
            );

            $rkasRealization = RkasRealization::create([
                'rkas_item_id' => $rkasItem->id,
                'rkas_source_id' => $rkasSource->id,
                'fiscal_period_id' => $payload['fiscal_period_id'],
                'journal_entry_id' => $journalEntry->id,
                'realization_date' => $payload['realization_date'],
                'description' => $payload['description'] ?? "Realisasi {$rkasItem->name}",
                'amount' => $amount,
                'tax_amount' => $taxAmount,
                'vendor_id' => $payload['vendor_id'] ?? null,
                'vendor_invoice_id' => $payload['vendor_invoice_id'] ?? null,
                'payment_reference' => $payload['payment_reference'] ?? null,
                'attachment_url' => $payload['attachment_url'] ?? null,
                'status' => 'RECORDED',
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            if ($isBosSource && $bosPeriod) {
                BosRealization::create([
                    'bos_period_id' => $bosPeriod->id,
                    'bos_usage_plan_id' => $bosUsagePlan?->id,
                    'rkas_realization_id' => $rkasRealization->id,
                    'journal_entry_id' => $journalEntry->id,
                    'vendor_id' => $payload['vendor_id'] ?? null,
                    'vendor_invoice_id' => $payload['vendor_invoice_id'] ?? null,
                    'receipt_number' => $payload['receipt_number'] ?? $payload['payment_reference'] ?? null,
                    'realization_date' => $payload['realization_date'],
                    'description' => $rkasRealization->description,
                    'amount' => $amount,
                    'tax_amount' => $taxAmount,
                    'payment_method' => $payload['payment_method'] ?? 'TRANSFER',
                    'attachment_url' => $payload['attachment_url'] ?? null,
                    'status' => 'RECORDED',
                    'is_active' => true,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);

                $bosPeriod->increment('total_used', $grossUsed);
                $bosPeriod->decrement('total_remaining', $grossUsed);
                $bosPeriod->update(['updated_by' => $userId]);
            }

            $this->rollUpRkasRealization($rkasItem, $grossUsed, $userId);

            return $rkasRealization->load(['journalEntry.lines', 'bosRealization']);
        });
    }

    /**
     * Membatalkan (VOID) realisasi RKAS secara audit-safe:
     * 1. Validasi status realisasi & periode BOS/fiskal belum terkunci (CLOSED/VERIFIED)
     * 2. Membentuk Jurnal Balik (Reversing Journal Entry) dengan membalik posisi Debit <-> Kredit
     * 3. Mengubah status rkas_realizations & bos_realizations menjadi VOID
     * 4. Mengembalikan saldo kas BOS (bos_periods) dan total_realization berantai di RKAS
     *
     * @param int $rkasRealizationId
     * @param string $reason Alasan pembatalan (wajib untuk jejak audit)
     * @param int $userId
     * @param int|null $voidFiscalPeriodId Periode fiskal saat pembatalan dilakukan (default mengikuti periode asal)
     * @return RkasRealization
     * @throws Throwable
     */
    public function void(
        int $rkasRealizationId,
        string $reason,
        int $userId,
        ?int $voidFiscalPeriodId = null
    ): RkasRealization {
        if (trim($reason) === '') {
            throw new InvalidArgumentException('Alasan pembatalan (void reason) wajib diisi untuk jejak audit.');
        }

        return DB::transaction(function () use ($rkasRealizationId, $reason, $userId, $voidFiscalPeriodId) {
            // 1. Kunci baris realisasi utama beserta relasinya
            $realization = RkasRealization::with([
                'item.subActivity.activity.program.document',
                'journalEntry.lines',
            ])->lockForUpdate()->findOrFail($rkasRealizationId);

            if ($realization->status === 'VOID') {
                throw new DomainException('Transaksi realisasi ini sudah berstatus VOID sebelumnya.');
            }

            // Cegah void jika sudah masuk ke laporan yang terverifikasi (opsional sesuai kebijakan audit)
            if ($realization->status === 'VERIFIED' || $realization->status === 'LOCKED') {
                throw new DomainException(
                    "Realisasi dengan status '{$realization->status}' tidak dapat dibatalkan karena sudah terkunci pada SPJ/LPJ."
                );
            }

            $grossAmount = (float) $realization->amount + (float) $realization->tax_amount;

            // 2. Cek dan kunci bos_realizations jika transaksi ini terhubung ke dana BOS
            $bosRealization = BosRealization::where('rkas_realization_id', $realization->id)
                ->where('status', '!=', 'VOID')
                ->lockForUpdate()
                ->first();

            $bosPeriod = null;
            if ($bosRealization) {
                $bosPeriod = BosPeriod::lockForUpdate()->findOrFail($bosRealization->bos_period_id);

                if (in_array(strtoupper($bosPeriod->status), ['CLOSED', 'LOCKED'], true)) {
                    throw new DomainException(
                        "Periode BOS '{$bosPeriod->name}' sudah ditutup ({$bosPeriod->status}), realisasi tidak dapat di-void."
                    );
                }
            }

            // 3. Buat Jurnal Balik (Reversing Journal Entry) jika transaksi asal memiliki jurnal
            $reversingJournal = null;
            if ($realization->journalEntry) {
                $reversingJournal = $this->createReversingJournalEntry(
                    originalJournal: $realization->journalEntry,
                    reason: $reason,
                    userId: $userId,
                    fiscalPeriodId: $voidFiscalPeriodId ?? $realization->fiscal_period_id
                );
            }

            // 4. Update status rkas_realizations menjadi VOID (pertahankan baris untuk audit trail)
            $voidNote = sprintf(
                '[VOID %s oleh User #%d: %s]%s',
                now()->format('Y-m-d H:i'),
                $userId,
                $reason,
                $reversingJournal ? " (Jurnal Balik: {$reversingJournal->entry_number})" : ''
            );

            $realization->update([
                'status' => 'VOID',
                'is_active' => false,
                'description' => trim(($realization->description ?? '') . "\n" . $voidNote),
                'updated_by' => $userId,
            ]);

            // 5. Sinkronisasi VOID ke bos_realizations & pulihkan saldo bos_periods
            if ($bosRealization && $bosPeriod) {
                $bosRealization->update([
                    'status' => 'VOID',
                    'is_active' => false,
                    'description' => trim(($bosRealization->description ?? '') . "\n" . $voidNote),
                    'updated_by' => $userId,
                ]);

                $bosPeriod->decrement('total_used', $grossAmount);
                $bosPeriod->increment('total_remaining', $grossAmount);
                $bosPeriod->update(['updated_by' => $userId]);
            }

            // 6. Roll-down (kurangi kembali) total_realization secara berantai di hierarki RKAS
            $this->rollUpRkasRealization($realization->item, -$grossAmount, $userId);

            return $realization->fresh(['journalEntry', 'bosRealization']);
        });
    }

    /**
     * Membentuk Journal Entry awal saat realisasi dicatat.
     */
    protected function createJournalEntry(
        RkasItem $rkasItem,
        RkasSource $rkasSource,
        array $payload,
        float $amount,
        float $taxAmount,
        int $userId
    ): JournalEntry {
        $expenseAccountId = $rkasItem->component?->account_id;
        $creditAccountId = $payload['credit_account_id'] ?? $rkasSource->account_id;

        if (!$expenseAccountId || !$creditAccountId) {
            throw new InvalidArgumentException(
                'Mapping Chart of Accounts (COA) pada rkas_components atau rkas_sources belum lengkap.'
            );
        }

        $document = $rkasItem->subActivity->activity->program->document;
        $totalDebit = $amount + $taxAmount;
        $description = $payload['description'] ?? "Realisasi RKAS: {$rkasItem->code} - {$rkasItem->name}";

        $journal = JournalEntry::create([
            'school_id' => $document->school_id,
            'fiscal_period_id' => $payload['fiscal_period_id'],
            'entry_number' => 'JE-RKAS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'entry_date' => $payload['realization_date'],
            'reference_type' => RkasRealization::class,
            'description' => $description,
            'total_debit' => $totalDebit,
            'total_credit' => $totalDebit,
            'status' => 'POSTED',
            'created_by' => $userId,
        ]);

        $journal->lines()->create([
            'account_id' => $expenseAccountId,
            'debit' => $amount,
            'credit' => 0,
            'description' => $description,
        ]);

        if ($taxAmount > 0 && !empty($payload['tax_account_id'])) {
            $journal->lines()->create([
                'account_id' => $payload['tax_account_id'],
                'debit' => $taxAmount,
                'credit' => 0,
                'description' => "Pajak - {$description}",
            ]);
        } elseif ($taxAmount > 0) {
            $journal->lines()->first()->increment('debit', $taxAmount);
        }

        $journal->lines()->create([
            'account_id' => $creditAccountId,
            'debit' => 0,
            'credit' => $totalDebit,
            'description' => "Pembayaran - {$description}",
        ]);

        return $journal;
    }

    /**
     * Membentuk Jurnal Balik (Reversing Entry) dengan menukar posisi Debit dan Kredit
     * dari setiap baris jurnal asli agar saldo buku besar kembali netral.
     */
    protected function createReversingJournalEntry(
        JournalEntry $originalJournal,
        string $reason,
        int $userId,
        int $fiscalPeriodId
    ): JournalEntry {
        $reversingDescription = "VOID / Jurnal Balik atas {$originalJournal->entry_number}: {$reason}";

        $reversingJournal = JournalEntry::create([
            'school_id' => $originalJournal->school_id,
            'fiscal_period_id' => $fiscalPeriodId,
            'entry_number' => 'JE-VOID-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'entry_date' => now()->toDateString(),
            'reference_type' => $originalJournal->reference_type,
            'description' => $reversingDescription,
            'total_debit' => $originalJournal->total_credit,
            'total_credit' => $originalJournal->total_debit,
            'status' => 'POSTED',
            'created_by' => $userId,
        ]);

        // Balik posisi Debit <-> Kredit untuk setiap detail baris jurnal asal
        foreach ($originalJournal->lines as $line) {
            $reversingJournal->lines()->create([
                'account_id' => $line->account_id,
                'debit' => $line->credit,
                'credit' => $line->debit,
                'description' => "Reversal - {$line->description}",
            ]);
        }

        return $reversingJournal;
    }

    /**
     * Memperbarui kolom denormalisasi total_realization secara berantai ke atas.
     * Menerima nilai delta positif (saat record) maupun negatif (saat void).
     */
    protected function rollUpRkasRealization(RkasItem $rkasItem, float $deltaAmount, int $userId): void
    {
        $subActivity = $rkasItem->subActivity()->lockForUpdate()->first();
        $activity = $subActivity->activity()->lockForUpdate()->first();
        $program = $activity->program()->lockForUpdate()->first();
        $document = $program->document()->lockForUpdate()->first();

        $subActivity->increment('total_realization', $deltaAmount);
        $subActivity->update(['updated_by' => $userId]);

        $activity->increment('total_realization', $deltaAmount);
        $activity->update(['updated_by' => $userId]);

        $program->increment('total_realization', $deltaAmount);
        $program->update(['updated_by' => $userId]);

        $document->increment('total_realization', $deltaAmount);
        $document->update(['updated_by' => $userId]);
    }
}