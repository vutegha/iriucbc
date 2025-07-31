<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Publication;
use App\Models\Projet;
use App\Models\Evenement;
use App\Models\Service;
use App\Models\Newsletter;
use App\Models\Contact;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'actualites' => Actualite::count(),
            'publications' => Publication::count(),
            'projets' => Projet::count(),
            'evenements' => Evenement::count(),
            'services' => Service::count(),
            'newsletters' => Newsletter::where('actif', true)->count(),
            'messages' => Contact::count(),
        ];

        // Statistiques des projets
        $statsProjects = [
            'total_projets' => Projet::count(),
            'projets_en_cours' => Projet::where('etat', 'en cours')->count(),
            'projets_termines' => Projet::where('etat', 'terminé')->count(),
            'total_beneficiaires' => Projet::sum('beneficiaires_total') ?: 0,
            'beneficiaires_hommes' => Projet::sum('beneficiaires_hommes') ?: 0,
            'beneficiaires_femmes' => Projet::sum('beneficiaires_femmes') ?: 0,
        ];

        // Statistiques des événements
        $statsEvenements = [
            'total_evenements' => Evenement::count(),
            'evenements_a_venir' => Evenement::aVenir()->count(),
            'evenements_passes' => Evenement::passe()->count(),
            'evenements_en_cours' => Evenement::enCours()->count(),
        ];

        // Dernières activités
        $dernieresActualites = Actualite::latest()->take(5)->get();
        $dernieresPublications = Publication::latest()->take(5)->get();
        $derniersProjets = Projet::latest()->take(5)->get();
        $derniersEvenements = Evenement::latest('date_evenement')->take(5)->get();
        $derniersMessages = Contact::latest()->take(5)->get();

        // Prochains événements
        $prochainsEvenements = Evenement::aVenir()
                                      ->orderBy('date_evenement', 'asc')
                                      ->take(3)
                                      ->get();

        // Graphiques mensuels (6 derniers mois)
        $moisLabels = [];
        $actualitesData = [];
        $publicationsData = [];
        $projetsData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i);
            $moisLabels[] = $mois->format('M Y');
            
            $actualitesData[] = Actualite::whereYear('created_at', $mois->year)
                                        ->whereMonth('created_at', $mois->month)
                                        ->count();
                                        
            $publicationsData[] = Publication::whereYear('created_at', $mois->year)
                                           ->whereMonth('created_at', $mois->month)
                                           ->count();
                                           
            $projetsData[] = Projet::whereYear('created_at', $mois->year)
                                  ->whereMonth('created_at', $mois->month)
                                  ->count();
        }

        return view('admin.dashboard', compact(
            'stats',
            'statsProjects', 
            'statsEvenements',
            'dernieresActualites',
            'dernieresPublications',
            'derniersProjets',
            'derniersEvenements',
            'derniersMessages',
            'prochainsEvenements',
            'moisLabels',
            'actualitesData',
            'publicationsData',
            'projetsData'
        ));
    }
}