<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;
use App\Models\EmailSetting;

class ContactMessageWithCopy extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $isForAdmin;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, bool $isForAdmin = true)
    {
        $this->contact = $contact;
        $this->isForAdmin = $isForAdmin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isForAdmin 
            ? 'Nouveau message de contact - ' . $this->contact->sujet
            : 'Copie de votre message - ' . $this->contact->sujet;

        return new Envelope(
            subject: $subject,
            replyTo: [
                $this->contact->email => $this->contact->nom,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->isForAdmin 
            ? 'emails.contact-message-admin'
            : 'emails.contact-message-copy';

        return new Content(
            view: $view,
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Envoyer le message à toutes les adresses configurées
     */
    public static function sendToConfiguredEmails(Contact $contact)
    {
        try {
            $totalSent = 0;
            $copyRecipients = [];
            $acknowledgmentRecipients = [];

            // 1. Récupérer la configuration email active
            $emailSetting = EmailSetting::where('active', true)->first();
            
            if (!$emailSetting) {
                throw new \Exception('Aucune configuration email active trouvée');
            }

            // 2. Préparer le message pour les administrateurs
            $adminMessage = "NOUVEAU MESSAGE DE CONTACT\n\n" .
                          "De: {$contact->nom} <{$contact->email}>\n" .
                          "Sujet: {$contact->sujet}\n" .
                          "Date: " . now()->format('d/m/Y à H:i:s') . "\n\n" .
                          "MESSAGE:\n" . str_repeat('-', 50) . "\n" .
                          $contact->message . "\n" .
                          str_repeat('-', 50) . "\n\n" .
                          "Vous pouvez répondre directement à cet email.\n\n" .
                          "-- \nSystème IRI-UCBC";

            // 3. Préparer le message d'accusé de réception
            $ackMessage = "ACCUSÉ DE RÉCEPTION\n\n" .
                         "Bonjour {$contact->nom},\n\n" .
                         "Nous avons bien reçu votre message intitulé \"{$contact->sujet}\".\n\n" .
                         "Votre message:\n" . str_repeat('-', 30) . "\n" .
                         $contact->message . "\n" .
                         str_repeat('-', 30) . "\n\n" .
                         "Nous vous répondrons dans les plus brefs délais.\n\n" .
                         "Cordialement,\nL'équipe IRI-UCBC";

            // 4. Envoyer aux adresses configurées
            $targetEmails = $emailSetting->emails;
            
            foreach ($targetEmails as $email) {
                \Mail::raw(
                    $adminMessage,
                    function ($message) use ($email, $contact) {
                        $message->to($email)
                               ->subject("Nouveau contact: {$contact->sujet}")
                               ->replyTo($contact->email, $contact->nom);
                    }
                );
                
                $copyRecipients[] = $email;
                $totalSent++;
            }

            // 5. Envoyer accusé de réception à l'expéditeur
            \Mail::raw(
                $ackMessage,
                function ($message) use ($contact) {
                    $message->to($contact->email, $contact->nom)
                           ->subject("Confirmation de réception: {$contact->sujet}");
                }
            );
            
            $acknowledgmentRecipients[] = $contact->email;
            $totalSent++;

            return [
                'success' => true,
                'total_sent' => $totalSent,
                'copy_recipients' => $copyRecipients,
                'acknowledgment_recipients' => $acknowledgmentRecipients
            ];

        } catch (\Exception $e) {
            \Log::error('Erreur ContactMessageWithCopy: ' . $e->getMessage(), [
                'contact_id' => $contact->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'total_sent' => 0
            ];
        }
    }
}
