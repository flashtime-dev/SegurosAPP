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

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile settings.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $request->user()->fill($request->validated());

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();
            return to_route('profile.edit');
        } catch (Throwable $e) {
            Log::error('❌ Error al actualizar el perfil del usuario: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
                'email' => $request->user()->email ?? null,
            ]);

            return back()->withErrors([
                'profile' => 'Ocurrió un error al actualizar tu perfil. Intenta nuevamente.',
            ]);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'password' => ['required', 'current_password'],
            ]);
            $user = $request->user();

            Auth::logout();
            $user->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } catch (Throwable $e) {
            Log::error('❌ Error al eliminar la cuenta del usuario: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
                'email' => $request->user()->email ?? null,
            ]);

            return back()->withErrors([
                'account_deletion' => 'Ocurrió un error al eliminar tu cuenta. Intenta nuevamente.',
            ]);
        }
    }
}
