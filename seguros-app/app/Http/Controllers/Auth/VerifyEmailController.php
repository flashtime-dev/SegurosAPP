<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Esta clase maneja la lógica de verificación de correo electrónico.
 * Permite a los usuarios verificar su dirección de correo electrónico después del registro.
 */
class VerifyEmailController extends Controller
{
    /**
     * Maneja la solicitud de verificación de correo electrónico.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        try {
            // Verifica si el usuario ya tiene el correo electrónico verificado
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
            }

            // Marca el correo electrónico como verificado
            // y dispara el evento Verified si la verificación es exitosa
            // Esto también actualiza el campo 'email_verified_at' en la base de datos
            if ($request->user()->markEmailAsVerified()) {
                /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
                $user = $request->user();
                
                event(new Verified($user));
                
                Log::info('✅ Email verificado correctamente.', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }
            
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        } catch (Throwable $e) {
            Log::error('❌ Error al verificar el email del usuario: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
                'email' => $request->user()->email ?? null,
            ]);
            
            abort(500, 'Ocurrió un error al verificar el correo electrónico.');
        }
    }
}
