<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Site\SiteController;
use App\Models\Contact;
use App\Mail\ContactMessageWithCopy;
use Illuminate\Support\Facades\Log;

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DE DÉBOGAGE DU FORMULAIRE DE CONTACT ===\n\n";

// Simuler les données du formulaire
$testData = [
    'nom' => 'Test Contact Form',
    'email' => 'test.contact.form@example.com',
    'sujet' => 'Test automatique du formulaire',
    'message' => 'Ceci est un test pour vérifier pourquoi le formulaire de contact ne déclenche pas l\'envoi d\'emails automatiques.'
];

echo "1. Données de test préparées :\n";
foreach ($testData as $key => $value) {
    echo "   - $key: $value\n";
}
echo "\n";

// Créer une fausse requête
$request = Request::create('/contact', 'POST', $testData);
$request->headers->set('X-CSRF-TOKEN', csrf_token());

echo "2. Requête simulée créée\n\n";

// Vérifier la configuration email
echo "3. Vérification de la configuration email :\n";

try {
    $emailSettings = \App\Models\EmailSetting::first();
    if ($emailSettings) {
        echo "   ✓ Configuration email trouvée\n";
        echo "   - Email principal: " . $emailSettings->main_email . "\n";
        echo "   - Emails en copie: " . count($emailSettings->copy_emails ?? []) . " adresses\n";
        echo "   - Emails accusé réception: " . count($emailSettings->acknowledgment_emails ?? []) . " adresses\n";
    } else {
        echo "   ✗ ERREUR: Aucune configuration email trouvée\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ✗ ERREUR lors de la vérification config: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n4. Test d'envoi direct avec ContactMessageWithCopy :\n";

try {
    // Créer d'abord un contact dans la base de données
    $contact = Contact::create([
        'nom' => $testData['nom'],
        'email' => $testData['email'],
        'sujet' => $testData['sujet'],
        'message' => $testData['message'],
        'statut' => 'nouveau'
    ]);
    
    echo "   ✓ Contact créé avec ID: " . $contact->id . "\n";
    
    // Tester l'envoi d'email
    $emailResult = ContactMessageWithCopy::sendToConfiguredEmails($contact);
    
    if ($emailResult && $emailResult['success']) {
        echo "   ✓ EMAILS ENVOYÉS AVEC SUCCÈS!\n";
        echo "   - Total envoyé: " . $emailResult['total_sent'] . "\n";
        echo "   - Destinataires principaux: " . implode(', ', $emailResult['main_recipients']) . "\n";
        echo "   - Destinataires copie: " . implode(', ', $emailResult['copy_recipients']) . "\n";
        echo "   - Destinataires accusé: " . implode(', ', $emailResult['acknowledgment_recipients']) . "\n";
    } else {
        echo "   ✗ ÉCHEC D'ENVOI D'EMAIL\n";
        if (isset($emailResult['error'])) {
            echo "   Erreur: " . $emailResult['error'] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ✗ ERREUR lors du test d'envoi: " . $e->getMessage() . "\n";
    echo "   Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n5. Test via le contrôleur SiteController :\n";

try {
    $controller = new SiteController();
    
    // Simuler la méthode storeContact
    echo "   - Appel de storeContact...\n";
    
    // Note: On ne peut pas facilement tester la redirection ici,
    // mais on peut au moins vérifier que la méthode existe et est appelable
    if (method_exists($controller, 'storeContact')) {
        echo "   ✓ Méthode storeContact existe\n";
    } else {
        echo "   ✗ Méthode storeContact N'EXISTE PAS\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ ERREUR lors du test du contrôleur: " . $e->getMessage() . "\n";
}

echo "\n6. Vérification de la route :\n";

try {
    $routes = app('router')->getRoutes();
    $contactRoute = null;
    
    foreach ($routes as $route) {
        if ($route->getName() === 'site.contact.store') {
            $contactRoute = $route;
            break;
        }
    }
    
    if ($contactRoute) {
        echo "   ✓ Route 'site.contact.store' trouvée\n";
        echo "   - URI: " . $contactRoute->uri() . "\n";
        echo "   - Méthodes: " . implode(', ', $contactRoute->methods()) . "\n";
        echo "   - Action: " . $contactRoute->getActionName() . "\n";
    } else {
        echo "   ✗ Route 'site.contact.store' NON TROUVÉE\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ ERREUR lors de la vérification de route: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
