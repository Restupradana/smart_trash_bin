<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use App\Models\User;
use App\Models\TempatSampah;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $tempatSampahList = TempatSampah::all();

        if ($users->count() < 3 || $tempatSampahList->isEmpty()) {
            $this->command->warn('Seeder membutuhkan minimal 3 user dan beberapa tempat sampah!');
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $tempatSampah = $tempatSampahList->random();
            $user = $users->random();
            $pengirim = $users->random();
            $penerima = $users->random();
            $petugas = $users->random();

            Notifikasi::create([
                'user_id' => $user->id,
                'tempat_sampah_id' => $tempatSampah->id,
                'lokasi' => $tempatSampah->lokasi ?? 'Lokasi Default',
                'pengirim_id' => $pengirim->id,
                'penerima_id' => $penerima->id,
                'pesan' => fake()->sentence(),
                'dikonfirmasi' => fake()->boolean(50),
                'bukti_foto' => fake()->boolean(50) ? 'bukti/foto_' . rand(1, 5) . '.jpg' : null,
                'petugas_id' => fake()->boolean(50) ? $petugas->id : null,
            ]);
        }
    }
}
