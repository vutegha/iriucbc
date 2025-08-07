<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "🔐 CRÉATION DES PERMISSIONS MANQUANTES POUR LES MENUS ADMIN\n";
echo str_repeat("=", 70) . "\n\n";

// Permissions pour le système admin
$adminMenuPermissions = [
    // Communication
    'manage_newsletter',
    'send_newsletter',
    'view_newsletter_stats',
    'manage_email_settings',
    'send_test_emails',
    
    // Administration générale
    'manage_users',
    'view_users',
    'create_users',
    'update_users',
    'delete_users',
    'assign_roles',
    'manage_permissions',
    
    // Permissions générales pour les exports
    'export_auteurs',
    'export_users',
    'export_job_applications',
    
    // Permissions pour les contacts
    'view_contacts',
    'create_contacts',
    'update_contacts',
    'delete_contacts',
    'respond_contacts',
    
    // Permissions spécifiques pour les différents modèles
    'moderate_actualites',
    'moderate_publications', 
    'moderate_projets',
    'moderate_evenements',
    'moderate_services',
    'moderate_media',
    'moderate_rapports',
    
    // Permissions de publication
    'publish_actualites',
    'unpublish_actualites',
    'publish_publications',
    'unpublish_publications',
    'publish_projets',
    'unpublish_projets',
    'publish_evenements',
    'unpublish_evenements',
    'publish_services',
    'unpublish_services',
    'publish_media',
    'unpublish_media',
    'publish_rapports',
    'unpublish_rapports',
    
    // Job offers et applications
    'view_job_offers',
    'create_job_offers',
    'update_job_offers',
    'delete_job_offers',
    'moderate_job_offers',
    'publish_job_offers',
    'unpublish_job_offers',
    
    'view_job_applications',
    'update_job_applications',
    'delete_job_applications',
    'export_job_applications'
];

echo "📋 Vérification des permissions existantes...\n";
$existingPermissions = DB::table('permissions')->pluck('name')->toArray();

$toCreate = [];
foreach ($adminMenuPermissions as $permission) {
    if (!in_array($permission, $existingPermissions)) {
        $toCreate[] = $permission;
    }
}

if (empty($toCreate)) {
    echo "✅ Toutes les permissions du menu admin existent déjà !\n\n";
} else {
    echo "📝 Création des permissions manquantes (" . count($toCreate) . "):\n";
    
    foreach ($toCreate as $permission) {
        try {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
            echo "  ✅ $permission\n";
        } catch (Exception $e) {
            echo "  ❌ $permission - Erreur: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n🔐 Attribution des nouvelles permissions aux rôles...\n";

// Attribution aux rôles existants
$roles = [
    'super-admin' => 'all', // Toutes les permissions
    'admin' => [
        'manage_newsletter', 'send_newsletter', 'view_newsletter_stats',
        'manage_email_settings', 'send_test_emails',
        'manage_users', 'view_users', 'create_users', 'update_users', 'delete_users',
        'export_auteurs', 'export_users', 'export_job_applications',
        'view_contacts', 'create_contacts', 'update_contacts', 'delete_contacts', 'respond_contacts',
        'moderate_actualites', 'moderate_publications', 'moderate_projets', 'moderate_evenements',
        'moderate_services', 'moderate_media', 'moderate_rapports',
        'publish_actualites', 'publish_publications', 'publish_projets', 'publish_evenements',
        'publish_services', 'publish_media', 'publish_rapports',
        'view_job_offers', 'create_job_offers', 'update_job_offers', 'delete_job_offers',
        'moderate_job_offers', 'publish_job_offers', 'unpublish_job_offers',
        'view_job_applications', 'update_job_applications', 'delete_job_applications', 'export_job_applications'
    ],
    'moderator' => [
        'view_newsletter_stats',
        'view_users',
        'view_contacts', 'respond_contacts',
        'moderate_actualites', 'moderate_publications', 'moderate_projets', 'moderate_evenements',
        'moderate_services', 'moderate_media', 'moderate_rapports',
        'publish_actualites', 'unpublish_actualites',
        'publish_publications', 'unpublish_publications',
        'publish_projets', 'unpublish_projets',
        'publish_evenements', 'unpublish_evenements',
        'publish_services', 'unpublish_services',
        'publish_media', 'unpublish_media',
        'publish_rapports', 'unpublish_rapports',
        'view_job_offers', 'moderate_job_offers', 'publish_job_offers', 'unpublish_job_offers',
        'view_job_applications', 'update_job_applications'
    ],
    'gestionnaire_projets' => [
        'view_contacts',
        'moderate_projets', 'moderate_actualites',
        'publish_projets', 'unpublish_projets',
        'publish_actualites', 'unpublish_actualites'
    ]
];

foreach ($roles as $roleName => $permissions) {
    $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
    
    if (!$role) {
        echo "  ⚠️  Rôle '$roleName' non trouvé\n";
        continue;
    }
    
    if ($permissions === 'all') {
        // Super-admin obtient toutes les permissions
        $allPermissions = Permission::where('guard_name', 'web')->get();
        $role->syncPermissions($allPermissions);
        echo "  👑 $roleName: " . $allPermissions->count() . " permissions (toutes)\n";
    } else {
        // Attribution des permissions spécifiques
        $validPermissions = [];
        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->where('guard_name', 'web')->first();
            if ($permission) {
                $validPermissions[] = $permission;
            } else {
                echo "    ⚠️  Permission '$permissionName' introuvable pour rôle '$roleName'\n";
            }
        }
        
        if (!empty($validPermissions)) {
            // Donner les nouvelles permissions en plus des existantes
            $role->givePermissionTo($validPermissions);
            echo "  🏷️  $roleName: " . count($validPermissions) . " nouvelles permissions ajoutées\n";
        }
    }
}

echo "\n🧹 Nettoyage du cache des permissions...\n";
try {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    echo "✅ Cache des permissions vidé\n";
} catch (Exception $e) {
    echo "⚠️  Erreur lors du nettoyage du cache: " . $e->getMessage() . "\n";
}

echo "\n📊 STATISTIQUES FINALES:\n";
echo str_repeat("-", 40) . "\n";
echo "• Total permissions créées: " . count($toCreate) . "\n";
echo "• Total permissions système: " . Permission::count() . "\n";
echo "• Total rôles configurés: " . count($roles) . "\n";

echo "\n🎉 Configuration des permissions du menu admin terminée !\n";
echo "💡 Les éléments du menu seront maintenant affichés selon les permissions de chaque utilisateur.\n\n";

// Vérification finale
echo "🔍 VÉRIFICATION FINALE - Permissions par rôle:\n";
echo str_repeat("=", 50) . "\n";

$rolesToCheck = ['super-admin', 'admin', 'moderator', 'gestionnaire_projets'];
foreach ($rolesToCheck as $roleName) {
    $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
    if ($role) {
        $permCount = $role->permissions()->count();
        echo "🏷️  $roleName: $permCount permissions\n";
    }
}

echo "\n✅ Script terminé avec succès !\n";
