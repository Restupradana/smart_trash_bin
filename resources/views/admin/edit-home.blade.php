<x-app-layout>
    <x-slot name="title">Edit Halaman Depan</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white"
             style="background-color:rgb(55, 200, 233);">
            <div class="d-flex align-items-center">
                <i class="bi bi-pencil-square fs-3 me-3"></i>
                <h4 class="mb-0">Edit Konten Halaman Depan</h4>
            </div>

            <div class="d-flex align-items-center">
                <!-- Notifikasi -->
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 mx-3 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown"
                       style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
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

    <div class="main-content flex-grow-1 p-4" style="background-color: #f8f9fa;">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm bg-white">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" class="form-control" id="title" name="title" required
                       value="{{ old('title', $section->title ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $section->description ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image_path" class="form-label">Gambar Sampul</label>
                <input class="form-control" type="file" id="image_path" name="image_path">
                @if (!empty($section?->image_path))
                    <div class="mt-3">
                        <p class="mb-2">Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $section->image_path) }}" alt="Sampul"
                             class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="logo_path" class="form-label">Logo</label>
                <input class="form-control" type="file" id="logo_path" name="logo_path">
                @if (!empty($section?->logo_path))
                    <div class="mt-3">
                        <p class="mb-2">Logo saat ini:</p>
                        <img src="{{ asset('storage/' . $section->logo_path) }}" alt="Logo"
                             class="img-fluid rounded shadow-sm" style="max-width: 150px;">
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
