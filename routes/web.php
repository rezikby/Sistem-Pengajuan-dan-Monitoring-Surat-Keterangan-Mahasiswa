<?php

use App\Http\Controllers\Admin\SuratTemplateController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mahasiswa\SuratPengajuanController;
use App\Http\Controllers\Mahasiswa\SuratAktifKuliahController;
use App\Http\Controllers\Mahasiswa\SuratMagangController;
use App\Http\Controllers\Mahasiswa\SuratRekomendasiController;
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
    
    // Redirect landing and dashboard to aktif type by default
    Route::get('/landing', fn() => redirect()->route('mahasiswa.aktif.landing'));
    Route::get('/', fn() => redirect()->route('mahasiswa.aktif.dashboard'));
    
    // Riwayat unified (shows all types for user)
    Route::get('/riwayat', [SuratPengajuanController::class, 'riwayatAll'])
        ->name('riwayat');

    // Download generated surat for owner
    Route::get('/pengajuan/{id}/download', [SuratPengajuanController::class, 'downloadForStudent'])->name('pengajuan.download');

    // *** SURAT AKTIF KULIAH ROUTES ***
    Route::prefix('aktif')->name('aktif.')->group(function () {
        Route::get('/landing', [SuratAktifKuliahController::class, 'landingPage'])->name('landing');
        Route::get('/', [SuratAktifKuliahController::class, 'dashboard'])->name('dashboard');
        Route::get('/riwayat', [SuratAktifKuliahController::class, 'riwayat'])->name('riwayat');
        
        Route::get('/form', [SuratAktifKuliahController::class, 'create'])->name('form');
        Route::post('/', [SuratAktifKuliahController::class, 'store'])->name('store');
        
        Route::get('/{pengajuan}/edit', [SuratAktifKuliahController::class, 'edit'])->name('edit');
        Route::put('/{pengajuan}', [SuratAktifKuliahController::class, 'update'])->name('update');
        Route::delete('/{pengajuan}', [SuratAktifKuliahController::class, 'destroy'])->name('destroy');
    });

    // *** SURAT MAGANG ROUTES ***
    Route::prefix('magang')->name('magang.')->group(function () {
        Route::get('/landing', [SuratMagangController::class, 'landingPage'])->name('landing');
        Route::get('/', [SuratMagangController::class, 'dashboard'])->name('dashboard');
        Route::get('/riwayat', [SuratMagangController::class, 'riwayat'])->name('riwayat');
        
        Route::get('/form', [SuratMagangController::class, 'create'])->name('form');
        Route::post('/', [SuratMagangController::class, 'store'])->name('store');
        
        Route::get('/{pengajuan}/edit', [SuratMagangController::class, 'edit'])->name('edit');
        Route::put('/{pengajuan}', [SuratMagangController::class, 'update'])->name('update');
        Route::delete('/{pengajuan}', [SuratMagangController::class, 'destroy'])->name('destroy');
    });

    // *** SURAT REKOMENDASI ROUTES ***
    Route::prefix('rekomendasi')->name('rekomendasi.')->group(function () {
        Route::get('/landing', [SuratRekomendasiController::class, 'landingPage'])->name('landing');
        Route::get('/', [SuratRekomendasiController::class, 'dashboard'])->name('dashboard');
        Route::get('/riwayat', [SuratRekomendasiController::class, 'riwayat'])->name('riwayat');
        
        Route::get('/form', [SuratRekomendasiController::class, 'create'])->name('form');
        Route::post('/', [SuratRekomendasiController::class, 'store'])->name('store');
        
        Route::get('/{pengajuan}/edit', [SuratRekomendasiController::class, 'edit'])->name('edit');
        Route::put('/{pengajuan}', [SuratRekomendasiController::class, 'update'])->name('update');
        Route::delete('/{pengajuan}', [SuratRekomendasiController::class, 'destroy'])->name('destroy');
    });
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
        Route::get('/{id}', [SuratPengajuanController::class, 'show'])->name('show');
        Route::get('/chart', [SuratPengajuanController::class, 'chartData'])->name('chart');
        Route::put('/{id}', [SuratPengajuanController::class, 'update'])->name('update');
        Route::put('/{id}/admin-update', [SuratPengajuanController::class, 'adminUpdate'])->name('admin.update');
        Route::put('/{id}/status', [SuratPengajuanController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{id}/download', [SuratPengajuanController::class, 'downloadGenerated'])->name('downloadGenerated');
        
        // Terima dan Tolak menggunakan POST dengan form
        Route::post('/{id}/terima', [SuratPengajuanController::class, 'terima'])->name('terima');
        Route::post('/{id}/tolak', [SuratPengajuanController::class, 'tolak'])->name('tolak');
        Route::delete('/{id}', [SuratPengajuanController::class, 'adminDestroy'])->name('destroy');
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
        Route::get('template', [SuratTemplateController::class, 'index'])->name('template.index');
        Route::get('template/list', [SuratTemplateController::class, 'listJson'])->name('template.list');
        Route::post('template', [SuratTemplateController::class, 'store'])->name('template.store');
        Route::put('template/{template}', [SuratTemplateController::class, 'update'])->name('template.update');
        Route::delete('template/{template}', [SuratTemplateController::class, 'destroy'])->name('template.destroy');
        Route::get('template/{template}/download', [SuratTemplateController::class, 'download'])->name('template.download');
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