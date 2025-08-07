<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrement du View Composer pour les liens sociaux
        view()->composer('partials.footer', \App\Http\View\Composers\FooterComposer::class);
        
        // PATCH: Enregistrement forcé des listeners newsletter
        // Car EventServiceProvider ne semble pas les charger automatiquement
        $events = $this->app['events'];
        
        $listeners = [
            \App\Events\PublicationFeaturedCreated::class => [\App\Listeners\SendNewsletterEmail::class],
            \App\Events\ActualiteFeaturedCreated::class => [\App\Listeners\SendNewsletterEmail::class],
            \App\Events\ProjectCreated::class => [\App\Listeners\SendNewsletterEmail::class],
            \App\Events\RapportCreated::class => [\App\Listeners\SendNewsletterEmail::class],
        ];
        
        foreach ($listeners as $event => $eventListeners) {
            foreach ($eventListeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }
}
