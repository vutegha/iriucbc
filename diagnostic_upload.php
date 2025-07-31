<?php

/**
 * Script de diagnostic pour les problèmes d'upload de fichier
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC UPLOAD FICHIER ===\n\n";

// 1. Vérifier les permissions des dossiers
echo "1. Vérification des permissions...\n";

$directories = [
    'storage/app' => storage_path('app'),
    'storage/app/public' => storage_path('app/public'),
    'storage/app/public/assets' => storage_path('app/public/assets'),
    'public/storage' => public_path('storage'),
];

foreach ($directories as $name => $path) {
    echo "   $name: ";
    
    if (!file_exists($path)) {
        echo "❌ N'existe pas\n";
        echo "      Tentative de création...\n";
        if (mkdir($path, 0755, true)) {
            echo "      ✅ Créé avec succès\n";
        } else {
            echo "      ❌ Échec de création\n";
        }
    } else {
        echo "✅ Existe";
        
        if (is_writable($path)) {
            echo " - ✅ Accessible en écriture";
        } else {
            echo " - ❌ PAS accessible en écriture";
        }
        
        if (is_dir($path)) {
            echo " - ✅ Dossier";
        } elseif (is_link($path)) {
            echo " - 🔗 Lien symbolique";
            $target = readlink($path);
            echo " -> $target";
            if (file_exists($target)) {
                echo " (✅ Cible existe)";
            } else {
                echo " (❌ Cible manquante)";
            }
        }
        echo "\n";
    }
}

// 2. Vérifier la configuration PHP
echo "\n2. Configuration PHP pour les uploads...\n";

$configs = [
    'file_uploads' => ini_get('file_uploads') ? 'Activé' : 'Désactivé',
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_execution_time' => ini_get('max_execution_time') . 's',
    'memory_limit' => ini_get('memory_limit'),
    'upload_tmp_dir' => ini_get('upload_tmp_dir') ?: 'Défaut système',
];

foreach ($configs as $key => $value) {
    echo "   $key: $value\n";
}

// 3. Vérifier le dossier temporaire
echo "\n3. Dossier temporaire...\n";
$tmpDir = sys_get_temp_dir();
echo "   Dossier temp: $tmpDir\n";
echo "   Accessible en écriture: " . (is_writable($tmpDir) ? "✅ Oui" : "❌ Non") . "\n";

// 4. Test d'écriture
echo "\n4. Test d'écriture dans storage/app/public/assets...\n";
$testPath = storage_path('app/public/assets');

if (!is_dir($testPath)) {
    mkdir($testPath, 0755, true);
}

$testFile = $testPath . '/test_write.txt';
$testContent = 'Test d\'écriture - ' . date('Y-m-d H:i:s');

try {
    if (file_put_contents($testFile, $testContent) !== false) {
        echo "   ✅ Écriture réussie\n";
        
        if (file_get_contents($testFile) === $testContent) {
            echo "   ✅ Lecture réussie\n";
        } else {
            echo "   ❌ Lecture échouée\n";
        }
        
        if (unlink($testFile)) {
            echo "   ✅ Suppression réussie\n";
        } else {
            echo "   ❌ Suppression échouée\n";
        }
    } else {
        echo "   ❌ Écriture échouée\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

// 5. Vérifier l'espace disque
echo "\n5. Espace disque...\n";
$freeBytes = disk_free_space(storage_path());
$totalBytes = disk_total_space(storage_path());

if ($freeBytes !== false && $totalBytes !== false) {
    $freeMB = round($freeBytes / 1024 / 1024, 2);
    $totalMB = round($totalBytes / 1024 / 1024, 2);
    $usedPercent = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 2);
    
    echo "   Libre: {$freeMB} MB\n";
    echo "   Total: {$totalMB} MB\n";
    echo "   Utilisé: {$usedPercent}%\n";
    
    if ($freeMB < 100) {
        echo "   ⚠️  Espace disque faible!\n";
    } else {
        echo "   ✅ Espace disque suffisant\n";
    }
} else {
    echo "   ❌ Impossible de déterminer l'espace disque\n";
}

// 6. Test de simulation d'upload
echo "\n6. Configuration Laravel...\n";

try {
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    echo "   Disque 'public': ✅ Configuré\n";
    echo "   Chemin root: " . $disk->path('') . "\n";
    
    // Test d'écriture avec Storage
    $testContent = 'Test Laravel Storage - ' . time();
    if ($disk->put('test_storage.txt', $testContent)) {
        echo "   ✅ Écriture Storage réussie\n";
        
        if ($disk->get('test_storage.txt') === $testContent) {
            echo "   ✅ Lecture Storage réussie\n";
        } else {
            echo "   ❌ Lecture Storage échouée\n";
        }
        
        if ($disk->delete('test_storage.txt')) {
            echo "   ✅ Suppression Storage réussie\n";
        } else {
            echo "   ❌ Suppression Storage échouée\n";
        }
    } else {
        echo "   ❌ Écriture Storage échouée\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur Storage: " . $e->getMessage() . "\n";
}

echo "\n=== RECOMMANDATIONS ===\n";

// Recommandations basées sur les tests
if (!is_writable(storage_path('app/public/assets'))) {
    echo "⚠️  Corriger les permissions du dossier storage/app/public/assets\n";
    echo "   Commande: chmod -R 755 " . storage_path('app/public') . "\n";
}

if (ini_get('file_uploads') != 1) {
    echo "⚠️  Activer file_uploads dans php.ini\n";
}

$maxUpload = ini_get('upload_max_filesize');
if (strpos($maxUpload, 'M') !== false && (int)$maxUpload < 50) {
    echo "⚠️  Augmenter upload_max_filesize dans php.ini (actuellement: $maxUpload)\n";
}

echo "\n🎯 Diagnostic terminé!\n";
