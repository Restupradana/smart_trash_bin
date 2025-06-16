<x-app-layout>
    <x-slot name="title">Konfirmasi Notifikasi</x-slot>

    <div class="container py-4">
        <h2 class="mb-4">Konfirmasi Notifikasi</h2>

        <form action="{{ route('petugas.konfirmasi.simpan') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="notifikasi_id" value="{{ $notifikasi->id }}">

            <div class="mb-3">
                <label>Lokasi</label>
                <input type="text" class="form-control" value="{{ $notifikasi->lokasi }}" disabled>
            </div>

            <div class="mb-3">
                <label for="bukti_foto" class="form-label">Upload Bukti Foto</label>
                <input type="file" class="form-control" id="bukti_foto" name="bukti_foto" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan Konfirmasi</button>
        </form>
    </div>
</x-app-layout>
