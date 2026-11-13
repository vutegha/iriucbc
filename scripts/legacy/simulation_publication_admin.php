<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel comme le fait le serveur web
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simuler une session utilisateur admin
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['HTTP_HOST'] = 'localhost:8000';
$_SERVER['REQUEST_URI'] = '/admin/publications/publish';

echo "=== SIMULATION PUBLICATION ADMIN ===\n\n";

try {
    // Simuler la connexion admin (utilisateur ID 1)
    \Illuminate\Support\Facades\Auth::loginUsingId(1);
    
    // Récupérer une publication
    $publication = \App\Models\Publication::first();
    
    if (!$publication) {
        echo "❌ Aucune publication trouvée\n";
        exit;
    }
    
    echo "📰 Publication sélectionnée: " . substr($publication->titre, 0, 50) . "...\n";
    echo "    ID: {$publication->id}\n\n";
    
    // Simuler exactement ce que fait le controller
    echo "🎯 Simulation du processus de publication...\n";
    
    // 1. Log de début
    \Illuminate\Support\Facades\Log::info("=== DÉBUT PUBLICATION MANUELLE ===", [
        'publication_id' => $publication->id,
        'user_id' => 1,
        'timestamp' => now()
    ]);
    
    // 2. Déclencher l'événement comme le fait le controller
    echo "   Déclenchement de l'événement PublicationFeaturedCreated...\n";
    event(new \App\Events\PublicationFeaturedCreated($publication));
    
    echo "   ✅ Événement déclenché\n";
    
    // 3. Log de fin
    \Illuminate\Support\Facades\Log::info("=== FIN PUBLICATION MANUELLE ===", [
        'publication_id' => $publication->id,
        'success' => true
    ]);
    
    echo "\n📊 Vérification des logs récents...\n";
    
    // Lire les dernières lignes du log
    $logContent = file_get_contents(storage_path('logs/laravel.log'));
    $lines = explode("\n", $logContent);
    $recentLines = array_slice($lines, -10);
    
    foreach ($recentLines as $line) {
        if (strpos($line, 'Newsletter') !== false || strpos($line, 'PUBLICATION') !== false) {
            echo "   📝 " . trim($line) . "\n";
        }
    }
    
    echo "\n✅ Simulation terminée - Vérifiez vos emails !\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📁 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== FIN SIMULATION ===\n";
