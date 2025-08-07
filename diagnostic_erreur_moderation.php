<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Route;

echo "=== DIAGNOSTIC DES ERREURS DE MODÉRATION ===\n\n";

// 1. Vérifier les routes de modération
echo "=== VÉRIFICATION DES ROUTES ===\n";
$moderationRoutes = [
    'admin.media.approve',
    'admin.media.reject', 
    'admin.media.publish',
    'admin.media.unpublish'
];

foreach ($moderationRoutes as $routeName) {
    if (Route::has($routeName)) {
        $route = Route::getRoutes()->getByName($routeName);
        echo "✅ {$routeName}: " . $route->uri() . " [" . implode('|', $route->methods()) . "]\n";
    } else {
        echo "❌ {$routeName}: Route manquante\n";
    }
}

// 2. Tester une requête de modération simulée
echo "\n=== TEST DE REQUÊTE AJAX SIMULÉE ===\n";

$user = User::where('email', 'admin@ucbc.org')->first();
$media = Media::first();

if ($user && $media) {
    echo "✅ Utilisateur: {$user->name}\n";
    echo "✅ Média: {$media->titre} (Status: {$media->status})\n";
    
    // Simuler une requête
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    
    try {
        // Test du contrôleur directement
        $controller = new App\Http\Controllers\Admin\MediaController();
        
        // Créer une fausse requête
        $request = new Illuminate\Http\Request();
        $request->merge(['comment' => 'Test de modération']);
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        
        // Authentifier l'utilisateur
        auth()->login($user);
        
        echo "\n--- Test approve() ---\n";
        try {
            $response = $controller->approve($media);
            echo "✅ approve() fonctionne: " . $response->getContent() . "\n";
        } catch (Exception $e) {
            echo "❌ approve() erreur: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Utilisateur ou média introuvable\n";
}

// 3. Vérifier les logs Laravel
echo "\n=== VÉRIFICATION DES LOGS ===\n";
$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath)) {
    $logs = file_get_contents($logPath);
    $recentLogs = array_slice(explode("\n", $logs), -50); // 50 dernières lignes
    
    $errorLines = array_filter($recentLogs, function($line) {
        return strpos($line, 'ERROR') !== false || strpos($line, 'media') !== false;
    });
    
    if (!empty($errorLines)) {
        echo "Erreurs récentes trouvées:\n";
        foreach (array_slice($errorLines, -5) as $error) {
            echo "- " . trim($error) . "\n";
        }
    } else {
        echo "✅ Aucune erreur récente dans les logs\n";
    }
} else {
    echo "❌ Fichier de log introuvable: {$logPath}\n";
}

// 4. Vérifier le token CSRF
echo "\n=== VÉRIFICATION CSRF ===\n";
try {
    $token = csrf_token();
    echo "✅ Token CSRF généré: " . substr($token, 0, 10) . "...\n";
} catch (Exception $e) {
    echo "❌ Erreur token CSRF: " . $e->getMessage() . "\n";
}

// 5. Vérifier les constantes du modèle Media
echo "\n=== VÉRIFICATION CONSTANTES MEDIA ===\n";
$constants = [
    'STATUS_PENDING' => Media::STATUS_PENDING ?? 'NON_DÉFINIE',
    'STATUS_APPROVED' => Media::STATUS_APPROVED ?? 'NON_DÉFINIE',
    'STATUS_PUBLISHED' => Media::STATUS_PUBLISHED ?? 'NON_DÉFINIE',
    'STATUS_REJECTED' => Media::STATUS_REJECTED ?? 'NON_DÉFINIE',
];

foreach ($constants as $name => $value) {
    $status = $value !== 'NON_DÉFINIE' ? '✅' : '❌';
    echo "{$status} {$name}: '{$value}'\n";
}

echo "\n=== RECOMMANDATIONS DE DÉBOGAGE ===\n";
echo "1. Ouvrez les outils de développement (F12) dans votre navigateur\n";
echo "2. Allez dans l'onglet Console pour voir les erreurs JavaScript\n";
echo "3. Allez dans l'onglet Network pour voir les requêtes AJAX\n";
echo "4. Cliquez sur un bouton de modération et vérifiez:\n";
echo "   - La requête est-elle envoyée ?\n";
echo "   - Quel est le code de réponse HTTP ?\n";
echo "   - Quel est le contenu de la réponse ?\n";
echo "5. Vérifiez les logs Laravel: tail -f storage/logs/laravel.log\n";
