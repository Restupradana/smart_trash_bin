<x-app-layout>
    <x-slot name="title">Edit Tempat Sampah</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white" style="background-color:rgb(55, 200, 233);">
            <div class="d-flex align-items-center">
                <i class="bi bi-pencil-square fs-3 me-3"></i>
                <h4 class="mb-0">Edit Tempat Sampah</h4>
            </div>

            <div class="d-flex align-items-center">
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
        <div class="container bg-white p-4 rounded shadow-sm">
            <h2 class="mb-4">Form Edit Tempat Sampah</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.tempat_sampah.update', $tempat->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Tempat Sampah</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $tempat->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                        value="{{ old('lokasi', $tempat->lokasi) }}" required>
                    @error('lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.tempat_sampah.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
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
