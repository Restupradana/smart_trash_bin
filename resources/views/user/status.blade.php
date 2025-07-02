<x-app-layout>

    <x-slot name="title">User Status</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white"
            style="background: linear-gradient(135deg, rgb(55, 200, 233), rgb(0, 140, 255));">
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0 fw-semibold">Smart Trash Bin</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <i class="bi bi-bell-fill fs-4 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown"
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

    {{-- Konten Utama --}}
    <div class="py-6">
        <div class="container">
            <div class="bg-white rounded shadow-sm p-5">
                <h1 class="text-center mb-5 fw-bold text-primary">Status Tempat Sampah</h1>

                @php
                    $totalKapasitas = collect($data)->sum('kapasitas');
                    $totalBerat = collect($data)->sum('berat');
                @endphp

                <div class="text-center mb-4">
                    <h5>Total Sampah: <strong>{{ $totalBerat }} kg</strong></h5>
                    <h6>Rata-rata Kapasitas: <strong>{{ round($totalKapasitas / max(count($data), 1), 1) }}%</strong>
                    </h6>
                </div>

                <div class="d-flex flex-wrap justify-content-center gap-4">
                    @foreach ($data as $item)
                        @php
                            $key = strtolower($item['jenis']);
                            $label = match ($key) {
                                'organik' => 'Sampah Organik',
                                'plastik' => 'Sampah Botol Plastik/Kaca',
                                'metal' => 'Sampah Logam',
                                default => 'Sampah',
                            };
                            $color = match ($key) {
                                'organik' => '#4CAF50',
                                'plastik' => '#FFC107',
                                'metal' => '#F44336',
                                default => '#2196F3',
                            };
                            $kapasitas = $item['kapasitas'] ?? 0;
                            $berat = is_numeric($item['berat']) ? $item['berat'] : 0;
                            $status = $kapasitas >= 90 ? 'PENUH' : 'BELUM PENUH';
                        @endphp

                        <div class="card border-0 shadow-sm p-4" style="width: 320px;">
                            <h5 class="text-center mb-3">{{ $label }}</h5>
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <div class="progress-circle"
                                    style="background: conic-gradient({{ $color }} {{ $kapasitas }}%, #e0e0e0 {{ $kapasitas }}%);">
                                    <div class="circle">
                                        <span class="percentage">{{ $kapasitas }}%</span>
                                    </div>
                                </div>
                                <div class="status-detail">
                                    <p class="mb-1">Kapasitas: <strong>{{ $kapasitas }}%</strong></p>
                                    <p class="mb-1">Berat: <strong>{{ $berat }} kg</strong></p>
                                    <p>Status: <span
                                            class="fw-bold {{ $kapasitas >= 90 ? 'text-danger' : 'text-success' }}">
                                            {{ $status }}</span></p>
                                </div>
                            </div>

                            <form action="{{ route('user.notifikasi.kirim') }}" method="POST" class="mt-3"
                                onsubmit="return confirmSend(this)">
                                @csrf
                                <input type="hidden" name="tempat_sampah_id" value="{{ $item['id'] }}">
                                <input type="hidden" name="sensor_id" value="{{ $item['sensor_id'] }}">

                                @php
                                    $sudahAda = \App\Models\Notifikasi::where('tempat_sampah_id', $item['id'])
                                        ->where('sensor_id', $item['sensor_id'])
                                        ->where('status', 'pending')
                                        ->exists();
                                @endphp

                                @if($kapasitas >= 90)
                                    <button type="button" class="btn w-100 btn-outline-danger" disabled>
                                        {{ $sudahAda ? 'Notifikasi Dikirim' : 'Mengirim Otomatis...' }}
                                    </button>
                                @else
                                    <button type="button" class="btn w-100 btn-outline-secondary" disabled>
                                        Belum Penuh
                                    </button>
                                @endif

                            </form>


                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Style --}}
    @push('styles')
        <style>
            .progress-circle {
                width: 90px;
                height: 90px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #e0e0e0;
                position: relative;
                transition: all 0.4s ease;
            }

            .progress-circle .circle {
                width: 65px;
                height: 65px;
                background-color: #fff;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
                font-weight: 600;
                font-size: 1rem;
                position: relative;
                z-index: 2;
            }

            .status-detail {
                font-size: 0.95rem;
                line-height: 1.4;
            }

            .card:hover {
                transform: translateY(-3px);
                transition: 0.3s ease-in-out;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            }

            .btn-outline-danger {
                transition: all 0.2s;
            }

            .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #fff;
                border-color: #dc3545;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function confirmSend(form) {
                const kapasitas = parseFloat(form.kapasitas.value);
                const berat = parseFloat(form.berat.value);

                if (isNaN(kapasitas) || isNaN(berat)) {
                    alert('Data kapasitas atau berat tidak valid.');
                    return false;
                }

                if (kapasitas < 90) {
                    alert('Tempat sampah belum penuh.');
                    return false;
                }

                return confirm(`Kirim notifikasi untuk ${form.lokasi.value}?\nKapasitas: ${kapasitas}%\nBerat: ${berat} kg`);
            }
        </script>
    @endpush


</x-app-layout>