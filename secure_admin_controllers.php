<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== SÉCURISATION DES CONTRÔLEURS ADMIN ===\n\n";

// Liste des contrôleurs à sécuriser avec leurs modèles associés
$controllers = [
    'ActualiteController' => 'Actualite',
    'PublicationController' => 'Publication', 
    'ProjetController' => 'Projet',
    'ServiceController' => 'Service',
    'AuteurController' => 'Auteur',
    'CategorieController' => 'Categorie',
    'MediaController' => 'Media',
    'EvenementController' => 'Evenement',
    'RapportController' => 'Rapport',
    'JobOfferController' => 'JobOffer',
    'JobApplicationController' => 'JobApplication',
    'ContactController' => 'Contact',
    'NewsletterController' => 'Newsletter',
    'PartenaireController' => 'Partenaire',
    'SocialLinkController' => 'SocialLink',
    'UserController' => 'User',
];

$controllersPath = app_path('Http/Controllers/Admin/');

foreach ($controllers as $controllerName => $modelName) {
    $filePath = $controllersPath . $controllerName . '.php';
    
    if (!file_exists($filePath)) {
        echo "⚠️  Contrôleur non trouvé : $controllerName\n";
        continue;
    }
    
    echo "🔒 Analyse de $controllerName...\n";
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Méthodes à sécuriser avec leurs autorisations
    $methodAuthorizations = [
        'public function index(' => "\$this->authorize('viewAny', {$modelName}::class);",
        'public function create(' => "\$this->authorize('create', {$modelName}::class);",
        'public function store(' => "\$this->authorize('create', {$modelName}::class);",
        'public function show(' => "\$this->authorize('view', \${$modelName});", // Variable pour show
        'public function edit(' => "\$this->authorize('update', \${$modelName});", // Variable pour edit  
        'public function update(' => "\$this->authorize('update', \${$modelName});", // Variable pour update
        'public function destroy(' => "\$this->authorize('delete', \${$modelName});", // Variable pour destroy
    ];
    
    $modified = false;
    
    foreach ($methodAuthorizations as $methodPattern => $authorization) {
        // Chercher les méthodes sans autorisation
        $pattern = '/(' . preg_quote($methodPattern, '/') . '[^{]*{\s*)(?!\s*\$this->authorize)/';
        
        if (preg_match($pattern, $content)) {
            // Pour les méthodes show/edit/update/destroy, extraire le nom de la variable du paramètre
            if (in_array($methodPattern, ['public function show(', 'public function edit(', 'public function update(', 'public function destroy('])) {
                // Trouver le nom de la variable du modèle dans les paramètres
                $paramPattern = '/' . preg_quote($methodPattern, '/') . '[^(]*\([^(]*(' . $modelName . '\s+\$(\w+))/';
                if (preg_match($paramPattern, $content, $matches)) {
                    $varName = $matches[2];
                    $authorization = str_replace('$' . $modelName, '$' . $varName, $authorization);
                }
            }
            
            $content = preg_replace($pattern, '$1' . "\n        " . $authorization . "\n", $content);
            $modified = true;
            echo "  ✅ Ajouté autorisation pour méthode : " . trim($methodPattern) . "\n";
        }
    }
    
    // Chercher et activer les autorisations commentées
    $commentedPattern = '/\/\/\s*(\$this->authorize\([^)]+\);)/';
    if (preg_match_all($commentedPattern, $content, $matches)) {
        foreach ($matches[0] as $index => $match) {
            $content = str_replace($match, $matches[1][$index], $content);
            $modified = true;
            echo "  ✅ Activé autorisation commentée : " . trim($matches[1][$index]) . "\n";
        }
    }
    
    if ($modified && $content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "  💾 Fichier mis à jour : $controllerName\n";
    } else {
        echo "  ℹ️  Aucune modification nécessaire : $controllerName\n";
    }
    
    echo "\n";
}

echo "🎉 SÉCURISATION TERMINÉE !\n\n";

// Vérification finale - scanner tous les contrôleurs pour les méthodes non protégées
echo "🔍 VÉRIFICATION FINALE...\n";
echo str_repeat("=", 50) . "\n";

$allControllers = glob($controllersPath . '*.php');
$unprotectedMethods = [];

foreach ($allControllers as $file) {
    $content = file_get_contents($file);
    $controllerName = basename($file, '.php');
    
    // Chercher les méthodes CRUD sans autorisation
    $methodPatterns = [
        'index' => 'public function index\(',
        'create' => 'public function create\(',
        'store' => 'public function store\(',
        'show' => 'public function show\(',
        'edit' => 'public function edit\(',
        'update' => 'public function update\(',
        'destroy' => 'public function destroy\(',
    ];
    
    foreach ($methodPatterns as $method => $pattern) {
        if (preg_match('/' . $pattern . '[^{]*{([^}]*(?:{[^}]*}[^}]*)*)}/s', $content, $matches)) {
            $methodBody = $matches[1];
            
            // Vérifier si la méthode contient une autorisation
            if (!preg_match('/\$this->authorize\(/', $methodBody)) {
                $unprotectedMethods[] = [
                    'controller' => $controllerName,
                    'method' => $method,
                    'file' => $file
                ];
            }
        }
    }
}

if (!empty($unprotectedMethods)) {
    echo "⚠️  MÉTHODES NON PROTÉGÉES TROUVÉES :\n";
    foreach ($unprotectedMethods as $item) {
        echo "  • {$item['controller']}::{$item['method']}()\n";
    }
} else {
    echo "✅ TOUS LES CONTRÔLEURS SONT SÉCURISÉS !\n";
}

echo "\n📋 RÉSUMÉ :\n";
echo "• Contrôleurs analysés : " . count($controllers) . "\n";
echo "• Méthodes non protégées : " . count($unprotectedMethods) . "\n";
echo "• Status : " . (empty($unprotectedMethods) ? "🟢 SÉCURISÉ" : "🟠 À COMPLÉTER") . "\n";
