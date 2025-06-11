<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;

    /**
     *  Crear una nueva instancia de mensaje.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     *  Definir el sobre del mensaje.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Restablecer contraseña',
        );
    }

    /**
     *  Definir el contenido del mensaje.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.reset_password', // plantilla blade markdown
            with: [
                'user' => $this->user,
                'token' => $this->token,
                'resetUrl' => url(route('password.reset', ['token' => $this->token, 'email' => $this->user->email], false)),
            ],
        );
    }

    /**
     *  Definir los adjuntos del mensaje.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
