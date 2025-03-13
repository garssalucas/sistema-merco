<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdutoOryonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/oryon', [ProdutoOryonController::class, 'index'])->name('oryon.index'); // Para exibir os produtos
Route::get('/importar-oryon', [ProdutoOryonController::class, 'importarProdutos'])->name('oryon.importar'); // Para importar os produtos
require __DIR__.'/auth.php';
