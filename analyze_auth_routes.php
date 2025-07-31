<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 RÉVISION DU FICHIER WEB.PHP - ROUTES AUTH\n";
echo "============================================\n\n";

try {
    $webRoutesPath = base_path('routes/web.php');
    $content = file_get_contents($webRoutesPath);
    
    echo "📋 **ANALYSE ACTUELLE DES ROUTES AUTH** :\n";
    echo "---------------------------------------\n";
    
    // Vérifier la structure des routes auth
    $authRoutes = [
        'login (GET)' => [
            'pattern' => "/Route::get\('\/login'/",
            'name' => 'login',
            'url' => '/login'
        ],
        'login (POST)' => [
            'pattern' => "/Route::post\('\/login'/",
            'name' => 'login.post', 
            'url' => '/login'
        ],
        'register (GET)' => [
            'pattern' => "/Route::get\('\/register'/",
            'name' => 'register',
            'url' => '/register'
        ],
        'register (POST)' => [
            'pattern' => "/Route::post\('\/register'/",
            'name' => 'register.post',
            'url' => '/register'
        ],
        'password.request' => [
            'pattern' => "/Route::get\('\/password\/reset'/",
            'name' => 'password.request',
            'url' => '/password/reset'
        ],
        'password.email' => [
            'pattern' => "/Route::post\('\/password\/email'/",
            'name' => 'password.email',
            'url' => '/password/email'
        ],
        'password.reset' => [
            'pattern' => "/Route::get\('\/password\/reset\/\{token\}'/",
            'name' => 'password.reset',
            'url' => '/password/reset/{token}'
        ],
        'password.update' => [
            'pattern' => "/Route::post\('\/password\/reset'/",
            'name' => 'password.update',
            'url' => '/password/reset'
        ],
        'logout' => [
            'pattern' => "/Route::post\('\/logout'/",
            'name' => 'logout',
            'url' => '/logout'
        ]
    ];
    
    $foundRoutes = 0;
    foreach ($authRoutes as $routeName => $routeInfo) {
        if (preg_match($routeInfo['pattern'], $content)) {
            echo "✅ $routeName → " . $routeInfo['url'] . " (name: " . $routeInfo['name'] . ")\n";
            $foundRoutes++;
        } else {
            echo "❌ $routeName → MANQUANT\n";
        }
    }
    
    echo "\n🎯 **VÉRIFICATION DES MIDDLEWARE** :\n";
    echo "----------------------------------\n";
    
    // Vérifier les middleware
    if (strpos($content, "Route::middleware(['guest'])") !== false) {
        echo "✅ Middleware 'guest' détecté pour les routes auth\n";
    } else {
        echo "❌ Middleware 'guest' manquant\n";
    }
    
    if (strpos($content, "Route::middleware(['auth'])") !== false) {
        echo "✅ Middleware 'auth' détecté pour logout\n";
    } else {
        echo "❌ Middleware 'auth' manquant\n";
    }
    
    echo "\n📊 **STRUCTURE DU FICHIER** :\n";
    echo "----------------------------\n";
    
    // Analyser la structure
    $sections = [
        'ROUTES D\'AUTHENTIFICATION' => 'Section auth trouvée',
        'ROUTES FRONTEND' => 'Section frontend trouvée',
        'ROUTES ADMIN' => 'Section admin trouvée'
    ];
    
    foreach ($sections as $section => $message) {
        if (strpos($content, $section) !== false) {
            echo "✅ $message\n";
        } else {
            echo "❌ Section '$section' manquante\n";
        }
    }
    
    echo "\n🔍 **PROBLÈMES POTENTIELS DÉTECTÉS** :\n";
    echo "------------------------------------\n";
    
    $issues = [];
    
    // Vérifier les doublons de commentaires
    if (substr_count($content, 'ROUTES D\'AUTHENTIFICATION') > 1) {
        $issues[] = "Commentaires de section dupliqués détectés";
    }
    
    // Vérifier l'ordre des routes
    $loginPos = strpos($content, "Route::get('/admin/login'");
    $adminPos = strpos($content, "Route::middleware(['auth'])->prefix('admin')");
    
    if ($loginPos !== false && $adminPos !== false && $loginPos > $adminPos) {
        $issues[] = "Routes auth après les routes admin (ordre incorrect)";
    }
    
    // Vérifier la cohérence des noms
    if (strpos($content, "->name('login')") === false) {
        $issues[] = "Route login sans nom 'login'";
    }
    
    if (count($issues) > 0) {
        foreach ($issues as $issue) {
            echo "⚠️  $issue\n";
        }
    } else {
        echo "✅ Aucun problème majeur détecté\n";
    }
    
    echo "\n🔧 **OPTIMISATIONS SUGGÉRÉES** :\n";
    echo "-------------------------------\n";
    
    // Suggérer des améliorations
    $suggestions = [];
    
    // Vérifier si on peut grouper les routes auth
    if (strpos($content, "Route::middleware(['guest'])") === false) {
        $suggestions[] = "Ajouter middleware guest pour les routes auth";
    }
    
    // Vérifier les commentaires
    if (strpos($content, '// Routes guest (authentification)') === false) {
        $suggestions[] = "Ajouter des commentaires explicites pour les sections";
    }
    
    if (count($suggestions) > 0) {
        foreach ($suggestions as $suggestion) {
            echo "💡 $suggestion\n";
        }
    } else {
        echo "✅ Structure optimale déjà en place\n";
    }
    
    echo "\n🎉 **RÉSUMÉ** :\n";
    echo "=============\n";
    echo "📈 Routes auth trouvées: $foundRoutes/9\n";
    echo "🎯 Architecture: " . ($foundRoutes === 9 ? "✅ Complète" : "⚠️  Incomplète") . "\n";
    echo "🔒 Sécurité: " . (strpos($content, "middleware(['guest'])") ? "✅ Middleware appropriés" : "❌ Middleware manquants") . "\n";
    echo "📝 Documentation: " . (strpos($content, 'ROUTES D\'AUTHENTIFICATION') ? "✅ Commentée" : "❌ Non commentée") . "\n";
    
    if ($foundRoutes === 9) {
        echo "\n🎊 **ROUTES AUTH PARFAITEMENT CONFIGURÉES !** 🎊\n";
        echo "\n🔗 **URLs d'accès** :\n";
        try {
            echo "• Connexion: " . route('login') . "\n";
            echo "• Inscription: " . route('register') . "\n";
            echo "• Mot de passe oublié: " . route('password.request') . "\n";
            echo "• Déconnexion: " . route('logout') . " (POST)\n";
        } catch (Exception $e) {
            echo "⚠️  Erreur lors de la génération des URLs: " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'analyse: " . $e->getMessage() . "\n";
}
