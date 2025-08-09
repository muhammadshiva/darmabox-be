<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@darmabox.test', 'password' => Hash::make('admin123'), 'role' => 'admin'],
            ['name' => 'Kasir', 'email' => 'kasir@darmabox.test', 'password' => Hash::make('kasir123'), 'role' => 'kasir'],
            ['name' => 'Warehouse', 'email' => 'warehouse@darmabox.test', 'password' => Hash::make('warehouse123'), 'role' => 'warehouse'],
            ['name' => 'Planner', 'email' => 'planner@darmabox.test', 'password' => Hash::make('planner123'), 'role' => 'planner'],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(['email' => $u['email']], $u);
        }
    }
}
