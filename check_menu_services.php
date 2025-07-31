<?php
// Script pour vérifier les services et leurs champs nom_menu

$host = 'localhost';
$dbname = 'iriadmin';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICATION DES SERVICES ET CHAMPS NOM_MENU ===\n\n";
    
    // Vérifier la structure de la table services
    $sql = "DESCRIBE services";
    $stmt = $pdo->query($sql);
    echo "Structure de la table services:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['Field']} ({$row['Type']}) - Null: {$row['Null']}\n";
    }
    
    echo "\n=== SERVICES AVEC LEURS CHAMPS NOM_MENU ===\n";
    
    // Récupérer tous les services avec leurs champs nom_menu
    $sql = "SELECT id, nom, nom_menu, slug, actif FROM services ORDER BY id";
    $stmt = $pdo->query($sql);
    
    $total = 0;
    $withNomMenu = 0;
    $withoutNomMenu = 0;
    
    while ($service = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $total++;
        $nomMenuStatus = '';
        
        if (empty($service['nom_menu']) || trim($service['nom_menu']) === '') {
            $nomMenuStatus = "❌ VIDE/NULL";
            $withoutNomMenu++;
        } else {
            $nomMenuStatus = "✅ OK";
            $withNomMenu++;
        }
        
        echo sprintf(
            "ID: %d | Nom: %s | Nom Menu: %s [%s] | Slug: %s | Actif: %s\n",
            $service['id'],
            $service['nom'] ?: 'NULL',
            $service['nom_menu'] ?: 'NULL',
            $nomMenuStatus,
            $service['slug'] ?: 'NULL',
            $service['actif'] ? 'Oui' : 'Non'
        );
    }
    
    echo "\n=== RESUME ===\n";
    echo "Total services: $total\n";
    echo "Services avec nom_menu: $withNomMenu\n";
    echo "Services sans nom_menu: $withoutNomMenu\n";
    
    if ($withoutNomMenu > 0) {
        echo "\n⚠️  PROBLEME DETECTE: $withoutNomMenu service(s) n'ont pas de nom_menu défini!\n";
        echo "Ces services ne s'afficheront pas dans le menu.\n";
    }
    
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?>
