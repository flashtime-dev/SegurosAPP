<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
            }

            if ($request->user()->markEmailAsVerified()) {
                /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
                $user = $request->user();

                event(new Verified($user));

                Log::info('✅ Email verificado correctamente.', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }

            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        } catch (Throwable $e) {
            Log::error('❌ Error al verificar el email del usuario: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()->id ?? null,
                'email' => $request->user()->email ?? null,
            ]);

            abort(500, 'Ocurrió un error al verificar el correo electrónico.');
        }
    }
}
