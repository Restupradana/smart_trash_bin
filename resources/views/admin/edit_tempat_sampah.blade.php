<x-app-layout>
    <x-slot name="title">Edit Tempat Sampah</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white" style="background-color:rgb(55, 200, 233);">
            <div class="d-flex align-items-center">
                <i class="bi bi-pencil-square fs-3 me-3"></i>
                <h4 class="mb-0">Edit Tempat Sampah</h4>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <i class="bi bi-person-circle fs-4 dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="main-content flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="container bg-white p-4 rounded shadow-sm">
            <h2 class="mb-4">Form Edit Tempat Sampah</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.tempat_sampah.update', $tempat->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Tempat Sampah</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $tempat->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input pencarian lokasi --}}
                <div class="mb-3">
                    <label for="search-lokasi" class="form-label">Cari Kota/Daerah</label>
                    <div class="input-group">
                        <input type="text" id="search-lokasi" class="form-control" placeholder="Contoh: Jakarta Selatan">
                        <button type="button" id="btn-cari-lokasi" class="btn btn-outline-primary">Cari</button>
                    </div>
                </div>

                {{-- Peta untuk memilih lokasi --}}
                <div id="map" style="height: 400px;" class="mb-3"></div>

                <div class="mb-3">
                    <label for="lokasi" class="form-label">Koordinat Lokasi (Lat,Lng)</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                        value="{{ old('lokasi', $tempat->lokasi) }}" required>
                    @error('lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.tempat_sampah.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tambah Style dan Script --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            let lokasiInput = document.getElementById('lokasi');
            let initialCoords = lokasiInput.value.split(',');
            let lat = parseFloat(initialCoords[0]) || -6.200000;
            let lng = parseFloat(initialCoords[1]) || 106.816666;

            const map = L.map('map').setView([lat, lng], 13);
            const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(map);

            marker.on('dragend', function (e) {
                const position = marker.getLatLng();
                lokasiInput.value = `${position.lat.toFixed(6)},${position.lng.toFixed(6)}`;
            });

            document.getElementById('btn-cari-lokasi').addEventListener('click', function () {
                const query = document.getElementById('search-lokasi').value;

                if (!query) return alert('Silakan masukkan nama kota atau daerah.');

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            alert('Lokasi tidak ditemukan!');
                            return;
                        }

                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        const newLatLng = [lat, lon];

                        map.setView(newLatLng, 15);
                        marker.setLatLng(newLatLng);
                        lokasiInput.value = `${lat.toFixed(6)},${lon.toFixed(6)}`;
                    })
                    .catch(error => {
                        console.error('Geocoding error:', error);
                        alert('Terjadi kesalahan saat mencari lokasi.');
                    });
            });
        </script>
    @endpush
</x-app-layout>
