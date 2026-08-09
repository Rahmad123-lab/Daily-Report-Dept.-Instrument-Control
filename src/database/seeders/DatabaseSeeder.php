<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nik' => '20250001',
            'name' => 'Rahmad Joko Susilo Situmorang',
            'department' => 'Instrument & Control',
            'position' => 'Instrument Engineer',
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('password'),
        ]);
    }
}