<x-app-layout>
    <x-slot name="title">Admin Locations</x-slot>

    <x-slot name="header">
        <!-- Header tetap sesuai kebutuhanmu -->
        <div class="px-4 py-3 bg-blue-500 text-white shadow-sm flex justify-between items-center">
            <div class="flex items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0">Smart Trash Bin</h4>
            </div>
            <div class="flex items-center">
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 mx-3 dropdown-toggle" data-bs-toggle="dropdown"
                        style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <i class="bi bi-person-circle fs-4 dropdown-toggle" data-bs-toggle="dropdown"
                        style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
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

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            #map {
                height: 320px;
                width: 100%;
                border-radius: 8px;
            }

            table th,
            table td {
                border: 1px solid #ddd;
                padding: 8px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }
        </style>
    @endpush

    <main class="flex-1 p-6">
        <div style="background-color: #3b82f6; color: white; text-align: center; padding: 1rem; border-radius: 0.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">Lokasi Tempat Sampah</h2>
        </div>

        <!-- Peta -->
        <div id="map" class="mt-4 shadow"></div>

        <!-- Tabel Lokasi -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-3">Daftar Lokasi</h3>
            <div class="overflow-x-auto">
                <table class="table-auto border w-full">
                    <thead class="bg-blue-100">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Koordinat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tempat_sampah as $index => $tempat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tempat->nama }}</td>
                                <td>{{ ucfirst($tempat->jenis) }}</td>
                                <td>{{ $tempat->lokasi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            const map = L.map('map').setView([1.026125, 104.064691], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const locations = @json($tempat_sampah);
            const usedCoords = {};

            locations.forEach((loc, index) => {
                if (loc.lokasi) {
                    let [lat, lng] = loc.lokasi.split(',').map(Number);

                    // Deteksi duplikat koordinat
                    const key = `${lat},${lng}`;
                    if (usedCoords[key]) {
                        // Offset agar tidak menumpuk (contoh: geser 0.00005 derajat per duplikat)
                        const offset = usedCoords[key] * 0.00005;
                        lat += offset;
                        lng += offset;
                        usedCoords[key]++;
                    } else {
                        usedCoords[key] = 1;
                    }

                    if (!isNaN(lat) && !isNaN(lng)) {
                        L.marker([lat, lng]).addTo(map)
                            .bindPopup(`<b>${loc.nama}</b><br>Jenis: ${loc.jenis}`);
                    }
                }
            });
        </script>
    @endpush


</x-app-layout>