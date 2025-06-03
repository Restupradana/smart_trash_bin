<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan semua seeder yang diperlukan secara berurutan
        $this->call([
            RoleSeeder::class,            // Seeder untuk role (admin, petugas, user, dll)
            UserSeeder::class,            // Seeder user & relasi ke role
            TempatSampahSeeder::class,    // Seeder untuk tempat sampah
            SensorSeeder::class,          // Seeder untuk sensor (ultrasonik & load cell)
            SensorDataSeeder::class,      // Seeder untuk data sensor (jarak & berat)
        ]);
    }
}
