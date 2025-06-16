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

    {{-- ✅ Konten Utama --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-center mb-6">Status Tempat Sampah</h1>

                <div class="d-flex justify-content-around flex-wrap gap-4">
                    @foreach ($data as $item)
                        @php
                            $key = strtolower($item['jenis']); // 'organik', 'plastik', 'metal'
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
                        @endphp

                        <div class="progress-container">
                            <h4 class="text-center fw-bold">{{ $label }}</h4>
                            <div class="progress-row">
                                <div class="progress-circle"
                                    style="background: conic-gradient({{ $color }} {{ $kapasitas }}%, #e0e0e0 {{ $kapasitas }}%);">
                                    <div class="circle">
                                        <span class="percentage">{{ $kapasitas }}%</span>
                                    </div>
                                </div>
                                <div class="status-detail">
                                    <p>Kapasitas: <span>{{ $kapasitas }}%</span></p>
                                    <p>Berat: <span>{{ $berat !== null ? $berat . ' kg' : '-' }}</span></p>
                                    <p>Status: <span
                                            class="fw-bold {{ $kapasitas >= 90 ? 'text-danger' : 'text-success' }}">{{ $status }}</span>
                                    </p>
                                </div>
                            </div>

                            <form action="{{ route('user.notifikasi.kirim') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="lokasi" value="{{ $label }}">
                                <input type="hidden" name="kapasitas" value="{{ $kapasitas }}">
                                <input type="hidden" name="berat" value="{{ $berat }}">
                                <button type="submit" class="btn btn-danger btn-sm">Kirim Notifikasi</button>
                            </form>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    {{-- ✅ Style --}}
    @push('styles')
        <style>
            .progress-container {
                text-align: center;
                margin: 10px;
            }

            .progress-row {
                display: flex;
                align-items: center;
                gap: 20px;
            }

            .progress-circle {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #e0e0e0;
                position: relative;
                transition: background 0.5s ease;
            }

            .progress-circle .circle {
                width: 60px;
                height: 60px;
                background-color: #fff;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1;
                position: relative;
            }

            .progress-circle .percentage {
                font-weight: bold;
            }

            .status-detail {
                text-align: left;
            }
        </style>
    @endpush


</x-app-layout>
