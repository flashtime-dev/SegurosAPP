<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

// Esta clase maneja la lógica para mostrar la página de verificación de email.(no se usa actualmente)
// Se utiliza para mostrar un mensaje al usuario si su correo electrónico ya está verificado o no
// Si el correo ya está verificado, redirige al dashboard con un mensaje de éxito
class EmailVerificationPromptController extends Controller
{
    /**
     * Show the email verification prompt page.
     */
    public function __invoke(Request $request): Response|RedirectResponse
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                Log::info('✔ Usuario ya tiene email verificado', ['user_id' => $request->user()->id]);
                return redirect()->intended(route('dashboard', absolute: false))->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Tu correo ya está verificado",
                    ],
                ]);
            } else {
                return Inertia::render('auth/verify-email', [
                    'status' => $request->session()->get('status'),
                ]);
            }
        } catch (Throwable $e) {
            Log::error('❌ Error en EmailVerificationPromptController::__invoke: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
            ]);

            abort(500, 'Error interno al mostrar la página de verificación de email.');
        }
    }
}
