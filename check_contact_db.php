<?php

echo "=== VÉRIFICATION DES CONTACTS EN BASE DE DONNÉES ===\n\n";

try {
    $host = 'localhost';
    $dbname = 'iriucbc';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connexion à la base de données réussie\n\n";
    
    // Lister les 5 derniers contacts
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($contacts)) {
        echo "Aucun contact trouvé dans la base de données.\n";
    } else {
        echo "Derniers contacts enregistrés :\n";
        echo str_repeat("=", 80) . "\n";
        
        foreach ($contacts as $contact) {
            echo "ID: " . $contact['id'] . "\n";
            echo "Nom: " . $contact['nom'] . "\n";
            echo "Email: " . $contact['email'] . "\n";
            echo "Sujet: " . $contact['sujet'] . "\n";
            echo "Message: " . substr($contact['message'], 0, 100) . "...\n";
            echo "Statut: " . $contact['statut'] . "\n";
            echo "Créé le: " . $contact['created_at'] . "\n";
            echo str_repeat("-", 50) . "\n";
        }
    }
    
    // Compter le total
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM contacts");
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal de contacts dans la base: " . $total['total'] . "\n";
    
} catch (PDOException $e) {
    echo "✗ Erreur de connexion à la base: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE LA VÉRIFICATION ===\n";
