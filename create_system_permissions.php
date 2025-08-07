<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== CRÉATION DES PERMISSIONS SYSTÈME MANQUANTES ===\n\n";

// Permissions système spéciales
$systemPermissions = [
    'access_admin_dashboard',
    'manage_email_settings',
    'manage_newsletter',
    'view_system_logs',
    'manage_system',
];

echo "📝 Création des permissions système...\n";
foreach ($systemPermissions as $permission) {
    $perm = Permission::firstOrCreate([
        'name' => $permission,
        'guard_name' => 'web'
    ]);
    
    if ($perm->wasRecentlyCreated) {
        echo "  ✅ Créé : $permission\n";
    } else {
        echo "  ℹ️  Existe : $permission\n";
    }
}

// Assurer que tous les modèles ont leurs permissions de base
$modelsPermissions = [
    'Contact' => ['view_contacts', 'manage_contacts', 'respond_contacts'],
    'Newsletter' => ['view_newsletters', 'manage_newsletter', 'send_newsletter'],
    'JobOffer' => ['view_job_offers', 'create_job_offers', 'update_job_offers', 'delete_job_offers'],
    'JobApplication' => ['view_job_applications', 'manage_job_applications'],
    'Categorie' => ['view_categories', 'create_categories', 'update_categories', 'delete_categories'],
    'Evenement' => ['view_evenements', 'create_evenements', 'update_evenements', 'delete_evenements'],
];

echo "\n📝 Vérification des permissions pour modèles spéciaux...\n";
foreach ($modelsPermissions as $model => $permissions) {
    echo "🔍 Modèle $model :\n";
    foreach ($permissions as $permission) {
        $perm = Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web'
        ]);
        
        if ($perm->wasRecentlyCreated) {
            echo "  ✅ Créé : $permission\n";
        } else {
            echo "  ℹ️  Existe : $permission\n";
        }
    }
}

// Attribution des nouvelles permissions au rôle super-admin
echo "\n🔐 Attribution des permissions au rôle super-admin...\n";
$superAdmin = Role::where('name', 'super-admin')->first();

if ($superAdmin) {
    // Donner toutes les nouvelles permissions au super-admin
    $allNewPermissions = array_merge($systemPermissions, ...array_values($modelsPermissions));
    
    foreach ($allNewPermissions as $permission) {
        if (!$superAdmin->hasPermissionTo($permission)) {
            $superAdmin->givePermissionTo($permission);
            echo "  ✅ Assigné '$permission' au super-admin\n";
        }
    }
    
    echo "✅ Super-admin mis à jour avec toutes les nouvelles permissions\n";
} else {
    echo "⚠️  Rôle super-admin introuvable\n";
}

// Attribution sélective aux autres rôles
echo "\n🎭 Attribution des permissions aux autres rôles...\n";

$admin = Role::where('name', 'admin')->first();
if ($admin) {
    $adminPermissions = [
        'access_admin_dashboard',
        'manage_email_settings',
        'manage_newsletter',
        'view_contacts', 'manage_contacts', 'respond_contacts',
        'view_newsletters', 'send_newsletter',
        'view_job_offers', 'create_job_offers', 'update_job_offers', 'delete_job_offers',
        'view_job_applications', 'manage_job_applications',
        'view_categories', 'create_categories', 'update_categories', 'delete_categories',
        'view_evenements', 'create_evenements', 'update_evenements', 'delete_evenements',
    ];
    
    foreach ($adminPermissions as $permission) {
        if (!$admin->hasPermissionTo($permission)) {
            $admin->givePermissionTo($permission);
            echo "  ✅ Admin: $permission\n";
        }
    }
}

$moderator = Role::where('name', 'moderator')->first();
if ($moderator) {
    $moderatorPermissions = [
        'access_admin_dashboard',
        'view_contacts',
        'view_newsletters', 
        'view_job_offers', 'view_job_applications',
        'view_categories', 'view_evenements',
    ];
    
    foreach ($moderatorPermissions as $permission) {
        if (!$moderator->hasPermissionTo($permission)) {
            $moderator->givePermissionTo($permission);
            echo "  ✅ Modérateur: $permission\n";
        }
    }
}

echo "\n🎉 CRÉATION DES PERMISSIONS TERMINÉE !\n\n";

// Résumé final
$totalPermissions = Permission::count();
$totalRoles = Role::count();

echo "📊 RÉSUMÉ :\n";
echo "• Total permissions dans le système : $totalPermissions\n";  
echo "• Total rôles : $totalRoles\n";
echo "• Permissions système ajoutées : " . count($systemPermissions) . "\n";
echo "• Permissions de modèles vérifiées : " . array_sum(array_map('count', $modelsPermissions)) . "\n";

// Vérifier si l'utilisateur principal a bien accès
echo "\n👤 VÉRIFICATION UTILISATEUR PRINCIPAL :\n";
$user = \App\Models\User::where('email', 'iri@ucbc.org')->first();
if ($user) {
    echo "✅ Utilisateur trouvé : {$user->name}\n";
    echo "🎭 Rôles : " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "🔑 Total permissions : " . $user->getAllPermissions()->count() . "\n";
    
    // Tester quelques permissions clés
    $keyPermissions = ['access_admin_dashboard', 'view_actualites', 'manage_users'];
    foreach ($keyPermissions as $perm) {
        $hasPermission = $user->hasPermissionTo($perm);
        echo ($hasPermission ? "✅" : "❌") . " $perm\n";
    }
} else {
    echo "⚠️  Utilisateur principal (iri@ucbc.org) introuvable\n";
}
