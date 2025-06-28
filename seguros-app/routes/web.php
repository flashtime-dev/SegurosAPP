<?php

use App\Http\Controllers\ComunidadController;
use App\Http\Controllers\SiniestroController;
use App\Http\Controllers\ChatPolizaController;
use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgenteController;
use App\Http\Controllers\ChatSiniestroController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Rutas protegidas por autenticación y verificación de correo electrónico
Route::middleware(['auth', 'verified'])->group(function () {

    //Panel de control del usuario
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas para el manejo de usuarios
    Route::resource('usuarios', UserController::class)->except(['create', 'edit']);

    // Rutas para el manejo de roles
    Route::resource('roles', RolController::class)->except(['create', 'edit']);

    // Rutas para el manejo de empleados(reutiliza rutas de usuarios)
    Route::get('empleados', [UserController::class, 'empleados'])->name('empleados.index');

    // Rutas para el manejo de comunidades
    Route::resource('comunidades', ComunidadController::class)->except(['create', 'edit']);

    // Rutas para el manejo de agentes
    Route::resource('agentes', AgenteController::class)->except(['create', 'edit']);

    // Rutas para el manejo de pólizas
    Route::resource('polizas', PolizaController::class)->except(['create', 'edit']);
    Route::get('/polizas/{id}/pdf', [PolizaController::class, 'servePdf'])->name('polizas.pdf');
    Route::post('/chat-poliza/{poliza}', [ChatPolizaController::class, 'store'])->name('chat-poliza.store');
    Route::post('/polizas/{poliza}/solicitar-anulacion', [PolizaController::class, 'solicitarAnulacion'])->name('polizas.solicitar-anulacion');

    // Rutas para el manejo de siniestros
    Route::resource('siniestros', SiniestroController::class)->except(['create', 'edit']);
    Route::post('/chat-siniestro/{siniestro}', [ChatSiniestroController::class, 'store'])->name('chat-siniestro.store');
    Route::get('/siniestros/{id}/archivo/{filename}', [SiniestroController::class, 'servePdf'])->name('siniestro.pdf');
    Route::post('/siniestros/{id}/cerrar', [SiniestroController::class, 'cerrar'])->name('siniestros.cerrar');
    Route::post('/siniestros/{id}/reabrir', [SiniestroController::class, 'reabrir'])->name('siniestros.reabrir');


    // Rutas para consultar los teléfonos de asistencia de las compañías
    Route::resource('companias', CompaniaController::class)->except(['create', 'edit']);
    Route::get('telefonos-asistencia', [CompaniaController::class, 'telefonosAsistencia'])->name('telefonos-asistencia');
});


// Rutas de autenticación
require __DIR__ . '/auth.php';

// Rutas de configuración de la aplicación y datos del usuario
require __DIR__ . '/settings.php';


//              #
//            ###
//          #####
//        #######
//      #########
//    ######################
//  ######################
//             #########
//             #######
//             #####
//             ###
//             #

// ⚡ Este rayo marca el inicio de algo poderoso... o no.
// Easter Egg by @Cristy