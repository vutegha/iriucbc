<?php

echo "=== NETTOYAGE ET SURVEILLANCE DES LOGS ===\n\n";

$logFile = __DIR__ . '/storage/logs/laravel.log';

// Vider le fichier de log
if (file_exists($logFile)) {
    file_put_contents($logFile, '');
    echo "✓ Fichier de log vidé\n";
} else {
    echo "✗ Fichier de log non trouvé: $logFile\n";
    exit(1);
}

echo "\n--- LOG VIDÉ - PRÊT POUR NOUVEAU TEST ---\n";
echo "Maintenant, utilisez le formulaire de contact sur le site,\n";
echo "puis exécutez ce script pour voir les logs :\n\n";
echo "php show_logs.php\n\n";

// Créer aussi un script pour afficher les logs
$showLogsScript = '<?php

echo "=== LOGS APRÈS TEST DU FORMULAIRE ===\n\n";

$logFile = __DIR__ . "/storage/logs/laravel.log";

if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    if (empty($content)) {
        echo "Aucun log généré. Le formulaire n\'a peut-être pas été soumis ou il y a un problème.\n";
    } else {
        echo $content;
    }
} else {
    echo "Fichier de log non trouvé.\n";
}

echo "\n=== FIN DES LOGS ===\n";
';

file_put_contents(__DIR__ . '/show_logs.php', $showLogsScript);
echo "✓ Script 'show_logs.php' créé\n\n";

echo "INSTRUCTIONS:\n";
echo "1. Allez sur votre site: http://localhost/projets/iriucbc/public/contact\n";
echo "2. Remplissez et soumettez le formulaire de contact\n";
echo "3. Exécutez: php show_logs.php\n";
echo "4. Analysez les logs pour voir où le processus échoue\n\n";
