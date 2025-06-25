<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TempatSampah;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $notifikasis = Notifikasi::where('status', 'pending')->latest()->get();
        return view('petugas.dashboard', compact('notifikasis'));
    }

    public function konfirmasiForm($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        return view('petugas.konfirmasi-form', compact('notifikasi'));
    }


    public function konfirmasiSimpan(Request $request, $id)
    {
        $request->validate([
            'bukti_foto' => 'required|image|max:2048',
        ]);

        $notifikasi = Notifikasi::findOrFail($id);

        $path = $request->file('bukti_foto')->store('bukti', 'public');

        $notifikasi->update([
            'petugas_id' => Auth::id(),
            'bukti_foto' => $path,
            'status' => 'dikonfirmasi', // Konsisten dengan sistem
            'dikonfirmasi_pada' => now(),
        ]);

        return redirect()->route('petugas.dashboard')->with('success', 'Konfirmasi berhasil disimpan.');
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

        return view('petugas.status', compact('data'));
    }

    public function location()
    {
        $tempat_sampah = TempatSampah::all(); // Ambil semua data dari DB
        return view('petugas.location', compact('tempat_sampah'));
    }

    public function history()
    {
        $histories = Notifikasi::with(['tempatSampah', 'petugas', 'pengirim'])
            ->whereNotNull('petugas_id')
            ->latest()
            ->get();

        return view('petugas.history', compact('histories'));
    }
}
