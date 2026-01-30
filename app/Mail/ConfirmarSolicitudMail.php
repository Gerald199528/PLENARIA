<?php

namespace App\Mail;

use App\Models\Solicitud;
use App\Models\Ciudadano;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmarSolicitudMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Solicitud $solicitud,
        public Ciudadano $ciudadano,
        public string $respuesta = ''
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $asunto = match($this->solicitud->estado) {
            'aprobada' => 'Tu Solicitud de Atención Ciudadana ha sido Aprobada',
            'rechazada' => 'Tu Solicitud de Atención Ciudadana ha sido Rechazada',
            'en_proceso' => 'Tu Solicitud de Atención Ciudadana está En Proceso',
            default => 'Actualización de tu Solicitud de Atención Ciudadana',
        };

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
            view: 'emails.confirmar-solicitud',
            with: [
                'nombre' => $this->ciudadano->nombre . ' ' . $this->ciudadano->apellido,
                'email' => $this->ciudadano->email,
                'cedula' => $this->ciudadano->cedula,
                'telefono' => $this->ciudadano->telefono_movil,
                'whatsapp' => $this->ciudadano->whatsapp ?? 'N/A',
                'tipo_solicitud' => $this->solicitud->tipoSolicitud?->nombre ?? 'N/A',
                'descripcion' => $this->solicitud->descripcion,
                'estado' => $this->solicitud->estado,
                'respuesta' => $this->respuesta,
                'acepta_terminos' => $this->solicitud->acepta_terminos ? 'Sí' : 'No',
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
