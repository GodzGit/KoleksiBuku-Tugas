<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;



// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Auth Only)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // Kategori Routes
    Route::get('/kategori', [KategoriController::class, 'index'])
        ->name('kategori.index');

    Route::get('/kategori/create', [KategoriController::class, 'create'])
        ->name('kategori.create');

    Route::post('/kategori', [KategoriController::class, 'store'])
        ->name('kategori.store');

    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])
        ->name('kategori.edit');

    Route::put('/kategori/{id}', [KategoriController::class, 'update'])
        ->name('kategori.update');

    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])
        ->name('kategori.destroy');

    // Koleksi buku
    Route::get('/koleksi-buku', [BukuController::class, 'index'])
        ->name('koleksi-buku.index');

    Route::get('/koleksi-buku/create', [BukuController::class, 'create'])
        ->name('koleksi-buku.create');

    Route::post('/koleksi-buku', [BukuController::class, 'store'])
        ->name('koleksi-buku.store');

    Route::get('/koleksi-buku/{id}/edit', [BukuController::class, 'edit'])
        ->name('koleksi-buku.edit');

    Route::put('/koleksi-buku/{id}', [BukuController::class, 'update'])
        ->name('koleksi-buku.update');

    Route::delete('/koleksi-buku/{id}', [BukuController::class, 'destroy'])
        ->name('koleksi-buku.destroy');

});

/*
|--------------------------------------------------------------------------
| DEFAULT REDIRECT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// --------------------------------------------------------------------------
