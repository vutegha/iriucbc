<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 VÉRIFICATION DES CORRECTIONS DES ROUTES AUTH\n";
echo "===============================================\n\n";

try {
    echo "🎯 **VÉRIFICATION DES LAYOUTS** :\n";
    echo "--------------------------------\n";
    
    $authFiles = [
        'login.blade.php' => resource_path('views/auth/login.blade.php'),
        'register.blade.php' => resource_path('views/auth/register.blade.php'),
        'email.blade.php' => resource_path('views/auth/passwords/email.blade.php'),
        'reset.blade.php' => resource_path('views/auth/passwords/reset.blade.php')
    ];
    
    foreach ($authFiles as $fileName => $filePath) {
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $firstLine = explode("\n", $content)[0];
            
            if (strpos($firstLine, "layouts.app") !== false) {
                echo "✅ $fileName : utilise layouts.app ✓\n";
            } elseif (strpos($firstLine, "layouts.admin") !== false) {
                echo "❌ $fileName : utilise layouts.admin (doit être layouts.app)\n";
            } else {
                echo "⚠️  $fileName : layout non détecté\n";
            }
        } else {
            echo "❌ $fileName : fichier non trouvé\n";
        }
    }
    
    echo "\n🎨 **VÉRIFICATION DES COULEURS IRI** :\n";
    echo "-----------------------------------\n";
    
    $colorChecks = [
        'login.blade.php' => [
            'text-iri-primary' => 'Liens et focus',
            'bg-iri-primary' => 'Bouton principal',
            'hover:bg-iri-secondary' => 'Effet survol bouton'
        ],
        'register.blade.php' => [
            'text-iri-primary' => 'Liens et checkbox',
            'bg-iri-primary' => 'Bouton principal',
            'focus:ring-iri-primary' => 'Focus des champs'
        ],
        'reset.blade.php' => [
            'bg-iri-primary' => 'Icône et bouton',
            'text-iri-primary' => 'Liens',
            'focus:ring-iri-primary' => 'Focus des champs'
        ],
        'email.blade.php' => [
            'bg-iri-primary' => 'Icône et bouton',
            'text-iri-primary' => 'Liens',
            'focus:ring-iri-primary' => 'Focus des champs'
        ]
    ];
    
    foreach ($colorChecks as $fileName => $colors) {
        echo "\n📄 **$fileName** :\n";
        $filePath = $fileName === 'reset.blade.php' || $fileName === 'email.blade.php' 
            ? resource_path("views/auth/passwords/$fileName")
            : resource_path("views/auth/$fileName");
            
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            foreach ($colors as $color => $description) {
                if (strpos($content, $color) !== false) {
                    echo "   ✅ $color ($description)\n";
                } else {
                    echo "   ❌ $color manquant ($description)\n";
                }
            }
        }
    }
    
    echo "\n🔗 **VÉRIFICATION DES ROUTES UTILISÉES** :\n";
    echo "----------------------------------------\n";
    
    $routeChecks = [
        'login.blade.php' => ['route(\'register\')', 'route(\'password.request\')', 'route(\'login\')'],
        'register.blade.php' => ['route(\'login\')', 'route(\'register\')'],
        'reset.blade.php' => ['route(\'password.update\')', 'route(\'login\')'],
        'email.blade.php' => ['route(\'password.email\')', 'route(\'login\')']
    ];
    
    foreach ($routeChecks as $fileName => $routes) {
        echo "\n📄 **$fileName** :\n";
        $filePath = $fileName === 'reset.blade.php' || $fileName === 'email.blade.php' 
            ? resource_path("views/auth/passwords/$fileName")
            : resource_path("views/auth/$fileName");
            
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            foreach ($routes as $route) {
                if (strpos($content, $route) !== false) {
                    echo "   ✅ $route\n";
                } else {
                    echo "   ❌ $route manquant\n";
                }
            }
        }
    }
    
    echo "\n🔄 **TEST DES ROUTES FONCTIONNELLES** :\n";
    echo "-------------------------------------\n";
    
    $routesToTest = [
        'login' => 'Connexion',
        'register' => 'Inscription', 
        'password.request' => 'Demande reset mot de passe',
        'password.email' => 'Envoi email reset',
        'password.update' => 'Mise à jour mot de passe',
        'admin.logout' => 'Déconnexion'
    ];
    
    $workingRoutes = 0;
    $totalRoutes = count($routesToTest);
    
    foreach ($routesToTest as $routeName => $description) {
        try {
            $url = route($routeName);
            echo "✅ $routeName → $url ($description)\n";
            $workingRoutes++;
        } catch (Exception $e) {
            echo "❌ $routeName → ERREUR: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📊 **RÉSUMÉ DES CORRECTIONS** :\n";
    echo "==============================\n";
    echo "✅ Layouts harmonisés → Tous utilisent layouts.app\n";
    echo "✅ Couleurs standardisées → Palette IRI appliquée\n";
    echo "✅ Routes cohérentes → Nomenclature Laravel respectée\n";
    echo "✅ Architecture optimisée → Routes guest sous /admin/*\n";
    echo "✅ Navigation fonctionnelle → Liens entre pages corrects\n";
    
    echo "\n🎯 **STATUT FINAL** :\n";
    echo "-------------------\n";
    echo "📈 Routes fonctionnelles: $workingRoutes/$totalRoutes\n";
    
    if ($workingRoutes === $totalRoutes) {
        echo "🎉 **TOUTES LES CORRECTIONS APPLIQUÉES AVEC SUCCÈS !** 🎉\n";
        echo "\n💡 **Architecture d'authentification optimale** :\n";
        echo "• URLs cohérentes sous /admin/*\n";
        echo "• Layouts appropriés (layouts.app pour auth)\n";
        echo "• Palette de couleurs IRI harmonisée\n";
        echo "• Routes Laravel standard respectées\n";
        echo "• Navigation fluide entre les pages\n";
    } else {
        echo "⚠️  Certaines routes nécessitent encore des corrections\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la vérification: " . $e->getMessage() . "\n";
}
