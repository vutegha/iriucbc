<?php

namespace App\Providers;

use App\Events\PublicationFeaturedCreated;
use App\Events\ActualiteFeaturedCreated;
use App\Events\ProjectCreated;
use App\Listeners\SendNewsletterEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        PublicationFeaturedCreated::class => [
            SendNewsletterEmail::class,
        ],
        ActualiteFeaturedCreated::class => [
            SendNewsletterEmail::class,
        ],
        ProjectCreated::class => [
            SendNewsletterEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
