<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FacturaController;

Route::get('/', [FacturaController::class, 'getAll'])->name('facturas.index');
Route::post('/facturas', [FacturaController::class, 'save'])->name('facturas.save');
