<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\User;
use App\Models\TempatSampah;
use App\Models\Sensor;
use App\Models\DataSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $notifikasis = Notifikasi::where('pengirim_id', Auth::id())->latest()->get();

        $tinggi_total = 15;
        $tinggi_minimal = 1;

        $tempatSampah = TempatSampah::with(['sensors.data_sensors' => function ($query) {
            $query->latest('waktu')->limit(1);
        }])->get();

        $sampah = [
            'organik' => [],
            'plastik' => [],
            'metal' => [],
        ];

        foreach ($tempatSampah as $item) {
            $ultrasonik = $item->sensors->firstWhere('tipe', 'ultrasonik');
            $jarak = $ultrasonik?->data_sensors->first()?->nilai;

            if ($jarak === null) continue;

            // Hitung kapasitas
            $kapasitas = round(($tinggi_total - $jarak) / ($tinggi_total - $tinggi_minimal) * 100);
            $kapasitas = max(0, min(100, $kapasitas));

            // Tambahkan ke array rata-rata
            if (isset($sampah[$item->jenis])) {
                $sampah[$item->jenis][] = $kapasitas;
            }

            // Kirim notifikasi otomatis jika kapasitas >= 90%
            if ($kapasitas >= 90) {
                // Cek apakah sudah ada notifikasi pending untuk tempat dan sensor ini
                $sudahAda = Notifikasi::where('tempat_sampah_id', $item->id)
                    ->where('sensor_id', $ultrasonik->id)
                    ->where('status', 'pending')
                    ->exists();

                if (!$sudahAda) {
                    $sensorBerat = $item->sensors->firstWhere('tipe', 'load_cell');
                    $berat = $sensorBerat?->data_sensors->first()?->nilai;

                    // Cari petugas
                    $petugas = User::whereHas('roles', function ($query) {
                        $query->where('name', 'petugas');
                    })->first();

                    if ($petugas) {
                        $pesan = "Tempat Sampah {$item->nama} di {$item->lokasi} telah mencapai kapasitas {$kapasitas}%. Segera kosongkan!";

                        Notifikasi::create([
                            'pengirim_id' => Auth::id(),
                            'tempat_sampah_id' => $item->id,
                            'sensor_id' => $ultrasonik->id,
                            'nilai_kapasitas' => $kapasitas,
                            'nilai_berat' => $berat,
                            'lokasi' => $item->lokasi,
                            'pesan' => $pesan,
                            'status' => 'pending',
                            'petugas_id' => $petugas->id,
                        ]);
                    }
                }
            }
        }

        $avg_organik = $this->average($sampah['organik']);
        $avg_plastik = $this->average($sampah['plastik']);
        $avg_metal = $this->average($sampah['metal']);
        $avg_total = round(($avg_organik + $avg_plastik + $avg_metal) / 3);

        return view('user.dashboard', compact(
            'notifikasis',
            'avg_total',
            'avg_organik',
            'avg_plastik',
            'avg_metal'
        ));
    }


    private function average(array $values): int
    {
        return count($values) ? round(array_sum($values) / count($values)) : 0;
    }

    public function status()
    {
        $tinggi_total = 15;
        $tinggi_minimal = 1;

        $tempatSampah = TempatSampah::with(['sensors.data_sensors' => function ($query) {
            $query->latest('waktu')->limit(1);
        }])->get();

        $data = $tempatSampah->map(function ($item) use ($tinggi_total, $tinggi_minimal) {
            $ultrasonik = $item->sensors->firstWhere('tipe', 'ultrasonik');
            $loadcell = $item->sensors->firstWhere('tipe', 'load_cell');

            $jarak = $ultrasonik?->data_sensors->first()?->nilai;
            $berat = $loadcell?->data_sensors->first()?->nilai;

            $kapasitas = null;
            if ($jarak !== null) {
                $kapasitas = round(($tinggi_total - $jarak) / ($tinggi_total - $tinggi_minimal) * 100);
                $kapasitas = max(0, min(100, $kapasitas));
            }

            return [
                'id' => $item->id,
                'sensor_id' => $ultrasonik?->id,
                'nama' => $item->nama,
                'jenis' => $item->jenis,
                'kapasitas' => $kapasitas,
                'berat' => $berat,
            ];
        });

        return view('user.status', compact('data'));
    }

    public function location()
    {
        $tempat_sampah = TempatSampah::all(); // Ambil semua data dari DB
        return view('user.location', compact('tempat_sampah'));
    }

    public function history()
    {
        $histories = Notifikasi::with(['tempatSampah', 'petugas', 'pengirim'])
            ->whereNotNull('petugas_id')
            ->latest()
            ->get();

        return view('user.history', compact('histories'));
    }

    public function formNotifikasi()
    {
        $tempatSampahs = TempatSampah::all();
        $sensors = Sensor::all();

        return view('user.notifikasi-form', compact('tempatSampahs', 'sensors'));
    }

    public function kirimNotifikasi(Request $request)
    {
        $request->validate([
            'tempat_sampah_id' => 'required|exists:tempat_sampah,id',
            'sensor_id' => 'required|exists:sensors,id',
            'pesan' => 'nullable|string|max:1000',
        ]);

        $tempatSampah = TempatSampah::findOrFail($request->tempat_sampah_id);

        // Sensor ultrasonik
        $sensorUltrasonik = Sensor::where('id', $request->sensor_id)
            ->where('tempat_sampah_id', $tempatSampah->id)
            ->where('tipe', 'ultrasonik')
            ->first();

        if (!$sensorUltrasonik) {
            return redirect()->back()->withErrors(['sensor' => 'Sensor ultrasonik tidak valid.']);
        }

        $jarak = DataSensor::where('sensor_id', $sensorUltrasonik->id)
            ->latest('waktu')
            ->value('nilai');

        // Logika kapasitas
        $tinggi_total = 30;
        $tinggi_minimal = 10;

        $kapasitasValue = $jarak !== null
            ? round(($tinggi_total - $jarak) / ($tinggi_total - $tinggi_minimal) * 100)
            : null;

        $kapasitasValue = $kapasitasValue !== null
            ? max(0, min(100, $kapasitasValue))
            : null;

        // Sensor berat (opsional)
        $sensorBerat = Sensor::where('tempat_sampah_id', $tempatSampah->id)
            ->where('tipe', 'load_cell')
            ->first();

        $beratValue = $sensorBerat
            ? DataSensor::where('sensor_id', $sensorBerat->id)->latest('waktu')->value('nilai')
            : null;

        // Ambil petugas
        $petugas = User::whereHas('roles', function ($query) {
            $query->where('name', 'petugas');
        })->first();

        if (!$petugas) {
            return redirect()->back()->withErrors(['petugas' => 'Petugas tidak tersedia saat ini.']);
        }

        // Jika kapasitas >= 100%, gunakan pesan default
        $pesan = $request->pesan;
        if ($kapasitasValue !== null && $kapasitasValue >= 100) {
            $pesan = "Tempat Sampah {$tempatSampah->nama} di {$tempatSampah->lokasi} telah penuh (100%). Segera kosongkan!";
        }

        // Hindari duplikasi notifikasi jika sudah ada notifikasi status pending untuk tempat & sensor yg sama
        $sudahAda = Notifikasi::where('tempat_sampah_id', $tempatSampah->id)
            ->where('sensor_id', $sensorUltrasonik->id)
            ->where('status', 'pending')
            ->exists();

        if ($sudahAda) {
            return redirect()->route('user.dashboard')->with('info', 'Notifikasi sudah dikirim dan sedang diproses.');
        }

        Notifikasi::create([
            'pengirim_id' => Auth::id(),
            'tempat_sampah_id' => $tempatSampah->id,
            'sensor_id' => $sensorUltrasonik->id,
            'nilai_kapasitas' => $kapasitasValue,
            'nilai_berat' => $beratValue,
            'lokasi' => $tempatSampah->lokasi,
            'pesan' => $pesan,
            'status' => 'pending',
            'petugas_id' => $petugas->id,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Notifikasi berhasil dikirim dan menunggu konfirmasi.');
    }
}
