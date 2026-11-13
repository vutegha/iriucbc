<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST SIMULATION APPEL HTTP PUBLISH ===\n\n";

try {
    // 1. Créer une publication non publiée
    $publication = \App\Models\Publication::create([
        'titre' => 'Test HTTP Publish - ' . now()->format('Y-m-d H:i:s'),
        'slug' => 'test-http-publish-' . time(),
        'resume' => 'Publication pour test HTTP.',
        'en_vedette' => true,
        'is_published' => false,
        'user_id' => 1
    ]);
    
    echo "✅ Publication créée (ID: {$publication->id})\n";
    echo "   - Titre: {$publication->titre}\n";
    echo "   - En vedette: " . ($publication->en_vedette ? 'OUI' : 'NON') . "\n\n";

    // 2. Jobs avant
    $jobsBefore = DB::table('jobs')->count();
    echo "📋 Jobs avant: $jobsBefore\n\n";

    // 3. Simuler l'appel HTTP comme le ferait l'interface admin
    echo "🌐 Simulation appel HTTP POST publish...\n";
    
    // URL complète de la route
    $url = route('admin.publication.publish', $publication);
    echo "URL: $url\n";
    
    // Utilisateur admin pour les permissions
    $user = \App\Models\User::first();
    
    // Simuler une requête HTTP avec Illuminate\Http\Request
    $request = \Illuminate\Http\Request::create(
        $url,
        'POST',
        ['comment' => 'Test depuis simulation HTTP'],
        [], // cookies
        [], // files
        [   // server params
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'HTTP_X_CSRF_TOKEN' => csrf_token()
        ]
    );
    
    // Simuler l'authentification
    auth()->login($user);
    
    // Instancier le controller
    $controller = new \App\Http\Controllers\Admin\PublicationController();
    
    // Appeler directement la méthode publish
    echo "📞 Appel de la méthode publish()...\n";
    $response = $controller->publish($request, $publication);
    
    echo "✅ Méthode publish() exécutée\n";
    echo "Type de réponse: " . get_class($response) . "\n";
    
    if (method_exists($response, 'getData')) {
        $data = $response->getData(true);
        echo "Contenu réponse: " . json_encode($data) . "\n";
    }

    // 4. Vérifier les résultats
    sleep(2);
    $jobsAfter = DB::table('jobs')->count();
    echo "\n📋 Jobs après: $jobsAfter\n";
    echo "📧 Nouveaux jobs: " . ($jobsAfter - $jobsBefore) . "\n";
    
    // Vérifier l'état de la publication
    $publication = $publication->fresh();
    echo "\n📖 État publication après:\n";
    echo "   - is_published: " . ($publication->is_published ? 'OUI' : 'NON') . "\n";
    echo "   - published_at: " . ($publication->published_at ? $publication->published_at->format('Y-m-d H:i:s') : 'null') . "\n";
    echo "   - published_by: " . ($publication->published_by ?: 'null') . "\n";

    if ($jobsAfter > $jobsBefore) {
        echo "\n🎉 SUCCÈS! L'appel HTTP fonctionne correctement\n";
        echo "Le problème doit venir de l'interface JavaScript ou des routes.\n\n";
        
        echo "📝 Prochaines étapes:\n";
        echo "1. Vérifier que la route admin.publication.publish existe\n";
        echo "2. Vérifier les logs Laravel: tail -f storage/logs/laravel.log\n";
        echo "3. Tester depuis l'interface admin avec F12 > Network ouvert\n";
    } else {
        echo "\n❌ Même l'appel HTTP direct ne fonctionne pas\n";
        echo "Le problème est dans le controller ou les listeners.\n";
    }

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN TEST HTTP ===\n";
