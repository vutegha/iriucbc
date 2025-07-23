<?php

namespace App\Mail;

use App\Models\Actualite;
use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActualiteNewsletter extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $actualite;
    public $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct(Actualite $actualite, Newsletter $subscriber)
    {
        $this->actualite = $actualite;
        $this->subscriber = $subscriber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📰 Nouvelle Actualité : ' . $this->actualite->titre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.actualite',
            with: [
                'actualite' => $this->actualite,
                'subscriber' => $this->subscriber,
                'preferencesUrl' => route('newsletter.preferences', $this->subscriber->token)
            ]
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
