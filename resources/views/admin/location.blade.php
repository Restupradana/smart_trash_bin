<x-app-layout>
    <x-slot name="title">Admin Locations</x-slot>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white"
            style="background-color:rgb(55, 200, 233);">
            <!-- Kiri: Ikon dan Judul -->
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0">Smart Trash Bin</h4>
            </div>
            <!-- Kanan: Notifikasi dan User -->
            <div class="d-flex align-items-center">
                <!-- Notifikasi -->
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 mx-3 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown"
                        style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                        <li><a class="dropdown-item" href="#">View All Notifications</a></li>
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <i class="bi bi-person-circle fs-4 dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
                        style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
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

            .leaflet-container {
                z-index: 0;
            }

            .sidebar a.active {
                background-color: #2563eb;
                /* Tailwind blue-600 */
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

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <div style="background-color: #3b82f6; color: white; text-align: center; padding: 1rem; border-radius: 0.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">Lokasi Tempat Sampah</h2>
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
                            <th>Tempat</th>
                            <th>Sampah</th>
                            <th>Alamat</th>
                            <th>Koordinat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Isi tabel dinamis bisa disesuaikan -->
                        <tr>
                            <td>1</td>
                            <td>Nongsa</td>
                            <td>Organik</td>
                            <td>Jl. Nongsa Raya</td>
                            <td>1.125, 104.057</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Batam Center</td>
                            <td>Anorganik</td>
                            <td>Jl. Engku Putri</td>
                            <td>1.138, 104.028</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Jodoh</td>
                            <td>Campuran</td>
                            <td>Jl. Bunga Raya</td>
                            <td>1.145, 104.026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            const map = L.map('map').setView([1.119, 104.049], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const locations = [{
                lat: 1.125,
                lng: 104.057,
                place: "Nongsa",
                trashType: "Organik"
            },
            {
                lat: 1.138,
                lng: 104.028,
                place: "Batam Center",
                trashType: "Anorganik"
            },
            {
                lat: 1.145,
                lng: 104.026,
                place: "Jodoh",
                trashType: "Campuran"
            }
            ];

            locations.forEach(loc => {
                L.marker([loc.lat, loc.lng]).addTo(map)
                    .bindPopup(`<b>${loc.place}</b><br>Sampah: ${loc.trashType}`);
            });
        </script>
    @endpush
</x-app-layout>