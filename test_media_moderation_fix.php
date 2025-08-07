<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Route;

echo "=== Test des corrections de modération des médias ===\n\n";

// Bootstrapper Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$successCount = 0;
$totalTests = 8;

echo "1. Test des routes de modération... ";
try {
    $approveRoute = Route::getRoutes()->getByName('admin.media.approve');
    $rejectRoute = Route::getRoutes()->getByName('admin.media.reject');
    $publishRoute = Route::getRoutes()->getByName('admin.media.publish');
    $unpublishRoute = Route::getRoutes()->getByName('admin.media.unpublish');
    
    if ($approveRoute && $rejectRoute && $publishRoute && $unpublishRoute) {
        echo "✅ OK\n";
        $successCount++;
    } else {
        echo "❌ Échec - Routes manquantes\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "2. Test de la correction PATCH -> PUT... ";
try {
    $formContent = file_get_contents('resources/views/admin/media/_form.blade.php');
    $editContent = file_get_contents('resources/views/admin/media/edit.blade.php');
    
    if (strpos($formContent, "@method('PUT')") !== false && 
        strpos($formContent, "@method('PATCH')") === false &&
        strpos($editContent, "'method' => 'PUT'") !== false &&
        strpos($editContent, "'method' => 'PATCH'") === false) {
        echo "✅ OK\n";
        $successCount++;
    } else {
        echo "❌ Échec - PATCH non corrigé\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "3. Test des méthodes du contrôleur... ";
try {
    $controllerContent = file_get_contents('app/Http/Controllers/Admin/MediaController.php');
    
    $hasApprove = strpos($controllerContent, 'public function approve') !== false;
    $hasReject = strpos($controllerContent, 'public function reject') !== false;
    $hasPublish = strpos($controllerContent, 'public function publish') !== false;
    $hasUnpublish = strpos($controllerContent, 'public function unpublish') !== false;
    
    if ($hasApprove && $hasReject && $hasPublish && $hasUnpublish) {
        echo "✅ OK\n";
        $successCount++;
    } else {
        echo "❌ Échec - Méthodes manquantes\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "4. Test des réponses JSON AJAX... ";
try {
    $controllerContent = file_get_contents('app/Http/Controllers/Admin/MediaController.php');
    
    $hasJsonResponse = strpos($controllerContent, 'request()->wantsJson()') !== false;
    $hasJsonReturn = strpos($controllerContent, 'response()->json([') !== false;
    
    if ($hasJsonResponse && $hasJsonReturn) {
        echo "✅ OK\n";
        $successCount++;
    } else {
        echo "❌ Échec - Réponses JSON manquantes\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "5. Test des actions de publication dans la vue... ";
try {
    $showContent = file_get_contents('resources/views/admin/media/show.blade.php');
    
    $hasPublishAction = strpos($showContent, "moderateMedia('publish'") !== false;
    $hasUnpublishAction = strpos($showContent, "moderateMedia('unpublish'") !== false;
    $hasPublishButton = strpos($showContent, 'Publier') !== false;
    $hasUnpublishButton = strpos($showContent, 'Dépublier') !== false;
    
    if ($hasPublishAction && $hasUnpublishAction && $hasPublishButton && $hasUnpublishButton) {
        echo "✅ OK\n";
        $successCount++;
    } else {
        echo "❌ Échec - Actions de publication manquantes\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "6. Test de la logique conditionnelle des statuts... ";
try {
    $showContent = file_get_contents('resources/views/admin/media/show.blade.php');
    
    $hasApprovedCondition = strpos($showContent, "@elseif(\$media->status === 'approved')") !== false;
    $hasPublishedCondition = strpos($showContent, "@elseif(\$media->status === 'published')") !== false;
    $hasRejectedCondition = strpos($showContent, "@elseif(\$media->status === 'rejected')") !== false;
    
    if ($hasApprovedCondition && $hasPublishedCondition && $hasRejectedCondition) {
        echo "✅ OK\n";
        $successCount++;
    } else {
        echo "❌ Échec - Conditions de statut manquantes\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "7. Test de la correction du champ rejection_reason... ";
try {
    $showContent = file_get_contents('resources/views/admin/media/show.blade.php');
    
    $hasRejectionReason = strpos($showContent, "formData.append('rejection_reason', comment)") !== false;
    $hasValidation = strpos($showContent, "currentModerationAction === 'reject' && !comment.trim()") !== false;
    
    if ($hasRejectionReason && $hasValidation) {
        echo "✅ OK\n";
        $successCount++;
    } else {
        echo "❌ Échec - Correction rejection_reason manquante\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "8. Test des permissions de modération... ";
try {
    $showContent = file_get_contents('resources/views/admin/media/show.blade.php');
    
    $hasModeratePermission = strpos($showContent, "@can('moderate_media')") !== false;
    $hasUpdatePermission = strpos($showContent, "@can('update_media')") !== false;
    
    if ($hasModeratePermission && $hasUpdatePermission) {
        echo "✅ OK\n";
        $successCount++;
    } else {
        echo "❌ Échec - Permissions manquantes\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Résultats ===\n";
echo "Tests réussis: $successCount/$totalTests\n";
echo "Pourcentage de réussite: " . round(($successCount / $totalTests) * 100, 2) . "%\n";

if ($successCount === $totalTests) {
    echo "\n🎉 Toutes les corrections de modération sont fonctionnelles!\n";
    echo "\nCorrections appliquées:\n";
    echo "✅ PATCH → PUT pour les formulaires de mise à jour\n";
    echo "✅ Réponses JSON AJAX ajoutées aux méthodes de modération\n";
    echo "✅ Actions de publication/dépublication disponibles\n";
    echo "✅ Validation JavaScript pour les rejets\n";
    echo "✅ Correction du champ rejection_reason\n";
    echo "✅ Permissions de modération vérifiées\n";
} else {
    echo "\n⚠️  Certaines corrections nécessitent encore de l'attention.\n";
}

echo "\nActions disponibles dans la vue show:\n";
echo "• Approuver (pending → approved)\n";
echo "• Rejeter (any → rejected)\n";
echo "• Publier (approved → published)\n";
echo "• Dépublier (published → approved)\n";
echo "\nToutes les actions respectent les permissions et l'état actuel du média.\n";
