<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempatSampah;

class TempatSampahController extends Controller
{
    // Menampilkan semua tempat sampah
    public function index()
    {
        $tempatSampahs = TempatSampah::all();
        return view('admin.tempat_sampah', compact('tempatSampahs'));
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $tempat = TempatSampah::findOrFail($id);
        return view('admin.edit_tempat_sampah', compact('tempat'));
    }

    // Menyimpan hasil edit
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        $tempat = TempatSampah::findOrFail($id);
        $tempat->update([
            'nama' => $request->nama,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('admin.tempat_sampah.index')->with('success', 'Data berhasil diperbarui.');
    }
}
