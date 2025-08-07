<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Rapport;
use Illuminate\Support\Str;

echo "=== GÉNÉRATION DES SLUGS POUR LES RAPPORTS ===" . PHP_EOL;

$rapports = Rapport::whereNull('slug')->orWhere('slug', '')->get();

echo "Rapports sans slug trouvés: " . $rapports->count() . PHP_EOL;

foreach ($rapports as $rapport) {
    $date = $rapport->created_at ? $rapport->created_at->format('Ymd') : now()->format('Ymd');
    $slug = $date . '-' . Str::slug($rapport->titre);
    
    // Vérifier l'unicité
    $originalSlug = $slug;
    $counter = 1;
    while (Rapport::where('slug', $slug)->where('id', '!=', $rapport->id)->exists()) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }
    
    $rapport->slug = $slug;
    $rapport->save();
    
    echo "✅ Slug généré pour '{$rapport->titre}': {$slug}" . PHP_EOL;
}

echo PHP_EOL . "=== GÉNÉRATION TERMINÉE ===" . PHP_EOL;

?>
