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

    <h1 class="text-xl font-semibold mb-4">Kirim Notifikasi Sampah</h1>

    <form method="POST" action="{{ route('user.notifikasi.kirim') }}">
        @csrf

        <!-- Pilih Tempat Sampah -->
        <label for="tempat_sampah_id" class="block mb-2">Tempat Sampah:</label>
        <select name="tempat_sampah_id" id="tempat_sampah_id" class="w-full border p-2 rounded mb-4" required>
            <option value="" disabled selected>-- Pilih Tempat Sampah --</option>
            @foreach($tempatSampahs as $ts)
                <option value="{{ $ts->id }}">{{ $ts->nama }} ({{ $ts->lokasi }})</option>
            @endforeach
        </select>

        <!-- Pilih Sensor (ultrasonik sebagai sumber kapasitas) -->
        <label for="sensor_id" class="block mb-2">Sensor Kapasitas (Ultrasonik):</label>
        <select name="sensor_id" id="sensor_id" class="w-full border p-2 rounded mb-4" required>
            <option value="" disabled selected>-- Pilih Sensor Kapasitas --</option>
            @foreach($sensors as $sensor)
                @if($sensor->tipe === 'ultrasonik')
                    <option value="{{ $sensor->id }}">{{ $sensor->kode }} ({{ $sensor->tipe }})</option>
                @endif
            @endforeach
        </select>

        <!-- Pesan Opsional -->
        <label for="pesan" class="block mb-2">Pesan (Opsional):</label>
        <textarea name="pesan" id="pesan" rows="4" class="w-full border p-2 rounded mb-4"></textarea>

        <!-- Tombol Submit -->
        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Kirim Notifikasi
        </button>
    </form>
</x-app-layout>
