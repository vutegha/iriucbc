<?php

echo "=== DIAGNOSTIC DE CONFIGURATION DU SYSTÈME ===\n\n";

// 1. Vérifier les extensions PHP nécessaires
echo "1. Extensions PHP nécessaires :\n";
$requiredExtensions = ['pdo', 'pdo_mysql', 'curl', 'openssl', 'mbstring'];

foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "   ✓ $ext\n";
    } else {
        echo "   ✗ $ext (MANQUANT)\n";
    }
}
echo "\n";

// 2. Vérifier les fichiers clés
echo "2. Fichiers clés du système :\n";
$keyFiles = [
    'app/Mail/ContactMessageWithCopy.php',
    'app/Models/EmailSetting.php', 
    'app/Http/Controllers/Site/SiteController.php',
    'app/Http/Requests/ContactRequest.php',
    'resources/views/contact.blade.php'
];

foreach ($keyFiles as $file) {
    if (file_exists($file)) {
        echo "   ✓ $file\n";
    } else {
        echo "   ✗ $file (MANQUANT)\n";
    }
}
echo "\n";

// 3. Vérifier le fichier .env
echo "3. Configuration .env :\n";
if (file_exists('.env')) {
    echo "   ✓ Fichier .env trouvé\n";
    
    $envContent = file_get_contents('.env');
    
    // Vérifier les variables de mail
    $mailVars = ['MAIL_MAILER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD'];
    foreach ($mailVars as $var) {
        if (strpos($envContent, $var) !== false) {
            echo "   ✓ $var défini\n";
        } else {
            echo "   ⚠ $var non trouvé\n";
        }
    }
} else {
    echo "   ✗ Fichier .env manquant\n";
}
echo "\n";

// 4. Tester l'autoload Composer
echo "4. Test de l'autoload Composer :\n";
if (file_exists('vendor/autoload.php')) {
    echo "   ✓ vendor/autoload.php trouvé\n";
    try {
        require_once 'vendor/autoload.php';
        echo "   ✓ Autoload chargé avec succès\n";
    } catch (Exception $e) {
        echo "   ✗ Erreur lors du chargement de l'autoload: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ✗ vendor/autoload.php manquant - exécutez 'composer install'\n";
}
echo "\n";

// 5. Test de l'application Laravel basique
echo "5. Test de l'application Laravel :\n";
try {
    require_once 'vendor/autoload.php';
    
    // Vérifier les classes clés
    $classes = [
        'App\Mail\ContactMessageWithCopy',
        'App\Models\EmailSetting',
        'App\Http\Controllers\Site\SiteController',
        'App\Http\Requests\ContactRequest'
    ];
    
    foreach ($classes as $class) {
        if (class_exists($class)) {
            echo "   ✓ Classe $class accessible\n";
        } else {
            echo "   ✗ Classe $class non trouvée\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ✗ Erreur lors du test des classes: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Test de connexion à la base de données
echo "6. Test de connexion à la base de données :\n";
try {
    $host = 'localhost';
    $dbname = 'iriucbc';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "   ✓ Connexion à la base de données réussie\n";
    
    // Vérifier les tables importantes
    $tables = ['email_settings', 'contacts'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "   ✓ Table $table existe\n";
        } else {
            echo "   ✗ Table $table manquante\n";
        }
    }
    
} catch (PDOException $e) {
    echo "   ✗ Erreur de connexion à la base: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== FIN DU DIAGNOSTIC ===\n";
