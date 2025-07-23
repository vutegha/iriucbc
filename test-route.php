<?php

Route::get('/test-partenaires', function () {
    $partenaires = \App\Models\Partenaire::whereNotNull('logo')
                                         ->where('afficher_publiquement', true)
                                         ->where('statut', 'actif')
                                         ->orderBy('ordre_affichage')
                                         ->orderBy('nom')
                                         ->get();
    
    return response()->json([
        'count' => $partenaires->count(),
        'partenaires' => $partenaires->map(function($p) {
            return [
                'nom' => $p->nom,
                'logo' => $p->logo,
                'logo_url' => $p->logo_url,
                'statut' => $p->statut,
                'afficher_publiquement' => $p->afficher_publiquement
            ];
        })
    ]);
});
