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
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            Log::info('🔒 Contraseña actualizada correctamente.', [
                'user_id' => $request->user()->id ?? null,
                'email' => $request->user()->email ?? null,
            ]);

            return back()->with('success', 'Tu contraseña ha sido actualizada correctamente.');
        } catch (Throwable $e) {
            Log::error('❌ Error al actualizar la contraseña del usuario: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
                'email' => $request->user()->email ?? null,
            ]);

            return back()->withErrors([
                'password' => 'Ocurrió un error al actualizar tu contraseña. Intenta nuevamente.',
            ]);
        }
    }
}
