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

            if (isset($sampah[$item->jenis])) {
                $sampah[$item->jenis][] = $kapasitas;
            }
        }

        // Hitung rata-rata
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
        // Catatan: sebaiknya ambil lokasi dari DB (TempatSampah::all())
        $locations = TempatSampah::select('nama as name', 'latitude', 'longitude')->get();

        return view('user.location', compact('locations'));
    }

    public function history()
    {
        $history = Notifikasi::where('pengirim_id', Auth::id())->latest()->get();

        return view('user.history', compact('history'));
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

        // Ambil sensor ultrasonik
        $sensorUltrasonik = Sensor::where('tempat_sampah_id', $tempatSampah->id)
            ->where('tipe', 'ultrasonik')
            ->first();

        $jarak = $sensorUltrasonik
            ? DataSensor::where('sensor_id', $sensorUltrasonik->id)->latest('waktu')->value('nilai')
            : null;

        $tinggi_total = 30;
        $tinggi_minimal = 10;

        $kapasitasValue = $jarak !== null
            ? round(($tinggi_total - $jarak) / ($tinggi_total - $tinggi_minimal) * 100)
            : null;

        $kapasitasValue = $kapasitasValue !== null
            ? max(0, min(100, $kapasitasValue))
            : null;

        // Ambil berat jika ada
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

        Notifikasi::create([
            'pengirim_id' => Auth::id(),
            'tempat_sampah_id' => $tempatSampah->id,
            'sensor_id' => $request->sensor_id,
            'nilai_kapasitas' => $kapasitasValue,
            'nilai_berat' => $beratValue,
            'lokasi' => $tempatSampah->lokasi,
            'pesan' => $request->pesan,
            'status' => 'pending',
            'petugas_id' => $petugas->id,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Notifikasi berhasil dikirim dan menunggu konfirmasi.');
    }
}
