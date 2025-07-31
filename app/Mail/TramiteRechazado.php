<?php

namespace App\Mail;

use App\Models\Tramite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TramiteRechazado extends Mailable
{
    use Queueable, SerializesModels;

    public $tramite;

    /**
     * Create a new message instance.
     */
    public $tipo;
    public $asunto;

    public function __construct(Tramite $tramite, $tipo = 'rechazado')
    {
        $this->tramite = $tramite;
        $this->tipo = $tipo;
        $this->asunto = $tipo === 'reagendado' ? 'Cita Reagendada' : 'Trámite Rechazado';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->asunto . ' - Folio #' . $this->tramite->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tramite-notificacion',
            with: [
                'tramite' => $this->tramite,
                'usuario' => $this->tramite->proveedor->user,
                'tipo' => $this->tipo,
                'asunto' => $this->asunto,
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
