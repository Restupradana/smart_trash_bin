<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TempatSampahSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Insert data tempat sampah
        DB::table('tempat_sampah')->insert([
            [
                'nama' => 'Tempat Sampah Organik',
                'jenis' => 'organik',
                'lokasi' => '1.026089,104.064232',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Tempat Sampah Plastik',
                'jenis' => 'plastik',
                'lokasi' => '1.026089,104.064234',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Tempat Sampah Metal',
                'jenis' => 'metal',
                'lokasi' => '1.026089,104.064235',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
