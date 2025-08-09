<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        return Journal::with('entries')->orderByDesc('date')->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
            'created_by' => ['nullable', 'exists:users,id'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_code' => ['required', 'string'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
        ]);

        $journal = Journal::create([
            'date' => $data['date'],
            'description' => $data['description'] ?? null,
            'created_by' => $data['created_by'] ?? null,
            'created_at' => now(),
        ]);

        foreach ($data['lines'] as $l) {
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => \App\Models\Account::where('code', $l['account_code'])->firstOrFail()->id,
                'debit' => $l['debit'] ?? 0,
                'credit' => $l['credit'] ?? 0,
            ]);
        }

        return response()->json($journal->load('entries'), 201);
    }

    public function show(Journal $journal)
    {
        return $journal->load('entries');
    }
}
