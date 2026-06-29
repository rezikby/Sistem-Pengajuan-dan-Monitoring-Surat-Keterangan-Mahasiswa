<?php

use App\Http\Controllers\Auth\AuthController as AuthAuthController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


//
Route::get('/login/admin', [AuthAuthController::class, 'showAdminLogin'])->name('auth.admin.login');

// login mahasiswa
Route::get('/', [AuthAuthController::class, 'showMahasiswaLogin'])->name('auth.mahasiswa.login');
Route::post('/login/mahasiswa', [AuthAuthController::class, 'loginMahasiswa'])->name('auth.mahasiswa.login.submit');

// dahsboard mahasiswa
Route::get('/siswa', function () {
    return view('dashboard.mahasiswa.app'); 
})->name('mahasiswa.dashboard');