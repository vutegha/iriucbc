<?php
echo "=== ANALYSE DE L'ERREUR SYSTÈME [DEBUG: CATCH-FINAL-555] ===\n\n";

// Lire le fichier de log
$logFile = 'storage/logs/laravel.log';
if (!file_exists($logFile)) {
    echo "❌ Fichier de log non trouvé : $logFile\n";
    exit;
}

echo "📄 Lecture du fichier de log : $logFile\n";
$content = file_get_contents($logFile);

// Chercher les erreurs récentes avec notre debug marker
echo "\n🔍 RECHERCHE DES ERREURS AVEC [DEBUG: CATCH-FINAL-555]...\n";

$lines = explode("\n", $content);
$errorLines = [];
$captureNext = 0;

foreach ($lines as $i => $line) {
    if (strpos($line, 'DEBUG: CATCH-FINAL-555') !== false) {
        echo "\n✅ ERREUR TROUVÉE À LA LIGNE " . ($i + 1) . ":\n";
        
        // Capturer le contexte (10 lignes avant et 20 après)
        $start = max(0, $i - 10);
        $end = min(count($lines) - 1, $i + 20);
        
        for ($j = $start; $j <= $end; $j++) {
            $marker = ($j == $i) ? " >>> " : "     ";
            echo $marker . "[$j] " . $lines[$j] . "\n";
        }
        echo "\n" . str_repeat("=", 80) . "\n";
    }
    
    // Chercher aussi les exceptions non catégorisées récentes
    if (strpos($line, 'Exception non catégorisée lors de la création de projet') !== false) {
        echo "\n🔍 EXCEPTION DÉTAILLÉE TROUVÉE:\n";
        $start = max(0, $i - 5);
        $end = min(count($lines) - 1, $i + 15);
        
        for ($j = $start; $j <= $end; $j++) {
            echo "     [$j] " . $lines[$j] . "\n";
        }
        echo "\n" . str_repeat("-", 80) . "\n";
    }
}

echo "\n📊 ANALYSE DES ERREURS LES PLUS FRÉQUENTES:\n";

// Analyser les types d'erreurs
$errorTypes = [];
foreach ($lines as $line) {
    if (strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false) {
        // Extraire le type d'erreur
        if (preg_match('/(\w+Exception|\w+Error)/', $line, $matches)) {
            $errorType = $matches[1];
            $errorTypes[$errorType] = ($errorTypes[$errorType] ?? 0) + 1;
        }
    }
}

arsort($errorTypes);
foreach ($errorTypes as $type => $count) {
    echo "  • $type: $count occurrence(s)\n";
}

echo "\n💡 RECOMMANDATIONS POUR LE DEBUG:\n";
echo "1. Vérifier les dernières modifications dans ProjetController.php\n";
echo "2. Tester la validation CKEditor avec du contenu simple\n";
echo "3. Vérifier la configuration de la base de données\n";
echo "4. Tester sans upload d'image\n";
echo "5. Vérifier les permissions des fichiers\n";

?>
