<?php

namespace App\Http\Controllers;

use App\Models\TempatSampah;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function dashboard()
    {
        $notifikasis = Notifikasi::with('user', 'petugas')->latest()->get();
        return view('admin.dashboard', compact('notifikasis'));
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

        return view('admin.status', compact('data'));
    }

    public function location()
    {
        $tempat_sampah = TempatSampah::all(); // Ambil semua data dari DB
        return view('admin.location', compact('tempat_sampah'));
    }

    public function history()
    {
        return view('admin.history');
    }
}
