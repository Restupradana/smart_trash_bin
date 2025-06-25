<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

    {{-- Header --}}
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white" style="background: linear-gradient(90deg, #37c8e9, #3f9bd9);">
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0 fw-bold">Smart Trash Bin Dashboard</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifikasi -->
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                        <li><a class="dropdown-item" href="#">View All Notifications</a></li>
                    </ul>
                </div>

                <!-- User Dropdown -->
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

    {{-- Main Content --}}
    <div class="bg-light p-4">
        <h1 class="mb-4 fw-bold">Dashboard || Admin</h1>

        {{-- Ringkasan Total Sampah --}}
        <div class="card shadow-sm mb-5 p-4">
            <h2 class="mb-4 text-primary">📊 Rangkuman Sampah</h2>
            <div class="d-flex align-items-center flex-wrap gap-4">
                <div class="progress-circle text-center">
                    <div class="circle">
                        <span class="percentage">{{ $avg_total }}%</span>
                    </div>
                </div>

                <div class="ms-3">
                    <h4 class="fw-semibold">Persentase Sampah:</h4>
                    <div class="waste-types text-muted">
                        <p>♻️ Organik: {{ $avg_organik }}%</p>
                        <p>🧴 Plastik/Kaca: {{ $avg_plastik }}%</p>
                        <p>🛠️ Logam: {{ $avg_metal }}%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Notifikasi --}}
        <div class="card shadow-sm p-4">
            <h2 class="text-primary mb-3">🔔 Notifikasi Sampah</h2>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama Tempat Sampah</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Bukti Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifikasis as $notif)
                        <tr>
                            <td>{{ $notif->tempatSampah->nama ?? '-' }}</td>
                            <td>{{ $notif->tempatSampah->lokasi ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $notif->status === 'dikonfirmasi' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($notif->status) }}
                                </span>
                            </td>
                            <td>
                                @if($notif->bukti_foto)
                                    <img src="{{ asset('storage/' . $notif->bukti_foto) }}" class="img-thumbnail" style="width: 80px; height: auto;">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada notifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Styles --}}
    @push('styles')
        <style>
            .progress-circle {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                background: conic-gradient(#0d6efd {{ $avg_total ?? 0 }}%, #e0e0e0 0%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                font-weight: bold;
            }

            .circle {
                width: 100px;
                height: 100px;
                background: #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                color: #0d6efd;
            }

            .waste-types p {
                margin: 4px 0;
                font-size: 16px;
            }
        </style>
    @endpush

    {{-- Scripts --}}
    @push('scripts')
        <script>
            function fetchRubbishStatus() {
                $.ajax({
                    url: '/api/rubbish-status',
                    method: 'GET',
                    success: function (data) {
                        $('.progress-circle').css(
                            'background',
                            `conic-gradient(#0d6efd ${data.total_rubbish}%, #e0e0e0 ${data.total_rubbish}%)`
                        );
                        $('.progress-circle .percentage').text(data.total_rubbish + '%');
                        $('.waste-types').html(`
                            <p>♻️ Organik: ${data.organic}%</p>
                            <p>🧴 Plastik/Kaca: ${data.plastic}%</p>
                            <p>🛠️ Logam: ${data.metal}%</p>
                        `);
                    },
                    error: function () {
                        console.error('Gagal mengambil data sampah!');
                    }
                });
            }

            setInterval(fetchRubbishStatus, 5000);
            fetchRubbishStatus();
        </script>
    @endpush
</x-app-layout>
