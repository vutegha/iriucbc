<?php
// Script pour vérifier les actualités avec des dates nulles

$host = 'localhost';
$dbname = 'iriadmin';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICATION DES DATES NULLES DANS LES ACTUALITES ===\n\n";
    
    // Vérifier les actualités avec created_at null
    $sql = "SELECT id, titre, created_at, updated_at FROM actualites WHERE created_at IS NULL OR updated_at IS NULL";
    $stmt = $pdo->query($sql);
    
    $nullDates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($nullDates)) {
        echo "✅ Aucune actualité avec des dates nulles trouvée.\n";
    } else {
        echo "⚠️  Actualités avec des dates nulles :\n";
        foreach ($nullDates as $actualite) {
            echo sprintf(
                "ID: %d | Titre: %s | Created: %s | Updated: %s\n",
                $actualite['id'],
                $actualite['titre'] ?: 'Sans titre',
                $actualite['created_at'] ?: 'NULL',
                $actualite['updated_at'] ?: 'NULL'
            );
        }
        
        echo "\n=== CORRECTION AUTOMATIQUE ===\n";
        
        // Corriger les dates nulles
        $updateSql = "UPDATE actualites SET 
                        created_at = COALESCE(created_at, NOW()),
                        updated_at = COALESCE(updated_at, NOW())
                      WHERE created_at IS NULL OR updated_at IS NULL";
        
        $result = $pdo->exec($updateSql);
        echo "✅ $result actualité(s) corrigée(s).\n";
    }
    
    // Statistiques générales
    echo "\n=== STATISTIQUES ===\n";
    $sql = "SELECT COUNT(*) as total FROM actualites";
    $stmt = $pdo->query($sql);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "Total actualités : $total\n";
    
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?>
