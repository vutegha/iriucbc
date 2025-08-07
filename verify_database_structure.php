<?php
// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VÉRIFICATION STRUCTURE BASE DE DONNÉES ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

try {
    // 1. Obtenir toutes les tables de la base
    echo "📋 1. TOUTES LES TABLES PRÉSENTES\n";
    echo str_repeat("=", 60) . "\n";
    
    $tables = DB::select('SHOW TABLES');
    $databaseName = DB::connection()->getDatabaseName();
    $tableColumn = "Tables_in_$databaseName";
    
    $allTables = [];
    foreach ($tables as $table) {
        $allTables[] = $table->$tableColumn;
    }
    
    echo "Base de données: $databaseName\n";
    echo "Total des tables: " . count($allTables) . "\n\n";
    
    // Organiser les tables par type
    $spatieRelated = [];
    $permissionRelated = [];
    $projectRelated = [];
    $otherTables = [];
    
    foreach ($allTables as $tableName) {
        if (strpos($tableName, 'model_has_') !== false || 
            strpos($tableName, 'role_has_') !== false ||
            in_array($tableName, ['roles', 'permissions'])) {
            $spatieRelated[] = $tableName;
        } elseif (strpos($tableName, 'permission') !== false || 
                  strpos($tableName, 'role') !== false) {
            $permissionRelated[] = $tableName;
        } elseif (strpos($tableName, 'projet') !== false) {
            $projectRelated[] = $tableName;
        } else {
            $otherTables[] = $tableName;
        }
    }
    
    echo "🔐 Tables liées à Spatie/Permissions:\n";
    if (!empty($spatieRelated)) {
        foreach ($spatieRelated as $table) {
            echo "  ✅ $table\n";
        }
    } else {
        echo "  ❌ Aucune table Spatie trouvée\n";
    }
    echo "\n";
    
    echo "🏷️ Autres tables de permissions/rôles:\n";
    if (!empty($permissionRelated)) {
        foreach ($permissionRelated as $table) {
            echo "  ✅ $table\n";
        }
    } else {
        echo "  ❌ Aucune autre table de permissions\n";
    }
    echo "\n";
    
    echo "🎯 Tables liées aux projets:\n";
    if (!empty($projectRelated)) {
        foreach ($projectRelated as $table) {
            echo "  ✅ $table\n";
        }
    } else {
        echo "  ❌ Aucune table projet trouvée\n";
    }
    echo "\n";
    
    // 2. Si les tables Spatie existent, examiner leur contenu
    if (in_array('roles', $allTables)) {
        echo "🏷️ 2. CONTENU DE LA TABLE 'roles'\n";
        echo str_repeat("=", 60) . "\n";
        
        $roles = DB::table('roles')->get();
        echo "Nombre de rôles: " . $roles->count() . "\n\n";
        
        foreach ($roles as $role) {
            echo "ID: {$role->id} | Nom: '{$role->name}'";
            if (isset($role->guard_name)) {
                echo " | Guard: {$role->guard_name}";
            }
            if (isset($role->created_at)) {
                echo " | Créé: " . date('d/m/Y', strtotime($role->created_at));
            }
            echo "\n";
        }
        echo "\n";
    }
    
    if (in_array('permissions', $allTables)) {
        echo "🔐 3. CONTENU DE LA TABLE 'permissions'\n";
        echo str_repeat("=", 60) . "\n";
        
        $permissions = DB::table('permissions')->get();
        echo "Nombre de permissions: " . $permissions->count() . "\n\n";
        
        // Grouper par préfixe
        $permissionGroups = [];
        foreach ($permissions as $perm) {
            $prefix = explode('_', $perm->name)[0] ?? explode(' ', $perm->name)[0] ?? 'autres';
            $permissionGroups[$prefix][] = $perm;
        }
        
        foreach ($permissionGroups as $group => $perms) {
            echo "📦 Groupe '$group' (" . count($perms) . " permissions):\n";
            foreach (array_slice($perms, 0, 5) as $perm) { // Limiter à 5 pour éviter trop d'affichage
                echo "  • {$perm->name}";
                if (isset($perm->guard_name)) {
                    echo " (guard: {$perm->guard_name})";
                }
                echo "\n";
            }
            if (count($perms) > 5) {
                echo "  ... et " . (count($perms) - 5) . " autres\n";
            }
            echo "\n";
        }
    }
    
    // 3. Vérifier les tables de liaison Spatie
    $spatieJoinTables = [
        'model_has_roles' => 'Liaison utilisateurs-rôles',
        'model_has_permissions' => 'Liaison utilisateurs-permissions',
        'role_has_permissions' => 'Liaison rôles-permissions'
    ];
    
    echo "🔗 4. TABLES DE LIAISON SPATIE\n";
    echo str_repeat("=", 60) . "\n";
    
    foreach ($spatieJoinTables as $tableName => $description) {
        if (in_array($tableName, $allTables)) {
            echo "✅ $tableName ($description)\n";
            
            $count = DB::table($tableName)->count();
            echo "   Nombre d'enregistrements: $count\n";
            
            if ($count > 0 && $count <= 10) {
                echo "   Contenu:\n";
                $records = DB::table($tableName)->limit(5)->get();
                foreach ($records as $record) {
                    $recordArray = (array) $record;
                    echo "   • " . implode(' | ', array_map(function($k, $v) {
                        return "$k: $v";
                    }, array_keys($recordArray), $recordArray)) . "\n";
                }
            }
            echo "\n";
        } else {
            echo "❌ $tableName ($description) - MANQUANTE\n";
        }
    }
    
    // 4. Vérifier la structure spécifique aux projets
    if (in_array('projets', $allTables)) {
        echo "🎯 5. STRUCTURE TABLE 'projets'\n";
        echo str_repeat("=", 60) . "\n";
        
        $columns = DB::select("DESCRIBE projets");
        echo "Colonnes de la table projets:\n";
        foreach ($columns as $column) {
            echo "  • {$column->Field} ({$column->Type})";
            if ($column->Null === 'NO') echo " NOT NULL";
            if ($column->Key) echo " KEY: {$column->Key}";
            if ($column->Default) echo " DEFAULT: {$column->Default}";
            echo "\n";
        }
        echo "\n";
        
        $projetCount = DB::table('projets')->count();
        echo "Nombre de projets: $projetCount\n\n";
    }
    
    // 5. Diagnostic final
    echo "🔍 6. DIAGNOSTIC FINAL\n";
    echo str_repeat("=", 60) . "\n";
    
    $hasSpatieTables = in_array('roles', $allTables) && 
                       in_array('permissions', $allTables) && 
                       in_array('model_has_roles', $allTables) && 
                       in_array('role_has_permissions', $allTables);
    
    if ($hasSpatieTables) {
        echo "✅ Spatie Laravel Permission est CORRECTEMENT installé\n";
        echo "✅ Toutes les tables nécessaires sont présentes\n";
        
        // Vérifier s'il y a des données
        $hasRoles = DB::table('roles')->count() > 0;
        $hasPermissions = DB::table('permissions')->count() > 0;
        $hasAssignments = DB::table('model_has_roles')->count() > 0 || 
                         DB::table('role_has_permissions')->count() > 0;
        
        if ($hasRoles && $hasPermissions) {
            echo "✅ Le système contient des rôles et permissions\n";
            if ($hasAssignments) {
                echo "✅ Des assignations sont configurées\n";
                echo "\n🎯 CONCLUSION: Le système Spatie est FONCTIONNEL\n";
            } else {
                echo "⚠️ Aucune assignation rôle-utilisateur ou rôle-permission\n";
                echo "\n🎯 CONCLUSION: Le système Spatie existe mais n'est PAS CONFIGURÉ\n";
            }
        } else {
            echo "⚠️ Tables vides - pas de rôles ou permissions configurés\n";
            echo "\n🎯 CONCLUSION: Le système Spatie doit être INITIALISÉ\n";
        }
    } else {
        echo "❌ Spatie Laravel Permission N'EST PAS correctement installé\n";
        echo "❌ Tables manquantes - migrations Spatie non exécutées\n";
        echo "\n🎯 CONCLUSION: Spatie doit être INSTALLÉ et CONFIGURÉ\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR lors de la vérification: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DE LA VÉRIFICATION ===\n";
?>
