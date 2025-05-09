<?php

use App\Http\Controllers\ComunidadController;
use App\Http\Controllers\SiniestroController;
use App\Http\Controllers\ChatPolizaController;
use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
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
    Route::post('/chat-poliza/{poliza}', [ChatPolizaController::class, 'store'])->name('chat-poliza.store');
    Route::resource('usuarios', UserController::class);
    
    Route::resource('roles', RolController::class);
    Route::resource('comunidades', ComunidadController::class);
    Route::resource('siniestros', SiniestroController::class);

});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
