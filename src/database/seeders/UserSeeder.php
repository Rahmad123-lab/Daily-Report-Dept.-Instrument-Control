<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'nik' => '20250001',
            'email' => 'admin@drics.test',
            'password' => Hash::make('password'),
            'role' => 'Supervisor',
            'status' => 'Active',
        ]);
    }
}