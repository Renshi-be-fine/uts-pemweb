<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesertaController;

Route::get('/', [PesertaController::class, 'index'])
    ->name('peserta.index');

Route::post('/peserta', [PesertaController::class, 'store'])
    ->name('peserta.store');

Route::get('/peserta/{id}/edit', [PesertaController::class, 'edit'])
    ->whereNumber('id')
    ->name('peserta.edit');

Route::put('/peserta/{id}', [PesertaController::class, 'update'])
    ->whereNumber('id')
    ->name('peserta.update');

Route::delete('/peserta/{id}', [PesertaController::class, 'destroy'])
    ->whereNumber('id')
    ->name('peserta.destroy');
