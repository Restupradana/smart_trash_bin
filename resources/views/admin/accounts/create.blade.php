<x-app-layout>
    <x-slot name="title">Admin User Create</x-slot>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Tambah Akun Admin</h2>
    </x-slot>

    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-5 bg-white">
                <form action="{{ route('admin.accounts.store') }}" method="POST" novalidate>
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Nama</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" 
                            placeholder="Masukkan nama lengkap"
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" 
                            placeholder="email@example.com"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor WhatsApp --}}
                    <div class="mb-4">
                        <label for="wa_number" class="form-label fw-semibold">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text">+62</span>
                            <input 
                                type="text" 
                                name="wa_number" 
                                id="wa_number" 
                                value="{{ old('wa_number') }}"
                                class="form-control @error('wa_number') is-invalid @enderror" 
                                placeholder="81234567890"
                            >
                        </div>
                        @error('wa_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Minimal 6 karakter"
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="form-control" 
                            placeholder="Ulangi password"
                        >
                    </div>

                    {{-- Roles --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Roles</label>
                        <div>
                            @foreach ($roles as $role)
                                <div class="form-check form-check-inline">
                                    <input 
                                        class="form-check-input @error('roles') is-invalid @enderror" 
                                        type="checkbox" 
                                        name="roles[]" 
                                        id="role{{ $role->id }}" 
                                        value="{{ $role->id }}"
                                        {{ (is_array(old('roles')) && in_array($role->id, old('roles'))) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="role{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @endforeach
                            @error('roles')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
