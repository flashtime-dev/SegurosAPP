<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password page.
     */
    public function show(): Response
    {
        try {
            return Inertia::render('auth/confirm-password');
        } catch (Throwable $e) {
            Log::error('❌ Error al mostrar la página de confirmación de contraseña: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            abort(500, 'Error interno al mostrar la página de confirmación de contraseña.');
        }
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            if (! Auth::guard('web')->validate([
                'email' => $request->user()->email,
                'password' => $request->password,
            ])) {
                throw ValidationException::withMessages([
                    'password' => __('auth.password'),
                ]);
            }
            $request->session()->put('auth.password_confirmed_at', time());

            Log::info('🔐 Contraseña confirmada correctamente.', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
            ]);

            return redirect()->intended(route('dashboard', absolute: false))
            ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Contraseña confirmada exitosamente",
                    ],
                ]);
        } catch (ValidationException $ve) {
            // Errores de validación normales, no los logueamos como errores de sistema
            throw $ve;

        } catch (Throwable $e) {
            Log::error('❌ Error inesperado al confirmar la contraseña: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
            ]);

            abort(500, 'Error interno al confirmar la contraseña.');
        }
    }
}
