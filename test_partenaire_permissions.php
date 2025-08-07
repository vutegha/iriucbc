<?php

/**
 * Test des permissions pour les partenaires
 * À exécuter via : php test_partenaire_permissions.php
 */

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Partenaire;
use Spatie\Permission\Models\Permission;

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "🔍 Test des permissions pour les partenaires\n\n";

    // Vérifier les permissions créées
    echo "📋 Permissions disponibles :\n";
    $permissions = Permission::where('name', 'LIKE', '%partenaires%')->get();
    foreach ($permissions as $permission) {
        echo "  ✓ {$permission->name}\n";
    }

    // Vérifier les utilisateurs avec permissions
    echo "\n👥 Utilisateurs avec permissions partenaires :\n";
    $users = User::permission('view_partenaires')->get();
    foreach ($users as $user) {
        $roles = $user->getRoleNames()->toArray();
        echo "  • {$user->name} ({$user->email}) - Rôles: " . implode(', ', $roles) . "\n";
    }

    // Test d'un utilisateur spécifique (premier admin trouvé)
    $adminUser = User::role(['super-admin', 'admin'])->first();
    if ($adminUser) {
        echo "\n🧪 Test avec l'utilisateur : {$adminUser->name}\n";
        
        $tests = [
            'view_partenaires' => $adminUser->can('viewAny', Partenaire::class),
            'create_partenaires' => $adminUser->can('create', Partenaire::class),
            'Méthode canViewPartenaires()' => $adminUser->canViewPartenaires(),
            'Méthode canCreatePartenaires()' => $adminUser->canCreatePartenaires(),
            'Méthode canUpdatePartenaires()' => $adminUser->canUpdatePartenaires(),
            'Méthode canDeletePartenaires()' => $adminUser->canDeletePartenaires(),
            'Méthode canModeratePartenaires()' => $adminUser->canModeratePartenaires(),
        ];

        foreach ($tests as $testName => $result) {
            $status = $result ? '✅' : '❌';
            echo "  {$status} {$testName}: " . ($result ? 'AUTORISÉ' : 'REFUSÉ') . "\n";
        }
    } else {
        echo "\n⚠ Aucun utilisateur admin trouvé pour les tests\n";
    }

    // Compter les partenaires
    $partenaireCount = Partenaire::count();
    echo "\n📊 Nombre de partenaires en base : {$partenaireCount}\n";

    echo "\n✅ Tests terminés avec succès !\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Stack trace :\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
