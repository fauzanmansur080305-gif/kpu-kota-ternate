<?php

use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\TamuController as AdminTamuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TamuController;
use App\Http\Middleware\EnsureAdminSessionIsCurrent;
use App\Models\Tamu;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// ==============================
// DASHBOARD ADMINISTRATOR
// ==============================

Route::get('/', [TamuController::class, 'home'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
    ->middleware('throttle:password-recovery')
    ->name('password.email');
Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/verify-password-code', [PasswordResetController::class, 'verifyCode'])
    ->middleware('throttle:password-recovery')
    ->name('password.verify');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', EnsureAdminSessionIsCurrent::class])->group(function () {
    Route::get('/admin', function () {
        abort_unless(auth()->user()->role === 'admin', 403);

        $totalTamu = Tamu::count();

        $tamuHariIni = Tamu::whereDate(
            'hari_tanggal',
            now('Asia/Jayapura')->toDateString()
        )->count();

        $tamuTerbaru = Tamu::latest()
            ->take(5)
            ->get();

        $tamuBulanIni = Tamu::whereBetween('hari_tanggal', [
            now('Asia/Jayapura')->startOfMonth()->toDateString(),
            now('Asia/Jayapura')->endOfMonth()->toDateString(),
        ])->count();

        return view('admin.dashboard', compact(
            'totalTamu',
            'tamuHariIni',
            'tamuBulanIni',
            'tamuTerbaru'
        ));
    })->name('admin.dashboard');

    Route::get('/admin/pengaturan', [PengaturanController::class, 'index'])
        ->name('admin.pengaturan.index');
    Route::post('/admin/pengaturan/profil', [PengaturanController::class, 'updateProfile'])
        ->name('admin.pengaturan.profile');
    Route::post('/admin/pengaturan/kata-sandi', [PengaturanController::class, 'updatePassword'])
        ->name('admin.pengaturan.password');
    Route::post('/admin/pengaturan/reset-sistem', [PengaturanController::class, 'resetSystem'])
        ->name('admin.pengaturan.reset-system');

});

Route::get('/anggota', function () {
    abort_unless(auth()->user()->role === 'anggota', 403);

    return view('anggota.absensi');
})->middleware('auth')->name('anggota.dashboard');

// ==============================
// BUKU TAMU
// ==============================

Route::get('/tamu', [TamuController::class, 'index'])
    ->name('tamu.index');

Route::post('/tamu', [TamuController::class, 'store'])
    ->name('tamu.store');

Route::get('/tamu/berhasil', function () {
    return view('tamu.berhasil');
})->name('tamu.berhasil');

// ==============================
// ADMINISTRATOR - DATA TAMU
// ==============================

Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureAdminSessionIsCurrent::class])->group(function () {
    Route::get('tamu/{tamu}/gambar', function (Tamu $tamu) {
        $documentationPath = $tamu->documentationPath();
        abort_unless($documentationPath && Storage::disk('local')->exists($documentationPath), 404);

        return response()->file(Storage::disk('local')->path($documentationPath));
    })->name('tamu.gambar');
    Route::get('tamu/{tamu}/paraf', function (Tamu $tamu) {
        abort_unless(is_string($tamu->paraf) && preg_match('/^data:image\/(png|jpeg);base64,/', $tamu->paraf) === 1, 404);

        [, $encodedImage] = explode(',', $tamu->paraf, 2);
        $image = base64_decode($encodedImage, true);

        abort_unless($image !== false, 404);
        $details = getimagesizefromstring($image);

        abort_unless($details && in_array($details['mime'], ['image/png', 'image/jpeg'], true), 404);

        return response($image)->header(
            'Content-Type',
            $details['mime']
        )->header('Cache-Control', 'private, no-store');
    })->name('tamu.paraf');

    Route::delete('tamu', [AdminTamuController::class, 'destroyMatching'])
        ->name('tamu.destroy-matching');
    Route::resource('tamu', AdminTamuController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export/tamu', [LaporanController::class, 'exportTamu'])->name('laporan.export.tamu');
});
