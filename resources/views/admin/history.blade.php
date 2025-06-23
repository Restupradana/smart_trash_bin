<x-app-layout>
    <x-slot name="title">Admin History</x-slot>

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

    <div class="main-content flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="header mb-4 d-flex justify-content-between align-items-center">
            <h1>Riwayat Notifikasi Pembuangan Sampah</h1>
        </div>

        <div class="table-container table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Lokasi Tempat Sampah</th>
                        <th>Petugas</th>
                        <th>Pengirim (Guru)</th>
                        <th>Pesan</th>
                        <th>Status</th>
                        <th>Bukti Foto</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifikasis as $notif)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $notif->tempatSampah->lokasi ?? '-' }}</td>
                            <td>{{ $notif->petugas->name ?? '-' }}</td>
                            <td>{{ $notif->pengirim->name ?? '-' }}</td>
                            <td>{{ $notif->pesan }}</td>
                            <td>
                                @if($notif->status === 'dikonfirmasi')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif

                            </td>
                            <td>
                                @if($notif->bukti_foto)
                                    <a href="{{ asset('storage/' . $notif->bukti_foto) }}" target="_blank">Lihat Foto</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Tidak ada data notifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Print Button -->
        <div class="mt-3 text-end">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> Print data
            </button>
        </div>
    </div>

    @push('styles')
        <style>
            .sidebar a:hover {
                background-color: #0056b3;
                border-radius: 5px;
            }

            @media print {

                .btn,
                .dropdown,
                .main-content>.header {
                    display: none !important;
                }

                table {
                    font-size: 12px;
                }
            }
        </style>
    @endpush

</x-app-layout>