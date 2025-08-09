<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return Account::orderBy('code')->paginate(50);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:accounts,code'],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:Asset,Liability,Equity,Revenue,Expense'],
            'parent_id' => ['nullable', 'exists:accounts,id'],
        ]);
        $acc = Account::create($data);
        return response()->json($acc, 201);
    }

    public function show(Account $account)
    {
        return $account->load('children');
    }

    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'code' => ['sometimes', 'string', 'max:30', 'unique:accounts,code,' . $account->id],
            'name' => ['sometimes', 'string', 'max:150'],
            'type' => ['sometimes', 'in:Asset,Liability,Equity,Revenue,Expense'],
            'parent_id' => ['nullable', 'exists:accounts,id'],
        ]);
        $account->update($data);
        return $account;
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return response()->noContent();
    }
}
