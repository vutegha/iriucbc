<?php
// Simple debug script sans dépendances Laravel
$host = 'localhost';
$dbname = 'iriadmin';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUG ACTUALITÉS ET SLUGS ===\n\n";
    
    // Vérifier les colonnes de la table
    $stmt = $pdo->query("SHOW COLUMNS FROM actualites");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Colonnes de la table actualites:\n";
    $hasSlug = false;
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
        if ($column['Field'] === 'slug') {
            $hasSlug = true;
        }
    }
    
    echo "\nColonne slug présente: " . ($hasSlug ? "OUI" : "NON") . "\n\n";
    
    // Compter les actualités
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM actualites");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Nombre total d'actualités: $count\n\n";
    
    if ($count > 0) {
        // Afficher quelques actualités avec leurs slugs
        $stmt = $pdo->query("SELECT id, titre, slug, created_at FROM actualites ORDER BY id DESC LIMIT 5");
        $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Dernières actualités:\n";
        foreach ($actualites as $actu) {
            echo "ID: " . $actu['id'] . "\n";
            echo "Titre: " . substr($actu['titre'], 0, 50) . "...\n";
            echo "Slug: " . ($actu['slug'] ?? 'NULL') . "\n";
            echo "Created: " . $actu['created_at'] . "\n";
            echo "---\n";
        }
        
        // Vérifier les slugs NULL
        $stmt = $pdo->query("SELECT COUNT(*) as null_slugs FROM actualites WHERE slug IS NULL OR slug = ''");
        $nullSlugs = $stmt->fetch(PDO::FETCH_ASSOC)['null_slugs'];
        echo "\nActualités sans slug: $nullSlugs\n";
        
        if ($nullSlugs > 0) {
            echo "⚠️ Il y a des actualités sans slug!\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?>
