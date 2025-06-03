<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
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

    <!-- Main Content -->
    <div class="flex-grow-1 bg-light p-4">
        <h1 class="mb-4">Dashboard || Admin</h1>

        <!-- Ringkasan Sampah -->
        <h2>Total Rubbish</h2>
        <div class="d-flex align-items-center justify-content-between mt-4 mb-5">
            <div class="progress-circle text-center">
                <div class="circle">
                    <span class="percentage">75%</span>
                </div>
            </div>
            <div class="ms-5">
                <h2>The total of every waste</h2>
                <div class="waste-types">
                    <p>Organic waste: 50%</p>
                    <p>Plastic/glass bottle waste: 50%</p>
                    <p>Metal: 50%</p>
                </div>
            </div>
        </div>

        <!-- Tabel Notifikasi -->
        <h2 class="text-xl font-semibold mb-3">Notifikasi Sampah</h2>
        <table class="w-full bg-white shadow rounded table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Bukti Foto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifikasis as $notif)
                    <tr>
                        <td>{{ $notif->user->name }}</td>
                        <td>{{ $notif->lokasi }}</td>
                        <td>{{ $notif->dikonfirmasi ? 'Dikonfirmasi' : 'Belum' }}</td>
                        <td>
                            @if($notif->bukti_foto)
                                <img src="{{ asset('storage/' . $notif->bukti_foto) }}" class="w-20">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada notifikasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @push('styles')
    <style>
        .progress-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: conic-gradient(#007BFF 75%, #e0e0e0 75%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #007BFF;
            position: relative;
        }

        .progress-circle .circle {
            width: 100px;
            height: 100px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .waste-types p {
            margin: 10px 0;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function fetchRubbishStatus() {
            $.ajax({
                url: '/api/rubbish-status',
                method: 'GET',
                success: function (data) {
                    $('.progress-circle').css(
                        'background',
                        `conic-gradient(#007BFF ${data.total_rubbish}%, #e0e0e0 ${data.total_rubbish}%)`
                    );
                    $('.progress-circle .percentage').text(data.total_rubbish + '%');
                    $('.waste-types').html(`
                        <p>Organic waste: ${data.organic}%</p>
                        <p>Plastic/glass bottle waste: ${data.plastic}%</p>
                        <p>Metal: ${data.metal}%</p>
                    `);
                },
                error: function () {
                    console.error('Failed to fetch rubbish status');
                }
            });
        }

        setInterval(fetchRubbishStatus, 5000);
        fetchRubbishStatus();
    </script>
    @endpush
</x-app-layout>
