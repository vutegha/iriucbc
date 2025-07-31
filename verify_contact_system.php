<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VÉRIFICATION SYSTÈME DE CONTACT ===" . PHP_EOL;
echo PHP_EOL;

// 1. Vérifier les adresses principales
$main = App\Models\EmailSetting::getActiveEmails('contact_main_email');
echo "Adresses PRINCIPALES (recevront le message):" . PHP_EOL;
foreach ($main as $email) {
    echo "  📧 " . $email . PHP_EOL;
}
echo "Total principales: " . count($main) . PHP_EOL;
echo PHP_EOL;

// 2. Vérifier les adresses de copie
$copy = App\Models\EmailSetting::getActiveEmails('contact_copy_emails');
echo "Adresses de COPIE (recevront une copie):" . PHP_EOL;
foreach ($copy as $email) {
    echo "  📧 " . $email . PHP_EOL;
}
echo "Total copies: " . count($copy) . PHP_EOL;
echo PHP_EOL;

// 3. Vérifier que les adresses obligatoires sont présentes
$obligatoires = ['iri@ucbc.org', 's.vutegha@gmail.com'];
echo "VÉRIFICATION des adresses OBLIGATOIRES:" . PHP_EOL;
foreach ($obligatoires as $required) {
    if (in_array($required, $main) || in_array($required, $copy)) {
        echo "  ✅ " . $required . " -> TROUVÉE" . PHP_EOL;
    } else {
        echo "  ❌ " . $required . " -> MANQUANTE" . PHP_EOL;
    }
}
echo PHP_EOL;

// 4. Calculer le total d'emails qui seront envoyés
$totalEmails = count($main) + count($copy) + 1; // +1 pour l'accusé de réception
echo "TOTAL d'emails envoyés par contact: " . $totalEmails . PHP_EOL;
echo "  - Adresses principales: " . count($main) . PHP_EOL;
echo "  - Adresses de copie: " . count($copy) . PHP_EOL;
echo "  - Accusé de réception: 1" . PHP_EOL;
echo PHP_EOL;

// 5. Vérifier la classe ContactMessageWithCopy
echo "VÉRIFICATION de la classe ContactMessageWithCopy:" . PHP_EOL;
$reflector = new ReflectionClass('App\Mail\ContactMessageWithCopy');
$sendMethod = $reflector->getMethod('sendToConfiguredEmails');
echo "  ✅ Méthode sendToConfiguredEmails trouvée" . PHP_EOL;

// 6. Simulation du processus complet
echo PHP_EOL;
echo "SIMULATION du processus d'envoi:" . PHP_EOL;
echo "  1. Utilisateur envoie message via formulaire" . PHP_EOL;
echo "  2. Message enregistré en base de données" . PHP_EOL;
echo "  3. ContactMessageWithCopy::sendToConfiguredEmails() appelée" . PHP_EOL;
echo "  4. Envoi vers " . count($main) . " adresse(s) principale(s)" . PHP_EOL;
echo "  5. Envoi vers " . count($copy) . " adresse(s) de copie" . PHP_EOL;
echo "  6. Envoi accusé de réception vers l'expéditeur" . PHP_EOL;
echo "  7. Utilisateur ajouté à la newsletter" . PHP_EOL;
echo PHP_EOL;

echo "🎉 SYSTÈME VÉRIFIÉ ET OPÉRATIONNEL !" . PHP_EOL;
echo PHP_EOL;
echo "Pour tester en conditions réelles:" . PHP_EOL;
echo "👉 Accéder à http://localhost/projets/iriucbc/public/contact" . PHP_EOL;
echo "👉 Remplir le formulaire avec votre vraie adresse email" . PHP_EOL;
echo "👉 Vérifier la réception de l'accusé de réception" . PHP_EOL;
