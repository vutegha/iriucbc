<?php

/**
 * Script de diagnostic pour l'inscription newsletter depuis le footer
 */

echo "🔍 DIAGNOSTIC INSCRIPTION NEWSLETTER FOOTER\n";
echo "==========================================\n\n";

// Simuler les données du formulaire footer
$formData = [
    'email' => 'test@example.com',
    'preferences' => ['actualites', 'publications'], // Comme envoyé par le footer
    'redirect_url' => 'http://localhost',
    '_token' => 'test_token'
];

echo "📋 Données envoyées par le footer :\n";
print_r($formData);
echo "\n";

// Test validation
echo "🔍 Test de validation :\n";

$rules = [
    'email' => 'required|email|max:255',
    'preferences' => 'required|array|min:1',
    'preferences.*' => 'in:actualites,publications,rapports,evenements',
    'redirect_url' => 'nullable|url'
];

echo "Règles de validation :\n";
foreach ($rules as $field => $rule) {
    echo "  - $field: $rule\n";
}
echo "\n";

// Vérifier chaque règle manuellement
echo "📊 Vérification manuelle :\n";

// Email
if (filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
    echo "✅ Email valide\n";
} else {
    echo "❌ Email invalide\n";
}

// Preferences array
if (is_array($formData['preferences']) && count($formData['preferences']) >= 1) {
    echo "✅ Preferences est un array avec au moins 1 élément\n";
} else {
    echo "❌ Preferences doit être un array avec au moins 1 élément\n";
}

// Preferences values
$allowedTypes = ['actualites', 'publications', 'rapports', 'evenements'];
$invalidTypes = array_diff($formData['preferences'], $allowedTypes);
if (empty($invalidTypes)) {
    echo "✅ Toutes les préférences sont valides\n";
} else {
    echo "❌ Préférences invalides: " . implode(', ', $invalidTypes) . "\n";
}

// Redirect URL
if (empty($formData['redirect_url']) || filter_var($formData['redirect_url'], FILTER_VALIDATE_URL)) {
    echo "✅ URL de redirection valide\n";
} else {
    echo "❌ URL de redirection invalide\n";
}

echo "\n";

// Test transformation des préférences
echo "🔄 Test transformation préférences :\n";
$preferences = [];
foreach(['actualites', 'publications', 'rapports', 'evenements'] as $type) {
    $preferences[$type] = in_array($type, $formData['preferences']);
}

echo "Préférences transformées :\n";
print_r($preferences);
echo "\n";

echo "🎯 ANALYSE :\n";
echo "===========\n";
echo "Le formulaire footer envoie seulement 'actualites' et 'publications'\n";
echo "mais la validation autorise aussi 'rapports' et 'evenements'.\n";
echo "Cela devrait fonctionner correctement.\n\n";

echo "💡 CAUSES POSSIBLES DE L'ERREUR :\n";
echo "=================================\n";
echo "1. Problème de token CSRF\n";
echo "2. Erreur dans la création du Newsletter\n";
echo "3. Problème avec la méthode updatePreferences()\n";
echo "4. Erreur dans l'envoi de l'email de bienvenue\n";
echo "5. Problème avec la redirection\n\n";

echo "🔧 SOLUTIONS À TESTER :\n";
echo "======================\n";
echo "1. Vérifier les logs Laravel pour l'erreur exacte\n";
echo "2. Ajouter plus de logging dans SiteController::subscribeNewsletter\n";
echo "3. Tester la méthode updatePreferences dans Newsletter model\n";
echo "4. Vérifier que la migration a été appliquée\n";

?>
