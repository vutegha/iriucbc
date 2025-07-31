<?php

// Script pour lire les logs Laravel plus facilement
$logPath = 'storage/logs/laravel.log';

if (!file_exists($logPath)) {
    echo "❌ Fichier de log non trouvé: $logPath\n";
    echo "Vérifiez que Laravel est configuré et que les logs sont activés.\n";
    exit;
}

echo "=== Logs Laravel (Newsletter) ===\n\n";

// Lire les dernières lignes du fichier de log
$lines = file($logPath);
$totalLines = count($lines);

// Afficher les 50 dernières lignes
$startLine = max(0, $totalLines - 50);
$recentLines = array_slice($lines, $startLine);

echo "Affichage des " . count($recentLines) . " dernières lignes de log:\n";
echo "─────────────────────────────────────────────────────────────\n";

foreach ($recentLines as $line) {
    // Filtrer les lignes contenant "Newsletter" ou "newsletter"
    if (stripos($line, 'newsletter') !== false || 
        stripos($line, 'subscription') !== false ||
        stripos($line, 'ERROR') !== false ||
        stripos($line, 'EXCEPTION') !== false) {
        echo "🔍 " . trim($line) . "\n";
    }
}

echo "─────────────────────────────────────────────────────────────\n";
echo "Filtrage terminé. Si aucune ligne n'est affichée, aucun log récent concernant la newsletter n'a été trouvé.\n";
