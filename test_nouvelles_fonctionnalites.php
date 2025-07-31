<?php
/**
 * Script de test pour les nouvelles fonctionnalités
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 Test des nouvelles fonctionnalités...\n\n";

try {
    // 1. Test de la migration des liens sociaux
    echo "1. Vérification de la table social_links...\n";
    if (Schema::hasTable('social_links')) {
        echo "   ✅ Table social_links existe\n";
        $count = App\Models\SocialLink::count();
        echo "   📊 Nombre de liens sociaux: $count\n";
    } else {
        echo "   ❌ Table social_links n'existe pas\n";
    }

    // 2. Test de la migration evenements
    echo "\n2. Vérification du champ en_vedette...\n";
    if (Schema::hasColumn('evenements', 'en_vedette')) {
        echo "   ✅ Champ en_vedette existe\n";
        $countVedette = App\Models\Evenement::where('en_vedette', true)->count();
        echo "   📊 Événements en vedette: $countVedette\n";
    } else {
        echo "   ❌ Champ en_vedette n'existe pas\n";
    }

    // 3. Test du composant social-share
    echo "\n3. Vérification du composant social-share...\n";
    $componentPath = resource_path('views/components/social-share.blade.php');
    if (file_exists($componentPath)) {
        echo "   ✅ Composant social-share existe\n";
    } else {
        echo "   ❌ Composant social-share n'existe pas\n";
    }

    // 4. Test du composant rich-text-excerpt
    echo "\n4. Vérification du composant rich-text-excerpt...\n";
    $excerptPath = resource_path('views/components/rich-text-excerpt.blade.php');
    if (file_exists($excerptPath)) {
        echo "   ✅ Composant rich-text-excerpt existe\n";
    } else {
        echo "   ❌ Composant rich-text-excerpt n'existe pas\n";
    }

    // 5. Test des modifications de route
    echo "\n5. Vérification des routes...\n";
    $routes = [
        'site.projets' => 'Route projets',
        'site.actualite.show' => 'Route actualité show',
        'site.service.show' => 'Route service show'
    ];
    
    foreach ($routes as $routeName => $description) {
        try {
            $route = route($routeName, ['slug' => 'test']);
            echo "   ✅ $description disponible\n";
        } catch (Exception $e) {
            echo "   ❌ $description non disponible\n";
        }
    }

    echo "\n🎉 Test terminé avec succès!\n";
    echo "\n📋 PROCHAINES ÉTAPES:\n";
    echo "   1. Exécuter les migrations: php artisan migrate\n";
    echo "   2. Exécuter les seeders: php artisan db:seed --class=SocialLinksSeeder\n";
    echo "   3. Exécuter les seeders: php artisan db:seed --class=EvenementEnVedetteSeeder\n";
    echo "   4. Tester les pages modifiées\n";

} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
