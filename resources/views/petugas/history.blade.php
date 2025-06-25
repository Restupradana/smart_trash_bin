<x-app-layout>
    <x-slot name="title">Petugas History</x-slot>
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

    <!-- Main Content -->
    <div class="main-content flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="header mb-4 d-flex justify-content-between align-items-center">
            <h1>Histori Pembuangan Sampah</h1>
        </div>

        <!-- Table -->
        <div class="table-container table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Tempat Sampah</th>
                        <th>Nama Pengirim</th>
                        <th>Nama Petugas Sampah</th>
                        <th>Kategori</th>
                        <th>Berat</th>
                        <th>Status</th>
                        <th>Waktu Konfirmasi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($histories as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->tempatSampah->nama ?? '-' }}</td>
                            <td>{{ $item->pengirim->name ?? '-' }}</td>
                            <td>{{ $item->petugas->name ?? '-' }}</td>
                            <td>{{ $item->tempatSampah->jenis ?? '-' }}</td>
                            <td>{{ $item->nilai_berat ?? '-' }} kg</td>
                            <td>
                                @if($item->status === 'dikonfirmasi')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($item->status === 'diproses')
                                    <span class="badge bg-info text-dark">Diproses</span>
                                @elseif($item->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($item->status === 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Diketahui</span>
                                @endif
                            </td>
                            <td>
                                {{ $item->dikonfirmasi_pada ? \Carbon\Carbon::parse($item->dikonfirmasi_pada)->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Tidak ada histori tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Print Button -->
        <div class="mt-3 text-end">
            <button class="btn btn-primary"><i class="bi bi-printer"></i> Print data</button>
        </div>
    </div>

    @push('styles')
        <style>
            .sidebar a:hover {
                background-color: #0056b3;
                border-radius: 5px;
            }
        </style>
    @endpush
</x-app-layout>
