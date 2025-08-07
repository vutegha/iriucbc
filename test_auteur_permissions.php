<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Auteur;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

try {
    echo "🔐 VÉRIFICATION DES PERMISSIONS AUTEURS\n";
    echo "=====================================\n\n";
    
    // 1. Vérifier les permissions auteurs
    $auteursPermissions = [
        'view_auteurs',
        'create_auteurs', 
        'update_auteurs',
        'delete_auteurs',
        'export_auteurs',
        'manage_auteurs'
    ];
    
    echo "📋 PERMISSIONS AUTEURS:\n";
    foreach ($auteursPermissions as $permission) {
        $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
        echo ($exists ? "✅" : "❌") . " $permission\n";
    }
    
    // 2. Vérifier qu'il y a des auteurs
    $auteurCount = Auteur::count();
    echo "\n📊 DONNÉES:\n";
    echo "• Nombre d'auteurs: $auteurCount\n";
    
    // 3. Vérifier qu'il y a des utilisateurs admin
    $adminUsers = User::whereHas('permissions', function($query) {
        $query->where('name', 'view_auteurs');
    })->orWhereHas('roles', function($query) {
        $query->whereHas('permissions', function($subQuery) {
            $subQuery->where('name', 'view_auteurs');
        });
    })->get();
    
    echo "• Utilisateurs avec permissions auteurs: " . $adminUsers->count() . "\n";
    
    // 4. Test des policies avec un utilisateur admin
    if ($adminUsers->count() > 0) {
        $admin = $adminUsers->first();
        echo "\n🔍 TEST DES POLICIES (utilisateur: {$admin->email}):\n";
        
        echo "• Peut voir les auteurs: " . ($admin->can('viewAny', Auteur::class) ? "✅" : "❌") . "\n";
        echo "• Peut créer un auteur: " . ($admin->can('create', Auteur::class) ? "✅" : "❌") . "\n";
        echo "• Peut exporter les auteurs: " . ($admin->can('export', Auteur::class) ? "✅" : "❌") . "\n";
        
        if ($auteurCount > 0) {
            $auteur = Auteur::first();
            echo "• Peut voir l'auteur '{$auteur->nom}': " . ($admin->can('view', $auteur) ? "✅" : "❌") . "\n";
            echo "• Peut modifier l'auteur '{$auteur->nom}': " . ($admin->can('update', $auteur) ? "✅" : "❌") . "\n";
            echo "• Peut supprimer l'auteur '{$auteur->nom}': " . ($admin->can('delete', $auteur) ? "✅" : "❌") . "\n";
        }
    }
    
    // 5. Vérifier la Policy Registration
    echo "\n🎯 VERIFICATION POLICY:\n";
    $policies = app('Illuminate\Contracts\Auth\Access\Gate')->policies();
    $auteurPolicyRegistered = isset($policies[Auteur::class]);
    echo "• AuteurPolicy enregistrée: " . ($auteurPolicyRegistered ? "✅" : "❌") . "\n";
    
    if ($auteurPolicyRegistered) {
        echo "• Policy class: " . $policies[Auteur::class] . "\n";
    }
    
    echo "\n✨ VÉRIFICATION TERMINÉE!\n";
    
    if ($auteurCount > 0 && $adminUsers->count() > 0) {
        echo "\n🎉 Le système de permissions pour les auteurs est opérationnel!\n";
        echo "Vous pouvez maintenant tester l'interface à l'adresse: /admin/auteur\n";
    } else {
        echo "\n⚠️  Actions recommandées:\n";
        if ($auteurCount === 0) {
            echo "- Créer quelques auteurs de test\n";
        }
        if ($adminUsers->count() === 0) {
            echo "- Assigner les permissions auteurs à un utilisateur admin\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
