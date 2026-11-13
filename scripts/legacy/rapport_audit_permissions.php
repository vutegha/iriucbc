<?php
// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== RAPPORT D'AUDIT DÉTAILLÉ DES PERMISSIONS ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

echo "🚨 PROBLÈMES CRITIQUES IDENTIFIÉS\n";
echo str_repeat("=", 60) . "\n\n";

// 1. PROBLÈME MAJEUR: Tables de liaison manquantes
echo "❌ 1. TABLES DE LIAISON MANQUANTES\n";
echo "Les tables 'role_user' et 'permission_role' sont absentes.\n";
echo "Cela signifie que le système de permissions n'est PAS fonctionnel.\n\n";

echo "Impact:\n";
echo "• Aucun utilisateur n'a de rôle assigné\n";
echo "• Aucune permission n'est accordée\n";
echo "• Le système de sécurité est compromis\n\n";

// 2. Analyse des utilisateurs sans email vérifié
echo "📧 2. UTILISATEURS AVEC EMAIL NON VÉRIFIÉ\n";
$unverifiedUsers = DB::table('users')
    ->whereNull('email_verified_at')
    ->select('id', 'name', 'email', 'created_at')
    ->get();

echo "Nombre: " . $unverifiedUsers->count() . " utilisateurs\n\n";
foreach ($unverifiedUsers as $user) {
    echo "• {$user->name} ({$user->email}) - Créé le " . 
         date('d/m/Y', strtotime($user->created_at)) . "\n";
}
echo "\n";

// 3. Analyse des rôles en doublon
echo "👥 3. RÔLES EN DOUBLON DÉTECTÉS\n";
$roles = DB::table('roles')->get();
$roleNames = [];
$duplicates = [];

foreach ($roles as $role) {
    $cleanName = strtolower(trim($role->name));
    if (isset($roleNames[$cleanName])) {
        $duplicates[] = $role->name;
    }
    $roleNames[$cleanName] = $role;
}

if (!empty($duplicates)) {
    echo "Rôles en doublon trouvés:\n";
    foreach ($duplicates as $duplicate) {
        echo "• $duplicate\n";
    }
} else {
    echo "✅ Aucun rôle en doublon\n";
}
echo "\n";

// 4. Permissions redondantes
echo "🔄 4. PERMISSIONS REDONDANTES\n";
$permissions = DB::table('permissions')->get();
$permissionGroups = [];

foreach ($permissions as $permission) {
    // Grouper par base (ex: "manage projets" et "manage_projets")
    $base = strtolower(preg_replace('/[_\s-]+/', ' ', $permission->name));
    $permissionGroups[$base][] = $permission->name;
}

$redundant = array_filter($permissionGroups, function($group) {
    return count($group) > 1;
});

if (!empty($redundant)) {
    echo "Permissions potentiellement redondantes:\n";
    foreach ($redundant as $base => $perms) {
        echo "• Groupe '$base':\n";
        foreach ($perms as $perm) {
            echo "  - $perm\n";
        }
    }
} else {
    echo "✅ Aucune redondance détectée\n";
}
echo "\n";

// 5. Vérification des Policies
echo "📋 5. VÉRIFICATION DES POLICIES\n";
$policyFiles = glob('app/Policies/*.php');
$models = ['Projet', 'Actualite', 'Publication', 'Evenement', 'Media', 'Service', 'Rapport'];

echo "Policies disponibles:\n";
foreach ($policyFiles as $policy) {
    $policyName = basename($policy, '.php');
    echo "• $policyName\n";
}
echo "\n";

echo "Modèles sans Policy:\n";
foreach ($models as $model) {
    $policyExists = file_exists("app/Policies/{$model}Policy.php");
    if (!$policyExists) {
        echo "• $model (manquant)\n";
    }
}
echo "\n";

// 6. Analyse des méthodes dans les controllers
echo "🎮 6. ANALYSE DES CONTRÔLEURS\n";
$controllers = [
    'app/Http/Controllers/Admin/ProjetController.php',
    'app/Http/Controllers/Admin/ActualiteController.php',
    'app/Http/Controllers/Admin/PublicationController.php'
];

foreach ($controllers as $controller) {
    if (file_exists($controller)) {
        $content = file_get_contents($controller);
        $controllerName = basename($controller, '.php');
        echo "📄 $controllerName:\n";
        
        // Vérifier la présence de middleware auth
        if (strpos($content, 'middleware(\'auth\')') !== false || 
            strpos($content, '__construct') !== false) {
            echo "  ✅ Middleware d'authentification détecté\n";
        } else {
            echo "  ❌ Aucun middleware d'auth visible\n";
        }
        
        // Vérifier les directives @can
        $canDirectives = substr_count($content, '@can(') + substr_count($content, '$this->authorize(');
        echo "  📊 Vérifications d'autorisation: $canDirectives\n";
        
        echo "\n";
    }
}

// 7. RECOMMANDATIONS PRIORITAIRES
echo "🎯 7. PLAN D'ACTION PRIORITAIRE\n";
echo str_repeat("=", 60) . "\n\n";

echo "🔥 ACTIONS URGENTES (à faire immédiatement):\n\n";

echo "1. CRÉER LES TABLES DE LIAISON MANQUANTES\n";
echo "   php artisan make:migration create_role_user_table\n";
echo "   php artisan make:migration create_permission_role_table\n\n";

echo "2. STRUCTURE POUR role_user:\n";
echo "   Schema::create('role_user', function (Blueprint \$table) {\n";
echo "       \$table->id();\n";
echo "       \$table->foreignId('user_id')->constrained()->onDelete('cascade');\n";
echo "       \$table->foreignId('role_id')->constrained()->onDelete('cascade');\n";
echo "       \$table->timestamps();\n";
echo "       \$table->unique(['user_id', 'role_id']);\n";
echo "   });\n\n";

echo "3. STRUCTURE POUR permission_role:\n";
echo "   Schema::create('permission_role', function (Blueprint \$table) {\n";
echo "       \$table->id();\n";
echo "       \$table->foreignId('permission_id')->constrained()->onDelete('cascade');\n";
echo "       \$table->foreignId('role_id')->constrained()->onDelete('cascade');\n";
echo "       \$table->timestamps();\n";
echo "       \$table->unique(['permission_id', 'role_id']);\n";
echo "   });\n\n";

echo "4. NETTOYER LES RÔLES EN DOUBLON\n";
if (!empty($duplicates)) {
    echo "   Supprimer: " . implode(', ', $duplicates) . "\n";
}
echo "   Garder: admin, moderator, editor, user, super-admin\n\n";

echo "5. ASSIGNER DES RÔLES AUX UTILISATEURS EXISTANTS\n";
$users = DB::table('users')->select('id', 'name', 'email')->get();
echo "   Utilisateurs à traiter:\n";
foreach ($users as $user) {
    echo "   • {$user->name} (ID: {$user->id}) - Assigner rôle approprié\n";
}
echo "\n";

echo "📋 ACTIONS SECONDAIRES (après les urgences):\n\n";
echo "1. Implémenter la vérification d'email\n";
echo "2. Créer un seeder pour les permissions par défaut\n";
echo "3. Documenter le système de permissions\n";
echo "4. Créer des tests automatisés\n";
echo "5. Mettre en place un audit régulier\n\n";

echo "🛡️ SÉCURITÉ RECOMMANDÉE:\n";
echo "• Middleware 'auth' sur toutes les routes admin\n";
echo "• Directive @can dans les vues sensibles\n";
echo "• Validation des permissions dans les policies\n";
echo "• Logging des actions sensibles\n\n";

echo "=== FIN DU RAPPORT DÉTAILLÉ ===\n";

?>
