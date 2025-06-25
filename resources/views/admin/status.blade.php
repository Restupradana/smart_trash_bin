<x-app-layout>
    <x-slot name="title">Admin Status</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white"
            style="background-color:rgb(55, 200, 233);">
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0">Smart Trash Bin</h4>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 mx-3 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown"
                        style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                        <li><a class="dropdown-item" href="#">View All Notifications</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <i class="bi bi-person-circle fs-4 dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
                        style="cursor: pointer;"></i>
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

    <div class="py-10 bg-light min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-3xl font-bold text-center mb-8">Status Tempat Sampah</h1>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($data as $item)
                        @php
                            $key = strtolower($item['jenis']);
                            $label = match($key) {
                                'organik' => 'Sampah Organik',
                                'plastik' => 'Sampah Botol Plastik/Kaca',
                                'metal' => 'Sampah Metal',
                                default => 'Sampah',
                            };
                            $icon = match($key) {
                                'organik' => 'bi bi-flower2',
                                'plastik' => 'bi bi-droplet-half',
                                'metal' => 'bi bi-cpu-fill',
                                default => 'bi bi-trash',
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
                            $lokasi = $item['lokasi'] ?? 'Lokasi tidak diketahui';
                        @endphp
                        
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body text-center">
                                    <i class="{{ $icon }} fs-1 mb-3 text-secondary"></i>
                                    <h5 class="card-title fw-bold">{{ $label }}</h5>
                                    <p class="text-muted small mb-2">{{ $lokasi }}</p>
                                    <div class="d-flex justify-content-center my-3">
                                        <div class="progress-circle"
                                            style="background: conic-gradient({{ $color }} {{ $kapasitas }}%, #e0e0e0 {{ $kapasitas }}%);">
                                            <div class="circle">
                                                <span class="percentage">{{ $kapasitas }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled text-start mt-3">
                                        <li>Kapasitas: <strong>{{ $kapasitas }}%</strong></li>
                                        <li>Berat: <strong>{{ $berat !== null ? $berat . ' kg' : '-' }}</strong></li>
                                        <li>Status: <strong class="{{ $kapasitas >= 90 ? 'text-danger' : 'text-success' }}">{{ $status }}</strong></li>
                                    </ul>
                                </div>
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
                background-color: #e0e0e0;
                position: relative;
                transition: background 0.5s ease;
            }

            .progress-circle .circle {
                width: 70px;
                height: 70px;
                background-color: #fff;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1;
                position: relative;
                box-shadow: 0 0 5px rgba(0,0,0,0.1);
            }

            .progress-circle .percentage {
                font-weight: 600;
                font-size: 1rem;
            }

            .card-title {
                font-size: 1.1rem;
            }

            @media (max-width: 768px) {
                .progress-circle {
                    width: 80px;
                    height: 80px;
                }

                .progress-circle .circle {
                    width: 60px;
                    height: 60px;
                }

                .progress-circle .percentage {
                    font-size: 0.9rem;
                }
            }
        </style>
    @endpush
</x-app-layout>
