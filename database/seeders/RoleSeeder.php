<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'petugas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
