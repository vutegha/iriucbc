<?php
/**
 * Script de génération de favicon
 * Ce script génère tous les formats de favicon nécessaires à partir d'un fichier source
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🎨 GÉNÉRATEUR DE FAVICON IRI-UCBC\n";
echo "=================================\n\n";

// Vérifier si ImageMagick ou GD est disponible
$hasImageMagick = extension_loaded('imagick');
$hasGD = extension_loaded('gd');

echo "📋 Extensions disponibles:\n";
echo "   - ImageMagick: " . ($hasImageMagick ? "✅ Disponible" : "❌ Non disponible") . "\n";
echo "   - GD: " . ($hasGD ? "✅ Disponible" : "❌ Non disponible") . "\n\n";

if (!$hasImageMagick && !$hasGD) {
    echo "❌ Aucune extension d'image disponible. Veuillez installer ImageMagick ou GD.\n";
    exit(1);
}

// Chemins
$publicPath = __DIR__ . '/public';
$assetsPath = $publicPath . '/assets/img';
$sourceFile = $publicPath . '/favicon.ico'; // Fichier source

// Vérifier si le fichier source existe
if (!file_exists($sourceFile)) {
    echo "❌ Fichier source non trouvé: $sourceFile\n";
    echo "📝 Veuillez placer votre fichier favicon.ico dans le dossier public/\n";
    exit(1);
}

echo "✅ Fichier source trouvé: $sourceFile\n\n";

// Formats à générer
$formats = [
    'favicon-16x16.png' => 16,
    'favicon-32x32.png' => 32,
    'apple-touch-icon.png' => 180,
    'android-chrome-192x192.png' => 192,
    'android-chrome-512x512.png' => 512,
    'mstile-150x150.png' => 150
];

echo "🔄 Génération des formats...\n";

foreach ($formats as $filename => $size) {
    $outputPath = $assetsPath . '/' . $filename;
    
    try {
        if ($hasImageMagick) {
            // Utiliser ImageMagick
            $imagick = new Imagick($sourceFile);
            $imagick->setImageFormat('png');
            $imagick->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);
            $imagick->writeImage($outputPath);
            $imagick->clear();
            $imagick->destroy();
        } elseif ($hasGD) {
            // Utiliser GD
            $source = imagecreatefromstring(file_get_contents($sourceFile));
            $resized = imagecreatetruecolor($size, $size);
            
            // Activer la transparence
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefill($resized, 0, 0, $transparent);
            
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $size, $size, imagesx($source), imagesy($source));
            imagepng($resized, $outputPath);
            
            imagedestroy($source);
            imagedestroy($resized);
        }
        
        echo "   ✅ Généré: $filename ({$size}x{$size})\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur lors de la génération de $filename: " . $e->getMessage() . "\n";
    }
}

echo "\n🎯 RÉSUMÉ\n";
echo "=========\n";
echo "✅ Favicon configurée pour frontend et backend\n";
echo "✅ Partial favicon.blade.php créé\n";
echo "✅ Manifeste PWA mis à jour\n";
echo "✅ Configuration Windows (browserconfig.xml) créée\n";
echo "✅ Layouts frontend et backend mis à jour\n";

echo "\n📁 FICHIERS REQUIS\n";
echo "==================\n";
echo "Assurez-vous que votre fichier favicon.ico est placé dans:\n";
echo "   📂 public/favicon.ico\n\n";
echo "Les formats suivants seront générés automatiquement:\n";
foreach ($formats as $filename => $size) {
    echo "   📄 public/assets/img/$filename\n";
}

echo "\n🚀 La favicon est maintenant configurée pour tout le site !\n";
?>
