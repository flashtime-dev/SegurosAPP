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

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        try {
            return Inertia::render('auth/login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => $request->session()->get('status'),
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al mostrar la página de login: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Opcional: Puedes mostrar una vista de error o redirigir
            abort(500, 'Error interno al mostrar la página de login.');
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            Log::info('✅ Usuario autenticado correctamente.', [
                'user_id' => Auth::id(),
                'email' => $request->input('email'),
            ]);

            return redirect()->intended(route('dashboard', absolute: false))
                ->with('success', 'Has iniciado sesión correctamente.');
        } catch (Throwable $e) {
            Log::error('❌ Error durante la autenticación: ' . $e->getMessage(), [
                'exception' => $e,
                'input' => $request->only('email'),
            ]);

            // Puedes redirigir de nuevo con mensaje de error
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Error al iniciar sesión. Por favor verifica tus credenciales.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('👋 Usuario cerró sesión correctamente.', [
                'user_id' => Auth::id(),
            ]);

            return redirect('/')->with('success', 'Has cerrado sesión correctamente.');
        } catch (Throwable $e) {
            Log::error('❌ Error al cerrar sesión: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
            ]);

            // En caso de error, puedes redirigir con mensaje o abortar
            return redirect('/')->withErrors(['logout' => 'Error al cerrar sesión. Intenta nuevamente.']);
        }
    }
}
