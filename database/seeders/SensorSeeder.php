<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SensorSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Ambil tempat sampah yang sudah dibuat
        $tempatSampah= DB::table('tempat_sampah')->get();

        foreach ($tempatSampah as $tempat) {
            // Buat sensor ultrasonik untuk semua jenis tempat sampah
            DB::table('sensors')->insert([
                'tipe' => 'ultrasonik',
                'nama_sensor' => $tempat->jenis . ' Sensor Ultrasonik',
                'tempat_sampah_id' => $tempat->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Buat sensor load_cell hanya untuk jenis bukan organik
            if ($tempat->jenis !== 'organik') {
                DB::table('sensors')->insert([
                    'tipe' => 'load_cell',
                    'nama_sensor' => $tempat->jenis . ' Sensor Load Cell',
                    'tempat_sampah_id' => $tempat->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
