@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Dashboard Petugas</h1>

    <ul class="bg-white shadow rounded p-4">
        @foreach($notifikasis as $notif)
            <li class="border-b py-4">
                <div>
                    Lokasi: {{ $notif->lokasi }}

                    {{-- Tombol ke halaman konfirmasi (GET) --}}
                    <a href="{{ route('petugas.konfirmasi.form', ['id' => $notif->id]) }}"
                       class="bg-green-600 text-white px-2 py-1 rounded inline-block mt-2">
                        Konfirmasi
                    </a>
                </div>
            </li>
        @endforeach
    </ul>
@endsection
