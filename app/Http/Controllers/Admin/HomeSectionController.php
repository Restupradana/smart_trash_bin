<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSection;
use Illuminate\Support\Facades\Storage;

class HomeSectionController extends Controller
{
    public function showHome()
    {
        $section = HomeSection::first(); // Ambil data pertama
        return view('welcome', compact('section'));
    }

    public function edit()
    {
        $section = HomeSection::firstOrCreate([], [
            'title' => '',
            'description' => '',
            'image_path' => '',
            'logo_path' => '', // ➕ Default logo kosong
        ]);

        return view('admin.edit-home', compact('section'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'logo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ➕ Validasi logo
        ]);

        $section = HomeSection::firstOrFail();

        // Ganti gambar utama jika ada
        if ($request->hasFile('image_path')) {
            if ($section->image_path && Storage::exists('public/' . $section->image_path)) {
                Storage::delete('public/' . $section->image_path);
            }

            $path = $request->file('image_path')->store('sampul', 'public');
            $section->image_path = $path;
        }

        // Ganti logo jika ada
        if ($request->hasFile('logo_path')) {
            if ($section->logo_path && Storage::exists('public/' . $section->logo_path)) {
                Storage::delete('public/' . $section->logo_path);
            }

            $logoPath = $request->file('logo_path')->store('logo', 'public');
            $section->logo_path = $logoPath;
        }

        // Update konten lainnya
        $section->title = $request->input('title');
        $section->description = $request->input('description');
        $section->save();

        return redirect()->route('admin.dashboard')->with('success', 'Konten halaman depan berhasil diperbarui!');
    }
}
