<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class PasswordController extends Controller
{
    /**
     * Show the user's password settings page.
     */
    public function edit(): Response
    {
        return Inertia::render('settings/password');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Este comentario no se borra, en un futuro se deberí usar esta configuracion 
        // para validar las contraseñas y otros campos

        // $validated = $request->validate([
        //     'current_password' => ['required', 'current_password'],
        //     'password' => ['required', Password::defaults(), 'confirmed'],
        // ]);

        // $request->user()->update([
        //     'password' => Hash::make($validated['password']),
        // ]);

        // Comienza validaciones personalizadas
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_.])[A-Za-z\d@$!%*?&#_.]{8,}$/',
            ],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe tener al menos 8 carácteres, una mayúscula, una minúscula, un número y un carácter especial (@$!%*?&#_.).',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Finaliza validaciones personalizadas

        Log::info('🔒 Contraseña actualizada correctamente.', [
            'user_id' => $request->user()->id ?? null,
            'email' => $request->user()->email ?? null,
        ]);

        return back()->with([
            'success' => [
                'id' => uniqid(),
                'mensaje' => "Tu contraseña ha sido actualizada correctamente",
            ],
        ]);
    }
}
