<?php

use App\Http\Controllers\Auth\AuthController as AuthAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Mahasiswa\SuratPengajuanController;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Support\Facades\Route;


//
Route::get('/login/admin', [AuthAuthController::class, 'showAdminLogin'])->name('auth.admin.login');
Route::post('/login/admin', [AuthAuthController::class, 'loginAdmin'])->name('auth.admin.login.submit');

// login mahasiswa
Route::get('/', [AuthAuthController::class, 'showMahasiswaLogin'])->name('auth.mahasiswa.login');
Route::post('/login/mahasiswa', [AuthAuthController::class, 'loginMahasiswa'])->name('auth.mahasiswa.login.submit');

// dahsboard mahasiswa
Route::get('/siswa', function () {
    $user = User::find(session('user_id'));

    $pengajuan = SuratPengajuan::where('user_id', session('user_id'))
        ->latest()
        ->take(10)
        ->get();

    return view('dashboard.mahasiswa.app', compact('user', 'pengajuan'));
})->name('mahasiswa.dashboard');

// pengajuan surat mahasiswa (pilih jenis surat -> isi form -> kirim pengajuan)
Route::get('/siswa/surat/{jenis}', [SuratPengajuanController::class, 'create'])
    ->name('mahasiswa.surat.form')->where('jenis', 'aktif|magang|rekomendasi');

Route::post('/siswa/surat/{jenis}', [SuratPengajuanController::class, 'store'])
    ->name('mahasiswa.surat.submit')->where('jenis', 'aktif|magang|rekomendasi');

// edit & hapus pengajuan (riwayat pengajuan terbaru)
Route::get('/siswa/pengajuan/{pengajuan}/edit', [SuratPengajuanController::class, 'edit'])
    ->name('mahasiswa.pengajuan.edit');

Route::put('/siswa/pengajuan/{pengajuan}', [SuratPengajuanController::class, 'update'])
    ->name('mahasiswa.pengajuan.update');

Route::delete('/siswa/pengajuan/{pengajuan}', [SuratPengajuanController::class, 'destroy'])
    ->name('mahasiswa.pengajuan.destroy');

Route::get('/admin', function () {
    return view('dashboard.admin.app'); 
})->name('admin.dashboard');

Route::post('/logout', [AuthAuthController::class, 'logout'])->name('logout');


Route::prefix('admin')->name('admin.')->group(function () {

    Route::prefix('akademik')->name('akademik.')->group(function () {
        Route::get('tahun', fn() => view('dashboard.admin.coming-soon'))->name('tahun');
        Route::get('kalender', fn() => view('dashboard.admin.coming-soon'))->name('kalender');
        Route::get('jadwal', fn() => view('dashboard.admin.coming-soon'))->name('jadwal');
        Route::get('kelas', fn() => view('dashboard.admin.coming-soon'))->name('kelas');
        Route::get('ruangan', fn() => view('dashboard.admin.coming-soon'))->name('ruangan');
    });

    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('data', fn() => view('dashboard.admin.coming-soon'))->name('data');
        Route::get('status', fn() => view('dashboard.admin.coming-soon'))->name('status');
        Route::get('import', fn() => view('dashboard.admin.coming-soon'))->name('import');
    });

    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('data', fn() => view('dashboard.admin.coming-soon'))->name('data');
        Route::get('beban', fn() => view('dashboard.admin.coming-soon'))->name('beban');
    });

    Route::prefix('matkul')->name('matkul.')->group(function () {
        Route::get('/', fn() => view('dashboard.admin.coming-soon'))->name('index');
        Route::get('kurikulum', fn() => view('dashboard.admin.coming-soon'))->name('kurikulum');
        Route::get('prodi', fn() => view('dashboard.admin.coming-soon'))->name('prodi');
    });

    Route::prefix('krs')->name('krs.')->group(function () {
        Route::get('pengajuan', fn() => view('dashboard.admin.coming-soon'))->name('pengajuan');
        Route::get('validasi', fn() => view('dashboard.admin.coming-soon'))->name('validasi');
        Route::get('riwayat', fn() => view('dashboard.admin.coming-soon'))->name('riwayat');
    });

    Route::prefix('nilai')->name('nilai.')->group(function () {
        Route::get('input', fn() => view('dashboard.admin.coming-soon'))->name('input');
        Route::get('validasi', fn() => view('dashboard.admin.coming-soon'))->name('validasi');
        Route::get('khs', fn() => view('dashboard.admin.coming-soon'))->name('khs');
        Route::get('transkrip', fn() => view('dashboard.admin.coming-soon'))->name('transkrip');
    });

    Route::prefix('kelulusan')->name('kelulusan.')->group(function () {
        Route::get('yudisium', fn() => view('dashboard.admin.coming-soon'))->name('yudisium');
        Route::get('wisuda', fn() => view('dashboard.admin.coming-soon'))->name('wisuda');
        Route::get('ijazah', fn() => view('dashboard.admin.coming-soon'))->name('ijazah');
    });

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