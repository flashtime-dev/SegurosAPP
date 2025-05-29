<?php

use Illuminate\Support\Facades\Broadcast;
use App\Http\Middleware\CheckPermiso;
use Illuminate\Support\Facades\Log;

Broadcast::channel('chatPoliza.{id_poliza}', function ($user, $id_poliza) {
    // Debug: Log para ver qué está pasando
    Log::info('🔐 Autorización de canal solicitada', [
        'user_id' => $user ? $user->id : 'null',
        'poliza_id' => $id_poliza,
        'user_data' => $user ? $user->toArray() : 'no user'
    ]);

    // Por ahora, simplemente autorizar a todos los usuarios autenticados
    // Más tarde puedes agregar lógica más específica
    if ($user) {
        Log::info('✅ Usuario autorizado para el canal');
        return true;
    }
    
    Log::info('❌ Usuario no autorizado');
    return false;
});


Broadcast::channel('chatSiniestro.{id_siniestro}', function ($user, $id_siniestro) {
    // Debug: Log para ver qué está pasando
    Log::info('🔐 Autorización de canal solicitada', [
        'user_id' => $user ? $user->id : 'null',
        'siniestro_id' => $id_siniestro,
        'user_data' => $user ? $user->toArray() : 'no user'
    ]);

    // Por ahora, simplemente autorizar a todos los usuarios autenticados
    // Más tarde puedes agregar lógica más específica
    if ($user) {
        Log::info('✅ Usuario autorizado para el canal');
        return true;
    }
    
    Log::info('❌ Usuario no autorizado');
    return false;
});
