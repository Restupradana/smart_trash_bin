<x-app-layout>
    <x-slot name="title">Admin User List</x-slot>
    <x-slot name="header">
        <div class="shadow-sm" style="background-color: rgb(55, 200, 233);">
            <!-- Bar atas: logo, notif, user -->
            <div class="d-flex align-items-center justify-content-between px-4 py-3 text-white">
                <div class="d-flex align-items-center">
                    <i class="bi bi-trash-fill fs-3 me-3"></i>
                    <h4 class="mb-0">Smart Trash Bin</h4>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Notifikasi -->
                    <div class="dropdown">
                        <i class="bi bi-bell fs-4 mx-3 dropdown-toggle" id="notificationDropdown"
                            data-bs-toggle="dropdown" style="cursor: pointer;"></i>
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

            <!-- Bar bawah: judul halaman -->
            <div class="px-4 py-2 border-top border-white text-white">
                <h2 class="fw-semibold fs-4 mb-0">Manajemen Akun</h2>
            </div>
        </div>
    </x-slot>


    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.accounts.create') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Akun
            </a>
        </div>

        <div class="table-responsive bg-white rounded shadow-sm p-3">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>WhatsApp</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->wa_number }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td>
                                <a href="{{ route('admin.accounts.edit', $user->id) }}"
                                    class="btn btn-sm btn-primary me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.accounts.destroy', $user->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada akun ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
