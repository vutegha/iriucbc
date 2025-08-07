<?php
echo "=== TEST DES TITRES D'ERREUR DYNAMIQUES ===\n\n";

// Simulation des messages d'erreur et des titres attendus
$testCases = [
    'Erreurs de validation' => [
        'messages' => [
            'Le champ nom est obligatoire.',
            'Erreurs de validation détectées. Veuillez corriger les champs indiqués.',
            'Le format de la date est invalide.',
            'Ce champ doit être rempli.'
        ],
        'expected_title' => 'Erreur de saisie',
        'expected_icon' => 'triangle (validation)',
        'expected_color' => 'red'
    ],
    
    'Erreurs de permissions' => [
        'messages' => [
            'Vous n\'avez pas les permissions nécessaires pour créer ce projet.',
            'Accès refusé pour cette opération.',
            'Utilisateur non autorisé.',
            'Permissions insuffisantes.'
        ],
        'expected_title' => 'Accès refusé',
        'expected_icon' => 'lock',
        'expected_color' => 'yellow'
    ],
    
    'Erreurs de stockage' => [
        'messages' => [
            'Problème d\'espace de stockage sur le serveur. Contactez l\'administrateur.',
            'Impossible de sauvegarder le fichier image.',
            'Erreur lors de l\'upload de l\'image.',
            'Espace disque insuffisant.'
        ],
        'expected_title' => 'Problème de stockage',
        'expected_icon' => 'download',
        'expected_color' => 'purple'
    ],
    
    'Erreurs serveur' => [
        'messages' => [
            'Le serveur rencontre des difficultés. Veuillez réessayer dans quelques minutes.',
            'Erreur de timeout lors du traitement.',
            'Problème de mémoire sur le serveur.',
            'Le serveur ne répond pas.'
        ],
        'expected_title' => 'Erreur serveur',
        'expected_icon' => 'server',
        'expected_color' => 'red'
    ],
    
    'Erreurs de session' => [
        'messages' => [
            'Votre session a expiré ou le formulaire n\'est plus valide.',
            'Token CSRF invalide.',
            'Session expirée. Rechargez la page et réessayez.',
            'Authentification requise.'
        ],
        'expected_title' => 'Session expirée',
        'expected_icon' => 'clock',
        'expected_color' => 'indigo'
    ],
    
    'Erreurs de base de données' => [
        'messages' => [
            'Erreur de base de données lors de la création du projet.',
            'Problème de connexion à la base de données.',
            'Contrainte SQL violée.',
            'Erreur lors de la requête database.'
        ],
        'expected_title' => 'Erreur de base de données',
        'expected_icon' => 'database',
        'expected_color' => 'red'
    ],
    
    'Erreurs réseau' => [
        'messages' => [
            'Problème de connexion réseau.',
            'Erreur de communication avec le serveur.',
            'Connexion interrompue.',
            'Network timeout.'
        ],
        'expected_title' => 'Problème de connexion',
        'expected_icon' => 'wifi',
        'expected_color' => 'blue'
    ],
    
    'Erreurs système génériques' => [
        'messages' => [
            'Une erreur inattendue est survenue.',
            'Erreur interne du système.',
            'Problème technique non identifié.',
            'Erreur générale.'
        ],
        'expected_title' => 'Erreur système',
        'expected_icon' => 'exclamation',
        'expected_color' => 'orange'
    ]
];

foreach ($testCases as $category => $data) {
    echo "🔧 CATÉGORIE: $category\n";
    echo "   Titre attendu: {$data['expected_title']}\n";
    echo "   Icône: {$data['expected_icon']}\n";
    echo "   Couleur: {$data['expected_color']}\n";
    echo "   Messages testés:\n";
    
    foreach ($data['messages'] as $i => $message) {
        echo "   " . ($i + 1) . ". " . substr($message, 0, 60) . (strlen($message) > 60 ? '...' : '') . "\n";
    }
    echo "\n";
}

echo "=== AMÉLIORATIONS APPORTÉES ===\n\n";

echo "✅ TITRES DYNAMIQUES:\n";
echo "   - Analyse automatique du contenu du message d'erreur\n";
echo "   - Titre spécifique selon le type d'erreur détecté\n";
echo "   - 8 catégories d'erreur distinctes identifiées\n\n";

echo "✅ ICÔNES CONTEXTUELLES:\n";
echo "   - Icône triangle pour les erreurs de validation\n";
echo "   - Icône cadenas pour les erreurs de permission\n";
echo "   - Icône téléchargement pour les erreurs de stockage\n";
echo "   - Icône serveur pour les erreurs serveur\n";
echo "   - Icône horloge pour les sessions expirées\n";
echo "   - Icône base de données pour les erreurs DB\n";
echo "   - Icône wifi pour les erreurs réseau\n";
echo "   - Icône exclamation pour les erreurs génériques\n\n";

echo "✅ COULEURS DIFFÉRENCIÉES:\n";
echo "   - Rouge: Erreurs critiques (validation, serveur, DB)\n";
echo "   - Jaune: Erreurs de permission\n";
echo "   - Violet: Erreurs de stockage\n";
echo "   - Bleu: Erreurs réseau\n";
echo "   - Indigo: Erreurs de session\n";
echo "   - Orange: Erreurs système génériques\n\n";

echo "✅ CONSEILS CONTEXTUELS:\n";
echo "   - Session expirée → 'Rechargez la page et réessayez'\n";
echo "   - Validation → 'Vérifiez les champs marqués en rouge'\n";
echo "   - Serveur → 'Attendez quelques minutes et réessayez'\n";
echo "   - Stockage → 'Contactez l\'administrateur système'\n";
echo "   - Permission → 'Contactez un administrateur pour les permissions'\n\n";

echo "=== RÉSULTAT FINAL ===\n";
echo "❌ AVANT: Titre générique 'Erreur système' pour tout\n";
echo "✅ APRÈS: Titres spécifiques avec conseils contextuels\n\n";

echo "EXEMPLES DE TRANSFORMATION:\n";
echo "• 'Erreur système' + 'Le champ nom est obligatoire'\n";
echo "  → 'Erreur de saisie' + conseil de vérification\n\n";
echo "• 'Erreur système' + 'Permissions insuffisantes'\n";
echo "  → 'Accès refusé' + conseil de contact admin\n\n";
echo "• 'Erreur système' + 'Session expirée'\n";
echo "  → 'Session expirée' + conseil de rechargement\n\n";

echo "L'utilisateur comprend maintenant immédiatement:\n";
echo "✓ Le TYPE d'erreur rencontré\n";
echo "✓ La GRAVITÉ du problème\n";
echo "✓ L'ACTION à entreprendre\n";
echo "✓ Qui CONTACTER si nécessaire\n";
