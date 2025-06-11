<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

// Esta clase maneja la lógica para enviar un enlace de restablecimiento de contraseña
class PasswordResetLinkController extends Controller
{
    /**
     * Muestra la vista de solicitud de enlace de restablecimiento de contraseña.
     */
    public function create(Request $request): Response
    {
        try {
            return Inertia::render('auth/forgot-password', [
                'status' => $request->session()->get('status'),
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al mostrar la vista de recuperación de contraseña: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            abort(500, 'Error interno al mostrar la página de recuperación.');
        }
    }

    /**
     * Maneja la solicitud para enviar un enlace de restablecimiento de contraseña.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validar el email con una expresión regular para asegurar un formato correcto
            $request->validate([
                'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i',
            ],[
                'email.required' => 'El campo email es obligatorio.',
                'email.email' => 'El formato del email es inválido.',
            ]);

            // Intentar enviar el enlace de restablecimiento de contraseña
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Verificar el estado del envío del enlace
            if ($status === Password::RESET_LINK_SENT) {
                Log::info('✔ Enlace de restablecimiento de contraseña enviado', ['email' => $request->email]);
                return back()->with('status', __('Te enviaremos un enlace para restablecer tu contraseña si la cuenta existe.'));
            }

            // Si el estado no es exitoso, lanzar una excepción de validación
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        } catch (ValidationException $ve) {
            throw $ve; // Laravel mostrará los errores en la UI
        } catch (Throwable $e) {
            Log::error('❌ Error al enviar el enlace de restablecimiento de contraseña: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $request->email ?? null,
            ]);

            abort(500, 'Error interno al enviar el enlace de recuperación.');
        }
    }
}
