<?php
echo "=== SCRIPT DE DIAGNOSTIC DES ERREURS DE CRÉATION DE PROJET ===\n\n";

// Vérifier les logs Laravel récents
$logPath = storage_path('logs/laravel.log');

echo "📂 ANALYSE DES LOGS:\n";
if (file_exists($logPath)) {
    echo "✓ Fichier de log trouvé: {$logPath}\n";
    
    // Lire les dernières lignes du log
    $lines = file($logPath);
    $recentLines = array_slice($lines, -50); // 50 dernières lignes
    
    echo "🔍 RECHERCHE D'ERREURS RÉCENTES DE PROJET:\n";
    $foundErrors = false;
    
    foreach ($recentLines as $line) {
        if (strpos($line, 'Exception non catégorisée lors de la création de projet') !== false) {
            echo "❗ ERREUR TROUVÉE: " . trim($line) . "\n";
            $foundErrors = true;
        } elseif (strpos($line, 'création du projet') !== false || strpos($line, 'ProjetController') !== false) {
            echo "📋 LOG PERTINENT: " . trim(substr($line, 0, 100)) . "...\n";
            $foundErrors = true;
        }
    }
    
    if (!$foundErrors) {
        echo "ℹ️ Aucune erreur récente de création de projet trouvée dans les logs\n";
    }
} else {
    echo "❌ Fichier de log non trouvé: {$logPath}\n";
}

echo "\n" . "🔧 POINTS DE VÉRIFICATION:\n";
echo "1. Vérifiez si le message 'Une erreur système est survenue' apparaît toujours\n";
echo "2. Consultez les logs Laravel pour voir l'exception exacte\n";
echo "3. Testez avec différents types de données pour déclencher l'erreur\n";
echo "4. Vérifiez si le logging détaillé a été ajouté\n\n";

echo "📝 ÉTAPES DE TEST RECOMMANDÉES:\n";
echo "1. Tentez de créer un projet avec des données valides\n";
echo "2. Tentez de créer un projet avec des données invalides\n";
echo "3. Vérifiez les logs après chaque tentative\n";
echo "4. Notez le message d'erreur exact affiché à l'utilisateur\n\n";

echo "🚨 SI LE PROBLÈME PERSISTE:\n";
echo "1. L'exception pourrait être lancée avant d'atteindre notre catch\n";
echo "2. Il pourrait y avoir un autre endroit qui génère ce message\n";
echo "3. Le JavaScript pourrait intercepter et modifier le message\n";
echo "4. Une middleware pourrait affecter la gestion d'erreur\n\n";

echo "💡 SOLUTIONS ALTERNATIVES:\n";
echo "1. Rechercher ALL les occurrences du message dans le code\n";
echo "2. Ajouter un identifiant unique au message pour le tracer\n";
echo "3. Utiliser un debugger pour suivre l'exécution\n";
echo "4. Vérifier les handlers d'exception globaux\n\n";

// Rechercher d'autres occurrences potentielles
echo "🔎 RECHERCHE ÉTENDUE DU MESSAGE:\n";
$searchPaths = [
    'app/',
    'resources/',
    'routes/',
    'config/'
];

$searchMessage = 'Une erreur système est survenue lors de la création du projet';
$alternativeMessage = 'Une erreur est survenue lors de la création du projet';

foreach ($searchPaths as $path) {
    if (is_dir($path)) {
        $command = "grep -r \"$searchMessage\" $path 2>/dev/null || true";
        $result = shell_exec($command);
        if ($result) {
            echo "📁 Dans $path:\n$result\n";
        }
        
        $command2 = "grep -r \"$alternativeMessage\" $path 2>/dev/null || true";
        $result2 = shell_exec($command2);
        if ($result2) {
            echo "📁 Message alternatif dans $path:\n$result2\n";
        }
    }
}

echo "=== FIN DU DIAGNOSTIC ===\n";
