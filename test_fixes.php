<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST SLUGS ET REQUIREMENTS ===\n\n";

// Test 1: Vérifier les slugs
echo "1. Test des slugs:\n";
try {
    $offers = DB::select("SELECT id, title, slug FROM job_offers LIMIT 3");
    foreach ($offers as $offer) {
        echo "   ID {$offer->id}: '{$offer->title}' -> slug: '{$offer->slug}'\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Test des requirements
echo "2. Test des requirements:\n";
try {
    $jobOffer = App\Models\JobOffer::first();
    if ($jobOffer) {
        echo "   Offre: {$jobOffer->title}\n";
        echo "   Requirements type: " . gettype($jobOffer->requirements) . "\n";
        echo "   Requirements content: " . (is_array($jobOffer->requirements) ? json_encode($jobOffer->requirements) : $jobOffer->requirements) . "\n";
        echo "   URL: " . route('admin.job-offers.show', $jobOffer->slug) . "\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== FIN TEST ===\n";
