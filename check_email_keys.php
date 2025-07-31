<?php

echo "=== VÉRIFICATION DES CLÉS EMAIL SETTINGS ===\n\n";

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=iriadmin;charset=utf8", 'root', '');
    
    $stmt = $pdo->query("SELECT * FROM email_settings ORDER BY id");
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($settings)) {
        echo "Aucune configuration email trouvée!\n";
        exit(1);
    }
    
    echo "Configurations email trouvées:\n";
    echo str_repeat("=", 80) . "\n";
    
    foreach ($settings as $setting) {
        echo "ID: " . $setting['id'] . "\n";
        echo "Clé: " . $setting['key'] . "\n";
        echo "Label: " . $setting['label'] . "\n";
        echo "Email principal: " . $setting['main_email'] . "\n";
        echo "Active: " . ($setting['active'] ? 'OUI' : 'NON') . "\n";
        
        $copyEmails = json_decode($setting['copy_emails'], true);
        $ackEmails = json_decode($setting['acknowledgment_emails'], true);
        
        echo "Emails copie (" . count($copyEmails ?? []) . "):\n";
        foreach ($copyEmails ?? [] as $email) {
            echo "  - $email\n";
        }
        
        echo "Emails accusé (" . count($ackEmails ?? []) . "):\n";
        foreach ($ackEmails ?? [] as $email) {
            echo "  - $email\n";
        }
        
        echo str_repeat("-", 50) . "\n";
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE LA VÉRIFICATION ===\n";
