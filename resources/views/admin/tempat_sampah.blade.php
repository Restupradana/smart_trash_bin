<x-app-layout>
    <x-slot name="title">Kelola Tempat Sampah</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white" style="background-color:rgb(55, 200, 233);">
            <div class="d-flex align-items-center">
                <i class="bi bi-gear-fill fs-3 me-3"></i>
                <h4 class="mb-0">Manajemen Tempat Sampah</h4>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 mx-3 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                    </ul>
                </div>

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

    <div class="main-content flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <div class="header mb-4 d-flex justify-content-between align-items-center">
            <h1>Data Tempat Sampah</h1>
        </div>

        <!-- Table -->
        <div class="table-container table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Nama Tempat Sampah</th>
                        <th>Jenis</th>
                        <th>Lokasi</th>
                        <th>Dibuat</th>
                        <th>Terakhir Diubah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tempatSampahs as $index => $tempat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $tempat->nama }}</td>
                            <td>{{ ucfirst($tempat->jenis) }}</td>
                            <td>{{ $tempat->lokasi }}</td>
                            <td>{{ $tempat->created_at }}</td>
                            <td>{{ $tempat->updated_at }}</td>
                            <td>
                                <a href="{{ route('admin.tempat_sampah.edit', $tempat->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
