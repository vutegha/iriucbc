<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

echo "=== TEST DE MODÉRATION DIRECT ===\n\n";

// Authentifier l'utilisateur
$user = User::where('email', 'admin@ucbc.org')->first();
$media = Media::where('status', 'approved')->first();

if (!$user || !$media) {
    echo "❌ Utilisateur ou média introuvable\n";
    exit(1);
}

auth()->login($user);
echo "✅ Utilisateur authentifié: {$user->name}\n";
echo "✅ Média: {$media->titre} (Status: {$media->status})\n\n";

// Test direct du contrôleur
try {
    $controller = new App\Http\Controllers\Admin\MediaController();
    
    // Créer une requête simulée pour approve
    $request = new Request();
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    $request->merge(['comment' => 'Test de modération automatique']);
    
    echo "=== TEST APPROVE() ===\n";
    
    // Capturer les erreurs
    set_error_handler(function ($severity, $message, $file, $line) {
        echo "❌ PHP Error: {$message} in {$file}:{$line}\n";
    });
    
    try {
        $response = $controller->approve($media);
        
        if (is_object($response) && method_exists($response, 'getContent')) {
            $content = $response->getContent();
            echo "✅ Réponse: {$content}\n";
            
            // Décoder JSON si possible
            $data = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                echo "✅ JSON valide: " . ($data['success'] ? 'SUCCESS' : 'FAILURE') . "\n";
                if (isset($data['message'])) {
                    echo "✅ Message: {$data['message']}\n";
                }
            }
        } else {
            echo "✅ Réponse: " . var_export($response, true) . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "\n";
        echo "❌ Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo "❌ Trace: " . $e->getTraceAsString() . "\n";
    }
    
    restore_error_handler();
    
} catch (Exception $e) {
    echo "❌ Erreur fatale: " . $e->getMessage() . "\n";
}

// Vérifier l'état du média après
$media->refresh();
echo "\n=== ÉTAT APRÈS TEST ===\n";
echo "Status du média: {$media->status}\n";
echo "Modéré par: " . ($media->moderated_by ?: 'Non défini') . "\n";
echo "Modéré le: " . ($media->moderated_at ? $media->moderated_at->format('Y-m-d H:i:s') : 'Non défini') . "\n";

// Suggestion pour déboguer côté client
echo "\n=== DÉBOGAGE CÔTÉ CLIENT ===\n";
echo "1. Ouvrez les outils de développement (F12)\n";
echo "2. Allez dans l'onglet Network\n";
echo "3. Cliquez sur un bouton de modération\n";
echo "4. Cherchez la requête POST vers /admin/media/{$media->id}/approve\n";
echo "5. Vérifiez le code de statut HTTP (devrait être 200)\n";
echo "6. Vérifiez la réponse JSON\n";
echo "7. Si erreur 422, c'est un problème de validation\n";
echo "8. Si erreur 403, c'est un problème de permissions\n";
echo "9. Si erreur 500, c'est un problème serveur\n";
