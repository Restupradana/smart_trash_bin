@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-xl font-semibold mb-4">Form Konfirmasi Penjemputan Sampah</h1>

        <!-- Feedback -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded shadow-md">
            <form method="POST" action="{{ route('petugas.konfirmasi.simpan') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="notifikasi_id" value="{{ $notifikasi->id }}">

                <div class="mb-4">
                    <label for="lokasi" class="block font-medium">Lokasi Tempat Sampah</label>
                    <input type="text" name="lokasi" id="lokasi"
                        value="{{ $notifikasi->tempatSampah->lokasi ?? '-' }}" readonly
                        class="w-full border p-2 rounded bg-gray-100">
                </div>

                <div class="mb-4">
                    <label for="bukti_foto" class="block font-medium">Upload Bukti Foto Penjemputan</label>
                    <input type="file" name="bukti_foto" id="bukti_foto" accept="image/*" required
                        class="w-full border p-2 rounded">
                    <small class="text-muted">Hanya file gambar (jpg, jpeg, png).</small>
                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Konfirmasi Penjemputan
                </button>
            </form>
        </div>
    </div>
@endsection
