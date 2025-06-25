<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\TempatSampahController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Auth\RoleSelectionController;

// Halaman awal
Route::get('/', fn() => view('welcome'));

// Redirect dashboard berdasarkan role yang dipilih di session
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) return redirect()->route('login');

    $selectedRole = session('selected_role');
    if ($selectedRole && in_array($selectedRole, ['admin', 'user', 'petugas'])) {
        return redirect()->route($selectedRole . '.dashboard');
    }

    $roles = $user->roles()->pluck('name')->toArray();
    if (count($roles) === 1) {
        session(['selected_role' => $roles[0]]);
        return redirect()->route($roles[0] . '.dashboard');
    } elseif (count($roles) > 1) {
        return redirect()->route('pilih.role');
    }

    abort(403, 'Role tidak ditemukan.');
})->middleware(['auth', 'verified'])->name('dashboard');

// Pilih Role (multi-role)
Route::middleware('auth')->group(function () {
    Route::get('/pilih-role', [RoleSelectionController::class, 'show'])->name('pilih.role');
    Route::post('/pilih-role', [RoleSelectionController::class, 'store'])->name('pilih.role.post');
});

// Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ======================= ADMIN =======================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/status', [AdminController::class, 'status'])->name('status');
    Route::get('/location', [AdminController::class, 'location'])->name('location');

    // Histori notifikasi (semua data)
    Route::get('/history', [NotifikasiController::class, 'adminPanel'])->name('history');

    Route::get('/tempat-sampah', [TempatSampahController::class, 'index'])->name('tempat_sampah.index');
    Route::get('/tempat-sampah/{id}/edit', [TempatSampahController::class, 'edit'])->name('tempat_sampah.edit');
    Route::put('/tempat-sampah/{id}', [TempatSampahController::class, 'update'])->name('tempat_sampah.update');

    Route::resource('accounts', AdminAccountController::class);
});

// ======================= USER / GURU =======================
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/status', [UserController::class, 'status'])->name('status');
    Route::get('/location', [UserController::class, 'location'])->name('location');
    Route::get('/history', [UserController::class, 'history'])->name('history');

    // Form kirim notifikasi (GET)
    Route::get('/notifikasi', [UserController::class, 'formNotifikasi'])->name('notifikasi.form');


    // Kirim notifikasi via POST — langsung ke UserController
    Route::post('/notifikasi/kirim', [UserController::class, 'kirimNotifikasi'])->name('notifikasi.kirim');
});


// ======================= PETUGAS =======================
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/status', [PetugasController::class, 'status'])->name('status');
    Route::get('/location', [PetugasController::class, 'location'])->name('location');
    Route::get('/history', [PetugasController::class, 'history'])->name('history');

    // Form & simpan konfirmasi penjemputan
    Route::get('/konfirmasi/{id}', [PetugasController::class, 'petugasKonfirmasiForm'])->name('konfirmasi.form');
    Route::post('/konfirmasi', [PetugasController::class, 'konfirmasi'])->name('konfirmasi.simpan');

});

// Route auth
require __DIR__ . '/auth.php';
