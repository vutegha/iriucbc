<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Publication;
use Illuminate\Support\Facades\DB;

echo "Création de données de téléchargement...\n";

$publications = Publication::take(3)->get();

foreach($publications as $pub) {
    $downloads = rand(2, 8);
    echo "Publication '{$pub->title}' - {$downloads} téléchargements\n";
    
    for($i = 0; $i < $downloads; $i++) {
        DB::table('publication_downloads')->insert([
            'publication_id' => $pub->id,
            'ip_address' => '192.168.1.' . rand(1, 100),
            'user_agent' => 'Mozilla/5.0',
            'downloaded_at' => now()->subDays(rand(0, 30)),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

echo "Données de téléchargement créées avec succès!\n";
