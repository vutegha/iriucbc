<?php
require 'vendor/autoload.php';

use App\Models\Publication;

// Récupérer une publication avec un fichier PDF
$publications = Publication::whereNotNull('fichier_pdf')->get();

echo "=== DEBUG PDF FILES ===\n";
echo "Nombre de publications avec fichier PDF: " . $publications->count() . "\n\n";

foreach ($publications as $publication) {
    echo "Publication ID: {$publication->id}\n";
    echo "Titre: {$publication->titre}\n";
    echo "Fichier PDF: {$publication->fichier_pdf}\n";
    
    // Vérifier si le fichier existe
    $fullPath = storage_path('app/public/' . $publication->fichier_pdf);
    $publicPath = public_path('storage/' . $publication->fichier_pdf);
    
    echo "Chemin complet: {$fullPath}\n";
    echo "Chemin public: {$publicPath}\n";
    echo "Fichier existe (storage): " . (file_exists($fullPath) ? "OUI" : "NON") . "\n";
    echo "Fichier existe (public): " . (file_exists($publicPath) ? "OUI" : "NON") . "\n";
    
    if (file_exists($fullPath)) {
        echo "Taille du fichier: " . filesize($fullPath) . " bytes\n";
        echo "Extension: " . pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION) . "\n";
    }
    
    echo "URL générée: " . asset('storage/' . $publication->fichier_pdf) . "\n";
    echo "---\n";
}

// Vérifier le lien symbolique
echo "\n=== VERIFICATION LIEN SYMBOLIQUE ===\n";
$storageLink = public_path('storage');
echo "Lien storage existe: " . (is_link($storageLink) ? "OUI" : "NON") . "\n";
echo "Lien storage pointe vers: " . (is_link($storageLink) ? readlink($storageLink) : "N/A") . "\n";

// Vérifier les permissions
echo "\n=== VERIFICATION PERMISSIONS ===\n";
$storageDir = storage_path('app/public');
echo "Répertoire storage: {$storageDir}\n";
echo "Répertoire existe: " . (is_dir($storageDir) ? "OUI" : "NON") . "\n";
echo "Permissions: " . substr(sprintf('%o', fileperms($storageDir)), -4) . "\n";
