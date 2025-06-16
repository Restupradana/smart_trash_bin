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
        $notifikasis = Notifikasi::where('dikonfirmasi', false)->latest()->get();
        return view('petugas.dashboard', compact('notifikasis'));
    }

    public function konfirmasiForm($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        return view('petugas.konfirmasi-form', compact('notifikasi'));
    }

    public function konfirmasiSimpan(Request $request)
    {
        $request->validate([
            'notifikasi_id' => 'required|exists:notifikasis,id',
            'bukti_foto' => 'required|image|max:2048',
        ]);

        $notifikasi = Notifikasi::findOrFail($request->notifikasi_id);

        $path = $request->file('bukti_foto')->store('bukti', 'public');

        $notifikasi->update([
            'dikonfirmasi' => true,
            'petugas_id' => Auth::id(),
            'bukti_foto' => $path,
        ]);

        return redirect()->route('petugas.dashboard')->with('success', 'Konfirmasi berhasil disimpan.');
    }

    public function status()
    {
                // Eager load sensors dan data_sensors terbaru per sensor
        $tempatSampah = TempatSampah::with(['sensors.data_sensors' => function ($query) {
            $query->latest('waktu')->limit(1);
        }])->get();

        $data = $tempatSampah->map(function ($item) {
            $ultrasonik = $item->sensors->firstWhere('tipe', 'ultrasonik');
            $loadcell = $item->sensors->firstWhere('tipe', 'load_cell');

            // Ambil nilai sensor terbaru jika ada
            $jarak = $ultrasonik?->data_sensors->first()?->nilai;
            $berat = $loadcell?->data_sensors->first()?->nilai;

            // Hitung kapasitas berdasarkan jarak
            // 30 cm -> kapasitas 0%, 10 cm -> kapasitas 100%
            $kapasitas = null;
            if ($jarak !== null) {
                $kapasitas = round((30 - $jarak) / (30 - 10) * 100);
                $kapasitas = max(0, min(100, $kapasitas)); // Batasi antara 0 - 100%
            }

            return [
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
        return view('petugas.location');
    }

    public function history()
    {
        return view('petugas.history');
    }
}
