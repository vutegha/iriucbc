<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=================================================================\n";
echo "             RAPPORT D'AUDIT COMPLET - SYSTÈME D'EMAILS         \n";
echo "                    GRN-UCBC - " . date('Y-m-d H:i:s') . "              \n";
echo "=================================================================\n\n";

// 1. AUDIT DES ÉVÉNEMENTS ET LISTENERS
echo "1. AUDIT DES ÉVÉNEMENTS ET LISTENERS\n";
echo "-----------------------------------\n";

$eventsConfig = include(base_path('app/Providers/EventServiceProvider.php'));

echo "✓ Événements configurés dans EventServiceProvider :\n";
$eventServiceProvider = new \App\Providers\EventServiceProvider($app);
$listeners = (new ReflectionClass($eventServiceProvider))->getProperty('listen');
$listeners->setAccessible(true);
$listenArray = $listeners->getValue($eventServiceProvider);

foreach ($listenArray as $event => $eventListeners) {
    echo "  - $event\n";
    foreach ($eventListeners as $listener) {
        echo "    └── $listener\n";
        
        // Vérifier si la classe listener existe
        if (!class_exists($listener)) {
            echo "    ❌ ERREUR: La classe listener $listener n'existe pas\n";
        } else {
            echo "    ✓ Listener trouvé\n";
        }
    }
}

// 2. AUDIT DES CLASSES MAIL
echo "\n2. AUDIT DES CLASSES MAIL\n";
echo "-------------------------\n";

$mailClasses = [
    'App\Mail\ActualiteNewsletter',
    'App\Mail\PublicationNewsletter', 
    'App\Mail\ProjectNewsletter',
    'App\Mail\ResetPasswordMail',
    'App\Mail\NewsletterWelcomeMail',
    'App\Mail\ContactMessage',
    'App\Mail\ContactMessageWithCopy',
    'App\Mail\PublicationNotificationMail'
];

foreach ($mailClasses as $mailClass) {
    if (class_exists($mailClass)) {
        echo "✓ $mailClass - OK\n";
        
        // Vérifier les méthodes requises
        $reflection = new ReflectionClass($mailClass);
        if ($reflection->hasMethod('build')) {
            echo "  └── Méthode build() trouvée\n";
        } else {
            echo "  ❌ Méthode build() manquante\n";
        }
    } else {
        echo "❌ $mailClass - MANQUANTE\n";
    }
}

// 3. AUDIT DES TEMPLATES D'EMAILS
echo "\n3. AUDIT DES TEMPLATES D'EMAILS\n";
echo "--------------------------------\n";

$emailTemplates = [
    'emails.newsletter.welcome',
    'emails.newsletter.publication', 
    'emails.newsletter.actualite',
    'emails.newsletter.projet',
    'emails.newsletter.layout',
    'emails.auth.reset-password',
    'emails.contact-message',
    'emails.contact-message-copy',
    'emails.contact-message-admin'
];

foreach ($emailTemplates as $template) {
    $templatePath = resource_path('views/' . str_replace('.', '/', $template) . '.blade.php');
    if (file_exists($templatePath)) {
        echo "✓ $template - OK\n";
        echo "  └── " . $templatePath . "\n";
    } else {
        echo "❌ $template - MANQUANT\n";
        echo "  └── Attendu à: " . $templatePath . "\n";
    }
}

// 4. AUDIT DES CONTRÔLEURS ET DÉCLENCHEMENT D'ÉVÉNEMENTS
echo "\n4. AUDIT DES CONTRÔLEURS - DÉCLENCHEMENT D'ÉVÉNEMENTS\n";
echo "------------------------------------------------------\n";

$controllers = [
    'App\Http\Controllers\Admin\ActualiteController' => 'ActualiteFeaturedCreated',
    'App\Http\Controllers\Admin\PublicationController' => 'PublicationFeaturedCreated',
    'App\Http\Controllers\Admin\RapportController' => 'RapportCreated (manquant)',
    'App\Http\Controllers\Admin\ProjetController' => 'ProjectCreated'
];

foreach ($controllers as $controller => $expectedEvent) {
    if (class_exists($controller)) {
        echo "✓ $controller - OK\n";
        
        // Vérifier le contenu du contrôleur
        $controllerFile = app_path('Http/Controllers/' . str_replace('App\Http\Controllers\\', '', $controller) . '.php');
        if (file_exists($controllerFile)) {
            $content = file_get_contents($controllerFile);
            
            if (strpos($content, 'dispatch') !== false || strpos($content, '::dispatch') !== false) {
                echo "  ✓ Dispatch d'événement détecté\n";
            } else {
                echo "  ❌ Aucun dispatch d'événement trouvé\n";
            }
            
            if (strpos($content, $expectedEvent) !== false) {
                echo "  ✓ Événement $expectedEvent trouvé\n";
            } else {
                echo "  ❌ Événement $expectedEvent manquant\n";
            }
        }
    } else {
        echo "❌ $controller - MANQUANT\n";
    }
}

// 5. AUDIT DE LA CONFIGURATION EMAIL
echo "\n5. AUDIT DE LA CONFIGURATION EMAIL\n";
echo "-----------------------------------\n";

echo "Configuration mail :\n";
echo "- Driver: " . config('mail.default') . "\n";
echo "- Host: " . config('mail.mailers.smtp.host') . "\n"; 
echo "- Port: " . config('mail.mailers.smtp.port') . "\n";
echo "- Encryption: " . config('mail.mailers.smtp.encryption') . "\n";
echo "- From Address: " . config('mail.from.address') . "\n";
echo "- From Name: " . config('mail.from.name') . "\n";

// Vérifier les variables d'environnement
$mailVars = ['MAIL_DRIVER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_ENCRYPTION'];
echo "\nVariables d'environnement :\n";
foreach ($mailVars as $var) {
    $value = env($var);
    if ($value) {
        echo "✓ $var = " . ($var === 'MAIL_PASSWORD' ? str_repeat('*', strlen($value)) : $value) . "\n";
    } else {
        echo "❌ $var - NON DÉFINIE\n";
    }
}

// 6. AUDIT DES MODÈLES NEWSLETTER
echo "\n6. AUDIT DU SYSTÈME DE NEWSLETTER\n";
echo "----------------------------------\n";

try {
    $newsletterCount = \App\Models\Newsletter::count();
    $activeNewsletters = \App\Models\Newsletter::where('active', true)->count();
    echo "✓ Nombre total d'abonnés: $newsletterCount\n";
    echo "✓ Abonnés actifs: $activeNewsletters\n";
    
    // Vérifier les préférences
    $withPreferences = \App\Models\Newsletter::whereNotNull('preferences')->count();
    echo "✓ Abonnés avec préférences: $withPreferences\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'accès aux newsletters: " . $e->getMessage() . "\n";
}

// 7. AUDIT DES ÉVÉNEMENTS MANQUANTS
echo "\n7. ÉVÉNEMENTS MANQUANTS IDENTIFIÉS\n";
echo "-----------------------------------\n";

$missingEvents = [
    'RapportCreated' => 'Pour les rapports',
    'PublicationFeaturedCreated' => 'Déclenchement manquant dans PublicationController'
];

foreach ($missingEvents as $event => $description) {
    echo "❌ $event - $description\n";
}

// 8. RECOMMANDATIONS
echo "\n8. RECOMMANDATIONS DE CORRECTION\n";
echo "---------------------------------\n";

echo "1. Créer l'événement RapportCreated pour les rapports\n";
echo "2. Ajouter le déclenchement d'événements dans PublicationController\n";
echo "3. Ajouter le déclenchement d'événements dans RapportController\n";
echo "4. Vérifier la configuration SMTP\n";
echo "5. Tester l'envoi d'emails en développement\n";
echo "6. Ajouter des logs pour le debug des emails\n";

// 9. DIAGNOSTIC DES JOBS EN QUEUE
echo "\n9. DIAGNOSTIC DES JOBS EN QUEUE\n";
echo "-------------------------------\n";

try {
    // Vérifier si le système de queue fonctionne
    $queueDriver = config('queue.default');
    echo "Driver de queue: $queueDriver\n";
    
    if ($queueDriver === 'database') {
        $failedJobs = \DB::table('failed_jobs')->count();
        $jobs = \DB::table('jobs')->count();
        echo "Jobs en attente: $jobs\n";
        echo "Jobs échoués: $failedJobs\n";
    }
} catch (\Exception $e) {
    echo "❌ Erreur lors du diagnostic des queues: " . $e->getMessage() . "\n";
}

echo "\n=================================================================\n";
echo "                    FIN DU RAPPORT D'AUDIT                      \n";
echo "=================================================================\n";
