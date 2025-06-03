@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Form Konfirmasi Penjemputan Sampah</h1>

    <div class="bg-white p-6 rounded shadow-md">
        <form method="POST" action="{{ route('petugas.konfirmasi.simpan') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="notifikasi_id" value="{{ $notifikasi->id }}">

            <div class="mb-4">
                <label for="lokasi" class="block font-medium">Lokasi Tempat Sampah</label>
                <input type="text" name="lokasi" id="lokasi" value="{{ $notifikasi->lokasi }}" readonly
                    class="w-full border p-2 rounded bg-gray-100">
            </div>

            <div class="mb-4">
                <label for="bukti_foto" class="block font-medium">Upload Bukti Foto Penjemputan</label>
                <input type="file" name="bukti_foto" id="bukti_foto" required class="w-full border p-2 rounded">
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                Konfirmasi Penjemputan
            </button>
        </form>
    </div>
@endsection
