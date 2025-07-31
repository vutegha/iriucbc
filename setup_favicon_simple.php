<?php
/**
 * Script simple de configuration favicon
 * Configure les favicons en utilisant les fichiers existants
 */

echo "🔧 CONFIGURATION FAVICON SIMPLE\n";
echo "===============================\n\n";

$publicPath = __DIR__ . '/public';
$assetsImgPath = $publicPath . '/assets/img';

// Vérifier les fichiers existants
$existingFiles = [
    'favicon.ico' => $publicPath . '/favicon.ico',
    'favicon.png' => $assetsImgPath . '/favicon.png'
];

echo "📋 Vérification des fichiers existants:\n";
foreach ($existingFiles as $name => $path) {
    if (file_exists($path)) {
        echo "   ✅ $name trouvé\n";
    } else {
        echo "   ❌ $name non trouvé: $path\n";
    }
}

// Copier favicon.ico vers la racine si ce n'est pas déjà fait
$rootFavicon = $publicPath . '/favicon.ico';
$assetsFavicon = $assetsImgPath . '/favicon.ico';

if (file_exists($assetsFavicon) && !file_exists($rootFavicon)) {
    if (copy($assetsFavicon, $rootFavicon)) {
        echo "✅ Favicon.ico copié vers la racine\n";
    } else {
        echo "❌ Erreur lors de la copie du favicon\n";
    }
}

// Créer des liens symboliques ou copies pour les autres formats si favicon.png existe
if (file_exists($assetsImgPath . '/favicon.png')) {
    $formats = [
        'favicon-16x16.png',
        'favicon-32x32.png',
        'apple-touch-icon.png',
        'android-chrome-192x192.png',
        'android-chrome-512x512.png'
    ];
    
    echo "\n📦 Création des formats de base:\n";
    foreach ($formats as $format) {
        $targetPath = $assetsImgPath . '/' . $format;
        if (!file_exists($targetPath)) {
            if (copy($assetsImgPath . '/favicon.png', $targetPath)) {
                echo "   ✅ Créé: $format\n";
            } else {
                echo "   ❌ Erreur: $format\n";
            }
        } else {
            echo "   ⏭️  Existe déjà: $format\n";
        }
    }
}

echo "\n🎨 INSTRUCTIONS POUR OPTIMISER\n";
echo "==============================\n";
echo "Pour de meilleurs résultats, il est recommandé de:\n";
echo "1. 📁 Placer votre fichier favicon.ico dans public/\n";
echo "2. 🖼️  Créer les formats optimisés pour chaque taille:\n";
echo "   - favicon-16x16.png (16x16 pixels)\n";
echo "   - favicon-32x32.png (32x32 pixels)\n";
echo "   - apple-touch-icon.png (180x180 pixels)\n";
echo "   - android-chrome-192x192.png (192x192 pixels)\n";
echo "   - android-chrome-512x512.png (512x512 pixels)\n";
echo "3. 🛠️  Utiliser des outils comme https://favicon.io pour générer tous les formats\n";

echo "\n✅ Configuration de base terminée !\n";
echo "🌐 La favicon apparaîtra maintenant sur le frontend et le backend.\n";
?>
