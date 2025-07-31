<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 VÉRIFICATION STRUCTURE TABLE NEWSLETTERS\n";
echo "==========================================\n\n";

try {
    // Vérifier la structure de la table newsletters
    $columns = DB::select('DESCRIBE newsletters');
    
    echo "📋 Colonnes existantes :\n";
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }
    echo "\n";
    
    // Vérifier si les nouveaux champs existent
    $requiredFields = ['preferences', 'token', 'last_email_sent', 'emails_sent_count', 'unsubscribe_reason'];
    $existingFields = array_column($columns, 'Field');
    
    echo "🔍 Vérification des nouveaux champs :\n";
    foreach ($requiredFields as $field) {
        if (in_array($field, $existingFields)) {
            echo "  ✅ $field - présent\n";
        } else {
            echo "  ❌ $field - MANQUANT\n";
        }
    }
    echo "\n";
    
    // Test création d'un newsletter
    echo "🧪 Test création Newsletter :\n";
    $testData = [
        'email' => 'test@example.com',
        'token' => 'test123',
        'actif' => true,
        'confirme_a' => now(),
        'preferences' => [
            'actualites' => true,
            'publications' => true,
            'rapports' => false,
            'evenements' => false
        ]
    ];
    
    echo "Données de test :\n";
    print_r($testData);
    
    // Tentative de création (sans sauvegarder)
    $newsletter = new App\Models\Newsletter();
    foreach ($testData as $key => $value) {
        if (in_array($key, $existingFields)) {
            $newsletter->$key = $value;
            echo "  ✅ Champ $key assigné\n";
        } else {
            echo "  ❌ Champ $key non assignable (champ manquant)\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}

echo "\n🎯 CONCLUSION :\n";
echo "Si des champs sont manquants, exécuter : php artisan migrate\n";

?>
