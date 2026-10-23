
<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RiwayatStokController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ProdukController::class, 'dashboard'])
    ->name('dashboard');

Route::get('/produk', [ProdukController::class, 'index'])
    ->name('produk.index');

Route::get('/produk/create', [ProdukController::class, 'create'])
    ->name('produk.create');

Route::post('/produk', [ProdukController::class, 'store'])
    ->name('produk.store');

Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])
    ->name('produk.edit');

Route::put('/produk/{produk}', [ProdukController::class, 'update'])
    ->name('produk.update');

Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])
    ->name('produk.destroy');

Route::get('/riwayat-stok', [RiwayatStokController::class, 'index'])
    ->name('riwayat-stok.index');

Route::get('/riwayat-stok/create', [RiwayatStokController::class, 'create'])
    ->name('riwayat-stok.create');

Route::post('/riwayat-stok', [RiwayatStokController::class, 'store'])
    ->name('riwayat-stok.store');