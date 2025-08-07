<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;

echo "=== GUIDE DE DÉBOGAGE DES ERREURS DE MODÉRATION ===\n\n";

$user = User::where('email', 'admin@ucbc.org')->first();
$media = Media::where('status', 'approved')->first();

if (!$user || !$media) {
    echo "❌ Utilisateur ou média avec status 'approved' introuvable\n";
    
    // Créer un média de test si nécessaire
    if (!$media) {
        $media = new Media();
        $media->titre = 'Test Debug';
        $media->medias = 'test-debug.jpg';
        $media->status = 'approved';
        $media->is_public = false;
        $media->created_by = $user->id ?? 1;
        $media->save();
        echo "✅ Média de test créé avec ID: {$media->id}\n";
    }
}

echo "=== INSTRUCTIONS DE DÉBOGAGE ===\n\n";

echo "1. 🌐 OUVREZ VOTRE NAVIGATEUR et allez sur:\n";
echo "   http://localhost/admin/media/{$media->id}\n\n";

echo "2. 🔧 OUVREZ LES OUTILS DE DÉVELOPPEMENT (F12)\n";
echo "   - Allez dans l'onglet 'Console'\n";
echo "   - Allez dans l'onglet 'Network' (Réseau)\n\n";

echo "3. 🎯 CLIQUEZ SUR UN BOUTON DE MODÉRATION\n";
echo "   - Par exemple 'Publier' ou 'Rejeter'\n";
echo "   - Remplissez le commentaire si demandé\n\n";

echo "4. 🔍 VÉRIFIEZ LA CONSOLE:\n";
echo "   - Vous devriez voir les logs '=== DEBUG MODÉRATION ==='\n";
echo "   - Notez l'URL appelée et les données envoyées\n\n";

echo "5. 🌐 VÉRIFIEZ L'ONGLET NETWORK:\n";
echo "   - Cherchez une requête POST vers /admin/media/{$media->id}/approve (ou autre)\n";
echo "   - Cliquez dessus pour voir les détails\n";
echo "   - Vérifiez le code de statut:\n";
echo "     * 200 = Succès\n";
echo "     * 403 = Permissions refusées\n";
echo "     * 422 = Erreur de validation\n";
echo "     * 500 = Erreur serveur\n\n";

echo "6. 📄 VÉRIFIEZ LA RÉPONSE:\n";
echo "   - Dans l'onglet Network, regardez la 'Response'\n";
echo "   - Si c'est du JSON, vérifiez success: true/false\n";
echo "   - Si c'est du HTML, c'est probablement une erreur serveur\n\n";

echo "7. 📋 COPIEZ LES INFORMATIONS D'ERREUR:\n";
echo "   - Message d'erreur exact\n";
echo "   - Code de statut HTTP\n";
echo "   - URL appelée\n";
echo "   - Réponse du serveur\n\n";

echo "=== ERREURS COMMUNES ET SOLUTIONS ===\n\n";

echo "❌ 'Erreur lors de l'action de modération'\n";
echo "   → Erreur générique, vérifiez la console pour plus de détails\n\n";

echo "❌ HTTP 422 (Unprocessable Entity)\n";
echo "   → Problème de validation des données\n";
echo "   → Pour 'reject': le champ 'rejection_reason' est obligatoire\n\n";

echo "❌ HTTP 403 (Forbidden)\n";
echo "   → Problème de permissions\n";
echo "   → Vérifiez que l'utilisateur a les bonnes permissions\n\n";

echo "❌ HTTP 500 (Internal Server Error)\n";
echo "   → Erreur serveur PHP\n";
echo "   → Vérifiez les logs Laravel: storage/logs/laravel.log\n\n";

echo "❌ 'CSRF token mismatch'\n";
echo "   → Le token CSRF n'est pas valide\n";
echo "   → Rechargez la page et réessayez\n\n";

echo "=== COMMANDES UTILES ===\n\n";
echo "📋 Voir les logs Laravel:\n";
echo "   tail -f storage/logs/laravel.log\n\n";

echo "🔄 Vider le cache:\n";
echo "   php artisan cache:clear\n";
echo "   php artisan config:clear\n";
echo "   php artisan route:clear\n\n";

echo "🧪 URLs de test:\n";
echo "   - Média approuvé: http://localhost/admin/media/{$media->id}\n";

$pendingMedia = Media::where('status', 'pending')->first();
if ($pendingMedia) {
    echo "   - Média en attente: http://localhost/admin/media/{$pendingMedia->id}\n";
}

$publishedMedia = Media::where('status', 'published')->first();
if ($publishedMedia) {
    echo "   - Média publié: http://localhost/admin/media/{$publishedMedia->id}\n";
}

echo "\nSuivez ces étapes et envoyez-moi les détails de l'erreur exacte que vous obtenez ! 🚀\n";
