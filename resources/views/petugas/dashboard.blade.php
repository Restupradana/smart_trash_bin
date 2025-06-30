<x-app-layout>
    <x-slot name="title">Dashboard Petugas</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white bg-primary rounded-bottom">
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0">Smart Trash Bin</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifikasi Dropdown -->
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Lihat Semua</a></li>
                    </ul>
                </div>
                <!-- User Dropdown -->
                <div class="dropdown">
                    <i class="bi bi-person-circle fs-4 dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-5 bg-light">
        <div class="container">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Daftar Semua Notifikasi</h5>

                    @if($notifikasis->count() > 0)
                        <ul class="list-unstyled">
                            @foreach($notifikasis as $notif)
                                <li class="p-4 mb-3 border rounded-3 bg-white shadow-sm">
                                    <p class="mb-1 text-dark">
                                        <strong><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Lokasi:</strong> {{ $notif->lokasi }}
                                    </p>
                                    <p class="mb-1 text-secondary">
                                        <strong><i class="bi bi-chat-left-text me-2 text-info"></i>Pesan:</strong> {{ $notif->pesan }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Status:</strong>
                                        <span class="badge {{ $notif->status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                            {{ ucfirst($notif->status) }}
                                        </span>
                                    </p>
                                    <a href="{{ route('petugas.konfirmasi.form', ['id' => $notif->id]) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        {{ $notif->status === 'pending' ? 'Konfirmasi' : 'Ubah Konfirmasi' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Belum ada notifikasi yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
