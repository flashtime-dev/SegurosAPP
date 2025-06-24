<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

/**
 * Esta clase maneja la lógica de configuración del perfil del usuario.
 * Permite editar y actualizar la información del perfil, así como eliminar la cuenta.
 */
class ProfileController extends Controller
{
    /**
     * Mostrar la página de configuración del perfil del usuario.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Actualizar la información del perfil del usuario.
     */
    // Validar los datos del perfil usando el request personalizado
    // El request ProfileUpdateRequest ya maneja las validaciones necesarias
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            //fill() se usa para llenar el modelo con los datos validados del request
            // El método validated() devuelve los datos validados del request
            $request->user()->fill($request->validated());
            
            // isDirty verifica si el campo email ha cambiado, 
            // y si es así, se establece email_verified_at a null para forzar la verificación del email nuevamente           
            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();

            Log::info('✅ Perfil actualizado correctamente.', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
            ]);

            return to_route('profile.edit')->with([
                    'success' => [
                        'id' => uniqid(),
                        'mensaje' => "Tu perfil ha sido actualizado correctamente",
                    ],
                ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al actualizar el perfil del usuario: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
                'email' => $request->user()->email ?? null,
            ]);

            return back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Se produjo un error al actualizar tu perfil. Intentalo de nuevo",
                    ],
                ]);
            // ->withErrors([
            //     'profile' => 'Ocurrió un error al actualizar tu perfil. Intentalo de nuevo.',
            // ]);
        }
    }

    /**
     * Eliminar la cuenta del usuario. (actualemente no se usa) 
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
    
        try {
            $user = $request->user();

            Auth::logout();
            $user->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('🗑️ Cuenta de usuario eliminada correctamente.', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return redirect('/');
        } catch (Throwable $e) {
            Log::error('❌ Error al eliminar la cuenta del usuario: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
                'email' => $request->user()->email ?? null,
            ]);

            return back()->with([
                    'error' => [
                        'id' => uniqid(),
                        'mensaje' => "Se produjo un error al eliminar tu cuenta. Intentalo de nuevo",
                    ],
                ]);
            // ->withErrors([
            //     'account_deletion' => 'Ocurrió un error al eliminar tu cuenta. Intenta nuevamente.',
            // ]);
        }
    }
}
