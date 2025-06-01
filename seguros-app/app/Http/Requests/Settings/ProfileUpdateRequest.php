<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        try{
            return [
                'name' => ['required', 'string', 'max:255'],

                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($this->user()->id),
                ],
            ];
        } catch (Throwable $e) {
            Log::error('Error inesperado en ProfileUpdateRequest@rules: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => optional($this->user())->id,
            ]);

            // Devolver reglas vacías para que falle la validación o podrías lanzar excepción
            return [];
        }
    }
}
