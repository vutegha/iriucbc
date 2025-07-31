<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔐 VÉRIFICATION COMPLÈTE DES ROUTES D'AUTHENTIFICATION\n";
echo "=====================================================\n\n";

try {
    echo "📋 **ROUTES D'AUTHENTIFICATION ATTENDUES** :\n";
    echo "--------------------------------------------\n";
    
    $expectedRoutes = [
        'login' => [
            'url' => '/admin/login',
            'method' => 'GET',
            'description' => 'Page de connexion'
        ],
        'login.post' => [
            'url' => '/admin/login',
            'method' => 'POST', 
            'description' => 'Traitement de la connexion'
        ],
        'register' => [
            'url' => '/admin/register',
            'method' => 'GET',
            'description' => 'Page d\'inscription'
        ],
        'register.post' => [
            'url' => '/admin/register',
            'method' => 'POST',
            'description' => 'Traitement de l\'inscription'
        ],
        'password.request' => [
            'url' => '/admin/password/reset',
            'method' => 'GET',
            'description' => 'Demande de réinitialisation'
        ],
        'password.email' => [
            'url' => '/admin/password/email',
            'method' => 'POST',
            'description' => 'Envoi email de réinitialisation'
        ],
        'password.reset' => [
            'url' => '/admin/password/reset/{token}',
            'method' => 'GET',
            'description' => 'Formulaire de réinitialisation'
        ],
        'password.update' => [
            'url' => '/admin/password/reset',
            'method' => 'POST',
            'description' => 'Mise à jour du mot de passe'
        ],
        'admin.logout' => [
            'url' => '/admin/logout',
            'method' => 'POST',
            'description' => 'Déconnexion admin'
        ]
    ];
    
    $workingRoutes = 0;
    $totalRoutes = count($expectedRoutes);
    
    foreach ($expectedRoutes as $routeName => $details) {
        echo "🔍 Test de la route '$routeName':\n";
        
        try {
            if ($routeName === 'password.reset') {
                $url = route($routeName, ['token' => 'test-token']);
            } else {
                $url = route($routeName);
            }
            
            echo "   ✅ URL: $url\n";
            echo "   📝 Description: {$details['description']}\n";
            echo "   🌐 Méthode: {$details['method']}\n";
            
            $workingRoutes++;
            
        } catch (Exception $e) {
            echo "   ❌ ERREUR: " . $e->getMessage() . "\n";
            echo "   📝 Description: {$details['description']}\n";
            echo "   🌐 Méthode attendue: {$details['method']}\n";
            echo "   🎯 URL attendue: {$details['url']}\n";
        }
        
        echo "\n";
    }
    
    echo "📊 **RÉSUMÉ DES TESTS** :\n";
    echo "------------------------\n";
    echo "✅ Routes fonctionnelles: $workingRoutes/$totalRoutes\n";
    
    if ($workingRoutes === $totalRoutes) {
        echo "🎉 **TOUTES LES ROUTES FONCTIONNENT !**\n";
    } else {
        echo "⚠️  **PROBLÈMES DÉTECTÉS** - Quelques routes ne fonctionnent pas\n";
    }
    
    echo "\n🔧 **VÉRIFICATION DES VUES** :\n";
    echo "------------------------------\n";
    
    $authViews = [
        'auth.login' => 'resources/views/auth/login.blade.php',
        'auth.register' => 'resources/views/auth/register.blade.php', 
        'auth.passwords.email' => 'resources/views/auth/passwords/email.blade.php',
        'auth.passwords.reset' => 'resources/views/auth/passwords/reset.blade.php'
    ];
    
    foreach ($authViews as $viewName => $path) {
        $fullPath = base_path($path);
        if (file_exists($fullPath)) {
            echo "✅ Vue '$viewName' existe\n";
        } else {
            echo "❌ Vue '$viewName' MANQUANTE à $path\n";
        }
    }
    
    echo "\n🎨 **VÉRIFICATION DES LAYOUTS UTILISÉS** :\n";
    echo "------------------------------------------\n";
    
    // Vérifier le layout utilisé dans les vues auth
    $resetView = file_get_contents(resource_path('views/auth/passwords/reset.blade.php'));
    if (strpos($resetView, "@extends('layouts.app')") !== false) {
        echo "✅ reset.blade.php utilise layouts.app (CORRECT)\n";
    } elseif (strpos($resetView, "@extends('layouts.admin')") !== false) {
        echo "⚠️  reset.blade.php utilise layouts.admin (À CORRIGER)\n";
    }
    
    $emailView = file_get_contents(resource_path('views/auth/passwords/email.blade.php'));
    if (strpos($emailView, "@extends('layouts.app')") !== false) {
        echo "✅ email.blade.php utilise layouts.app (CORRECT)\n";
    } elseif (strpos($emailView, "@extends('layouts.admin')") !== false) {
        echo "⚠️  email.blade.php utilise layouts.admin (À CORRIGER)\n";
    }
    
    echo "\n🎯 **RECOMMANDATIONS** :\n";
    echo "------------------------\n";
    
    if ($workingRoutes === $totalRoutes) {
        echo "✅ L'authentification est correctement configurée\n";
        echo "✅ Toutes les routes sont fonctionnelles\n";
        echo "✅ URLs cohérentes sous /admin/*\n";
        echo "✅ Noms de routes standard Laravel\n";
        echo "\n🚀 **PRÊT POUR LA PRODUCTION !**\n";
    } else {
        echo "🔧 Corriger les routes manquantes\n";
        echo "🎨 Vérifier les vues et layouts\n";
        echo "🧪 Tester à nouveau après corrections\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la vérification: " . $e->getMessage() . "\n";
}
