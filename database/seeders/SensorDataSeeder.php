<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SensorDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $sensors = DB::table('sensors')->get();

        if ($sensors->isEmpty()) {
            echo "Tidak ada sensor di database. Seeder tidak dijalankan.\n";
            return;
        }

        foreach ($sensors as $sensor) {
            DB::table('data_sensors')->insert([
                'sensor_id'   => $sensor->id,
                'nilai'       => mt_rand(50, 100) / 1.0, // nilai float antara 50-100
                'waktu'       => $now,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
