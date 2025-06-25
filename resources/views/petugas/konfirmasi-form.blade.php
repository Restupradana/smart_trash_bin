<x-app-layout>
    <x-slot name="title">Konfirmasi Notifikasi</x-slot>

    <div class="container py-4">
        <h2 class="mb-4">Konfirmasi Notifikasi</h2>

        {{-- Tampilkan pesan sukses atau error --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('petugas.konfirmasi.simpan', ['id' => $notifikasi->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label><strong>Pengirim:</strong></label>
                <input type="text" class="form-control" value="{{ $notifikasi->pengirim->name ?? '-' }}" disabled>
            </div>

            <div class="mb-3">
                <label><strong>Lokasi Tempat Sampah:</strong></label>
                <input type="text" class="form-control"
                       value="{{ $notifikasi->tempatSampah->lokasi ?? $notifikasi->lokasi }}" disabled>
            </div>

            <div class="mb-3">
                <label><strong>Pesan:</strong></label>
                <textarea class="form-control" rows="3" disabled>{{ $notifikasi->pesan }}</textarea>
            </div>

            <div class="mb-3">
                <label for="bukti_foto" class="form-label"><strong>Upload Bukti Foto</strong></label>
                <input type="file" class="form-control" id="bukti_foto" name="bukti_foto" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan Konfirmasi</button>
            <a href="{{ route('petugas.dashboard') }}" class="btn btn-secondary ms-2">Kembali</a>
        </form>
    </div>
</x-app-layout>
