<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        try {
            return Inertia::render('auth/register');
        } catch (Throwable $e) {
            Log::error('❌ Error al mostrar la página de registro: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            abort(500, 'Error interno al mostrar la página de registro.');
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults(), 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_.])[A-Za-z\d@$!%*?&#_.]{8,}$/'],
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El formato del email es inválido.',
            'email.unique' => 'El email ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe ser de 8 carácteres y contener al menos: una letra mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.)',
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));
            Auth::login($user);

            Log::info('✅ Usuario registrado y autenticado correctamente', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return to_route('dashboard')->with('status', '¡Registro exitoso! Bienvenido, ' . $user->name . '.');
        } catch (ValidationException $ve) {
            throw $ve;
        } catch (Throwable $e) {
            Log::error('❌ Error durante el registro de usuario: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $request->email ?? null,
            ]);

            abort(500, 'Error interno durante el registro del usuario.');
        }
    }
}
