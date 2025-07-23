<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Section Partenaires</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8">Test - Section Partenaires</h1>
            
            <!-- Section partenaires identique à celle de index.blade.php -->
            <section class="bg-gray-50 py-8 rounded-lg">
                <div class="max-w-6xl mx-auto px-4">
                    <h2 class="text-center text-2xl font-semibold text-gray-800 mb-6">Nos partenaires</h2>
                    
                    @php
                        $partenaires = \App\Models\Partenaire::whereNotNull('logo')
                                                             ->publics()
                                                             ->actifs()
                                                             ->ordonnes()
                                                             ->get();
                    @endphp
                    
                    @if($partenaires->count() > 0)
                        <div class="overflow-hidden relative w-full">
                            <div class="animate-scroll-infinite flex gap-8" style="width: fit-content;">
                                <!-- Première série de logos -->
                                @foreach($partenaires as $partenaire)
                                    <img src="{{ $partenaire->logo_url }}" 
                                         alt="{{ $partenaire->nom }}" 
                                         title="{{ $partenaire->nom }}"
                                         class="partner-logo h-16 object-contain flex-shrink-0 transition-all duration-300"
                                         onerror="this.style.display='none'"
                                         onload="this.style.opacity='1'"
                                         style="opacity: 0; filter: grayscale(30%);"/>
                                @endforeach
                                <!-- Duplication pour boucle fluide -->
                                @foreach($partenaires as $partenaire)
                                    <img src="{{ $partenaire->logo_url }}" 
                                         alt="{{ $partenaire->nom }}" 
                                         title="{{ $partenaire->nom }}"
                                         class="partner-logo h-16 object-contain flex-shrink-0 transition-all duration-300"
                                         onerror="this.style.display='none'"
                                         onload="this.style.opacity='1'"
                                         style="opacity: 0; filter: grayscale(30%);"/>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mt-8 text-center">
                            <p class="text-sm text-gray-600">
                                <strong>{{ $partenaires->count() }}</strong> partenaires avec logos valides
                            </p>
                        </div>
                    @else
                        <div class="text-center text-gray-500">
                            <div class="bg-white rounded-lg p-8 shadow-sm border border-gray-200 max-w-md mx-auto">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-handshake text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Partenariats en développement</h3>
                                <p class="text-sm">Nous travaillons activement à établir de nouveaux partenariats stratégiques.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Informations de débogage -->
            <div class="mt-8 bg-white rounded-lg p-6 shadow">
                <h3 class="text-lg font-semibold mb-4">Informations de débogage</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <strong>Total partenaires :</strong> {{ \App\Models\Partenaire::count() }}
                    </div>
                    <div>
                        <strong>Avec logo :</strong> {{ \App\Models\Partenaire::whereNotNull('logo')->count() }}
                    </div>
                    <div>
                        <strong>Publics :</strong> {{ \App\Models\Partenaire::publics()->count() }}
                    </div>
                    <div>
                        <strong>Actifs :</strong> {{ \App\Models\Partenaire::actifs()->count() }}
                    </div>
                </div>
                
                @if($partenaires->count() > 0)
                    <div class="mt-4">
                        <strong>Partenaires affichés :</strong>
                        <ul class="list-disc ml-6 mt-2">
                            @foreach($partenaires as $partenaire)
                                <li>{{ $partenaire->nom }} - <code>{{ $partenaire->logo }}</code></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- CSS supplémentaire -->
    <style>
        .partner-logo:hover {
            filter: grayscale(0%) !important;
            transform: scale(1.05);
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const partnersContainer = document.querySelector('.animate-scroll-infinite');
            
            if (partnersContainer) {
                const partnerLogos = partnersContainer.querySelectorAll('.partner-logo');
                let validLogosCount = 0;
                let totalLogos = partnerLogos.length;
                
                console.log('Total logos à charger:', totalLogos);
                
                partnerLogos.forEach(function(img, index) {
                    const testImg = new Image();
                    testImg.onload = function() {
                        img.style.opacity = '1';
                        validLogosCount++;
                        console.log('Logo chargé avec succès:', img.alt, '(' + validLogosCount + '/' + totalLogos + ')');
                        
                        if (validLogosCount + (totalLogos - validLogosCount) === totalLogos) {
                            if (validLogosCount === 0) {
                                partnersContainer.closest('section').style.display = 'none';
                                console.log('Aucun logo valide, section masquée');
                            } else if (validLogosCount < 3) {
                                partnersContainer.style.animationDuration = '60s';
                                console.log('Peu de logos, animation ralentie');
                            }
                        }
                    };
                    
                    testImg.onerror = function() {
                        img.style.display = 'none';
                        console.log('Erreur de chargement pour:', img.alt, img.src);
                    };
                    
                    testImg.src = img.src;
                    
                    setTimeout(function() {
                        if (img.style.opacity === '0') {
                            img.style.display = 'none';
                            console.log('Timeout atteint pour:', img.alt);
                        }
                    }, 3000);
                });
            }
        });
    </script>
</body>
</html>
