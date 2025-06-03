<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user admin
        $admin = User::create([
            'name' => 'Admin Master',
            'email' => 'admin@example.com',
            'wa_number' => '+6281111111111', // Nomor WA admin
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Buat user petugas
        $petugas = User::create([
            'name' => 'Petugas Satu',
            'email' => 'petugas@example.com',
            'wa_number' => '+6282222222222', // Nomor WA petugas
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Buat user biasa
        $user = User::create([
            'name' => 'User Biasa',
            'email' => 'user@example.com',
            'wa_number' => '+6283333333333', // Nomor WA user
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Cari role-role yang sudah ada di tabel roles
        $roleAdmin = Role::where('name', 'admin')->first();
        $rolePetugas = Role::where('name', 'petugas')->first();
        $roleUser = Role::where('name', 'user')->first();

        // Attach role ke masing-masing user
        if ($roleAdmin) {
            $admin->roles()->attach($roleAdmin->id);
        }

        if ($rolePetugas) {
            $petugas->roles()->attach($rolePetugas->id);
        }

        if ($roleUser) {
            $user->roles()->attach($roleUser->id);
        }
    }
}
