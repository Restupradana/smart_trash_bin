<x-app-layout>
    <x-slot name="title">User Form Notifikasi</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white" style="background-color:rgb(55, 200, 233);">
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0">Smart Trash Bin</h4>
            </div>
            <div class="d-flex align-items-center">
                <!-- Notifikasi -->
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 mx-3 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
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

    <div class="container mt-4">
        <h2 class="mb-4">Kirim Permintaan Penjemputan Sampah</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('user.notifikasi') }}">
            @csrf

            <div class="mb-3">
                <label for="tempat_sampah_id" class="form-label">Tempat Sampah</label>
                <select name="tempat_sampah_id" class="form-select" required>
                    <option value="">-- Pilih Lokasi Tempat Sampah --</option>
                    @foreach(\App\Models\TempatSampah::all() as $tempat)
                        <option value="{{ $tempat->id }}">{{ $tempat->lokasi }}</option>
                    @endforeach
                </select>
                @error('tempat_sampah_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="penerima_id" class="form-label">Pilih Petugas</label>
                <select name="penerima_id" class="form-select" required>
                    <option value="">-- Pilih Petugas --</option>
                    @foreach(\App\Models\User::where('role', 'petugas')->get() as $petugas)
                        <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                    @endforeach
                </select>
                @error('penerima_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="pesan" class="form-label">Pesan</label>
                <textarea name="pesan" class="form-control" rows="3" required>{{ old('pesan') }}</textarea>
                @error('pesan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Kirim Notifikasi</button>
        </form>

        <!-- Riwayat Notifikasi -->
        <hr class="my-5">
        <h4>Riwayat Permintaan Anda</h4>

        <div class="table-responsive mt-3">
            <table class="table table-bordered text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>Lokasi</th>
                        <th>Petugas</th>
                        <th>Pesan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifikasis as $notif)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $notif->tempatSampah->lokasi ?? '-' }}</td>
                            <td>{{ $notif->petugas->name ?? '-' }}</td>
                            <td>{{ $notif->pesan }}</td>
                            <td>
                                @if($notif->dikonfirmasi)
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-warning">Belum</span>
                                @endif
                            </td>
                            <td>{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada notifikasi yang dikirim.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
