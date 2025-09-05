<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QrController;


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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

Route::middleware(['auth'])->group(function () {
    Route::get('products/{product}/qr/preview.svg', [QrController::class, 'preview'])->name('qr.preview');
    // Kategoriler (basit CRUD)
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Ürünler
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Sadece admin silebilir
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // QR işlemleri
    Route::post('products/{product}/qr/generate', [QrController::class, 'generate'])->name('qr.generate');   // sabit kod ilk kez set edilir
    Route::post('products/{product}/qr/toggle',   [QrController::class, 'toggle'])->name('qr.toggle');       // aktif/pasif
    Route::get('products/{product}/qr/png',       [QrController::class, 'downloadPng'])->name('qr.png');     // indir (PNG)
    Route::get('products/{product}/qr/svg',       [QrController::class, 'downloadSvg'])->name('qr.svg');     // indir (SVG)
    Route::get('products/{product}/stats',        [QrController::class, 'stats'])->name('qr.stats');         // istatistik sayfası
});

// Herkese açık QR yönlendirme (okutma)
Route::get('/q/{code}', [QrController::class, 'redirect'])->name('qr.redirect');

Route::get('/pdf/{slug}.pdf', [QrController::class, 'servePdf'])->name('pdf.serve');

require __DIR__ . '/auth.php';
