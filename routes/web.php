<?php

use App\Http\Controllers\Absensi;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\DinasLuar;
use App\Http\Controllers\Izin;
use App\Http\Controllers\JamKerja;
use App\Http\Controllers\Admin\Settings;
use App\Http\Controllers\Cuti;
use App\Http\Controllers\Master;
use App\Http\Controllers\Preload;
use App\Http\Controllers\Printing;
use App\Http\Controllers\Report;
use App\Http\Controllers\Uploads;
use App\Http\Controllers\User;
use App\Http\Controllers\Utils;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/', Auth::class)->name('auth');
Route::get('/auth', [Auth::class, 'index'])->name('auth');
Route::post('/auth', [Auth::class, 'login'])->name('auth');
Route::get('/auth/logout', [Auth::class, 'logout'])->name('logout');
Route::get('/download/{file}', [Utils::class, 'download'])->name('download');

Route::get('/preload', Preload::class)->name('preload');
Route::post('/preload', [Preload::class, 'store'])->name('preload');


Route::resource('upload', Uploads::class);
Route::prefix('panel')->middleware(['auth', 'cek_setting'])->group(function(){
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/absensi/riwayat', [Absensi::class, 'riwayat'])->name('absensi.riwayat');
    Route::get('/absensi/print', [Absensi::class, 'print']);
    Route::resource('/absensi', Absensi::class);
    
    Route::resource('/izin', Izin::class);
    Route::put('/izin/update_status/{id}', [Izin::class, 'update_status']);
    
    Route::resource('/cuti', Cuti::class);
    
    Route::resource('/jam_kerja', JamKerja::class);
    
    Route::get('/users/toggle_kabid_level', [User::class, 'toggle_kabid_level'])->name('toggle_kabid_level');
    Route::get('/users/toggle_admin_level', [User::class, 'toggle_admin_level'])->name('toggle_admin_level');
    Route::post('/users/reset_password/', [User::class, 'reset_password'])->name('reset_password');
    Route::post('/users/update_password/{id}', [User::class, 'update_password'])->name('update_password');
    Route::resource('/users', User::class);
    
    Route::resource('/settings', Settings::class);
    
    Route::resource('/dinas_luar', DinasLuar::class);

    Route::resource('/report', Report::class);

    Route::get('/printing', Printing::class);
    Route::post('/printing', [Printing::class, 'print'])->name('printing.post');

    Route::get('master/bidang', [Master::class, 'index_bidang'])->name('master.index_bidang');
    Route::post('master/bidang', [Master::class, 'store_bidang'])->name('master.store_bidang');
    Route::put('master/bidang/{id}', [Master::class, 'update_bidang'])->name('master.update_bidang');
    Route::delete('master/bidang/{id}', [Master::class, 'destroy_bidang'])->name('master.destroy_bidang');
});

require_once app_path('helpers.php');