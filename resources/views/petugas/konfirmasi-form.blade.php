<x-app-layout>
    <x-slot name="title">Konfirmasi Notifikasi</x-slot>

    <div class="container py-4">
        <h2 class="mb-4">Konfirmasi Notifikasi</h2>

        <form action="{{ route('petugas.konfirmasi.simpan', ['id' => $notifikasi->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                <label for="bukti_foto" class="form-label"><strong>Upload Bukti Foto</strong></label>
                <input type="file" class="form-control" id="bukti_foto" name="bukti_foto" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan Konfirmasi</button>
        </form>
    </div>
</x-app-layout>
