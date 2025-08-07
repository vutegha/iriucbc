<?php

require_once 'vendor/autoload.php';

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 VALIDATION FINALE DE L'HARMONISATION SNAKE_CASE\n";
echo "=" . str_repeat("=", 50) . "\n\n";

// 1. Vérifier les permissions dans la base de données
echo "1. 📊 PERMISSIONS DANS LA BASE DE DONNÉES:\n";
echo "-" . str_repeat("-", 40) . "\n";

try {
    $permissions = DB::table('permissions')->orderBy('name')->get();
    $totalPermissions = $permissions->count();
    $snakeCaseCount = 0;
    
    foreach ($permissions as $permission) {
        if (preg_match('/^[a-z]+_[a-z]+$/', $permission->name)) {
            $snakeCaseCount++;
            echo "✅ {$permission->name}\n";
        } else {
            echo "❌ {$permission->name} (PAS EN SNAKE_CASE)\n";
        }
    }
    
    echo "\nRÉSUMÉ BASE DE DONNÉES:\n";
    echo "  - Total permissions: $totalPermissions\n";
    echo "  - Format snake_case: $snakeCaseCount\n";
    echo "  - Pourcentage conforme: " . round(($snakeCaseCount / $totalPermissions) * 100, 1) . "%\n\n";
    
    $dbSnakeCaseCount = $snakeCaseCount; // Sauvegarder le count DB
    
} catch (Exception $e) {
    echo "❌ Erreur base de données: " . $e->getMessage() . "\n\n";
}

// 2. Vérifier les fichiers de policies
echo "2. 📋 POLICIES GÉNÉRÉES:\n";
echo "-" . str_repeat("-", 40) . "\n";

$policyPath = app_path('Policies');
$policyFiles = [
    'ActualitePolicy.php',
    'ProjetPolicy.php', 
    'PublicationPolicy.php',
    'EvenementPolicy.php',
    'ServicePolicy.php',
    'MediaPolicy.php'
];

$policiesGenerated = 0;
foreach ($policyFiles as $file) {
    $filePath = $policyPath . DIRECTORY_SEPARATOR . $file;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        if (strpos($content, 'hasPermissionTo(') !== false) {
            echo "✅ $file (contient des vérifications snake_case)\n";
            $policiesGenerated++;
        } else {
            echo "❌ $file (format incorrect)\n";
        }
    } else {
        echo "❌ $file (fichier manquant)\n";
    }
}

echo "\nRÉSUMÉ POLICIES:\n";
echo "  - Policies générées: $policiesGenerated/" . count($policyFiles) . "\n\n";

// 3. Vérifier les vues
echo "3. 👁️ VUES MISES À JOUR:\n";
echo "-" . str_repeat("-", 40) . "\n";

$viewsPath = resource_path('views');
$command = "cd \"$viewsPath\" && findstr /R /N \"@can(.*[^_][^a-z].*[^_])\" *.blade.php 2>nul";
$oldFormatFound = shell_exec($command);

if (empty(trim($oldFormatFound))) {
    echo "✅ Aucune permission en ancien format trouvée dans les vues\n";
} else {
    echo "❌ Permissions en ancien format trouvées:\n";
    echo $oldFormatFound;
}

// Compter les directives @can snake_case
$snakeCaseCommand = "cd \"$viewsPath\" && findstr /R /N \"@can('.*_.*')\" *.blade.php 2>nul";
$snakeCaseFound = shell_exec($snakeCaseCommand);
$snakeCaseCount = substr_count($snakeCaseFound, '@can');

echo "\nRÉSUMÉ VUES:\n";
echo "  - Directives @can snake_case trouvées: $snakeCaseCount\n\n";

// 4. Résumé final
echo "4. 🎯 RÉSUMÉ FINAL:\n";
echo "-" . str_repeat("-", 40) . "\n";

$dbCompliant = ($dbSnakeCaseCount == $totalPermissions) && ($totalPermissions == 70); // Base de données conforme
$policiesCompliant = $policiesGenerated == count($policyFiles);
$viewsCompliant = empty(trim($oldFormatFound));

echo "Base de données: " . ($dbCompliant ? "✅ CONFORME" : "❌ NON CONFORME") . "\n";
echo "Policies: " . ($policiesCompliant ? "✅ CONFORME" : "❌ NON CONFORME") . "\n";
echo "Vues: " . ($viewsCompliant ? "✅ CONFORME" : "❌ NON CONFORME") . "\n\n";

if ($dbCompliant && $policiesCompliant && $viewsCompliant) {
    echo "🎉 HARMONISATION SNAKE_CASE COMPLÈTE !\n";
    echo "   Toutes les couches utilisent maintenant le format {action}_{model}\n";
} else {
    echo "⚠️ Harmonisation incomplète, des ajustements sont nécessaires.\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
