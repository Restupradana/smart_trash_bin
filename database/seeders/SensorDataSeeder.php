<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SensorDataSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $sensors = DB::table('sensors')->get();

        foreach ($sensors as $sensor) {
            DB::table('data_sensors')->insert([
                'sensor_id' => $sensor->id,
                'nilai' => rand(10, 100),  // Contoh nilai random
                'waktu' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
