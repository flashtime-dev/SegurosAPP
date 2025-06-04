<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false));
            }
            $request->user()->sendEmailVerificationNotification();

            Log::info('📧 Enlace de verificación de email enviado.', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
            ]);

            return back()->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Se ha enviado un nuevo enlace de verificación a tu correo electrónico",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al enviar notificación de verificación de email: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
            ]);

            return back()->withErrors(['email_verification' => 'No se pudo enviar el enlace de verificación. Intenta nuevamente.']);
        }
    }
}
