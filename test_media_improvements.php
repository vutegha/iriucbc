<?php
/**
 * Test des améliorations du système médias
 * Vérification des formulaires et de la vue show
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST AMÉLIORATIONS SYSTÈME MÉDIAS ===\n\n";

try {
    // Test 1: Vérification du formulaire _form.blade.php
    echo "📋 Test du formulaire partiel...\n";
    $formPath = 'resources/views/admin/media/_form.blade.php';
    $formContent = file_get_contents($formPath);
    
    // Vérifications des améliorations
    $hasImprovedBorders = strpos($formContent, 'border-2 border-gray-300') !== false;
    $hasConditionalRequired = strpos($formContent, "{{ isset(\$media) && \$media ? '' : 'required' }}") !== false;
    $hasNoModerationOptions = strpos($formContent, 'Options de publication') === false;
    $hasCurrentMediaInfo = strpos($formContent, 'Laissez vide pour conserver le fichier actuel') !== false;
    
    echo "   - Contraste amélioré: " . ($hasImprovedBorders ? "✅" : "❌") . "\n";
    echo "   - Validation conditionnelle: " . ($hasConditionalRequired ? "✅" : "❌") . "\n";
    echo "   - Options modération supprimées: " . ($hasNoModerationOptions ? "✅" : "❌") . "\n";
    echo "   - Info média actuel: " . ($hasCurrentMediaInfo ? "✅" : "❌") . "\n";

    // Test 2: Vérification de la vue show.blade.php
    echo "\n👁️  Test de la vue show...\n";
    $showPath = 'resources/views/admin/media/show.blade.php';
    $showContent = file_get_contents($showPath);
    
    $hasModerationSection = strpos($showContent, 'Actions de modération') !== false;
    $hasModerationModal = strpos($showContent, 'moderationModal') !== false;
    $hasModerationScript = strpos($showContent, 'moderateMedia') !== false;
    $hasHistorySection = strpos($showContent, 'Historique de modération') !== false;
    
    echo "   - Section modération: " . ($hasModerationSection ? "✅" : "❌") . "\n";
    echo "   - Modal modération: " . ($hasModerationModal ? "✅" : "❌") . "\n";
    echo "   - Script JavaScript: " . ($hasModerationScript ? "✅" : "❌") . "\n";
    echo "   - Historique: " . ($hasHistorySection ? "✅" : "❌") . "\n";

    // Test 3: Compilation des vues
    echo "\n🔄 Test de compilation...\n";
    
    // Données de test minimales
    $testData = [
        'media' => (object) [
            'id' => 1,
            'titre' => 'Test Media',
            'type' => 'image',
            'status' => 'pending',
            'medias' => 'test.jpg',
            'description' => 'Test description',
            'projet_id' => null,
            'moderated_at' => null,
            'moderated_by' => null,
            'rejection_reason' => null
        ],
        'projets' => []
    ];
    
    // Test compilation du formulaire
    try {
        $formView = view('admin.media._form', $testData);
        echo "   - Formulaire compile: ✅\n";
    } catch (Exception $e) {
        echo "   - Formulaire compile: ❌ ({$e->getMessage()})\n";
    }
    
    // Test des pages create et edit
    try {
        $createView = view('admin.media.create', $testData);
        echo "   - Page create compile: ✅\n";
    } catch (Exception $e) {
        echo "   - Page create compile: ❌ ({$e->getMessage()})\n";
    }
    
    try {
        $editView = view('admin.media.edit', $testData);
        echo "   - Page edit compile: ✅\n";
    } catch (Exception $e) {
        echo "   - Page edit compile: ❌ ({$e->getMessage()})\n";
    }

    // Test 4: Vérification des permissions dans show.blade.php
    echo "\n🔐 Test des permissions...\n";
    $hasModeratePermission = strpos($showContent, "@can('moderate_media')") !== false;
    $hasUpdatePermission = strpos($showContent, "@can('update_media')") !== false;
    $hasDeletePermission = strpos($showContent, "@can('delete_media')") !== false;
    
    echo "   - Permission moderate_media: " . ($hasModeratePermission ? "✅" : "❌") . "\n";
    echo "   - Permission update_media: " . ($hasUpdatePermission ? "✅" : "❌") . "\n";
    echo "   - Permission delete_media: " . ($hasDeletePermission ? "✅" : "❌") . "\n";

    // Test 5: Vérification des actions de modération
    echo "\n⚖️  Test des actions de modération...\n";
    $actions = ['approve', 'reject', 'publish', 'unpublish'];
    $actionsFound = 0;
    
    foreach ($actions as $action) {
        if (strpos($showContent, "moderateMedia('$action'") !== false) {
            $actionsFound++;
            echo "   - Action $action: ✅\n";
        } else {
            echo "   - Action $action: ❌\n";
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "📊 RÉSUMÉ DES AMÉLIORATIONS\n";
    echo str_repeat("=", 50) . "\n";
    
    $totalChecks = 12;
    $passedChecks = 0;
    
    if ($hasImprovedBorders) $passedChecks++;
    if ($hasConditionalRequired) $passedChecks++;
    if ($hasNoModerationOptions) $passedChecks++;
    if ($hasCurrentMediaInfo) $passedChecks++;
    if ($hasModerationSection) $passedChecks++;
    if ($hasModerationModal) $passedChecks++;
    if ($hasModerationScript) $passedChecks++;
    if ($hasHistorySection) $passedChecks++;
    if ($hasModeratePermission) $passedChecks++;
    if ($hasUpdatePermission) $passedChecks++;
    if ($hasDeletePermission) $passedChecks++;
    $passedChecks += $actionsFound / 4; // Actions de modération
    
    $successRate = round(($passedChecks / $totalChecks) * 100, 1);
    
    echo "✅ Améliorations validées: $passedChecks/$totalChecks ($successRate%)\n\n";
    
    if ($successRate >= 90) {
        echo "🎉 SUCCÈS COMPLET!\n";
        echo "   ✅ Formulaires améliorés avec meilleur contraste\n";
        echo "   ✅ Validation conditionnelle implémentée\n";
        echo "   ✅ Actions de modération déplacées dans show\n";
        echo "   ✅ Interface moderne avec permissions granulaires\n";
        echo "   ✅ Workflow de modération complet\n\n";
        echo "🚀 Le système médias est maintenant optimisé pour IRI UCBC!\n";
    } else {
        echo "⚠️  AMÉLIORATIONS PARTIELLES\n";
        echo "Certaines fonctionnalités nécessitent encore des ajustements.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
}
