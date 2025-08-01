<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Throwable;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        try {
            $this->ensureIsNotRateLimited();
            if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }

            // Obtener el usuario autenticado después de Auth::attempt()
            $user = Auth::user();

            // Verificar si el usuario tiene state = 1 (activo)
            if ((int) $user->state !== 1) {
                Auth::logout();

                throw ValidationException::withMessages([
                    'email' => __('auth.inactive'),
                ]);
            }

            RateLimiter::clear($this->throttleKey());
        } catch (ValidationException $e) {
            // Re-lanzar para que la validación falle como se espera
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error inesperado en LoginRequest@authenticate: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $this->input('email'),
                'ip' => $this->ip(),
            ]);
            // Opcionalmente, podrías lanzar una excepción general o mostrar un mensaje genérico
            throw ValidationException::withMessages([
                'email' => __('auth.unexpected_error'),
            ]);
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        try {
            if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
                return;
            }

            event(new Lockout($this));
            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error inesperado en LoginRequest@ensureIsNotRateLimited: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $this->input('email'),
                'ip' => $this->ip(),
            ]);
            throw ValidationException::withMessages([
                'email' => __('auth.unexpected_error'),
            ]);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
