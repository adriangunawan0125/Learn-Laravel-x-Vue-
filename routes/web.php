
<?php

use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
});

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
