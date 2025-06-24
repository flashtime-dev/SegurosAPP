<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Esta clase maneja la lógica para restablecer la contraseña de un usuario.
 * Permite mostrar el formulario de restablecimiento y procesar la solicitud de nuevo password.
 */
class NewPasswordController extends Controller
{
    /**
     * Muestra la página de restablecimiento de contraseña.
     */
    public function create(Request $request): Response
    {
        try {
            return Inertia::render('auth/reset-password', [
                'email' => $request->email,
                'token' => $request->route('token'),
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al mostrar la página de restablecimiento de contraseña: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $request->email ?? null,
            ]);

            abort(500, 'Error interno al mostrar la página de restablecimiento de contraseña.');
        }
    }

    /**
     * Procesa la solicitud de restablecimiento de contraseña.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // De las validaciones se ha quitado Rules\Password::defaults()
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i',
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'confirmed', 'regex:/^(?=.*[a-zñ])(?=.*[A-ZÑ])(?=.*\d)(?=.*[@$!%*?&#_.-])[A-Za-znÑ\d@$!%*?&#_.-]{8,}$/'],
        ], [
            'email.required' => 'El campo email es obligatorio.',
            'email.regex' => 'El formato del correo electrónico es inválido',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe ser de 8 carácteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.-)',
        ]);
        try {
            // Intentamos restablecer la contraseña del usuario utilizando el token proporcionado
            // y el nuevo password. Si el restablecimiento es exitoso, se dispara el evento PasswordReset.
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            // Verificamos el estado del restablecimiento de contraseña
            // Si el estado es Password::PasswordReset, significa que la contraseña se ha restablecido correctamente
            if ($status == Password::PasswordReset) {
                Log::info('✔ Contraseña restablecida correctamente', ['email' => $request->email]);
                return to_route('login')->with('status', __($status));
            }
            
            // Si no, lanzamos una excepción de validación con el mensaje de error correspondiente
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        } catch (ValidationException $ve) {
            // Lanzamos la excepción para que Laravel la maneje y muestre errores de validación
            throw $ve;
        } catch (Throwable $e) {
            Log::error('❌ Error al procesar el restablecimiento de contraseña: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $request->email ?? null,
            ]);

            abort(500, 'Error interno al procesar el restablecimiento de contraseña.');
        }
    }
}
