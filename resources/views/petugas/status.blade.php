
<x-app-layout>
<x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 shadow-sm text-white" style="background-color:rgb(55, 200, 233);">
            <!-- Kiri: Ikon dan Judul -->
            <div class="d-flex align-items-center">
                <i class="bi bi-trash-fill fs-3 me-3"></i>
                <h4 class="mb-0">Smart Trash Bin</h4>
            </div>
            <!-- Kanan: Notifikasi dan User -->
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

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h1 class="text-2xl font-bold text-center mb-6">Status Tempat Sampah</h1>

                <div class="d-flex justify-content-around flex-wrap gap-4">
                    {{-- Organik --}}
                    <div class="progress-container">
                        <h4 class="text-center fw-bold">Sampah Organik</h4>
                        <div class="progress-row">
                            <div class="progress-circle organic">
                                <div class="circle">
                                    <span class="percentage" id="organic-circle">85%</span>
                                </div>
                            </div>
                            <div class="status-detail">
                                <p>Kapasitas: <span id="organic-capacity">85%</span></p>
                                <p>Berat: <span id="organic-weight">12 kg</span></p>
                                <p>Waktu: <span id="organic-time">10:45 AM</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Plastik --}}
                    <div class="progress-container">
                        <h4 class="text-center fw-bold">Sampah Plastik</h4>
                        <div class="progress-row">
                            <div class="progress-circle plastic">
                                <div class="circle">
                                    <span class="percentage" id="plastic-circle">60%</span>
                                </div>
                            </div>
                            <div class="status-detail">
                                <p>Kapasitas: <span id="plastic-capacity">60%</span></p>
                                <p>Berat: <span id="plastic-weight">8 kg</span></p>
                                <p>Waktu: <span id="plastic-time">10:45 AM</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Metal --}}
                    <div class="progress-container">
                        <h4 class="text-center fw-bold">Sampah Metal</h4>
                        <div class="progress-row">
                            <div class="progress-circle metal">
                                <div class="circle">
                                    <span class="percentage" id="metal-circle">40%</span>
                                </div>
                            </div>
                            <div class="status-detail">
                                <p>Kapasitas: <span id="metal-capacity">40%</span></p>
                                <p>Berat: <span id="metal-weight">5 kg</span></p>
                                <p>Waktu: <span id="metal-time">10:45 AM</span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('styles')
<style>
    .progress-container {
        text-align: center;
        margin: 10px;
    }

    .progress-row {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .progress-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #e0e0e0; /* default background sebelum gradient masuk */
        position: relative;
        transition: background 0.5s ease;
    }

    .progress-circle .circle {
        width: 60px;
        height: 60px;
        background-color: #fff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1;
        position: relative;
    }

    .progress-circle .percentage {
        font-weight: bold;
    }

    .status-detail {
        text-align: left;
    }
</style>
@endpush


    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function fetchWasteStatus() {
            $.ajax({
                url: '/api/waste-status',
                method: 'GET',
                success: function (data) {
                    $('.organic').css(
                        'background',
                        `conic-gradient(#4CAF50 ${data.organic.capacity}%,rgb(172, 58, 58) ${data.organic.capacity}%)`
                    );
                    $('#organic-circle').text(data.organic.capacity + '%');
                    $('#organic-capacity').text(data.organic.capacity + '%');
                    $('#organic-weight').text(data.organic.weight);
                    $('#organic-time').text(data.organic.time);

                    $('.plastic').css(
                        'background',
                        `conic-gradient(#FFC107 ${data.plastic.capacity}%,rgb(55, 198, 45) ${data.plastic.capacity}%)`
                    );
                    $('#plastic-circle').text(data.plastic.capacity + '%');
                    $('#plastic-capacity').text(data.plastic.capacity + '%');
                    $('#plastic-weight').text(data.plastic.weight);
                    $('#plastic-time').text(data.plastic.time);

                    $('.metal').css(
                        'background',
                        `conic-gradient(#F44336 ${data.metal.capacity}%,rgb(16, 47, 100) ${data.metal.capacity}%)`
                    );
                    $('#metal-circle').text(data.metal.capacity + '%');
                    $('#metal-capacity').text(data.metal.capacity + '%');
                    $('#metal-weight').text(data.metal.weight);
                    $('#metal-time').text(data.metal.time);
                },
                error: function () {
                    console.error('Gagal mengambil data status sampah.');
                }
            });
        }

        setInterval(fetchWasteStatus, 5000);
        fetchWasteStatus();
    </script>
    @endpush
</x-app-layout>
