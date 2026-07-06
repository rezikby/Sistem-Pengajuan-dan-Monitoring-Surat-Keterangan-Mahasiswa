<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'name'      => 'Administrator',
            'nim'       => 'ADMIN001',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // mahasiswa
        User::create([
            'name'      => 'REZI',
            'nim'       => 'cc',
            'password'  => Hash::make('230107Rezi'),
            'role'      => 'mahasiswa',
            'is_active' => true,
            'fakultas'  => 'Informatika & Bisnis',
            'prodi'     => 'Teknologi Rekayasa Perangkat Lunak',
        ]);
    }
}