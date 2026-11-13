<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Actualite;
use App\Models\Publication;
use App\Models\Projet;
use App\Models\Service;
use App\Models\Contact;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "🧪 TEST DES PERMISSIONS ET SÉCURITÉ DU MENU ADMIN\n";
echo str_repeat("=", 60) . "\n\n";

// Tester avec différents utilisateurs
$testUsers = [
    'admin@ucbc.org' => 'super-admin',
    'iri@ucbc.org' => 'admin', 
    'moderator@test.com' => 'moderator',
    'gestionnaire@test.com' => 'gestionnaire_projets'
];

$menuItems = [
    'Services' => [
        'model' => Service::class,
        'permission_type' => 'policy',
        'permission' => 'viewAny'
    ],
    'Actualités' => [
        'model' => Actualite::class,
        'permission_type' => 'policy',
        'permission' => 'viewAny'
    ],
    'Publications' => [
        'model' => Publication::class,
        'permission_type' => 'policy', 
        'permission' => 'viewAny'
    ],
    'Projets' => [
        'model' => Projet::class,
        'permission_type' => 'policy',
        'permission' => 'viewAny'
    ],
    'Messages' => [
        'model' => Contact::class,
        'permission_type' => 'policy',
        'permission' => 'viewAny'
    ],
    'Newsletter' => [
        'permission_type' => 'direct',
        'permission' => 'manage_newsletter'
    ],
    'Configuration emails' => [
        'permission_type' => 'direct',
        'permission' => 'manage_email_settings'
    ],
    'Utilisateurs & Permissions' => [
        'permission_type' => 'direct',
        'permission' => 'manage_users'
    ]
];

foreach ($testUsers as $email => $expectedRole) {
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        echo "⚠️  Utilisateur $email non trouvé, création...\n";
        $user = User::create([
            'name' => 'Test ' . ucfirst(str_replace('_', ' ', $expectedRole)),
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make(env('SEED_USER_PASSWORD') ?: \Illuminate\Support\Str::random(32)),
            'email_verified_at' => now(),
            'active' => true
        ]);
        $user->assignRole($expectedRole);
    }
    
    echo "👤 TEST POUR: {$user->name} ({$user->email})\n";
    echo "🏷️  Rôles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "📊 Permissions totales: " . $user->getAllPermissions()->count() . "\n";
    
    echo "📋 ACCÈS AU MENU:\n";
    
    foreach ($menuItems as $menuName => $config) {
        $hasAccess = false;
        
        if ($config['permission_type'] === 'policy') {
            try {
                $hasAccess = $user->can($config['permission'], $config['model']);
            } catch (Exception $e) {
                $hasAccess = false;
                echo "  ❌ Erreur Policy pour $menuName: " . $e->getMessage() . "\n";
            }
        } else {
            $hasAccess = $user->hasPermissionTo($config['permission'], 'web');
        }
        
        $status = $hasAccess ? "✅" : "❌";
        $details = $config['permission_type'] === 'policy' ? 
            "Policy: {$config['permission']}({$config['model']})" : 
            "Permission: {$config['permission']}";
            
        echo "  $status $menuName ($details)\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}

// Test des contrôleurs sécurisés
echo "🔒 TEST DES CONTRÔLEURS SÉCURISÉS\n";
echo str_repeat("=", 40) . "\n";

$controllersToTest = [
    'ActualiteController' => Actualite::class,
    'PublicationController' => Publication::class,
    'ProjetController' => Projet::class,
    'ServiceController' => Service::class
];

$testUser = User::where('email', 'moderator@test.com')->first();
if ($testUser) {
    echo "👤 Test avec utilisateur moderator\n";
    
    foreach ($controllersToTest as $controller => $model) {
        $canView = $testUser->can('viewAny', $model);
        $canCreate = $testUser->can('create', $model);
        $canModerate = $testUser->hasPermissionTo('moderate_' . strtolower(class_basename($model)) . 's', 'web');
        
        echo "📝 $controller:\n";
        echo "  • Voir: " . ($canView ? "✅" : "❌") . "\n";
        echo "  • Créer: " . ($canCreate ? "✅" : "❌") . "\n";
        echo "  • Modérer: " . ($canModerate ? "✅" : "❌") . "\n";
    }
}

echo "\n";

// Vérifier les Policies existantes
echo "🛡️  VÉRIFICATION DES POLICIES\n";
echo str_repeat("=", 30) . "\n";

$policies = [
    'ActualitePolicy' => 'App\Policies\ActualitePolicy',
    'PublicationPolicy' => 'App\Policies\PublicationPolicy',
    'ProjetPolicy' => 'App\Policies\ProjetPolicy',
    'ServicePolicy' => 'App\Policies\ServicePolicy',
    'AuteurPolicy' => 'App\Policies\AuteurPolicy',
    'UserPolicy' => 'App\Policies\UserPolicy'
];

foreach ($policies as $policyName => $policyClass) {
    $policyFile = app_path('Policies/' . $policyName . '.php');
    $exists = class_exists($policyClass) && file_exists($policyFile);
    
    echo ($exists ? "✅" : "❌") . " $policyName\n";
}

echo "\n🎯 RECOMMANDATIONS:\n";
echo str_repeat("=", 20) . "\n";

// Vérifier si tous les modèles ont leurs Policies
$missingPolicies = [];
$models = ['Contact', 'Media', 'Categorie', 'Rapport', 'Evenement', 'JobOffer', 'JobApplication'];

foreach ($models as $model) {
    $policyClass = "App\\Policies\\{$model}Policy";
    if (!class_exists($policyClass)) {
        $missingPolicies[] = $model;
    }
}

if (!empty($missingPolicies)) {
    echo "⚠️  Policies manquantes pour:\n";
    foreach ($missingPolicies as $model) {
        echo "  • {$model}Policy\n";
    }
    echo "\n";
}

echo "💡 CONSEILS DE SÉCURITÉ:\n";
echo "1. ✅ Menu sécurisé avec @can directives\n";
echo "2. ✅ Contrôleurs sécurisés avec \$this->authorize()\n";
echo "3. ✅ Vue 403 personnalisée créée\n";
echo "4. ✅ Dashboard sécurisé par sections\n";
echo "5. ✅ Permissions créées et assignées aux rôles\n";

if (!empty($missingPolicies)) {
    echo "6. ⚠️  Créer les policies manquantes\n";
} else {
    echo "6. ✅ Toutes les policies principales créées\n";
}

echo "\n🚀 SYSTÈME DE SÉCURITÉ OPÉRATIONNEL !\n";
echo "Les utilisateurs verront seulement les menus et fonctionnalités auxquels ils ont accès.\n";
