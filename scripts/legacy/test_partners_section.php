<?php
/*
 * Test de la section partenaires modernisée
 * 
 * Cette page teste la nouvelle section partenaires avec :
 * - Défilement horizontal moderne
 * - Logos cliquables avec target="_blank"
 * - Design professionnel et responsive
 * - Animations fluides
 * - Gestion d'erreur pour les logos
 */

// Données de test des partenaires
$partenairesMock = [
    (object) [
        'id' => 1,
        'nom' => 'Université de Kinshasa',
        'logo_url' => 'https://via.placeholder.com/150x80/1e472f/ffffff?text=UNIKIN',
        'site_web' => 'https://www.unikin.ac.cd',
        'type' => 'Université'
    ],
    (object) [
        'id' => 2,
        'nom' => 'UNESCO',
        'logo_url' => 'https://via.placeholder.com/150x80/0066cc/ffffff?text=UNESCO',
        'site_web' => 'https://www.unesco.org',
        'type' => 'Organisation internationale'
    ],
    (object) [
        'id' => 3,
        'nom' => 'PNUD Congo',
        'logo_url' => 'https://via.placeholder.com/150x80/00b04f/ffffff?text=PNUD',
        'site_web' => null, // Test pour partenaire sans site web
        'type' => 'Organisation internationale'
    ],
    (object) [
        'id' => 4,
        'nom' => 'Ministère de l\'Enseignement Supérieur',
        'logo_url' => 'https://via.placeholder.com/150x80/d2691e/ffffff?text=MESU',
        'site_web' => 'https://www.mesu.cd',
        'type' => 'Gouvernement'
    ],
    (object) [
        'id' => 5,
        'nom' => 'Organisation des Nations Unies',
        'logo_url' => 'https://via.placeholder.com/150x80/009fdb/ffffff?text=ONU',
        'site_web' => 'https://www.un.org',
        'type' => 'Organisation internationale'
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Section Partenaires Modernisée</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'iri-primary': '#1e472f',
                        'iri-secondary': '#2d5a3f',
                        'iri-accent': '#d2691e',
                        'iri-light': '#f0f9f4',
                        'iri-gold': '#b8860b',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h1 class="text-2xl font-bold text-center mb-4">🧪 Test - Section Partenaires Modernisée</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="bg-green-50 border border-green-200 rounded p-3">
                    <h3 class="font-semibold text-green-800">✅ Fonctionnalités testées :</h3>
                    <ul class="text-green-700 mt-2">
                        <li>• Défilement horizontal fluide</li>
                        <li>• Logos cliquables (si URL présente)</li>
                        <li>• Design moderne et professionnel</li>
                        <li>• Animations au survol</li>
                        <li>• Gestion d'erreur des images</li>
                    </ul>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded p-3">
                    <h3 class="font-semibold text-blue-800">🎨 Design :</h3>
                    <ul class="text-blue-700 mt-2">
                        <li>• Cartes avec ombres et bordures</li>
                        <li>• Effets de fondu sur les côtés</li>
                        <li>• Contrôles pause/lecture</li>
                        <li>• Grayscale → Couleur au survol</li>
                        <li>• Responsive et accessible</li>
                    </ul>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded p-3">
                    <h3 class="font-semibold text-orange-800">🔧 Technique :</h3>
                    <ul class="text-orange-700 mt-2">
                        <li>• Animation CSS pure</li>
                        <li>• JavaScript pour contrôles</li>
                        <li>• Lazy loading des images</li>
                        <li>• Target="_blank" sécurisé</li>
                        <li>• Fallback pour erreurs</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section partenaires moderne (copie du code implémenté) -->
        <section class="bg-gradient-to-b from-gray-50 to-white py-16">
            <div class="max-w-7xl mx-auto px-4">
                <!-- En-tête de section -->
                <div class="text-center mb-12">
                    <div class="inline-block p-2 bg-iri-primary/10 rounded-lg mb-4">
                        <i class="fas fa-handshake text-iri-primary text-2xl"></i>
                    </div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Nos Partenaires</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Nous collaborons avec des organisations de renom pour promouvoir l'excellence en recherche et innovation
                    </p>
                    <div class="w-24 h-1 bg-gradient-to-r from-iri-primary to-iri-accent mx-auto mt-6 rounded-full"></div>
                </div>

                <!-- Conteneur de défilement avec effet de fondu -->
                <div class="relative overflow-hidden">
                    <!-- Effets de fondu sur les côtés -->
                    <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
                    
                    <!-- Conteneur de défilement -->
                    <div class="flex animate-scroll-smooth gap-12 py-8" style="animation-duration: 60s;" id="partners-scroll">
                        <!-- Première série de logos -->
                        <?php foreach($partenairesMock as $partenaire): ?>
                            <div class="partner-item flex-shrink-0 group">
                                <?php if($partenaire->site_web): ?>
                                    <a href="<?= htmlspecialchars($partenaire->site_web) ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="block transition-all duration-300 transform group-hover:scale-105">
                                <?php endif; ?>
                                        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-iri-primary/20">
                                            <div class="h-20 flex items-center justify-center mb-4">
                                                <img src="<?= htmlspecialchars($partenaire->logo_url) ?>" 
                                                     alt="<?= htmlspecialchars($partenaire->nom) ?>" 
                                                     title="<?= htmlspecialchars($partenaire->nom) ?>"
                                                     class="max-h-16 max-w-32 object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300"
                                                     onerror="this.parentElement.parentElement.parentElement<?= $partenaire->site_web ? '.parentElement' : '' ?>.style.display='none'"
                                                     loading="lazy"/>
                                            </div>
                                            <div class="text-center">
                                                <h3 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2 group-hover:text-iri-primary transition-colors duration-300">
                                                    <?= htmlspecialchars($partenaire->nom) ?>
                                                </h3>
                                                <?php if($partenaire->site_web): ?>
                                                    <div class="flex items-center justify-center text-xs text-iri-accent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        <i class="fas fa-external-link-alt mr-1"></i>
                                                        Visiter le site
                                                    </div>
                                                <?php else: ?>
                                                    <div class="flex items-center justify-center text-xs text-gray-400">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Pas de site web
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                <?php if($partenaire->site_web): ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                        <!-- Duplication pour boucle fluide -->
                        <?php foreach($partenairesMock as $partenaire): ?>
                            <div class="partner-item flex-shrink-0 group">
                                <?php if($partenaire->site_web): ?>
                                    <a href="<?= htmlspecialchars($partenaire->site_web) ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="block transition-all duration-300 transform group-hover:scale-105">
                                <?php endif; ?>
                                        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-iri-primary/20">
                                            <div class="h-20 flex items-center justify-center mb-4">
                                                <img src="<?= htmlspecialchars($partenaire->logo_url) ?>" 
                                                     alt="<?= htmlspecialchars($partenaire->nom) ?>" 
                                                     title="<?= htmlspecialchars($partenaire->nom) ?>"
                                                     class="max-h-16 max-w-32 object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300"
                                                     onerror="this.parentElement.parentElement.parentElement<?= $partenaire->site_web ? '.parentElement' : '' ?>.style.display='none'"
                                                     loading="lazy"/>
                                            </div>
                                            <div class="text-center">
                                                <h3 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2 group-hover:text-iri-primary transition-colors duration-300">
                                                    <?= htmlspecialchars($partenaire->nom) ?>
                                                </h3>
                                                <?php if($partenaire->site_web): ?>
                                                    <div class="flex items-center justify-center text-xs text-iri-accent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        <i class="fas fa-external-link-alt mr-1"></i>
                                                        Visiter le site
                                                    </div>
                                                <?php else: ?>
                                                    <div class="flex items-center justify-center text-xs text-gray-400">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Pas de site web
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                <?php if($partenaire->site_web): ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Contrôles de défilement supprimés pour un design plus épuré -->
            </div>
        </section>

        <!-- Instructions -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
            <h2 class="text-xl font-bold mb-4">📋 Instructions de test :</h2>
            <div class="space-y-3 text-gray-700">
                <p>1. <strong>Défilement :</strong> Les logos défilent automatiquement de droite à gauche sans encadrement</p>
                <p>2. <strong>Design épuré :</strong> Plus d'encadrés ni de boutons de contrôle</p>
                <p>3. <strong>Liens cliquables :</strong> Survolez et cliquez sur les logos avec un site web</p>
                <p>4. <strong>Hover :</strong> L'animation se pause au survol et les logos passent de noir/blanc à couleur</p>
                <p>5. <strong>Responsive :</strong> Testez sur différentes tailles d'écran</p>
                <p>6. <strong>Simplification :</strong> Design minimaliste centré sur les logos</p>
            </div>
        </div>
    </div>

    <!-- Styles CSS -->
    <style>
        .animate-scroll-smooth {
            animation: scroll-horizontal linear infinite;
            animation-play-state: running;
        }

        .animate-scroll-smooth:hover {
            animation-play-state: paused;
        }

        @keyframes scroll-horizontal {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .partner-item {
            min-width: 150px;
        }

        .partner-item:hover {
            z-index: 10;
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const partnersScroll = document.getElementById('partners-scroll');
            const pauseBtn = document.getElementById('partners-pause');
            const playBtn = document.getElementById('partners-play');
            
            if (partnersScroll && pauseBtn && playBtn) {
                // Contrôles de pause/lecture
                pauseBtn.addEventListener('click', function() {
                    partnersScroll.style.animationPlayState = 'paused';
                    pauseBtn.classList.add('hidden');
                    playBtn.classList.remove('hidden');
                });

                playBtn.addEventListener('click', function() {
                    partnersScroll.style.animationPlayState = 'running';
                    playBtn.classList.add('hidden');
                    pauseBtn.classList.remove('hidden');
                });

                // Pause automatique au survol
                partnersScroll.addEventListener('mouseenter', function() {
                    this.style.animationPlayState = 'paused';
                });

                partnersScroll.addEventListener('mouseleave', function() {
                    if (!pauseBtn.classList.contains('hidden')) {
                        this.style.animationPlayState = 'running';
                    }
                });
            }

            console.log('✅ Section partenaires initialisée avec succès !');
            console.log('📊 Nombre de partenaires:', document.querySelectorAll('.partner-item').length / 2);
            console.log('🔗 Partenaires avec liens:', document.querySelectorAll('.partner-item a').length / 2);
        });
    </script>
</body>
</html>
