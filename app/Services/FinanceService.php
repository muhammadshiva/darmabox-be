<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\LedgerEntry;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    public function postJournal(string $description, array $lines, ?int $createdBy = null, ?string $date = null): int
    {
        return DB::transaction(function () use ($description, $lines, $createdBy, $date) {
            $date = $date ?: now()->toDateString();

            $journal = Journal::create([
                'date' => $date,
                'description' => $description,
                'created_by' => $createdBy,
                'created_at' => now(),
            ]);

            $debitSum = 0;
            $creditSum = 0;
            foreach ($lines as $l) {
                $acc = Account::where('code', $l['account_code'])->firstOrFail();
                $debit = (float) ($l['debit'] ?? 0);
                $credit = (float) ($l['credit'] ?? 0);
                $je = JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $acc->id,
                    'debit' => $debit,
                    'credit' => $credit,
                ]);
                $debitSum += $debit;
                $creditSum += $credit;

                // Running balance policy: Asset/Expense increase on debit; Liability/Equity/Revenue increase on credit
                $sign = in_array($acc->type, ['Asset', 'Expense'], true) ? 1 : -1;
                $delta = $sign * ($debit - $credit);

                $last = LedgerEntry::where('account_id', $acc->id)
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->first();
                $prevBalance = $last?->balance_after ?? 0.0;
                $balanceAfter = $prevBalance + $delta;

                LedgerEntry::create([
                    'account_id' => $acc->id,
                    'journal_entry_id' => $je->id,
                    'date' => $date,
                    'balance_after' => $balanceAfter,
                ]);
            }

            if (round($debitSum, 2) !== round($creditSum, 2)) {
                throw new \RuntimeException('Journal not balanced.');
            }

            return $journal->id;
        });
    }

    public function templateSaleCash(float $amount): int
    {
        return $this->postJournal('Cash Sale', [
            ['account_code' => '1000', 'debit' => $amount, 'credit' => 0],
            ['account_code' => '4000', 'debit' => 0, 'credit' => $amount],
        ]);
    }

    public function templateCOGS(float $cogsAmount): int
    {
        return $this->postJournal('COGS Posting', [
            ['account_code' => '5000', 'debit' => $cogsAmount, 'credit' => 0],
            ['account_code' => '1310', 'debit' => 0, 'credit' => $cogsAmount],
        ]);
    }

    public function templateGrToAp(float $amount): int
    {
        return $this->postJournal('GR/IR Clearing to AP', [
            ['account_code' => '2100', 'debit' => $amount, 'credit' => 0],
            ['account_code' => '2000', 'debit' => 0, 'credit' => $amount],
        ]);
    }

    public function templateAdvanceFromCustomer(float $amount): int
    {
        return $this->postJournal('Customer Down Payment', [
            ['account_code' => '1000', 'debit' => $amount, 'credit' => 0],
            ['account_code' => '2200', 'debit' => 0, 'credit' => $amount],
        ]);
    }

    public function templateAdvanceClearing(float $amount): int
    {
        return $this->postJournal('Advance Clearing to Sales', [
            ['account_code' => '2200', 'debit' => $amount, 'credit' => 0],
            ['account_code' => '4000', 'debit' => 0, 'credit' => $amount],
        ]);
    }
}
