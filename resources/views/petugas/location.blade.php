<x-app-layout>
    <x-slot name="header">
        <!-- ... HEADER sama seperti sebelumnya ... -->
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            #map {
                height: 320px;
                width: 100%;
                border-radius: 8px;
            }

            .leaflet-container {
                z-index: 0;
            }

            table th, table td {
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
        <div class="bg-blue-500 text-white text-center p-4 rounded">
            <h2 class="text-xl font-semibold">Lokasi Tempat Sampah</h2>
        </div>

        <!-- Peta -->
        <div id="map" class="mt-2 shadow"></div>

        <!-- Tabel Lokasi -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-3">Daftar Lokasi</h3>
            <div class="overflow-x-auto">
                <table class="table-auto border w-full">
                    <thead class="bg-blue-100">
                        <tr>
                            <th>No</th>
                            <th>Nama Tempat</th>
                            <th>Jenis Sampah</th>
                            <th>Koordinat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tempats as $index => $tempat)
                            @php
                                [$lat, $lng] = explode(',', $tempat->lokasi);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tempat->nama }}</td>
                                <td>{{ ucfirst($tempat->jenis) }}</td>
                                <td>{{ $lat }}, {{ $lng }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data lokasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            const map = L.map('map').setView([1.026, 104.064], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const locations = @json($tempats);

            locations.forEach(loc => {
                const [lat, lng] = loc.lokasi.split(',').map(Number);
                if (lat && lng) {
                    L.marker([lat, lng]).addTo(map)
                        .bindPopup(`<b>${loc.nama}</b><br>Jenis Sampah: ${loc.jenis}`);
                }
            });
        </script>
    @endpush
</x-app-layout>
