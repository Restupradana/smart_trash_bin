@php
    $role = session('selected_role') ?? '';
@endphp

<div class="text-white p-3 d-flex flex-column" style="width: 250px; min-height: 100vh; background-color: #4682B4;">
    <ul class="list-unstyled flex-grow-1">
        @if ($role === 'admin')
            <li><a href="{{ route('admin.dashboard') }}" class="text-white d-block py-2"><i class="bi bi-house"></i>
                    Dashboard</a></li>
            <li><a href="{{ route('admin.status') }}" class="text-white d-block py-2"><i class="bi bi-bar-chart"></i>
                    Status</a></li>
            <li><a href="{{ route('admin.location') }}" class="text-white d-block py-2"><i class="bi bi-geo-alt-fill"></i>
                    Location</a></li>
            <li><a href="{{ route('admin.history') }}" class="text-white d-block py-2"><i class="bi bi-clock-history"></i>
                    History</a></li>

            {{-- Menu CRUD Akun untuk admin --}}
            <li><a href="{{ route('admin.accounts.index') }}" class="text-white d-block py-2"><i class="bi bi-people"></i>
                    Manajemen Akun</a></li>

            {{-- Menu Tempat Sampah --}}
            <li><a href="{{ route('admin.tempat_sampah.index') }}" class="text-white d-block py-2"><i
                        class="bi bi-trash3-fill"></i> Tempat Sampah</a></li>

            {{-- Menu Edit Konten Halaman Depan --}}
            <li><a href="{{ route('admin.home.edit') }}" class="text-white d-block py-2"><i class="bi bi-pencil-square"></i>
                    Edit Halaman Depan</a></li>

        @elseif ($role === 'user')
            <li><a href="{{ route('user.dashboard') }}" class="text-white d-block py-2"><i class="bi bi-house"></i>
                    Dashboard</a></li>
            <li><a href="{{ route('user.status') }}" class="text-white d-block py-2"><i class="bi bi-bar-chart"></i>
                    Status</a></li>
            <li><a href="{{ route('user.location') }}" class="text-white d-block py-2"><i class="bi bi-geo-alt-fill"></i>
                    Location</a></li>
            <li><a href="{{ route('user.history') }}" class="text-white d-block py-2"><i class="bi bi-clock-history"></i>
                    History</a></li>
        @elseif ($role === 'petugas')
            <li><a href="{{ route('petugas.dashboard') }}" class="text-white d-block py-2"><i class="bi bi-house"></i>
                    Dashboard</a></li>
            <li><a href="{{ route('petugas.status') }}" class="text-white d-block py-2"><i class="bi bi-bar-chart"></i>
                    Status</a></li>
            <li><a href="{{ route('petugas.location') }}" class="text-white d-block py-2"><i class="bi bi-geo-alt-fill"></i>
                    Location</a></li>
            <li><a href="{{ route('petugas.history') }}" class="text-white d-block py-2"><i class="bi bi-clock-history"></i>
                    History</a></li>
        @else
            <li><span class="text-white">Role tidak dikenali</span></li>
        @endif
    </ul>
</div>
