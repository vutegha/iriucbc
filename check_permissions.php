<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICATION DES TABLES SPATIE PERMISSION ===" . PHP_EOL;
echo PHP_EOL;

try {
    // Vérifier la connexion DB
    echo "🔗 Test connexion base de données..." . PHP_EOL;
    $dbName = DB::connection()->getDatabaseName();
    echo "   Base de données connectée: {$dbName}" . PHP_EOL;
    echo PHP_EOL;

    // Lister toutes les tables
    echo "📋 Tables existantes dans la base:" . PHP_EOL;
    $tables = DB::select('SHOW TABLES');
    $tableNames = [];
    
    foreach ($tables as $table) {
        $tableName = array_values((array) $table)[0];
        $tableNames[] = $tableName;
        
        // Compter les enregistrements si c'est une table de permission
        if (in_array($tableName, ['permissions', 'roles', 'model_has_permissions', 'model_has_roles', 'role_has_permissions'])) {
            try {
                $count = DB::table($tableName)->count();
                echo "   ✅ {$tableName}: {$count} enregistrements" . PHP_EOL;
            } catch (Exception $e) {
                echo "   ❌ {$tableName}: Erreur - " . $e->getMessage() . PHP_EOL;
            }
        }
    }
    
    echo PHP_EOL;
    
    // Vérifier spécifiquement les tables de permissions
    $permissionTables = ['permissions', 'roles', 'model_has_permissions', 'model_has_roles', 'role_has_permissions'];
    $missingTables = [];
    
    foreach ($permissionTables as $table) {
        if (!in_array($table, $tableNames)) {
            $missingTables[] = $table;
        }
    }
    
    if (empty($missingTables)) {
        echo "✅ Toutes les tables Spatie Permission existent!" . PHP_EOL;
        
        // Vérifier le contenu des permissions
        echo PHP_EOL . "📝 Contenu des permissions:" . PHP_EOL;
        $permissions = DB::table('permissions')->get();
        if ($permissions->count() > 0) {
            foreach ($permissions as $perm) {
                echo "   - {$perm->name} ({$perm->guard_name})" . PHP_EOL;
            }
        } else {
            echo "   ⚠️ Aucune permission trouvée dans la table!" . PHP_EOL;
        }
        
        // Vérifier le contenu des rôles
        echo PHP_EOL . "👑 Contenu des rôles:" . PHP_EOL;
        $roles = DB::table('roles')->get();
        if ($roles->count() > 0) {
            foreach ($roles as $role) {
                echo "   - {$role->name} ({$role->guard_name})" . PHP_EOL;
            }
        } else {
            echo "   ⚠️ Aucun rôle trouvé dans la table!" . PHP_EOL;
        }
        
    } else {
        echo "❌ Tables manquantes: " . implode(', ', $missingTables) . PHP_EOL;
        echo "   Il faut exécuter les migrations Spatie Permission!" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . PHP_EOL;
    echo "   Stack trace: " . $e->getTraceAsString() . PHP_EOL;
}

echo PHP_EOL . "=== FIN VERIFICATION ===" . PHP_EOL;
