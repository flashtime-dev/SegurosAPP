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

class NewPasswordController extends Controller
{
    /**
     * Show the password reset page.
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
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i',
            'password' => ['required', 'confirmed', Rules\Password::defaults(), 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_.])[A-Za-z\d@$!%*?&#_.]{8,}$/'],
        ], [
            'email.required' => 'El campo email es obligatorio.',
            'email.regex' => 'El formato del correo electrónico es inválido',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe ser de 8 carácteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.)',
        ]);
        try {
            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
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

            // If the password was successfully reset, we will redirect the user back to
            // the application's home authenticated view. If there is an error we can
            // redirect them back to where they came from with their error message.
            if ($status == Password::PasswordReset) {
                Log::info('✔ Contraseña restablecida correctamente', ['email' => $request->email]);
                return to_route('login')->with('status', __($status));
            }

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
