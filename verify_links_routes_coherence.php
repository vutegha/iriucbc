<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔗 VÉRIFICATION DE COHÉRENCE: LIENS VS ROUTES\n";
echo "============================================\n\n";

try {
    echo "📋 **ANALYSE DES ROUTES DÉFINIES** :\n";
    echo "----------------------------------\n";
    
    // Récupérer toutes les routes auth définies
    $expectedRoutes = [
        'login' => 'Page de connexion (GET)',
        'login.post' => 'Traitement connexion (POST)',
        'register' => 'Page d\'inscription (GET)',
        'register.post' => 'Traitement inscription (POST)',
        'password.request' => 'Demande reset password (GET)',
        'password.email' => 'Envoi email reset (POST)',
        'password.reset' => 'Formulaire reset (GET)',
        'password.update' => 'Mise à jour password (POST)',
        'admin.logout' => 'Déconnexion (POST)'
    ];
    
    $routeUrls = [];
    $workingRoutes = 0;
    
    foreach ($expectedRoutes as $routeName => $description) {
        try {
            $url = route($routeName);
            $routeUrls[$routeName] = $url;
            echo "✅ $routeName → $url ($description)\n";
            $workingRoutes++;
        } catch (Exception $e) {
            echo "❌ $routeName → ERREUR: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📄 **ANALYSE DES LIENS DANS LES VUES** :\n";
    echo "--------------------------------------\n";
    
    // Analyser chaque vue d'authentification
    $authViews = [
        'login' => [
            'file' => resource_path('views/auth/login.blade.php'),
            'expectedLinks' => [
                'route(\'register\')' => 'Lien vers inscription',
                'route(\'password.request\')' => 'Lien mot de passe oublié',
                'route(\'login\')' => 'Action du formulaire'
            ]
        ],
        'register' => [
            'file' => resource_path('views/auth/register.blade.php'),
            'expectedLinks' => [
                'route(\'login\')' => 'Lien retour connexion',
                'route(\'register\')' => 'Action du formulaire'
            ]
        ],
        'password.email' => [
            'file' => resource_path('views/auth/passwords/email.blade.php'),
            'expectedLinks' => [
                'route(\'password.email\')' => 'Action du formulaire',
                'route(\'login\')' => 'Lien retour connexion'
            ]
        ],
        'password.reset' => [
            'file' => resource_path('views/auth/passwords/reset.blade.php'),
            'expectedLinks' => [
                'route(\'password.update\')' => 'Action du formulaire',
                'route(\'login\')' => 'Lien retour connexion'
            ]
        ]
    ];
    
    $totalLinks = 0;
    $workingLinks = 0;
    $issues = [];
    
    foreach ($authViews as $viewName => $viewInfo) {
        echo "\n🔍 **Analyse de $viewName.blade.php** :\n";
        
        if (!file_exists($viewInfo['file'])) {
            echo "   ❌ Fichier non trouvé: " . $viewInfo['file'] . "\n";
            continue;
        }
        
        $content = file_get_contents($viewInfo['file']);
        
        foreach ($viewInfo['expectedLinks'] as $routeCall => $description) {
            $totalLinks++;
            
            if (strpos($content, $routeCall) !== false) {
                echo "   ✅ $routeCall ($description)\n";
                $workingLinks++;
                
                // Vérifier que la route existe réellement
                $routeName = str_replace(['route(\'', '\')'], '', $routeCall);
                if (!isset($routeUrls[$routeName])) {
                    $issues[] = "Vue $viewName utilise '$routeName' mais route non définie";
                }
            } else {
                echo "   ❌ $routeCall MANQUANT ($description)\n";
                $issues[] = "Vue $viewName: lien '$routeCall' manquant";
            }
        }
    }
    
    echo "\n🔄 **VÉRIFICATION DE COHÉRENCE CROISÉE** :\n";
    echo "------------------------------------------\n";
    
    // Vérifier les liens bidirectionnels
    $crossChecks = [
        'login → register' => [
            'from' => 'login.blade.php',
            'to' => 'register',
            'description' => 'Lien inscription depuis connexion'
        ],
        'register → login' => [
            'from' => 'register.blade.php', 
            'to' => 'login',
            'description' => 'Lien connexion depuis inscription'
        ],
        'login → password.request' => [
            'from' => 'login.blade.php',
            'to' => 'password.request',
            'description' => 'Lien mot de passe oublié'
        ],
        'password.email → login' => [
            'from' => 'email.blade.php',
            'to' => 'login', 
            'description' => 'Retour connexion depuis reset email'
        ],
        'password.reset → login' => [
            'from' => 'reset.blade.php',
            'to' => 'login',
            'description' => 'Retour connexion depuis reset form'
        ]
    ];
    
    foreach ($crossChecks as $checkName => $checkInfo) {
        $fromFile = str_contains($checkInfo['from'], 'password') 
            ? resource_path("views/auth/passwords/{$checkInfo['from']}")
            : resource_path("views/auth/{$checkInfo['from']}");
            
        if (file_exists($fromFile)) {
            $content = file_get_contents($fromFile);
            $routeCall = "route('{$checkInfo['to']}')";
            
            if (strpos($content, $routeCall) !== false) {
                echo "✅ $checkName : {$checkInfo['description']}\n";
            } else {
                echo "❌ $checkName : LIEN MANQUANT\n";
                $issues[] = $checkInfo['description'] . " manquant";
            }
        } else {
            echo "⚠️  $checkName : Fichier source non trouvé\n";
        }
    }
    
    echo "\n🎯 **VÉRIFICATION DES ACTIONS DE FORMULAIRES** :\n";
    echo "-----------------------------------------------\n";
    
    // Vérifier les actions des formulaires
    $formActions = [
        'login.blade.php' => [
            'expected' => 'route(\'login\')',
            'method' => 'POST'
        ],
        'register.blade.php' => [
            'expected' => 'route(\'register\')',
            'method' => 'POST'
        ],
        'email.blade.php' => [
            'expected' => 'route(\'password.email\')',
            'method' => 'POST'
        ],
        'reset.blade.php' => [
            'expected' => 'route(\'password.update\')',
            'method' => 'POST'
        ]
    ];
    
    foreach ($formActions as $fileName => $actionInfo) {
        $filePath = str_contains($fileName, 'email') || str_contains($fileName, 'reset')
            ? resource_path("views/auth/passwords/$fileName")
            : resource_path("views/auth/$fileName");
            
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            
            // Rechercher l'action du formulaire
            if (preg_match('/action="\{\{\s*(' . preg_quote($actionInfo['expected'], '/') . ')\s*\}\}"/', $content)) {
                echo "✅ $fileName : Action formulaire correcte ({$actionInfo['expected']})\n";
            } else {
                echo "❌ $fileName : Action formulaire incorrecte ou manquante\n";
                $issues[] = "$fileName: action formulaire incorrecte";
            }
            
            // Vérifier la méthode
            if (strpos($content, 'method="POST"') !== false || strpos($content, "method='POST'") !== false) {
                echo "   ✅ Méthode POST définie\n";
            } else {
                echo "   ⚠️  Méthode POST non trouvée\n";
            }
            
            // Vérifier le token CSRF
            if (strpos($content, '@csrf') !== false) {
                echo "   ✅ Token CSRF présent\n";
            } else {
                echo "   ❌ Token CSRF manquant\n";
                $issues[] = "$fileName: token CSRF manquant";
            }
        }
    }
    
    echo "\n📊 **RÉSULTATS DE COHÉRENCE** :\n";
    echo "------------------------------\n";
    echo "🔗 Liens fonctionnels: $workingLinks/$totalLinks\n";
    echo "🛣️  Routes définies: $workingRoutes/" . count($expectedRoutes) . "\n";
    echo "⚠️  Problèmes détectés: " . count($issues) . "\n";
    
    if (count($issues) > 0) {
        echo "\n🚨 **PROBLÈMES DÉTECTÉS** :\n";
        echo "-------------------------\n";
        foreach ($issues as $issue) {
            echo "• $issue\n";
        }
    }
    
    echo "\n🎯 **TAUX DE COHÉRENCE** :\n";
    echo "------------------------\n";
    $coherenceRate = $totalLinks > 0 ? round(($workingLinks / $totalLinks) * 100, 1) : 0;
    echo "📈 Cohérence globale: $coherenceRate%\n";
    
    if ($coherenceRate >= 95) {
        echo "🎉 **EXCELLENTE COHÉRENCE !** 🎉\n";
    } elseif ($coherenceRate >= 80) {
        echo "✅ **BONNE COHÉRENCE** ✅\n";
    } else {
        echo "⚠️  **COHÉRENCE À AMÉLIORER** ⚠️\n";
    }
    
    echo "\n🏆 **RECOMMANDATIONS** :\n";
    echo "----------------------\n";
    
    if (count($issues) === 0) {
        echo "🎊 **PARFAIT !** Tous les liens sont cohérents avec les routes définies.\n";
        echo "✅ Navigation fluide garantie\n";
        echo "✅ Expérience utilisateur optimale\n";
        echo "✅ Architecture d'authentification robuste\n";
    } else {
        echo "🔧 **Actions recommandées** :\n";
        foreach ($issues as $issue) {
            echo "• Corriger: $issue\n";
        }
    }
    
    echo "\n🎯 **STATUT FINAL** :\n";
    echo "-------------------\n";
    if ($coherenceRate >= 95 && count($issues) <= 1) {
        echo "🚀 **SYSTÈME PRÊT POUR LA PRODUCTION !** 🚀\n";
    } else {
        echo "🔧 **CORRECTIONS MINEURES NÉCESSAIRES** 🔧\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la vérification: " . $e->getMessage() . "\n";
}
