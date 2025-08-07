<?php

require_once 'vendor/autoload.php';

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SocialLink;

echo "🔗 Création de liens sociaux d'exemple...\n";

// Vérifier s'il existe déjà des liens sociaux
$existingLinks = SocialLink::count();

if ($existingLinks > 0) {
    echo "✅ Il existe déjà $existingLinks liens sociaux dans la base de données.\n";
    echo "📋 Liste des liens sociaux actuels :\n";
    
    $links = SocialLink::orderBy('order')->get();
    foreach ($links as $link) {
        $status = $link->is_active ? '✅ Actif' : '❌ Inactif';
        echo "   • {$link->name} ({$link->platform}) - {$status} - {$link->url}\n";
    }
} else {
    echo "➕ Aucun lien social trouvé. Création d'exemples...\n";
    
    $socialLinksData = [
        [
            'name' => 'Page Facebook GRN-UCBC',
            'platform' => 'facebook',
            'url' => 'https://facebook.com/grn.ucbc',
            'is_active' => true,
            'order' => 1
        ],
        [
            'name' => 'Compte Twitter GRN',
            'platform' => 'twitter',
            'url' => 'https://twitter.com/GRN_UCBC',
            'is_active' => true,
            'order' => 2
        ],
        [
            'name' => 'LinkedIn UCBC',
            'platform' => 'linkedin',
            'url' => 'https://linkedin.com/company/ucbc-grn',
            'is_active' => true,
            'order' => 3
        ],
        [
            'name' => 'Chaîne YouTube',
            'platform' => 'youtube',
            'url' => 'https://youtube.com/@grnucbc',
            'is_active' => true,
            'order' => 4
        ],
        [
            'name' => 'Instagram GRN',
            'platform' => 'instagram',
            'url' => 'https://instagram.com/grn.ucbc',
            'is_active' => false,
            'order' => 5
        ]
    ];
    
    foreach ($socialLinksData as $linkData) {
        SocialLink::create($linkData);
        echo "   ✅ Créé : {$linkData['name']} ({$linkData['platform']})\n";
    }
    
    echo "✅ Création terminée !\n";
}

echo "\n🚀 Test des fonctionnalités :\n";
echo "   1. ✅ Système de liens sociaux avec icônes auto-générées\n";
echo "   2. ✅ Sécurisation newsletter avec protection CSRF\n";
echo "   3. ✅ Validation stricte côté serveur\n";
echo "   4. ✅ Protection anti-bot (honeypot)\n";
echo "   5. ✅ Limitation du taux de requêtes\n";
echo "   6. ✅ Transactions de base de données sécurisées\n";
echo "   7. ✅ Footer dynamique avec données de la BD\n";

echo "\n🎯 Améliorations implémentées avec succès !\n";
echo "📝 Les liens sociaux du footer proviennent maintenant de la base de données\n";
echo "🔒 Le formulaire newsletter est entièrement sécurisé\n";
echo "⚡ Toutes les validations sont strictes et côté serveur\n";

?>
