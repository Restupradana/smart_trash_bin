<x-app-layout>
    <x-slot name="title">User Dashboard</x-slot>

    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm"
            style="background-color: #3f51b5; color: white; border-bottom: 1px solid #ddd;">
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0">Smart Trash Bin</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifikasi -->
                <div class="dropdown">
                    <i class="bi bi-bell fs-4 dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" role="button" style="cursor: pointer;"></i>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                        <li><a class="dropdown-item text-primary" href="#">View All</a></li>
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <i class="bi bi-person-circle fs-4 dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" role="button" style="cursor: pointer;"></i>
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
    <div class="p-4 bg-white shadow-sm rounded-3 mt-3 mx-3">
        <h1 class="mb-4 text-primary">Dashboard <small class="text-muted">|| User</small></h1>

        <!-- Ringkasan Sampah -->
        <h2 class="h4 text-secondary">Total Waste Overview</h2>
        <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 mb-5 gap-4">
            <div class="progress-circle text-center">
                <div class="circle">
                    <span class="percentage fw-bold">{{ $avg_total }}%</span>
                </div>
            </div>

            <div class="ms-md-5 flex-grow-1">
                <h5 class="mb-3">Waste Breakdown</h5>
                <div class="waste-types text-muted">
                    <p>Organic waste: <strong>{{ $avg_organik }}%</strong></p>
                    <p>Plastic/Glass bottles: <strong>{{ $avg_plastik }}%</strong></p>
                    <p>Metal waste: <strong>{{ $avg_metal }}%</strong></p>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            body {
                background-color: #f8f9fa;
            }

            .progress-circle {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                background: conic-gradient(#3f51b5 75%, #e0e0e0 75%);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                color: #3f51b5;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .progress-circle .circle {
                width: 100px;
                height: 100px;
                background-color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }

            .waste-types p {
                margin: 0.5rem 0;
                font-size: 16px;
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
                            `conic-gradient(#3f51b5 ${data.total_rubbish}%, #e0e0e0 ${data.total_rubbish}%)`
                        );
                        $('.progress-circle .percentage').text(data.total_rubbish + '%');
                        $('.waste-types').html(`
                            <p>Organic waste: <strong>${data.organic}%</strong></p>
                            <p>Plastic/Glass bottles: <strong>${data.plastic}%</strong></p>
                            <p>Metal waste: <strong>${data.metal}%</strong></p>
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
