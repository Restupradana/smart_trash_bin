<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

        // Simpan foto
        $path = $request->file('bukti_foto')->store('bukti', 'public');

        $notifikasi->update([
            'dikonfirmasi' => true,
            'petugas_id' => Auth::id(),
            'bukti_foto' => $path,
        ]);

        return redirect()->route('petugas.dashboard')->with('success', 'Konfirmasi berhasil disimpan.');
    }
}
