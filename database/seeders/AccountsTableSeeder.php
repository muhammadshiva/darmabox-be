<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountsTableSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1000', 'name' => 'Cash', 'type' => 'Asset'],
            ['code' => '1100', 'name' => 'Bank', 'type' => 'Asset'],
            ['code' => '1300', 'name' => 'Inventory - Materials', 'type' => 'Asset'],
            ['code' => '1310', 'name' => 'Inventory - Finished Goods', 'type' => 'Asset'],
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'Liability'],
            ['code' => '2100', 'name' => 'GR/IR', 'type' => 'Liability'],
            ['code' => '2200', 'name' => 'Customer Advances', 'type' => 'Liability'],
            ['code' => '3000', 'name' => 'Owner Equity', 'type' => 'Equity'],
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'Revenue'],
            ['code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'Expense'],
        ];

        foreach ($accounts as $acc) {
            Account::firstOrCreate(['code' => $acc['code']], $acc);
        }
    }
}
