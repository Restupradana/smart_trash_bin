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
            'lokasi' => 'required|string|max:255',
            'pesan' => 'nullable|string|max:500',
        ]);

        $notifikasi = Notifikasi::create([
            'user_id' => Auth::id(),
            'lokasi' => $request->lokasi,
            'pesan' => $request->pesan,
        ]);

        $petugasList = User::whereHas('roles', function ($q) {
            $q->where('name', 'petugas');
        })->get();

        foreach ($petugasList as $petugas) {
            // Kirim Email
            Mail::to($petugas->email)->send(new \App\Mail\NotifikasiSampahPenuhMail($notifikasi, $petugas));

            // Kirim WhatsApp jika no_wa ada
            if ($petugas->no_wa) {
                try {
                    $pesanWa = "📢 Notifikasi Sampah Penuh!\n\n"
                        . "Lokasi: {$notifikasi->lokasi}\n"
                        . "Pesan: " . ($notifikasi->pesan ?? '-') . "\n"
                        . "Dari: " . Auth::user()->name;

                    Http::get('https://api.callmebot.com/whatsapp.php', [
                        'phone' => $petugas->no_wa,
                        'text' => $pesanWa,
                        'apikey' => env('CALLMEBOT_APIKEY'),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Gagal kirim WA ke ' . $petugas->name . ': ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('user.dashboard')->with('success', 'Notifikasi berhasil dikirim ke petugas!');
    }
}
