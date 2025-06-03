<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\Auth\RoleSelectionController;


// Halaman awal
Route::get('/', fn() => view('welcome'));

// Redirect dashboard berdasarkan role yang dipilih di session (multi-role system)
Route::get('/dashboard', function () {
    $user = auth()->user();

    // Jika belum login
    if (!$user) {
        return redirect()->route('login');
    }

    // Cek role yang dipilih user di session (setelah login)
    $selectedRole = session('selected_role');

    if ($selectedRole) {
        // Pastikan role valid
        $availableRoles = ['admin', 'user', 'petugas'];
        if (!in_array($selectedRole, $availableRoles)) {
            abort(403, 'Role yang dipilih tidak valid.');
        }

        return redirect()->route($selectedRole . '.dashboard');
    }

    // Kalau belum pilih role, cek berapa role user
    $roles = $user->roles()->pluck('name')->toArray();

    if (count($roles) === 1) {
        // Jika hanya satu role, simpan dan redirect
        session(['selected_role' => $roles[0]]);
        return redirect()->route($roles[0] . '.dashboard');
    } elseif (count($roles) > 1) {
        // Kalau lebih dari satu role, arahkan ke halaman pemilihan role
        return redirect()->route('pilih.role');
    } else {
        // Jika tidak ada role
        abort(403, 'Role tidak ditemukan untuk user ini.');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Halaman pemilihan role untuk user multi-role
Route::middleware(['auth'])->group(function () {
    Route::get('/pilih-role', [RoleSelectionController::class, 'show'])->name('pilih.role');
    // Route::post('/pilih-role', [RoleSelectionController::class, 'choose'])->name('pilih.role.post');
    Route::post('/pilih-role', [RoleSelectionController::class, 'store'])->name('pilih.role.post');

});

// Profil user (edit, update, delete)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes berdasarkan role (admin, user, petugas)
Route::middleware(['auth'])->group(function () {

    // ADMIN
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/status', [AdminController::class, 'status'])->name('status');
        Route::get('/location', [AdminController::class, 'location'])->name('location');
        Route::get('/history', [AdminController::class, 'history'])->name('history');

        // Route CRUD akun
        Route::resource('accounts', AdminAccountController::class);
    });

    // USER
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/status', [UserController::class, 'status'])->name('status');
        Route::get('/location', [UserController::class, 'location'])->name('location');
        Route::get('/history', [UserController::class, 'history'])->name('history');

        // Fitur notifikasi sampah penuh
        Route::get('/notifikasi', [UserController::class, 'formNotifikasi'])->name('notifikasi.form');
        Route::post('/notifikasi', [UserController::class, 'kirimNotifikasi'])->name('notifikasi');
        
    });

    // PETUGAS
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
        Route::get('/status', [PetugasController::class, 'status'])->name('status');
        Route::get('/location', [PetugasController::class, 'location'])->name('location');
        Route::get('/history', [PetugasController::class, 'history'])->name('history');

        // Konfirmasi tanggapan sampah penuh
        Route::get('/konfirmasi/{id}', [PetugasController::class, 'konfirmasiForm'])->name('konfirmasi.form');
        Route::post('/konfirmasi/simpan', [PetugasController::class, 'konfirmasiSimpan'])->name('konfirmasi.simpan');
    });
});


// Route auth (login, register, dll)
require __DIR__ . '/auth.php';
