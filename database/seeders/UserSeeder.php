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
            'name'     => 'Luiss',
            'surname'  => 'Garces',
            'email'    => 'luiss.garces@gmail.com',
            'password' => Hash::make('Wallet@2026!'),
            'status'   => 1,
        ]);
    }
}
