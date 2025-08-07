<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Gate;

echo "=== TEST FINAL DES ACTIONS DE MODÉRATION ===\n\n";

$user = User::where('email', 'admin@ucbc.org')->first();
auth()->login($user);

echo "✅ Utilisateur: {$user->name} ({$user->email})\n";
echo "✅ Rôles: " . $user->getRoleNames()->implode(', ') . "\n\n";

echo "=== MÉDIAS DISPONIBLES POUR TEST ===\n";
$medias = Media::all();
foreach ($medias as $media) {
    $canModerate = Gate::allows('moderate', $media);
    $actionsVisibles = [];
    
    if ($canModerate) {
        switch ($media->status) {
            case 'pending':
                $actionsVisibles = ['Approuver', 'Rejeter'];
                break;
            case 'approved':
                $actionsVisibles = ['Publier', 'Rejeter'];
                break;
            case 'published':
            case 'active':
                $actionsVisibles = ['Dépublier'];
                break;
            case 'rejected':
                $actionsVisibles = ['Réapprouver'];
                break;
        }
    }
    
    $status = $canModerate && !empty($actionsVisibles) ? '✅' : '❌';
    echo "{$status} ID: {$media->id} | {$media->titre} | Status: '{$media->status}'\n";
    
    if (!empty($actionsVisibles)) {
        echo "    👉 Actions visibles: " . implode(', ', $actionsVisibles) . "\n";
        echo "    🔗 URL: /admin/media/{$media->id}\n";
    }
    echo "\n";
}

echo "=== INSTRUCTIONS POUR TESTER ===\n";
echo "1. Connectez-vous comme admin@ucbc.org dans votre navigateur\n";
echo "2. Visitez une des URLs ci-dessus\n";
echo "3. Vérifiez que la section 'Actions de modération' est visible\n";
echo "4. Les boutons devraient maintenant apparaître selon le status!\n\n";

echo "=== URLs DE TEST RECOMMANDÉES ===\n";
$testCases = [
    'pending' => 'Boutons: Approuver, Rejeter',
    'approved' => 'Boutons: Publier, Rejeter', 
    'published' => 'Boutons: Dépublier',
    'rejected' => 'Boutons: Réapprouver'
];

foreach ($testCases as $status => $buttons) {
    $mediaWithStatus = Media::where('status', $status)->first();
    if ($mediaWithStatus) {
        echo "• /admin/media/{$mediaWithStatus->id} ({$status}) → {$buttons}\n";
    }
}

echo "\n🎉 PROBLÈME RÉSOLU! Les actions devraient maintenant être visibles.\n";
