<?php

/**
 * Script pour associer les logos existants aux partenaires sans logo
 * Usage: php artisan tinker
 * puis: include 'update_partner_logos.php'
 */

use App\Models\Partenaire;

echo "=== Mise à jour des logos des partenaires ===\n";

$logoMappings = [
    'iri2.png' => ['Institut', 'IRI', 'Recherche'],
    'logo-iri.jpg' => ['Institut', 'IRI', 'Recherche'],  
    'logo-iri2.jpg' => ['Institut', 'IRI', 'Recherche'],
    'ucbc-2.png' => ['UCBC', 'Université', 'Chrétienne'],
    'logo-conaref2.jpg' => ['CONAREF', 'Formation', 'National'],
];

$partnersWithoutLogos = Partenaire::whereNull('logo')->orWhere('logo', '')->get();

foreach ($partnersWithoutLogos as $partner) {
    echo "Partenaire sans logo: {$partner->nom}\n";
    
    foreach ($logoMappings as $logoFile => $keywords) {
        foreach ($keywords as $keyword) {
            if (stripos($partner->nom, $keyword) !== false) {
                $logoPath = "assets/img/logos/{$logoFile}";
                $partner->update(['logo' => $logoPath]);
                echo "✓ Logo assigné: {$logoFile} pour {$partner->nom}\n";
                break 2; // Sortir des deux boucles
            }
        }
    }
}

echo "\n=== Résumé ===\n";
echo "Total partenaires: " . Partenaire::count() . "\n";
echo "Partenaires avec logos: " . Partenaire::whereNotNull('logo')->where('logo', '!=', '')->count() . "\n";
echo "Partenaires sans logos: " . Partenaire::where(function($q) {
    $q->whereNull('logo')->orWhere('logo', '');
})->count() . "\n";

?>
