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
                $je = JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $acc->id,
                    'debit' => $l['debit'] ?? 0,
                    'credit' => $l['credit'] ?? 0,
                ]);
                $debitSum += ($l['debit'] ?? 0);
                $creditSum += ($l['credit'] ?? 0);

                $balanceAfter = ($l['debit'] ?? 0) - ($l['credit'] ?? 0);
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
}
