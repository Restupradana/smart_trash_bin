<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\TempatSampah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotifikasiController extends Controller
{
    // ========== ADMIN ==========
    public function adminPanel()
    {
        $notifikasis = Notifikasi::with(['pengirim', 'petugas', 'tempatSampah'])->latest()->get();
        return view('admin.history', compact('notifikasis'));
    }

    // // ========== GURU / USER ==========
    // public function guruPanel()
    // {
    //     $user = auth()->user();
    //     $notifikasis = Notifikasi::with('petugas', 'tempatSampah')
    //         ->where('pengirim_id', $user->id)
    //         ->latest()
    //         ->get();

    //     return view('user.notifikasi-form', compact('notifikasis'));
    // }

    // public function kirim(Request $request)
    // {
    //     $request->validate([
    //         'tempat_sampah_id' => 'required|exists:tempat_sampahs,id',
    //         'penerima_id' => 'required|exists:users,id',
    //         'pesan' => 'required|string|max:500',
    //     ]);

    //     $user = auth()->user();

    //     $tempat = TempatSampah::findOrFail($request->tempat_sampah_id);

    //     Notifikasi::create([
    //         'pengirim_id' => $user->id,
    //         'petugas_id' => $request->penerima_id,
    //         'tempat_sampah_id' => $request->tempat_sampah_id,
    //         'lokasi' => $tempat->lokasi,
    //         'pesan' => $request->pesan,
    //     ]);

    //     return redirect()->back()->with('success', 'Permintaan berhasil dikirim ke petugas.');
    // }

    // // ========== PETUGAS ==========
    // public function petugasKonfirmasiForm($id)
    // {
    //     $notifikasi = Notifikasi::with(['pengirim', 'tempatSampah'])->findOrFail($id);
    //     return view('petugas.konfirmasi-form', compact('notifikasi'));
    // }

    // public function konfirmasi(Request $request, $id)
    // {
    //     $request->validate([
    //         'bukti_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $notifikasi = Notifikasi::findOrFail($id);

    //     if ($request->hasFile('bukti_foto')) {
    //         $path = $request->file('bukti_foto')->store('bukti_sampah', 'public');

    //         $notifikasi->update([
    //             'bukti_foto' => $path,
    //             'dikonfirmasi' => true,
    //             'petugas_id' => auth()->id(),
    //         ]);
    //     }

    //     return redirect()->route('notifikasi.konfirmasi-form', $notifikasi->id)
    //         ->with('success', 'Penjemputan telah dikonfirmasi.');
    // }
}
