<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Poliza;

class SolicitudAnulacionPoliza extends Mailable
{
    use Queueable, SerializesModels;

    public $poliza; // propiedad pública para acceder desde la vista

    /**
     * Crear una nueva instancia del mensaje.
     */
    public function __construct(Poliza $poliza)
    {
        $this->poliza = $poliza;
    }

    /**
     * Definir el asunto del mensaje.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Solicitud Anulación Poliza',
        );
    }

    /**
     *  Definir el contenido del mensaje.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.polizas.solicitud_anulacion', // plantilla blade markdown
            with: [
                'poliza' => $this->poliza,
                // aquí puedes pasar más variables si las necesitas
            ],
        );
    }

    /**
     * Definir los adjuntos del mensaje.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
