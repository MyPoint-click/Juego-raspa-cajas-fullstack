<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPrizeCodeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $prizeCode;
    public $playerSessionId; // Para identificar la sesión del jugador
    /**
     * Create a new message instance.
     *
     * @param string $prizeCode El código de premio que ganó el jugador
     * @param string $playerSessionId El ID de sesión del jugador
     */
    public function __construct(string $prizeCode, string $playerSessionId)
    {
        $this->prizeCode = $prizeCode;
        $this->playerSessionId = $playerSessionId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Prize Code Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.admin-prize-code-notification',
            with: [
                'code' => $this->prizeCode,
                'sessionId' => $this->playerSessionId,
                // Puedes agregar más datos si los necesitas en el email
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
