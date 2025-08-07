<?php

require_once 'vendor/autoload.php';

// Configuration de la base de données
$config = [
    'host' => 'localhost',
    'database' => 'iriadmin',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4", 
        $config['username'], 
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "=== ÉTAT ACTUEL DE LA BASE DE DONNÉES ===\n";
    echo "Base de données: " . $config['database'] . "\n";
    echo "Date: " . date('Y-m-d H:i:s') . "\n\n";
    
    // Obtenir la liste des tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📊 TABLES TROUVÉES (" . count($tables) . "):\n";
    echo str_repeat("-", 50) . "\n";
    
    if (empty($tables)) {
        echo "❌ AUCUNE TABLE TROUVÉE!\n";
        echo "🚨 La base de données est complètement vide.\n\n";
    } else {
        foreach ($tables as $table) {
            // Compter les enregistrements dans chaque table
            try {
                $countStmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
                $count = $countStmt->fetch()['count'];
                echo "✅ $table: $count enregistrements\n";
            } catch (Exception $e) {
                echo "❌ $table: Erreur lors du comptage\n";
            }
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🔍 ANALYSE DE LA SITUATION:\n\n";
    
    if (count($tables) == 0) {
        echo "🚨 CRITIQUE: Base de données complètement vide\n";
        echo "📋 Action recommandée: Migration fresh complète\n";
    } elseif (count($tables) < 10) {
        echo "⚠️ PROBLÈME: Très peu de tables présentes\n";
        echo "📋 Action recommandée: Vérifier et restaurer les tables manquantes\n";
    } else {
        echo "✅ NORMAL: Structure de base présente\n";
        echo "📋 Action recommandée: Vérifier l'intégrité des données\n";
    }
    
    // Vérifier les tables critiques
    $criticalTables = [
        'users', 'roles', 'permissions', 'services', 
        'actualites', 'projets', 'evenements', 'publications',
        'model_has_roles', 'model_has_permissions', 'role_has_permissions'
    ];
    
    echo "\n🔧 VÉRIFICATION DES TABLES CRITIQUES:\n";
    echo str_repeat("-", 50) . "\n";
    
    foreach ($criticalTables as $criticalTable) {
        if (in_array($criticalTable, $tables)) {
            echo "✅ $criticalTable: Présente\n";
        } else {
            echo "❌ $criticalTable: MANQUANTE\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ ERREUR DE CONNEXION À LA BASE DE DONNÉES:\n";
    echo $e->getMessage() . "\n";
    echo "\n🔧 VÉRIFIEZ:\n";
    echo "- XAMPP est démarré\n";
    echo "- MySQL est actif\n";
    echo "- La base 'iriadmin' existe\n";
    echo "- Les paramètres de connexion sont corrects\n";
}
