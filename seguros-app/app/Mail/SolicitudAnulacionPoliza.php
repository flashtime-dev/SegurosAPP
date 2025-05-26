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
     * Create a new message instance.
     */
    public function __construct(Poliza $poliza)
    {
        $this->poliza = $poliza;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Solicitud Anulación Poliza',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.polizas.solicitud_anulacion',
            with: [
                'poliza' => $this->poliza,
                // aquí puedes pasar más variables si las necesitas
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
