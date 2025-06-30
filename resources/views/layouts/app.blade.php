<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ✅ Gunakan slot title jika tersedia, jika tidak fallback ke config --}}
    <title>{{ $title ?? config('app.name', 'SmartTrashBin') }}</title>

    {{-- ✅ Favicon Dinamis dari DB --}}
    @php
        use App\Models\HomeSection;
        $homeSection = HomeSection::first();
    @endphp

    @if (!empty($homeSection?->logo_path))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $homeSection->logo_path) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tambahan CSS -->
    @stack('styles')

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
</head>
<body class="font-sans antialiased">
    <div class="d-flex">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div class="flex-grow-1 d-flex flex-column" style="min-height: 100vh;">
            <!-- Page Heading -->
            @isset($header)
                <header class="shadow" style="background-color: #1E90FF; color: white;">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="p-4">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Tambahan JS -->
    @stack('scripts')
</body>
</html>
