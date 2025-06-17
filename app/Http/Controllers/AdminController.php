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
        $notifikasis = Notifikasi::with('pengirim')->latest()->get();

        $sampah = TempatSampah::with('sensors.latest_data_sensor')->get();

        $kategori = [
            'organic' => ['jenis' => 'Organik', 'total_kapasitas' => 0, 'jumlah' => 0],
            'plastic' => ['jenis' => 'Plastik/Kaca', 'total_kapasitas' => 0, 'jumlah' => 0],
            'metal' => ['jenis' => 'metal', 'total_kapasitas' => 0, 'jumlah' => 0],
        ];

        $total_kapasitas = 0;
        $jumlah_total = 0;

        foreach ($sampah as $item) {
            $ultrasonik = $item->sensors->firstWhere('tipe', 'ultrasonik');
            $jarak = $ultrasonik?->latest_data_sensor?->nilai;

            $kapasitas = null;
            if ($jarak !== null) {
                $tinggi_total = 30;
                $tinggi_minimal = 10;

                $kapasitas = round(($tinggi_total - $jarak) / ($tinggi_total - $tinggi_minimal) * 100);
                $kapasitas = max(0, min(100, $kapasitas));
            }

            if ($kapasitas !== null) {
                $total_kapasitas += $kapasitas;
                $jumlah_total++;

                if (str_contains(strtolower($item->jenis), 'organik')) {
                    $kategori['organic']['total_kapasitas'] += $kapasitas;
                    $kategori['organic']['jumlah']++;
                } elseif (str_contains(strtolower($item->jenis), 'plastik') || str_contains(strtolower($item->jenis), 'kaca')) {
                    $kategori['plastic']['total_kapasitas'] += $kapasitas;
                    $kategori['plastic']['jumlah']++;
                } elseif (str_contains(strtolower($item->jenis), 'metal')) {
                    $kategori['metal']['total_kapasitas'] += $kapasitas;
                    $kategori['metal']['jumlah']++;
                }
            }
        }

        $avg_total = $jumlah_total > 0 ? round($total_kapasitas / $jumlah_total) : 0;
        $avg_organik = $kategori['organic']['jumlah'] > 0 ? round($kategori['organic']['total_kapasitas'] / $kategori['organic']['jumlah']) : 0;
        $avg_plastik = $kategori['plastic']['jumlah'] > 0 ? round($kategori['plastic']['total_kapasitas'] / $kategori['plastic']['jumlah']) : 0;
        $avg_metal = $kategori['metal']['jumlah'] > 0 ? round($kategori['metal']['total_kapasitas'] / $kategori['metal']['jumlah']) : 0;

        return view('admin.dashboard', compact(
            'notifikasis',
            'avg_total',
            'avg_organik',
            'avg_plastik',
            'avg_metal'
        ));
    }

    public function status()
    {
        $tempatSampah = TempatSampah::with('sensors.latest_data_sensor')->get();

        $data = $tempatSampah->map(function ($item) {
            $ultrasonik = $item->sensors->firstWhere('tipe', 'ultrasonik');
            $loadcell = $item->sensors->firstWhere('tipe', 'load_cell');

            $jarak = $ultrasonik?->latest_data_sensor?->nilai;
            $berat = $loadcell?->latest_data_sensor?->nilai;

            $kapasitas = null;
            if ($jarak !== null) {
                $tinggi_total = 30;
                $tinggi_minimal = 10;

                $kapasitas = round(($tinggi_total - $jarak) / ($tinggi_total - $tinggi_minimal) * 100);
                $kapasitas = max(0, min(100, $kapasitas));
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
