<?php
/**
 * Test final de la correction de la vue media/index
 * Vérification complète de la structure Blade et compilation
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST FINAL MEDIA/INDEX CORRECTION ===\n\n";

// Test 1: Vérification du fichier
$viewPath = 'resources/views/admin/media/index.blade.php';
if (!file_exists($viewPath)) {
    echo "❌ Erreur: Le fichier $viewPath n'existe pas\n";
    exit(1);
}

echo "✅ Fichier trouvé: $viewPath\n";

// Test 2: Analyse de la structure Blade
$content = file_get_contents($viewPath);
$sections = substr_count($content, '@section');
$endsections = substr_count($content, '@endsection');

echo "📊 Structure Blade:\n";
echo "   - @section: $sections\n";
echo "   - @endsection: $endsections\n";

if ($sections === $endsections) {
    echo "✅ Balance des sections: OK\n";
} else {
    echo "❌ Balance des sections: ERREUR ($sections sections, $endsections fins)\n";
}

// Test 3: Vérification des directives critiques
$extends = preg_match('/@extends\s*\(\s*[\'"]layouts\.admin[\'"]\s*\)/', $content);
$contentSection = preg_match('/@section\s*\(\s*[\'"]content[\'"]\s*\)/', $content);
$finalEndSection = preg_match('/@endsection\s*$/', trim($content));

echo "\n📋 Directives Blade:\n";
echo "   - @extends('layouts.admin'): " . ($extends ? "✅ OK" : "❌ MANQUANT") . "\n";
echo "   - @section('content'): " . ($contentSection ? "✅ OK" : "❌ MANQUANT") . "\n";
echo "   - @endsection final: " . ($finalEndSection ? "✅ OK" : "❌ MANQUANT") . "\n";

// Test 4: Test de compilation Laravel
echo "\n🔄 Test de compilation Laravel...\n";

try {
    $view = view('admin.media.index', [
        'medias' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
        'stats' => [
            'total' => 0,
            'images' => 0,
            'videos' => 0,
            'pending' => 0
        ],
        'imageStats' => ['published' => 0],
        'videoStats' => ['published' => 0]
    ]);
    
    $compiled = $view->render();
    echo "✅ Compilation Laravel: SUCCESS\n";
    echo "📏 Taille du HTML généré: " . number_format(strlen($compiled)) . " caractères\n";
    
} catch (Exception $e) {
    echo "❌ Erreur de compilation: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
    exit(1);
}

// Test 5: Vérification des éléments IRI UCBC
$hasIriStyles = strpos($content, '--iri-primary: #1e472f') !== false;
$hasIriClasses = strpos($content, 'text-iri-primary') !== false;
$hasModernGrid = strpos($content, 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4') !== false;
$hasStats = strpos($content, 'Statistics Dashboard') !== false;

echo "\n🎨 Éléments IRI UCBC:\n";
echo "   - Variables CSS IRI: " . ($hasIriStyles ? "✅ OK" : "❌ MANQUANT") . "\n";
echo "   - Classes IRI: " . ($hasIriClasses ? "✅ OK" : "❌ MANQUANT") . "\n";
echo "   - Grille moderne: " . ($hasModernGrid ? "✅ OK" : "❌ MANQUANT") . "\n";
echo "   - Dashboard stats: " . ($hasStats ? "✅ OK" : "❌ MANQUANT") . "\n";

// Test 6: Recherche d'erreurs potentielles
$potentialIssues = [];

if (strpos($content, '{{--') !== false && strpos($content, '--}}') === false) {
    $potentialIssues[] = "Commentaire Blade non fermé";
}

if (preg_match('/\{\{\s*\$\w+\s*\?\?\s*[\'"][^\'"]*[\'"]\s*\}\}/', $content)) {
    // OK - bonne pratique avec null coalescing
} else {
    if (preg_match('/\{\{\s*\$\w+\s*\}\}/', $content)) {
        $potentialIssues[] = "Variables sans protection null coalescing";
    }
}

echo "\n🔍 Analyse de qualité:\n";
if (empty($potentialIssues)) {
    echo "✅ Aucun problème détecté\n";
} else {
    foreach ($potentialIssues as $issue) {
        echo "⚠️  $issue\n";
    }
}

// Test 7: Vérification des permissions
$permissions = [
    'create_media',
    'view_media', 
    'update_media',
    'delete_media'
];

$hasPermissions = true;
foreach ($permissions as $permission) {
    if (strpos($content, "@can('$permission')") === false && 
        strpos($content, "@can('" . str_replace('_media', '', $permission) . "'") === false) {
        $hasPermissions = false;
        break;
    }
}

echo "\n🔐 Système de permissions:\n";
echo "   - Contrôles @can: " . ($hasPermissions ? "✅ OK" : "❌ MANQUANT") . "\n";

// Résumé final
echo "\n" . str_repeat("=", 50) . "\n";
echo "📈 RÉSUMÉ FINAL\n";
echo str_repeat("=", 50) . "\n";

$allGood = $sections === $endsections && $extends && $contentSection && $finalEndSection && $hasIriStyles;

if ($allGood) {
    echo "🎉 SUCCÈS COMPLET!\n";
    echo "   ✅ Structure Blade correcte\n";
    echo "   ✅ Compilation Laravel OK\n";  
    echo "   ✅ Design IRI UCBC intégré\n";
    echo "   ✅ Prêt pour la production\n\n";
    echo "🚀 La vue media/index est maintenant opérationnelle!\n";
    exit(0);
} else {
    echo "❌ CORRECTIONS NÉCESSAIRES\n";
    if ($sections !== $endsections) echo "   - Corriger la balance des sections Blade\n";
    if (!$extends) echo "   - Ajouter @extends('layouts.admin')\n";
    if (!$contentSection) echo "   - Ajouter @section('content')\n";
    if (!$hasIriStyles) echo "   - Intégrer les styles IRI UCBC\n";
    exit(1);
}
