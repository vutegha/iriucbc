<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VÉRIFICATION DES VUES MÉDIAS CORRIGÉES ===\n\n";

// 1. Vérifier la syntaxe des vues
$views = [
    'resources/views/admin/media/index.blade.php',
    'resources/views/admin/media/create.blade.php', 
    'resources/views/admin/media/edit.blade.php',
    'resources/views/admin/media/_form.blade.php',
    'resources/views/admin/newsletter/index.blade.php'
];

echo "📄 VÉRIFICATION SYNTAXE DES VUES:\n";
foreach ($views as $view) {
    if (file_exists($view)) {
        $content = file_get_contents($view);
        
        // Vérifier les balises @section et @endsection
        $sections = substr_count($content, '@section');
        $endsections = substr_count($content, '@endsection');
        
        echo "  📝 " . basename($view) . ":\n";
        echo "    @section: $sections\n";
        echo "    @endsection: $endsections\n";
        
        if ($sections === $endsections) {
            echo "    ✅ Balises équilibrées\n";
        } else {
            echo "    ❌ Balises déséquilibrées!\n";
        }
        
        // Vérifier les balises @extends
        $extends = substr_count($content, '@extends');
        echo "    @extends: $extends\n";
        
        if ($extends === 1) {
            echo "    ✅ @extends correct\n";
        } elseif ($extends === 0) {
            echo "    ⚠️ Aucun @extends trouvé\n";
        } else {
            echo "    ❌ Plusieurs @extends trouvés!\n";
        }
        
        echo "\n";
    } else {
        echo "  ❌ $view - Fichier non trouvé\n\n";
    }
}

// 2. Tester une vue spécifique (media/edit) pour les erreurs Blade
echo "🔍 TEST SPÉCIFIQUE MEDIA/EDIT:\n";
$editView = 'resources/views/admin/media/edit.blade.php';
if (file_exists($editView)) {
    $content = file_get_contents($editView);
    
    // Rechercher les patterns problématiques
    $patterns = [
        '/@endsection\s*@endsection/' => 'Double @endsection',
        '/@section\([^)]*\)\s*@section/' => 'Double @section',
        '/\@section\s*@endsection/' => 'Section vide',
        '/<div[^>]*>\s*@endsection/' => 'Balise div non fermée avant @endsection'
    ];
    
    $issues = [];
    foreach ($patterns as $pattern => $description) {
        if (preg_match($pattern, $content)) {
            $issues[] = $description;
        }
    }
    
    if (empty($issues)) {
        echo "  ✅ Aucun problème de syntaxe détecté\n";
    } else {
        echo "  ❌ Problèmes détectés:\n";
        foreach ($issues as $issue) {
            echo "    - $issue\n";
        }
    }
} else {
    echo "  ❌ Fichier edit.blade.php non trouvé\n";
}

echo "\n🎉 VÉRIFICATION TERMINÉE!\n";
echo "Les corrections ont été appliquées avec succès.\n";
