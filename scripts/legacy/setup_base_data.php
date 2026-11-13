<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\Service;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

echo "=== CRÉATION DES DONNÉES DE BASE ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

try {
    // 1. Créer les permissions de base
    echo "📝 Création des permissions de base...\n";
    
    $permissions = [
        // Actualités
        'view_actualites', 'view_actualite', 'create_actualite', 'update_actualite', 'delete_actualite',
        'moderate_actualite', 'publish_actualite', 'unpublish_actualite',
        
        // Projets  
        'view_projets', 'view_projet', 'create_projet', 'update_projet', 'delete_projet',
        'moderate_projet', 'publish_projet', 'unpublish_projet',
        
        // Publications
        'view_publications', 'view_publication', 'create_publication', 'update_publication', 'delete_publication',
        'moderate_publication', 'publish_publication', 'unpublish_publication',
        
        // Événements
        'view_evenements', 'view_evenement', 'create_evenement', 'update_evenement', 'delete_evenement',
        'moderate_evenement', 'publish_evenement', 'unpublish_evenement',
        
        // Utilisateurs
        'view_users', 'create_user', 'update_user', 'delete_user',
        
        // Administration  
        'access_admin', 'manage_permissions', 'manage_roles', 'manage_services',
        'view_dashboard', 'manage_system',
        
        // Newsletter
        'manage_newsletter', 'send_newsletter', 'view_newsletter_stats'
    ];
    
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
        echo "  ✅ Permission créée: $permission\n";
    }
    
    // 2. Créer les rôles
    echo "\n👥 Création des rôles...\n";
    
    $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
    $admin = Role::firstOrCreate(['name' => 'admin']); 
    $moderator = Role::firstOrCreate(['name' => 'moderator']);
    $gestionnaire = Role::firstOrCreate(['name' => 'gestionnaire_projets']);
    $contributeur = Role::firstOrCreate(['name' => 'contributeur']);
    $user = Role::firstOrCreate(['name' => 'user']);
    
    echo "  ✅ Rôle créé: super-admin\n";
    echo "  ✅ Rôle créé: admin\n";
    echo "  ✅ Rôle créé: moderator\n";
    echo "  ✅ Rôle créé: gestionnaire_projets\n";
    echo "  ✅ Rôle créé: contributeur\n";
    echo "  ✅ Rôle créé: user\n";
    
    // 3. Assigner les permissions aux rôles
    echo "\n🔐 Attribution des permissions...\n";
    
    // Super Admin: toutes les permissions
    $superAdmin->syncPermissions(Permission::all());
    echo "  ✅ Super Admin: toutes les permissions assignées\n";
    
    // Admin: presque toutes sauf manage_system
    $adminPermissions = Permission::whereNotIn('name', ['manage_system'])->get();
    $admin->syncPermissions($adminPermissions);
    echo "  ✅ Admin: permissions assignées\n";
    
    // Modérateur: modération et visualisation
    $moderatorPermissions = Permission::where('name', 'like', '%moderate_%')
        ->orWhere('name', 'like', '%view_%')
        ->orWhere('name', 'like', '%publish_%')
        ->orWhere('name', 'like', '%unpublish_%')
        ->orWhere('name', 'access_admin')
        ->get();
    $moderator->syncPermissions($moderatorPermissions);
    echo "  ✅ Modérateur: permissions assignées\n";
    
    // Gestionnaire de projets: gestion des projets
    $gestionnairePermissions = Permission::where('name', 'like', '%projet%')
        ->orWhere('name', 'view_dashboard')
        ->orWhere('name', 'access_admin')
        ->get();
    $gestionnaire->syncPermissions($gestionnairePermissions);
    echo "  ✅ Gestionnaire projets: permissions assignées\n";
    
    // 4. Créer l'utilisateur administrateur principal
    echo "\n👤 Création de l'utilisateur administrateur...\n";
    
    $adminUser = User::firstOrCreate(
        ['email' => 'admin@ucbc.org'],
        [
            'name' => 'Administrateur IRI',
            'email' => 'admin@ucbc.org',
            'password' => Hash::make(env('SEED_USER_PASSWORD') ?: \Illuminate\Support\Str::random(32)),
            'active' => true,
            'email_verified_at' => now()
        ]
    );
    
    $adminUser->assignRole('super-admin');
    echo "  ✅ Utilisateur admin créé: admin@ucbc.org / admin123\n";
    
    // 5. Créer les services de base
    echo "\n🏢 Création des services...\n";
    
    $services = [
        [
            'nom' => 'Direction Générale',
            'description' => 'Direction générale de l\'Institut de Recherche Igiti',
            'responsable' => 'Directeur Général',
            'email' => 'direction@ucbc.org',
            'actif' => true
        ],
        [
            'nom' => 'Recherche et Études',
            'description' => 'Service de recherche et d\'études en droits humains',
            'responsable' => 'Chef de Service Recherche',
            'email' => 'recherche@ucbc.org',
            'actif' => true
        ],
        [
            'nom' => 'Communication',
            'description' => 'Service de communication et relations publiques',
            'responsable' => 'Responsable Communication',
            'email' => 'communication@ucbc.org',
            'actif' => true
        ],
        [
            'nom' => 'Administration',
            'description' => 'Service administratif et des ressources humaines',
            'responsable' => 'Responsable Administratif',
            'email' => 'admin@ucbc.org',
            'actif' => true
        ]
    ];
    
    foreach ($services as $serviceData) {
        Service::firstOrCreate(
            ['nom' => $serviceData['nom']],
            $serviceData
        );
        echo "  ✅ Service créé: {$serviceData['nom']}\n";
    }
    
    echo "\n🎉 CRÉATION TERMINÉE AVEC SUCCÈS!\n";
    echo "📊 Résumé:\n";
    echo "   - Permissions: " . Permission::count() . "\n";
    echo "   - Rôles: " . Role::count() . "\n";
    echo "   - Utilisateurs: " . User::count() . "\n";
    echo "   - Services: " . Service::count() . "\n";
    
    echo "\n🔑 ACCÈS ADMINISTRATEUR:\n";
    echo "   Email: admin@ucbc.org\n";
    echo "   Mot de passe: valeur de SEED_USER_PASSWORD, sinon aleatoire (utilisez mot de passe oublie)\n";
    echo "   Rôle: super-admin\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
