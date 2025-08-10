<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AccountsTableSeeder::class,
            UsersTableSeeder::class,
            ScenarioSeeder::class,
            ProductionTaskSeeder::class,
        ]);
    }
}
