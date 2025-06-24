<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Validation\ValidationException;

// Controlador para manejar la autenticación de usuarios
class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar la pagina de login
     */
    public function create(Request $request): Response
    {
        try {
            return Inertia::render('auth/login', [
                // canResetPassword: indica si existe la ruta para restablecer contraseña
                'canResetPassword' => Route::has('password.request'),
                // status: obtiene el estado de la sesión
                'status' => $request->session()->get('status'),
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al mostrar la página de login: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Vista de error en caso de fallo al cargar la página
            abort(500, 'Error interno al mostrar la página de login.');
        }
    }

    /**
     * Maneja la solicitud de autenticación cuando el usuario intenta iniciar sesión.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Autenticar al usuario con las credenciales proporcionadas
            // El LoginRequest ya valida las credenciales y lanza excepciones si hay errores
            $request->authenticate();
            // Si la autenticación es exitosa, se regenera la sesión del usuario
            $request->session()->regenerate();

            // Registrar un log informativo de la autenticación exitosa
            Log::info('✅ Usuario autenticado correctamente.', [
                'user_id' => Auth::id(),
                'email' => $request->input('email'),
            ]);

            // Redirigir al usuario a la ruta de dashboard o la ruta que se haya definido como destino
            // con un mensaje de bienvenida
            // 'absolute' => false indica que la URL no debe ser absoluta, útil para SPA
            return redirect()->intended(route('dashboard', absolute: false))
            ->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Bienvenido ". Auth::user()->name,
                    ],
                ]);
        } catch (ValidationException $e) {
            Log::error('❌ Error durante la autenticación: ' . $e->getMessage(), [
                'exception' => $e,
                'input' => $request->only('email'),
            ]);

            // Mostramos los errores reales de validación (por ejemplo, cuenta inactiva)
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors($e->errors());
        }
    }

    /**
     * Maneja el cierre de sesión del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Cierra la sesión del usuario autenticado
            Auth::guard('web')->logout();
            // Invalida la sesión actual y regenera el token CSRF para seguridad
            // Esto ayuda a prevenir ataques CSRF y asegura que la sesión no pueda ser reutilizada
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('👋 Usuario cerró sesión correctamente.', [
                'user_id' => Auth::id(),
            ]);

            // Redirige al usuario a la página de inicio o a la ruta deseada
            return redirect('/');
            
        } catch (Throwable $e) {
            Log::error('❌ Error al cerrar sesión: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
            ]);

            // En caso de error, puedes redirigir con mensaje o abortar
            return redirect('/')->withErrors(['logout' => 'Error al cerrar sesión. Intentalo nuevamente.']);
        }
    }
}
