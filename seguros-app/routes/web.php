<?php

use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\PolizaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::resource('polizas', PolizaController::class);
    Route::get('telefonos-asistencia', [CompaniaController::class, 'telefonosAsistencia'])->name('telefonos-asistencia');
    Route::resource('companias', CompaniaController::class);
    
    
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
