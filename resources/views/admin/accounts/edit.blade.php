<x-app-layout>
    <x-slot name="title">Admin User Edit</x-slot>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-white">
            Edit Akun Admin
        </h2>
    </x-slot>

    <div class="container" style="max-width: 600px;">
        <form action="{{ route('admin.accounts.update', $account) }}" method="POST"
            class="bg-white rounded-4 shadow-sm p-5 border border-light" novalidate>
            @csrf
            @method('PUT')

            <!-- Nama (readonly) -->
            <div class="mb-4">
                <label for="name" class="form-label fw-semibold text-secondary">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $account->name) }}"
                    class="form-control bg-light text-secondary" readonly>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold text-secondary">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $account->email) }}" required
                    class="form-control @error('email') is-invalid @enderror" placeholder="email@example.com"
                    autocomplete="email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- WhatsApp Number -->
            <div class="mb-4">
                <label for="wa_number" class="form-label fw-semibold text-secondary">Nomor WhatsApp</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0">+62</span>
                    <input type="text" name="wa_number" id="wa_number"
                        value="{{ old('wa_number', preg_replace('/^\+62/', '', $account->wa_number ?? '')) }}" required
                        class="form-control border-start-0 @error('wa_number') is-invalid @enderror"
                        placeholder="81234567890" autocomplete="tel">
                </div>
                @error('wa_number')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role (Multi Select) -->
            <div class="mb-4">
                <label for="roles" class="form-label fw-semibold text-secondary">Roles</label>
                <select name="roles[]" id="roles" multiple class="form-select @error('roles') is-invalid @enderror">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ in_array($role->id, $userRoles) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Gunakan Ctrl (Windows) / Command (Mac) untuk memilih lebih dari satu.</small>
                @error('roles')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold text-secondary">
                    Password <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                </label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter"
                    autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold text-secondary">Konfirmasi
                    Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder="Ulangi password" autocomplete="new-password">
            </div>



            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.accounts.index') }}"
                    class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit"
                    class="btn btn-primary rounded-pill px-5 py-2 fw-semibold d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>