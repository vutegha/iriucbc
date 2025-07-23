<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Service;

class ServiceMenuProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les services avec toutes les vues pour le menu dynamique
        View::composer('*', function ($view) {
            $services = Service::select('nom', 'nom_menu', 'slug')
                              ->orderBy('nom')
                              ->get();
            $view->with('menuServices', $services);
        });
    }
}
