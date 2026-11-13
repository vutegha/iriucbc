<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class BasicDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Création des données de base...');
        
        // 1. Créer les permissions de base
        $this->command->info('📝 Création des permissions...');
        
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
        }
        
        $this->command->info('✅ ' . count($permissions) . ' permissions créées');
        
        // 2. Créer les rôles
        $this->command->info('👥 Création des rôles...');
        
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']); 
        $moderator = Role::firstOrCreate(['name' => 'moderator']);
        $gestionnaire = Role::firstOrCreate(['name' => 'gestionnaire_projets']);
        $contributeur = Role::firstOrCreate(['name' => 'contributeur']);
        $user = Role::firstOrCreate(['name' => 'user']);
        
        $this->command->info('✅ 6 rôles créés');
        
        // 3. Assigner les permissions aux rôles
        $this->command->info('🔐 Attribution des permissions...');
        
        // Super Admin: toutes les permissions
        $superAdmin->syncPermissions(Permission::all());
        
        // Admin: presque toutes sauf manage_system
        $adminPermissions = Permission::whereNotIn('name', ['manage_system'])->get();
        $admin->syncPermissions($adminPermissions);
        
        // Modérateur: modération et visualisation
        $moderatorPermissions = Permission::where('name', 'like', '%moderate_%')
            ->orWhere('name', 'like', '%view_%')
            ->orWhere('name', 'like', '%publish_%')
            ->orWhere('name', 'like', '%unpublish_%')
            ->orWhere('name', 'access_admin')
            ->get();
        $moderator->syncPermissions($moderatorPermissions);
        
        // Gestionnaire de projets: gestion des projets
        $gestionnairePermissions = Permission::where('name', 'like', '%projet%')
            ->orWhere('name', 'view_dashboard')
            ->orWhere('name', 'access_admin')
            ->get();
        $gestionnaire->syncPermissions($gestionnairePermissions);
        
        $this->command->info('✅ Permissions attribuées aux rôles');
        
        // 4. Créer l'utilisateur administrateur principal
        $this->command->info('👤 Création de l\'utilisateur administrateur...');
        
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
        $this->command->info('✅ Utilisateur admin créé: admin@ucbc.org / admin123');
        
        // 5. Créer les services de base
        $this->command->info('🏢 Création des services...');
        
        $services = [
            [
                'nom' => 'Direction Générale',
                'nom_menu' => 'Direction',
                'resume' => 'Direction générale de l\'Institut de Recherche Igiti',
                'description' => 'La direction générale supervise l\'ensemble des activités de l\'institut et définit les orientations stratégiques.',
                'slug' => 'direction-generale',
                'is_published' => true,
                'show_in_menu' => true,
                'published_at' => now()
            ],
            [
                'nom' => 'Recherche et Études',
                'nom_menu' => 'Recherche',
                'resume' => 'Service de recherche et d\'études en droits humains',
                'description' => 'Ce service conduit les recherches et études approfondies sur les questions de droits humains et de justice.',
                'slug' => 'recherche-etudes',
                'is_published' => true,
                'show_in_menu' => true,
                'published_at' => now()
            ],
            [
                'nom' => 'Communication',
                'nom_menu' => 'Communication',
                'resume' => 'Service de communication et relations publiques',
                'description' => 'Gestion de la communication externe et interne, relations avec les médias et le public.',
                'slug' => 'communication',
                'is_published' => true,
                'show_in_menu' => true,
                'published_at' => now()
            ],
            [
                'nom' => 'Administration',
                'nom_menu' => 'Administration',
                'resume' => 'Service administratif et des ressources humaines',
                'description' => 'Gestion administrative, ressources humaines et support logistique de l\'institut.',
                'slug' => 'administration',
                'is_published' => true,
                'show_in_menu' => false,
                'published_at' => now()
            ]
        ];
        
        foreach ($services as $serviceData) {
            Service::firstOrCreate(
                ['nom' => $serviceData['nom']],
                $serviceData
            );
        }
        
        $this->command->info('✅ ' . count($services) . ' services créés');
        
        $this->command->info('🎉 CRÉATION TERMINÉE AVEC SUCCÈS!');
        $this->command->info('📊 Résumé:');
        $this->command->info('   - Permissions: ' . Permission::count());
        $this->command->info('   - Rôles: ' . Role::count());
        $this->command->info('   - Utilisateurs: ' . User::count());
        $this->command->info('   - Services: ' . Service::count());
        
        $this->command->warn('🔑 ACCÈS ADMINISTRATEUR:');
        $this->command->warn('   Email: admin@ucbc.org');
        $this->command->warn('   Mot de passe: valeur de SEED_USER_PASSWORD, sinon aléatoire (utilisez « mot de passe oublié »)');
        $this->command->warn('   Rôle: super-admin');
    }
}
