<x-mail::message>
# Hola {{ $user->name }},

Has solicitado restablecer tu contraseña. Haz clic en el siguiente botón para continuar:

<x-mail::button :url="route('password.reset', ['token' => $token, 'email' => $user->email])">
Restablecer contraseña
</x-mail::button>

Si no solicitaste este cambio, puedes ignorar este correo.

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
