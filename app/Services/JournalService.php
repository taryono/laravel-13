<?php

namespace App\Modules\Finance\Services;

use Models\JournalEntry;
use Models\JournalDetail;
use Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class JournalService
{
    public function createJournal($data, $totalDebit, $totalCredit): JournalEntry
    {
        // Generate journal number
        $journalNumber = $this->generateJournalNumber();

        $journalEntry = JournalEntry::create([
            'journal_number' => $journalNumber,
            'journal_date' => $data['journal_date'],
            'period' => date('Y-m', strtotime($data['journal_date'])),
            'description' => $data['description'],
            'reference_number' => $data['reference_number'] ?? null,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'status' => 'draft',
            'notes' => $data['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Create journal details
        foreach ($data['items'] as $item) {
            if ($item['debit'] > 0 || $item['credit'] > 0) {
                JournalDetail::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $item['account_id'],
                    'debit' => $item['debit'] ?? 0,
                    'credit' => $item['credit'] ?? 0,
                    'description' => $item['description'] ?? null,
                ]);
            }
        }

        return $journalEntry;
    }

    public function updateJournal($journalEntry, $data, $totalDebit, $totalCredit): void
    {
        // Update journal entry
        $journalEntry->update([
            'journal_date' => $data['journal_date'],
            'period' => date('Y-m', strtotime($data['journal_date'])),
            'description' => $data['description'],
            'reference_number' => $data['reference_number'] ?? null,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'notes' => $data['notes'] ?? null,
        ]);

        // Delete old details
        $journalEntry->details()->delete();

        // Create new details
        foreach ($data['items'] as $item) {
            if ($item['debit'] > 0 || $item['credit'] > 0) {
                JournalDetail::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $item['account_id'],
                    'debit' => $item['debit'] ?? 0,
                    'credit' => $item['credit'] ?? 0,
                    'description' => $item['description'] ?? null,
                ]);
            }
        }
    }

    public function postJournal($journalEntry): void
    {
        // Update status
        $journalEntry->update([
            'status' => 'posted',
            'posted_by' => auth()->id(),
            'posted_at' => now(),
        ]);

        // Update account balances (optional, bisa dihitung on-the-fly)
        $this->updateAccountBalances($journalEntry);
    }

    public function voidJournal($journalEntry): JournalEntry
    {
        // Create void journal (reverse entry)
        $voidJournal = $this->createVoidEntry($journalEntry);

        // Update original status
        $journalEntry->update([
            'status' => 'void',
        ]);

        return $voidJournal;
    }

    private function generateJournalNumber(): string
    {
        $lastJournal = JournalEntry::orderBy('id', 'desc')->first();
        $number = $lastJournal ? intval(substr($lastJournal->journal_number, -6)) + 1 : 1;
        
        return 'JRN-' . date('Ymd') . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    private function updateAccountBalances($journalEntry): void
    {
        $details = $journalEntry->details()->with('account')->get();

        foreach ($details as $detail) {
            $account = $detail->account;
            $balance = $account->current_balance;

            if ($detail->debit > 0) {
                $balance += $detail->debit;
            } elseif ($detail->credit > 0) {
                $balance -= $detail->credit;
            }

            $account->update(['current_balance' => $balance]);
        }
    }

    private function createVoidEntry($journalEntry): JournalEntry
    {
        // Reverse all journal details
        $items = [];
        foreach ($journalEntry->details as $detail) {
            $items[] = [
                'account_id' => $detail->account_id,
                'debit' => $detail->credit,
                'credit' => $detail->debit,
                'description' => 'Pembatalan: ' . ($detail->description ?? ''),
            ];
        }

        // Create void journal
        return $this->createJournal([
            'journal_date' => now()->format('Y-m-d'),
            'description' => 'Void Journal: ' . $journalEntry->journal_number . ' - ' . $journalEntry->description,
            'reference_number' => 'VOID-' . $journalEntry->journal_number,
            'items' => $items,
            'notes' => 'Membatalkan jurnal ' . $journalEntry->journal_number,
        ], $journalEntry->total_debit, $journalEntry->total_credit);
    }
}