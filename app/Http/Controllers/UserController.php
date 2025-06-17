<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\User;
use App\Models\TempatSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function dashboard()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())->latest()->get();
        return view('user.dashboard', compact('notifikasis'));
    }

    public function status()
    {
        $tinggi_total = 30;      // Tinggi tempat sampah dalam cm (kosong)
        $tinggi_minimal = 10;    // Tinggi saat penuh

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
                $kapasitas = max(0, min(100, $kapasitas)); // Jaga agar tetap di 0–100%
            }

            return [
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
        $locations = [
            ['name' => 'Tempat Sampah A', 'latitude' => -6.200000, 'longitude' => 106.816666],
            ['name' => 'Tempat Sampah B', 'latitude' => -6.210000, 'longitude' => 106.826666],
        ];
        return view('user.location', compact('locations'));
    }

    public function history()
    {
        $history = Notifikasi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('user.history', compact('history'));
    }

    public function formNotifikasi()
    {
        return view('user.notifikasi-form');
    }

    public function kirimNotifikasi(Request $request)
    {
        $request->validate([
            'tempat_sampah_id' => 'required|exists:tempat_sampah,id',
            'sensor_id' => 'required|exists:sensors,id',
            'pesan' => 'nullable|string|max:1000',
        ]);

        $tempatSampah = TempatSampah::findOrFail($request->tempat_sampah_id);

        // Ambil data sensor utama (kapasitas)
        $kapasitasSensorId = $request->sensor_id;
        $kapasitasValue = \App\Models\DataSensor::where('sensor_id', $kapasitasSensorId)
            ->latest('waktu')
            ->value('nilai');

        // Ambil sensor berat (optional, bisa null)
        $sensorBerat = \App\Models\Sensor::where('tempat_sampah_id', $tempatSampah->id)
            ->where('tipe', 'load_cell')
            ->first();

        $beratValue = $sensorBerat
            ? \App\Models\DataSensor::where('sensor_id', $sensorBerat->id)->latest('waktu')->value('nilai')
            : null;

        Notifikasi::create([
            'pengirim_id' => Auth::id(),
            'tempat_sampah_id' => $tempatSampah->id,
            'sensor_id' => $kapasitasSensorId,
            'nilai_kapasitas' => $kapasitasValue,
            'nilai_berat' => $beratValue,
            'lokasi' => $tempatSampah->lokasi,
            'pesan' => $request->pesan,
            'status' => 'pending',
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Notifikasi berhasil dikirim ke database dan menunggu konfirmasi petugas.');
    }
}
