<?php
// Script pour générer les slugs manquants
$host = 'localhost';
$dbname = 'iriadmin';
$username = 'root';
$password = '';

function generateSlug($title, $date) {
    // Supprimer les accents et caractères spéciaux
    $title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
    
    // Convertir en minuscules et remplacer les espaces par des tirets
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    
    // Ajouter la date au début (format YYYYMMDD)
    $datePrefix = date('Ymd', strtotime($date));
    
    return $datePrefix . '-' . $slug;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== GÉNÉRATION DES SLUGS MANQUANTS ===\n\n";
    
    // Récupérer les actualités sans slug
    $stmt = $pdo->query("SELECT id, titre, created_at FROM actualites WHERE slug IS NULL OR slug = ''");
    $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Actualités sans slug trouvées: " . count($actualites) . "\n\n";
    
    if (count($actualites) > 0) {
        $updateStmt = $pdo->prepare("UPDATE actualites SET slug = ? WHERE id = ?");
        
        foreach ($actualites as $actualite) {
            $slug = generateSlug($actualite['titre'], $actualite['created_at']);
            
            // Vérifier l'unicité du slug
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM actualites WHERE slug = ? AND id != ?");
            $checkStmt->execute([$slug, $actualite['id']]);
            $count = $checkStmt->fetchColumn();
            
            // Si le slug existe déjà, ajouter un suffixe
            if ($count > 0) {
                $suffix = 1;
                $originalSlug = $slug;
                do {
                    $slug = $originalSlug . '-' . $suffix;
                    $checkStmt->execute([$slug, $actualite['id']]);
                    $count = $checkStmt->fetchColumn();
                    $suffix++;
                } while ($count > 0);
            }
            
            // Mettre à jour
            $updateStmt->execute([$slug, $actualite['id']]);
            
            echo "ID {$actualite['id']}: {$actualite['titre']} -> $slug\n";
        }
        
        echo "\n✅ Slugs générés avec succès!\n";
    } else {
        echo "✅ Toutes les actualités ont déjà un slug.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
