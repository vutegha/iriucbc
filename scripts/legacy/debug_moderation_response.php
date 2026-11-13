<?php
/**
 * Script de diagnostic pour capturer la réponse exacte de modération
 * Simule une requête AJAX de modération pour voir la réponse serveur
 */

require_once 'vendor/autoload.php';

// Configuration
$baseUrl = 'http://localhost/projets/iriucbc';
$mediaId = 1; // ID d'un média existant
$action = 'approve'; // Action à tester

echo "=== DIAGNOSTIC DE MODÉRATION - CAPTURE DE RÉPONSE ===\n\n";

// URLs à tester
$urls = [
    'approve' => "$baseUrl/admin/media/$mediaId/approve",
    'reject' => "$baseUrl/admin/media/$mediaId/reject",
    'publish' => "$baseUrl/admin/media/$mediaId/publish",
    'unpublish' => "$baseUrl/admin/media/$mediaId/unpublish"
];

foreach ($urls as $action => $url) {
    echo "--- Test de $action ---\n";
    echo "URL: $url\n";
    
    // Configuration cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'X-Requested-With: XMLHttpRequest'
        ],
        CURLOPT_POSTFIELDS => http_build_query([
            '_token' => 'test-token', // Token fictif pour le test
            '_method' => 'POST'
        ])
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "Code HTTP: $httpCode\n";
    echo "Content-Type: $contentType\n";
    
    if ($error) {
        echo "Erreur cURL: $error\n";
    }
    
    echo "Réponse (premiers 500 caractères):\n";
    echo "--- DÉBUT RÉPONSE ---\n";
    echo substr($response, 0, 500) . (strlen($response) > 500 ? '...[TRONQUÉ]' : '');
    echo "\n--- FIN RÉPONSE ---\n\n";
    
    // Analyse de la réponse
    if (strpos($response, '<!DOCTYPE') === 0 || strpos($response, '<html') === 0) {
        echo "🚨 PROBLÈME: La réponse est du HTML, pas du JSON!\n";
        
        // Chercher des indices d'erreur dans le HTML
        if (preg_match('/<title>(.*?)<\/title>/i', $response, $matches)) {
            echo "Titre de la page: " . $matches[1] . "\n";
        }
        
        if (strpos($response, '404') !== false) {
            echo "Erreur probable: Route non trouvée (404)\n";
        } elseif (strpos($response, '403') !== false) {
            echo "Erreur probable: Accès interdit (403)\n";
        } elseif (strpos($response, '500') !== false) {
            echo "Erreur probable: Erreur serveur (500)\n";
        } elseif (strpos($response, 'Method Not Allowed') !== false) {
            echo "Erreur probable: Méthode HTTP non autorisée\n";
        }
        
    } elseif (json_decode($response)) {
        echo "✅ Réponse JSON valide\n";
        $json = json_decode($response, true);
        echo "Contenu JSON: " . print_r($json, true) . "\n";
    } else {
        echo "🔍 Réponse ni HTML ni JSON valide\n";
    }
    
    echo str_repeat("=", 80) . "\n\n";
}

// Test des routes définies
echo "=== VÉRIFICATION DES ROUTES ===\n";
$routeFile = 'routes/web.php';
if (file_exists($routeFile)) {
    $routes = file_get_contents($routeFile);
    
    $moderationRoutes = [
        'approve' => '/approve',
        'reject' => '/reject', 
        'publish' => '/publish',
        'unpublish' => '/unpublish'
    ];
    
    foreach ($moderationRoutes as $action => $route) {
        if (strpos($routes, $route) !== false) {
            echo "✅ Route $action trouvée\n";
        } else {
            echo "❌ Route $action non trouvée\n";
        }
    }
} else {
    echo "❌ Fichier routes/web.php non trouvé\n";
}

echo "\n=== RECOMMANDATIONS ===\n";
echo "1. Vérifiez que les routes de modération sont bien définies\n";
echo "2. Assurez-vous que le MediaController a les méthodes requises\n";
echo "3. Vérifiez que les permissions sont correctes\n";
echo "4. Testez avec un token CSRF valide\n";
echo "5. Vérifiez les logs Laravel pour plus de détails\n";

?>
