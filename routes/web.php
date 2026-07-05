<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mahasiswa\SuratPengajuanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================
// ROUTE AUTHENTICATION
// ========================

// Login Admin
// Login Admin
Route::get('/login/admin', [AuthController::class, 'showAdminLogin'])->name('auth.admin.login');
Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('auth.admin.login.submit');

// Login Mahasiswa
Route::get('/', [AuthController::class, 'showMahasiswaLogin'])->name('auth.mahasiswa.login');
Route::post('/login/mahasiswa', [AuthController::class, 'loginMahasiswa'])->name('auth.mahasiswa.login.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========================
// ROUTE MAHASISWA
// ========================

Route::prefix('siswa')->name('mahasiswa.')->group(function () {
    
    // Dashboard Mahasiswa
    Route::get('/', [SuratPengajuanController::class, 'dashboard'])
        ->name('dashboard');

    // Riwayat lengkap pengajuan
    Route::get('/riwayat', [SuratPengajuanController::class, 'riwayat'])
        ->name('riwayat');

    // Form & Submit Surat
    Route::get('/surat/{jenis}', [SuratPengajuanController::class, 'create'])
        ->name('surat.form')
        ->where('jenis', 'aktif|magang|rekomendasi');

    Route::post('/surat/{jenis}', [SuratPengajuanController::class, 'store'])
        ->name('surat.submit')
        ->where('jenis', 'aktif|magang|rekomendasi');

    // Edit & Hapus Pengajuan
    Route::get('/pengajuan/{pengajuan}/edit', [SuratPengajuanController::class, 'edit'])
        ->name('pengajuan.edit');

    Route::put('/pengajuan/{pengajuan}', [SuratPengajuanController::class, 'update'])
        ->name('pengajuan.update');

    Route::delete('/pengajuan/{pengajuan}', [SuratPengajuanController::class, 'destroy'])
        ->name('pengajuan.destroy');
});

// ========================
// ROUTE ADMIN
// ========================

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/', [SuratPengajuanController::class, 'adminDashboard'])
        ->name('dashboard');

    // Route Pengajuan - menggunakan POST untuk form submit
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/', [SuratPengajuanController::class, 'get'])->name('index');
        
        // Terima dan Tolak menggunakan POST dengan form
        Route::post('/{id}/terima', [SuratPengajuanController::class, 'terima'])->name('terima');
        Route::post('/{id}/tolak', [SuratPengajuanController::class, 'tolak'])->name('tolak');
    });

    // Akademik
    Route::prefix('akademik')->name('akademik.')->group(function () {
        Route::get('tahun', fn() => view('dashboard.admin.coming-soon'))->name('tahun');
        Route::get('kalender', fn() => view('dashboard.admin.coming-soon'))->name('kalender');
        Route::get('jadwal', fn() => view('dashboard.admin.coming-soon'))->name('jadwal');
        Route::get('kelas', fn() => view('dashboard.admin.coming-soon'))->name('kelas');
        Route::get('ruangan', fn() => view('dashboard.admin.coming-soon'))->name('ruangan');
    });

    // Mahasiswa
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('data', fn() => view('dashboard.admin.coming-soon'))->name('data');
        Route::get('status', fn() => view('dashboard.admin.coming-soon'))->name('status');
        Route::get('import', fn() => view('dashboard.admin.coming-soon'))->name('import');
    });

    // Dosen
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('data', fn() => view('dashboard.admin.coming-soon'))->name('data');
        Route::get('beban', fn() => view('dashboard.admin.coming-soon'))->name('beban');
    });

    // Mata Kuliah
    Route::prefix('matkul')->name('matkul.')->group(function () {
        Route::get('/', fn() => view('dashboard.admin.coming-soon'))->name('index');
        Route::get('kurikulum', fn() => view('dashboard.admin.coming-soon'))->name('kurikulum');
        Route::get('prodi', fn() => view('dashboard.admin.coming-soon'))->name('prodi');
    });

    // KRS
    Route::prefix('krs')->name('krs.')->group(function () {
        Route::get('pengajuan', fn() => view('dashboard.admin.krs.pengajuan'))->name('pengajuan');
        Route::get('validasi', fn() => view('dashboard.admin.coming-soon'))->name('validasi');
        Route::get('riwayat', fn() => view('dashboard.admin.coming-soon'))->name('riwayat');
    });

    // Nilai
    Route::prefix('nilai')->name('nilai.')->group(function () {
        Route::get('input', fn() => view('dashboard.admin.coming-soon'))->name('input');
        Route::get('validasi', fn() => view('dashboard.admin.coming-soon'))->name('validasi');
        Route::get('khs', fn() => view('dashboard.admin.coming-soon'))->name('khs');
        Route::get('transkrip', fn() => view('dashboard.admin.coming-soon'))->name('transkrip');
    });

    // Kelulusan
    Route::prefix('kelulusan')->name('kelulusan.')->group(function () {
        Route::get('yudisium', fn() => view('dashboard.admin.coming-soon'))->name('yudisium');
        Route::get('wisuda', fn() => view('dashboard.admin.coming-soon'))->name('wisuda');
        Route::get('ijazah', fn() => view('dashboard.admin.coming-soon'))->name('ijazah');
    });

    // Surat
    Route::prefix('surat')->name('surat.')->group(function () {
        Route::get('akademik', fn() => view('dashboard.admin.coming-soon'))->name('akademik');
        Route::get('template', fn() => view('dashboard.admin.coming-soon'))->name('template');
    });

    Route::get('pengumuman', fn() => view('dashboard.admin.coming-soon'))->name('pengumuman');
    Route::get('laporan', fn() => view('dashboard.admin.coming-soon'))->name('laporan');
    Route::get('pengguna', fn() => view('dashboard.admin.coming-soon'))->name('pengguna');
    Route::get('pengaturan', fn() => view('dashboard.admin.coming-soon'))->name('pengaturan');
    Route::get('keamanan', fn() => view('dashboard.admin.coming-soon'))->name('keamanan');
});

// ========================
// ROUTE API
// ========================

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/pengajuan', [SuratPengajuanController::class, 'get'])
        ->name('pengajuan.index');
});