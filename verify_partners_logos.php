<?php
/**
 * Script pour vérifier et nettoyer les partenaires avec des logos invalides
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Partenaire;

echo "=== Vérification des logos des partenaires ===\n\n";

$partenaires = Partenaire::whereNotNull('logo')->get();
$partenairesSansLogo = [];
$partenairesLogoInvalide = [];
$partenairesLogoValide = [];

foreach ($partenaires as $partenaire) {
    $logoPath = $partenaire->logo;
    
    // Vérifier si le logo existe
    if (str_starts_with($logoPath, 'assets/')) {
        $fullPath = public_path($logoPath);
    } else {
        $fullPath = storage_path('app/public/' . $logoPath);
    }
    
    if (!file_exists($fullPath)) {
        $partenairesLogoInvalide[] = [
            'id' => $partenaire->id,
            'nom' => $partenaire->nom,
            'logo' => $logoPath,
            'path' => $fullPath
        ];
    } else {
        // Vérifier si c'est une image valide
        $imageInfo = @getimagesize($fullPath);
        if ($imageInfo === false) {
            $partenairesLogoInvalide[] = [
                'id' => $partenaire->id,
                'nom' => $partenaire->nom,
                'logo' => $logoPath,
                'path' => $fullPath,
                'error' => 'Fichier non-image'
            ];
        } else {
            $partenairesLogoValide[] = [
                'id' => $partenaire->id,
                'nom' => $partenaire->nom,
                'logo' => $logoPath,
                'dimensions' => $imageInfo[0] . 'x' . $imageInfo[1],
                'type' => $imageInfo['mime']
            ];
        }
    }
}

// Partenaires sans logo
$partenairesTotal = Partenaire::count();
$partenairesAvecLogo = Partenaire::whereNotNull('logo')->count();
$partenairesSansLogoCount = $partenairesTotal - $partenairesAvecLogo;

echo "📊 STATISTIQUES :\n";
echo "- Total partenaires : $partenairesTotal\n";
echo "- Avec logo défini : $partenairesAvecLogo\n";
echo "- Sans logo : $partenairesSansLogoCount\n";
echo "- Logos valides : " . count($partenairesLogoValide) . "\n";
echo "- Logos invalides : " . count($partenairesLogoInvalide) . "\n\n";

if (!empty($partenairesLogoValide)) {
    echo "✅ PARTENAIRES AVEC LOGOS VALIDES :\n";
    foreach ($partenairesLogoValide as $p) {
        echo "   - {$p['nom']} (ID: {$p['id']}) - {$p['dimensions']} - {$p['type']}\n";
    }
    echo "\n";
}

if (!empty($partenairesLogoInvalide)) {
    echo "❌ PARTENAIRES AVEC LOGOS INVALIDES :\n";
    foreach ($partenairesLogoInvalide as $p) {
        $error = isset($p['error']) ? " - {$p['error']}" : " - Fichier introuvable";
        echo "   - {$p['nom']} (ID: {$p['id']}) - {$p['logo']}$error\n";
    }
    echo "\n";
    
    echo "🔧 SUGGESTIONS DE CORRECTION :\n";
    echo "1. Supprimer le champ logo pour ces partenaires :\n";
    foreach ($partenairesLogoInvalide as $p) {
        echo "   UPDATE partenaires SET logo = NULL WHERE id = {$p['id']}; -- {$p['nom']}\n";
    }
    echo "\n";
}

if ($partenairesSansLogoCount > 0) {
    $sansLogo = Partenaire::whereNull('logo')->get(['id', 'nom']);
    echo "ℹ️  PARTENAIRES SANS LOGO :\n";
    foreach ($sansLogo as $p) {
        echo "   - {$p->nom} (ID: {$p->id})\n";
    }
    echo "\n";
}

echo "✨ RECOMMANDATIONS :\n";
echo "1. Seuls les partenaires avec des logos valides seront affichés\n";
echo "2. Les logos doivent être dans les dossiers public/assets/ ou storage/app/public/\n";
echo "3. Formats recommandés : PNG, JPG, SVG\n";
echo "4. Taille recommandée : 200x80px minimum\n\n";

echo "=== Vérification terminée ===\n";
