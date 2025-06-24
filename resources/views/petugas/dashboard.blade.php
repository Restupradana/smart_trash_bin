<x-app-layout>
    <x-slot name="title">Dashboard Petugas</x-slot>

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

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Daftar Semua Notifikasi</h3>

                @if($notifikasis->count() > 0)
                    <ul class="space-y-4">
                        @foreach($notifikasis as $notif)
                            <li class="border p-4 rounded bg-gray-50 dark:bg-gray-700">
                                <p class="text-gray-800 dark:text-gray-100"><strong>Lokasi:</strong> {{ $notif->lokasi }}</p>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Pesan:</strong> {{ $notif->pesan }}</p>
                                <p class="text-sm mt-2">
                                    <strong>Status:</strong>
                                    <span class="px-2 py-1 rounded text-white
                                        {{ $notif->status === 'pending' ? 'bg-yellow-600' : 'bg-green-600' }}">
                                        {{ ucfirst($notif->status) }}
                                    </span>
                                </p>

                                {{-- Tombol Konfirmasi atau Edit --}}
                                <a href="{{ route('petugas.konfirmasi.form', ['id' => $notif->id]) }}"
                                    class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                    {{ $notif->status === 'pending' ? 'Konfirmasi' : 'Ubah Konfirmasi' }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 dark:text-gray-300">Belum ada notifikasi yang tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
