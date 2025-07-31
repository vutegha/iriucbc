<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configuration de la base de données
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'iriadmin',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== Correction Table Newsletter ===\n\n";

try {
    // 1. Vérifier la structure actuelle
    echo "1. Structure actuelle de la table newsletters:\n";
    $columns = Capsule::select("DESCRIBE newsletters");
    foreach ($columns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }
    echo "\n";

    // 2. Ajouter les colonnes manquantes une par une
    echo "2. Ajout des colonnes manquantes:\n";
    
    // Vérifier et ajouter la colonne preferences
    $hasPreferences = false;
    foreach ($columns as $column) {
        if ($column->Field === 'preferences') {
            $hasPreferences = true;
            break;
        }
    }
    
    if (!$hasPreferences) {
        echo "   - Ajout de la colonne 'preferences'...\n";
        Capsule::statement("ALTER TABLE newsletters ADD COLUMN preferences JSON NULL");
        echo "   ✅ Colonne 'preferences' ajoutée\n";
    } else {
        echo "   - Colonne 'preferences' déjà présente\n";
    }

    // Vérifier et ajouter last_email_sent
    $hasLastEmailSent = false;
    foreach ($columns as $column) {
        if ($column->Field === 'last_email_sent') {
            $hasLastEmailSent = true;
            break;
        }
    }
    
    if (!$hasLastEmailSent) {
        echo "   - Ajout de la colonne 'last_email_sent'...\n";
        Capsule::statement("ALTER TABLE newsletters ADD COLUMN last_email_sent TIMESTAMP NULL");
        echo "   ✅ Colonne 'last_email_sent' ajoutée\n";
    } else {
        echo "   - Colonne 'last_email_sent' déjà présente\n";
    }

    // Vérifier et ajouter emails_sent_count
    $hasEmailsSentCount = false;
    foreach ($columns as $column) {
        if ($column->Field === 'emails_sent_count') {
            $hasEmailsSentCount = true;
            break;
        }
    }
    
    if (!$hasEmailsSentCount) {
        echo "   - Ajout de la colonne 'emails_sent_count'...\n";
        Capsule::statement("ALTER TABLE newsletters ADD COLUMN emails_sent_count INT DEFAULT 0");
        echo "   ✅ Colonne 'emails_sent_count' ajoutée\n";
    } else {
        echo "   - Colonne 'emails_sent_count' déjà présente\n";
    }

    // Vérifier et ajouter unsubscribe_reason
    $hasUnsubscribeReason = false;
    foreach ($columns as $column) {
        if ($column->Field === 'unsubscribe_reason') {
            $hasUnsubscribeReason = true;
            break;
        }
    }
    
    if (!$hasUnsubscribeReason) {
        echo "   - Ajout de la colonne 'unsubscribe_reason'...\n";
        Capsule::statement("ALTER TABLE newsletters ADD COLUMN unsubscribe_reason TEXT NULL");
        echo "   ✅ Colonne 'unsubscribe_reason' ajoutée\n";
    } else {
        echo "   - Colonne 'unsubscribe_reason' déjà présente\n";
    }

    echo "\n";

    // 3. Vérifier la nouvelle structure
    echo "3. Nouvelle structure de la table newsletters:\n";
    $newColumns = Capsule::select("DESCRIBE newsletters");
    foreach ($newColumns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }
    echo "\n";

    // 4. Test d'insertion
    echo "4. Test d'insertion avec les nouvelles colonnes:\n";
    $testEmail = 'test_fix_' . time() . '@example.com';
    
    $result = Capsule::table('newsletters')->insert([
        'email' => $testEmail,
        'nom' => 'Test Fix',
        'token' => bin2hex(random_bytes(16)),
        'actif' => 1,
        'preferences' => json_encode(['actualites' => true]),
        'last_email_sent' => null,
        'emails_sent_count' => 0,
        'unsubscribe_reason' => null,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    if ($result) {
        echo "   ✅ Test d'insertion réussi pour: $testEmail\n";
    } else {
        echo "   ❌ Échec du test d'insertion\n";
    }

    echo "\n✅ Correction de la table newsletters terminée avec succès !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
