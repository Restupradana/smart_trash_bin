<x-app-layout>
    <x-slot name="title">Petugas Status</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white bg-primary rounded-bottom">
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0">Smart Trash Bin</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifikasi -->
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">View All Notifications</a></li>
                    </ul>
                </div>
                <!-- User -->
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

    <div class="py-5 bg-light">
        <div class="container">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h2 class="text-center text-primary fw-bold mb-4">Status Tempat Sampah</h2>

                <div class="d-flex flex-wrap justify-content-center gap-4">
                    @foreach ($data as $item)
                        @php
                            $key = strtolower($item['jenis']);
                            $label = match ($key) {
                                'organik' => 'Sampah Organik',
                                'plastik' => 'Sampah Botol Plastik/Kaca',
                                'metal' => 'Sampah Metal',
                                default => 'Sampah',
                            };
                            $color = match ($key) {
                                'organik' => '#4CAF50',
                                'plastik' => '#FFC107',
                                'metal' => '#F44336',
                                default => '#2196F3',
                            };
                            $kapasitas = $item['kapasitas'] ?? 0;
                            $berat = $item['berat'] ?? '-';
                            $status = $kapasitas >= 90 ? 'PENUH' : 'BELUM PENUH';
                            $statusClass = $kapasitas >= 90 ? 'bg-danger' : 'bg-success';
                        @endphp

                        <div class="p-4 bg-white rounded-3 shadow-sm text-center" style="min-width: 240px;">
                            <h5 class="fw-semibold text-secondary mb-3">{{ $label }}</h5>

                            <div class="position-relative mx-auto mb-3 progress-circle"
                                style="background: conic-gradient({{ $color }} {{ $kapasitas }}%, #e0e0e0 {{ $kapasitas }}%);">
                                <div class="circle">
                                    <span class="percentage">{{ $kapasitas }}%</span>
                                </div>
                            </div>

                            <div class="text-start text-secondary small">
                                <p class="mb-1">Kapasitas: <strong>{{ $kapasitas }}%</strong></p>
                                <p class="mb-1">Berat: <strong>{{ $berat }} kg</strong></p>
                                <p class="mb-0">
                                    Status:
                                    <span class="badge {{ $statusClass }}">{{ $status }}</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .progress-circle {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                transition: all 0.4s ease;
                box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
            }

            .progress-circle:hover {
                transform: scale(1.05);
            }

            .progress-circle .circle {
                width: 70px;
                height: 70px;
                background-color: #fff;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.05);
            }

            .progress-circle .percentage {
                font-weight: bold;
                color: #333;
            }

            .badge.bg-success {
                background-color: #28a745 !important;
            }

            .badge.bg-danger {
                background-color: #dc3545 !important;
            }
        </style>
    @endpush
</x-app-layout>
