<?php

// Test simple de connexion MySQL et vérification des tables
$host = '127.0.0.1';
$dbname = 'iriadmin';
$username = 'root';
$password = '';

echo "=== VERIFICATION TABLES PERMISSIONS (MySQL Direct) ===" . PHP_EOL;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base '$dbname' réussie!" . PHP_EOL;
    echo PHP_EOL;
    
    // Lister toutes les tables
    echo "📋 Toutes les tables:" . PHP_EOL;
    $stmt = $pdo->query("SHOW TABLES");
    $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($allTables as $table) {
        echo "   - $table" . PHP_EOL;
    }
    
    echo PHP_EOL;
    
    // Chercher spécifiquement les tables de permissions
    echo "🔍 Tables liées aux permissions:" . PHP_EOL;
    $permissionTables = ['permissions', 'roles', 'model_has_permissions', 'model_has_roles', 'role_has_permissions'];
    $foundTables = [];
    
    foreach ($permissionTables as $table) {
        if (in_array($table, $allTables)) {
            $foundTables[] = $table;
            
            // Compter les enregistrements
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $countStmt->fetchColumn();
            echo "   ✅ $table: $count enregistrements" . PHP_EOL;
            
            // Si c'est la table permissions ou roles, afficher le contenu
            if (($table === 'permissions' || $table === 'roles') && $count > 0) {
                $contentStmt = $pdo->query("SELECT name, guard_name FROM `$table` LIMIT 10");
                $content = $contentStmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($content as $row) {
                    echo "      - {$row['name']} ({$row['guard_name']})" . PHP_EOL;
                }
            }
        } else {
            echo "   ❌ $table: Table non trouvée" . PHP_EOL;
        }
    }
    
    echo PHP_EOL;
    
    // Résumé
    if (count($foundTables) === count($permissionTables)) {
        echo "✅ TOUTES les tables Spatie Permission existent!" . PHP_EOL;
        
        // Vérifier s'il y a des données
        $hasPermissions = false;
        $hasRoles = false;
        
        if (in_array('permissions', $foundTables)) {
            $permCount = $pdo->query("SELECT COUNT(*) FROM permissions")->fetchColumn();
            $hasPermissions = $permCount > 0;
            echo "   Permissions: $permCount" . PHP_EOL;
        }
        
        if (in_array('roles', $foundTables)) {
            $roleCount = $pdo->query("SELECT COUNT(*) FROM roles")->fetchColumn();
            $hasRoles = $roleCount > 0;
            echo "   Rôles: $roleCount" . PHP_EOL;
        }
        
        if (!$hasPermissions && !$hasRoles) {
            echo "⚠️ PROBLÈME: Les tables existent mais sont VIDES!" . PHP_EOL;
            echo "   Il faut créer les permissions et rôles via un seeder." . PHP_EOL;
        }
        
    } else {
        $missingTables = array_diff($permissionTables, $foundTables);
        echo "❌ Tables manquantes: " . implode(', ', $missingTables) . PHP_EOL;
        echo "   Il faut exécuter: php artisan migrate" . PHP_EOL;
    }
    
} catch (PDOException $e) {
    echo "❌ ERREUR de connexion: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== FIN VERIFICATION ===" . PHP_EOL;
