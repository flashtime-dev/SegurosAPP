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

// Esta clase maneja la confirmación de contraseña del usuario (no esta en uso)
// Se utiliza para proteger rutas que requieren una confirmación adicional de la contraseña del usuario
class ConfirmablePasswordController extends Controller
{
    /**
     * Mostrar la página de confirmación de contraseña.
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
     * Maneja la solicitud de confirmación de contraseña.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validar que el usuario esté autenticado
            if (! Auth::guard('web')->validate([
                'email' => $request->user()->email,
                'password' => $request->password,
            ])) {
                // Si la validación falla, lanzamos una excepción de validación
                throw ValidationException::withMessages([
                    'password' => __('auth.password'),
                ]);
            }
            // Si la validación es exitosa, confirmamos la contraseña
            $request->session()->put('auth.password_confirmed_at', time());

            Log::info('🔐 Contraseña confirmada correctamente.', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
            ]);

            // Redirigir al usuario a la ruta de dashboard o la ruta que se haya definido como destino
            // Usamos redirect()->intended para redirigir a la ruta que el usuario intentaba acceder
            // Si no hay ruta previa, redirigimos al dashboard
            // La opción absolute: false asegura que la URL no sea absoluta, manteniendo el esquema actual
            // y evitando problemas en aplicaciones SPA
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
