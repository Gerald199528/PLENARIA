<?php

namespace App\Mail;

use App\Models\DerechoDePalabra;
use App\Models\Ciudadano;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmarDerechoPalabraMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public DerechoDePalabra $derecho,
        public Ciudadano $ciudadano,
        public string $observaciones = ''
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $asunto = $this->derecho->estado === 'aprobada'
            ? 'Tu solicitud de Derecho de Palabra ha sido Aprobada'
            : 'Tu solicitud de Derecho de Palabra ha sido Rechazada';

        return new Envelope(
            subject: $asunto,
            to: [$this->ciudadano->email],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmar-derecho-palabra',
            with: [
                'nombre' => $this->ciudadano->nombre . ' ' . $this->ciudadano->apellido,
                'email' => $this->ciudadano->email,
                'cedula' => $this->ciudadano->cedula,
                'telefono' => $this->ciudadano->telefono_movil,
                'whatsapp' => $this->ciudadano->whatsapp,
                'sesion' => $this->derecho->sesion?->titulo ?? 'Sin sesión',
                'comision' => $this->derecho->comision?->nombre ?? 'Sin comisión',
                'motivo' => $this->derecho->motivo_solicitud,
                'estado' => $this->derecho->estado,
                'observaciones' => $this->observaciones,
                'fecha' => now()->format('d/m/Y H:i'),
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
