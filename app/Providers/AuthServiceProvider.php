<?php

namespace App\Providers;

use App\Models\Publication;
use App\Models\Actualite;
use App\Models\Service;
use App\Models\Projet;
use App\Models\Evenement;
use App\Models\Rapport;
use App\Policies\PublicationPolicy;
use App\Policies\ActualitePolicy;
use App\Policies\ServicePolicy;
use App\Policies\ProjetPolicy;
use App\Policies\EvenementPolicy;
use App\Policies\RapportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Publication::class => PublicationPolicy::class,
        Actualite::class => ActualitePolicy::class,
        Service::class => ServicePolicy::class,
        Projet::class => ProjetPolicy::class,
        Evenement::class => EvenementPolicy::class,
        Rapport::class => RapportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
