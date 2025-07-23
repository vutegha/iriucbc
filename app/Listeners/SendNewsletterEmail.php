<?php

namespace App\Listeners;

use App\Events\PublicationFeaturedCreated;
use App\Events\ActualiteFeaturedCreated;
use App\Events\ProjectCreated;
use App\Mail\PublicationNewsletter;
use App\Mail\ActualiteNewsletter;
use App\Mail\ProjectNewsletter;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewsletterEmail
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        try {
            switch (true) {
                case $event instanceof PublicationFeaturedCreated:
                    $this->sendPublicationNewsletter($event->publication);
                    break;
                case $event instanceof ActualiteFeaturedCreated:
                    $this->sendActualiteNewsletter($event->actualite);
                    break;
                case $event instanceof ProjectCreated:
                    $this->sendProjectNewsletter($event->projet);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi newsletter: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer newsletter pour publication
     */
    private function sendPublicationNewsletter($publication)
    {
        $subscribers = Newsletter::active()
            ->withPreference('publications')
            ->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->queue(new PublicationNewsletter($publication, $subscriber));
        }

        Log::info("Newsletter publication envoyée à {$subscribers->count()} abonnés");
    }

    /**
     * Envoyer newsletter pour actualité
     */
    private function sendActualiteNewsletter($actualite)
    {
        $subscribers = Newsletter::active()
            ->withPreference('actualites')
            ->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->queue(new ActualiteNewsletter($actualite, $subscriber));
        }

        Log::info("Newsletter actualité envoyée à {$subscribers->count()} abonnés");
    }

    /**
     * Envoyer newsletter pour projet
     */
    private function sendProjectNewsletter($projet)
    {
        $subscribers = Newsletter::active()
            ->withPreference('projets')
            ->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->queue(new ProjectNewsletter($projet, $subscriber));
        }

        Log::info("Newsletter projet envoyée à {$subscribers->count()} abonnés");
    }
}
