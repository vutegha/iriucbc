@extends('layouts.iri')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endpush

<div>
    <!-- HERO avec background -->
    <div class="relative w-full h-screen bg-cover bg-center"
         style="background-image: url('{{ asset('assets/img/research.jpg') }}');">

        <!-- Overlay pour assombrir l'image -->
        <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/70"></div>

        <!-- Actualités en vedette et à la une - Section supérieure -->
        <div class="absolute top-0 left-0 right-0 z-20 px-4 sm:px-6 lg:px-8 pt-0 hidden lg:block">
            <!-- Barre horizontale des actualités - Container full width -->
            <div class="bg-black/40 backdrop-blur-sm rounded-lg p-2 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-2">
                    @foreach($actualites as $actualite)
                        <div class="flex items-center space-x-2 hover:bg-white/10 rounded-lg p-2 transition-all duration-300 min-h-[60px]">
                            <!-- Image circulaire -->
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-orange-400 shadow-md flex-shrink-0">
                                <img src="{{ asset('storage/'.$actualite->image) }}" 
                                     alt="{{ $actualite->titre }}"
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Titre avec wrap -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('site.actualite.show', ['slug' => $actualite->slug]) }}" 
                                   class="text-white hover:text-orange-300 font-medium text-sm transition-colors duration-200 break-words leading-tight">
                                    {{ $actualite->titre }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Contenu principal centré -->
        <div class="relative z-10 flex items-center justify-center h-full">
            <div class="text-center text-white max-w-5xl mx-auto px-6">
                <div class="bg-gradient-to-r from-black/60 via-black/40 to-black/60 backdrop-blur-sm p-8 lg:p-12 rounded-2xl shadow-2xl">
                    <!-- Titre principal -->
                    <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold leading-tight mb-6">
                        <span class="bg-gradient-to-r from-iri-accent via-iri-gold to-iri-accent bg-clip-text text-transparent">
                            La recherche appliquée
                        </span>
                        <br>
                        <span class="text-white">
                            en réponse aux besoins sociétaux
                        </span>
                    </h1>

                    <!-- Sous-titre -->
                    <p class="text-lg sm:text-xl lg:text-2xl text-gray-200 max-w-4xl mx-auto mb-8 leading-relaxed">
                        Nous développons des solutions concrètes, ancrées dans les réalités congolaises,
                        pour bâtir des communautés résilientes et durables.
                    </p>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ url('/about') }}"
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-iri-accent to-iri-gold hover:from-iri-gold hover:to-iri-accent text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            Qui sommes-nous ?
                        </a>
                        
                        <a href="{{ url('/services') }}" 
                           class="inline-flex items-center px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-iri-primary font-bold rounded-lg transition-all duration-300 transform hover:scale-105">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Nos services
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MISSION & SECTEURS -->
    <section id="aboutus" class="py-20 bg-gradient-to-b from-white via-gray-50 to-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-20 left-10 w-32 h-32 bg-iri-accent rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-iri-gold rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-20">
                
                <h2 class="text-4xl md:text-5xl font-bold primary-text mb-6 font-poppins">
                    Recherche <span class="text-iri-accent">Appliquée</span> & Innovation
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Découvrez notre engagement pour la recherche scientifique et nos domaines d'expertise 
                    au service du développement durable en République Démocratique du Congo
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
                <!-- Mission Card Moderne -->
                <div class="relative overflow-hidden rounded-3xl shadow-2xl group hover:shadow-3xl transition-all duration-700 transform hover:-translate-y-3 mission-card-bg h-[500px]">
                    <img src="{{ asset('assets/img/iri.jpg') }}" alt="Mission" class="w-full h-64 object-cover">
                    <div class="p-6 space-y-4">
                        <h3 class="text-2xl font-bold">Notre Mission</h3>
                        <p class="text-md font-light mt-2">
                            L'Institut de Recherche Intégré à l'Université Chrétienne Bilingue du Congo (IRI-UCBC) a pour mission de promouvoir la recherche scientifique, l'innovation et le développement durable au service de la société congolaise et africaine. À travers des projets interdisciplinaires, l'institut vise à renforcer les capacités, encourager la collaboration et produire des connaissances utiles pour répondre aux défis locaux au sein des communautés locales en République Démocratique du Congo.
                        </p>
                    </div>
                </div>

                <!-- Secteurs -->
                <div class="space-y-6">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 bg-gradient-to-br from-iri-accent to-iri-gold rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-cogs text-white text-xl"></i>
                        </div>
                        <h4 class="text-2xl font-bold primary-text">Nos Secteurs d'interets</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($services as $service)
                        <a href="{{ route('site.service.show', ['slug' => $service->slug]) }}" 
                           class="group cursor-pointer block">
                            
                            <div class="relative overflow-hidden rounded-2xl bg-white border-2 border-gray-100 hover:border-iri-accent/30 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 transform">
                                <!-- Header moderne avec couleurs IRI -->
                                <div class="p-5 relative overflow-hidden bg-gradient-to-r from-iri-primary to-iri-secondary">
                                    <div class="relative flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0 border border-white/30">
                                            <img src="{{ asset('storage/' . $service->icone) }}"
                                                 alt="{{ $service->nom }}"
                                                 class="w-6 h-6 object-contain filter brightness-0 invert">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-bold text-white text-base leading-tight group-hover:text-iri-gold transition-colors duration-300">
                                                {{ $service->nom }}
                                            </h5>
                                            <div class="w-12 h-1 bg-iri-gold rounded-full mt-2 transform group-hover:w-20 transition-all duration-300"></div>
                                        </div>
                                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-iri-gold/30 transition-colors duration-300">
                                            <i class="fas fa-arrow-right text-white text-sm transform group-hover:translate-x-1 transition-transform duration-300"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contenu -->
                                <div class="p-5">
                                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 group-hover:text-gray-700 transition-colors duration-300">
                                        {{ Str::limit($service->description, 100) }}
                                    </p>
                                    
                                    <!-- Action indicator modernisé -->
                                    <div class="flex items-center justify-between mt-4">
                                        <span class="text-xs text-gray-400 group-hover:text-iri-accent transition-colors duration-300">Cliquez pour explorer</span>
                                        <div class="w-8 h-8 bg-gradient-to-r from-iri-accent to-iri-gold rounded-full flex items-center justify-center shadow-md transform group-hover:scale-110 transition-all duration-300">
                                            <i class="fas fa-arrow-right text-white text-sm"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Hover effect overlay avec couleurs IRI -->
                                <div class="absolute inset-0 bg-gradient-to-r from-iri-accent/5 to-iri-gold/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Publications & Événements -->
    <section id="publications" class="py-20 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <!-- Background decoratif -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-32 left-20 w-24 h-24 bg-iri-primary rounded-full blur-2xl"></div>
            <div class="absolute bottom-32 right-20 w-32 h-32 bg-iri-accent rounded-full blur-2xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <!-- Header principal -->
            <div class="text-center mb-&-">
                
                <h2 class="text-4xl md:text-5xl font-bold primary-text mb-6 font-poppins">
                    Nos <span class="text-iri-accent">Ressources</span> & Actualités
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Découvrez nos dernières publications de recherche, rapports d'activités et événements marquants de l'IRI-UCBC
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Publications Section - 2/3 de l'espace -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                <i class="fas fa-file-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold primary-text">Publications & Rapports</h3>
                                <p class="text-sm text-gray-500">Nos dernières recherches et ressources</p>
                            </div>
                        </div>
                        <a href="{{ url('/publications') }}" class="text-iri-accent hover:text-iri-primary transition-colors duration-300 font-semibold text-sm flex items-center">
                            Voir tout <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>

                    <!-- Carousel des publications et rapports modernisé -->
                    <div class="splide publication-carousel" id="project-carousel">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach($documentsRecents as $document)
                                <li class="splide__slide">
                                    <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 bg-white border border-gray-100 h-96">
                                        <!-- Canvas avec overlay -->
                                        <div class="relative h-full overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                            @if($document instanceof \App\Models\Publication)
                                                <canvas id="pdf-canvas-pub-{{$document->id}}" 
                                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                                        data-pdf-url="{{ asset('storage/'.$document->fichier_pdf) }}">
                                                </canvas>
                                            @else
                                                <canvas id="pdf-canvas-rap-{{$document->id}}" 
                                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                                        data-pdf-url="{{ asset('storage/'.$document->fichier) }}">
                                                </canvas>
                                            @endif
                                            
                                            <!-- Overlay avec informations -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40 opacity-90 group-hover:opacity-95 transition-opacity duration-300">
                                                <!-- Badge type en haut à gauche -->
                                                <div class="absolute top-4 left-4">
                                                    <span class="bg-iri-primary/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">
                                                        <i class="fas fa-file-pdf mr-1"></i>
                                                        {{ $document instanceof \App\Models\Publication ? 'Publication' : 'Rapport' }}
                                                    </span>
                                                </div>
                                                
                                                <!-- Catégorie en haut à droite (si applicable) -->
                                                @if($document instanceof \App\Models\Publication && $document->categorie)
                                                    <div class="absolute top-4 right-4">
                                                        <span class="bg-iri-accent/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">
                                                            {{ $document->categorie->nom }}
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Informations en bas -->
                                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                                    <!-- Date -->
                                                    <div class="mb-3">
                                                        <span class="text-xs text-iri-gold font-semibold uppercase tracking-wider">
                                                            {{ $document->created_at->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Titre -->
                                                    <h4 class="font-bold text-white text-lg leading-tight mb-3 group-hover:text-iri-gold transition-colors duration-300">
                                                        @if($document instanceof \App\Models\Publication)
                                                            <a href="{{ route('publication.show', ['slug' => $document->slug]) }}" class="hover:underline">
                                                                {{ $document->titre }}
                                                            </a>
                                                        @else
                                                            <a href="{{ asset('storage/'.$document->fichier) }}" target="_blank" class="hover:underline">
                                                                {{ $document->titre }}
                                                            </a>
                                                        @endif
                                                    </h4>
                                                    
                                                    <!-- Actions en bas -->
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-2">
                                                            @if($document instanceof \App\Models\Publication)
                                                                <span class="bg-white/20 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                                                                    Par {{ $document->auteur->nom ?? 'IRI-UCBC' }}
                                                                </span>
                                                            @else
                                                                <span class="bg-white/20 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                                                                    {{ $document->created_at->diffForHumans() }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Bouton d'action -->
                                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                            @if($document instanceof \App\Models\Publication)
                                                                <a href="{{ route('publication.show', ['slug' => $document->slug]) }}" 
                                                                   class="bg-iri-accent hover:bg-iri-gold text-white px-4 py-2 rounded-full font-semibold text-xs transition-all duration-300 flex items-center">
                                                                    <i class="fas fa-eye mr-2"></i>
                                                                    Consulter
                                                                </a>
                                                            @else
                                                                <a href="{{ asset('storage/'.$document->fichier) }}" target="_blank"
                                                                   class="bg-iri-accent hover:bg-iri-gold text-white px-4 py-2 rounded-full font-semibold text-xs transition-all duration-300 flex items-center">
                                                                    <i class="fas fa-download mr-2"></i>
                                                                    Télécharger
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div id="projets_stats" class="mt-16 bg-gradient-to-br from-white via-gray-50 to-blue-50 rounded-2xl p-8 shadow-xl border border-gray-100">
                        <!-- Header des statistiques -->
                        <div class="text-center mb-12">
                            
                            <h3 class="text-3xl md:text-4xl font-bold primary-text mb-4">
                                Notre <span class="text-iri-accent">Impact</span> en Chiffres
                            </h3>
                            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                                Découvrez l'impact concret de nos projets de recherche et d'intervention sur les communautés de la RDC
                            </p>
                        </div>

                        <!-- KPI Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                            <!-- Total Projets -->
                            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-iri-accent/30 group">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-project-diagram text-white text-xl"></i>
                                    </div>
                                    <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total</span>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-3xl font-bold text-iri-primary">{{ $statsProjects['total_projets'] }}</h4>
                                    <p class="text-sm text-gray-600">Projets réalisés</p>
                                </div>
                                <div class="mt-4 flex items-center text-xs">
                                    <span class="text-green-600 font-semibold">{{ $statsProjects['projets_en_cours'] }} en cours</span>
                                    <span class="mx-2 text-gray-400">•</span>
                                    <span class="text-blue-600 font-semibold">{{ $statsProjects['projets_termines'] }} terminés</span>
                                </div>
                            </div>

                            <!-- Total Bénéficiaires -->
                            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-iri-accent/30 group">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-iri-accent to-iri-gold rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-users text-white text-xl"></i>
                                    </div>
                                    <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Impact</span>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-3xl font-bold text-iri-accent">{{ number_format($statsProjects['total_beneficiaires']) }}</h4>
                                    <p class="text-sm text-gray-600">Bénéficiaires touchés</p>
                                </div>
                                <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                                    @php
                                        $totalBenef = $statsProjects['total_beneficiaires'];
                                        $pourcentageFemmes = $totalBenef > 0 ? ($statsProjects['beneficiaires_femmes'] / $totalBenef) * 100 : 50;
                                    @endphp
                                    <div class="bg-gradient-to-r from-pink-400 to-purple-500 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $pourcentageFemmes }}%"></div>
                                </div>
                                <div class="mt-2 flex justify-between text-xs">
                                    <span class="text-pink-600 font-semibold">{{ number_format($statsProjects['beneficiaires_femmes']) }} femmes</span>
                                    <span class="text-blue-600 font-semibold">{{ number_format($statsProjects['beneficiaires_hommes']) }} hommes</span>
                                </div>
                            </div>

                            <!-- Zones d'intervention -->
                            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-iri-accent/30 group">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-map-marked-alt text-white text-xl"></i>
                                    </div>
                                    <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Portée</span>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-3xl font-bold text-green-600">{{ $statsProjects['zones_intervention'] }}</h4>
                                    <p class="text-sm text-gray-600">Secteurs d'intervention</p>
                                </div>
                                <div class="mt-4 text-xs text-gray-500">
                                    <i class="fas fa-map-pin mr-1"></i>
                                    Présence active en RDC
                                </div>
                            </div>

                            <!-- Taux de réussite -->
                            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-iri-accent/30 group">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-trophy text-white text-xl"></i>
                                    </div>
                                    <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Performance</span>
                                </div>
                                <div class="space-y-1">
                                    @php
                                        $tauxReussite = $statsProjects['total_projets'] > 0 ? 
                                            round(($statsProjects['projets_termines'] / $statsProjects['total_projets']) * 100) : 0;
                                    @endphp
                                    <h4 class="text-3xl font-bold text-yellow-600">{{ $tauxReussite }}%</h4>
                                    <p class="text-sm text-gray-600">Taux de réussite</p>
                                </div>
                                <div class="mt-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-2 rounded-full transition-all duration-500" 
                                             style="width: {{ $tauxReussite }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Section -->
                       

                        <!-- Bénéficiaires par Secteur -->
                       

                        <!-- Call to Action -->
                        <div class="mt-12 text-center">
                            <div class="bg-gradient-to-r from-iri-primary via-iri-accent to-iri-gold p-8 rounded-2xl text-white shadow-xl">
                                <h4 class="text-2xl font-bold mb-4">Rejoignez Notre Mission</h4>
                                <p class="text-lg mb-6 opacity-90">Ensemble, créons un impact durable pour les communautés de la RDC</p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <a href="{{ url('/travailler avec nous') }}" 
                                       class="inline-flex items-center px-8 py-3 bg-white text-iri-primary font-bold rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                                        <i class="fas fa-handshake mr-2"></i>
                                        Travaillez avec nous
                                    </a>
                                    <a href="{{ url('/contact') }}" 
                                       class="inline-flex items-center px-8 py-3 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-iri-primary transition-all duration-300 transform hover:scale-105">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Nous Contacter
                                    </a>
                                    <a href="{{ url('/contact') }}" 
                                       class="inline-flex items-center px-8 py-3 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-iri-primary transition-all duration-300 transform hover:scale-105">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Faire un Don 
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <!-- Aside Événements -->
                <aside class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <!-- En-tête des événements -->
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-iri-accent to-iri-gold rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold primary-text">Événements</h3>
                                <p class="text-xs text-gray-500">Nos prochaines activités</p>
                            </div>
                        </div>
                        
                        <!-- Liste dynamique des événements -->
                        <div class="space-y-4 mb-8">
                            @forelse($evenements as $evenement)
                                <div class="bg-gray-50 rounded-lg p-4 relative">
                                    <!-- Badge distinctif pour événements passés -->
                                    @if($evenement->est_passe)
                                        <div class="absolute top-2 right-2">
                                            <span class="bg-gray-400 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                Passé
                                            </span>
                                        </div>
                                    @else
                                        <div class="absolute top-2 right-2">
                                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                À venir
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 {{ $evenement->est_passe ? 'bg-gray-400' : 'bg-iri-primary' }} rounded-lg flex items-center justify-center text-white">
                                            <span class="text-xs font-bold">
                                                {{ \Carbon\Carbon::parse($evenement->date_debut)->format('M') }}
                                            </span>
                                        </div>
                                        <div class="flex-1 pr-12">
                                            <h4 class="font-semibold text-sm {{ $evenement->est_passe ? 'text-gray-600' : 'text-gray-900' }} drop-shadow-sm">
                                                <a href="{{ route('site.evenement.show', $evenement->id) }}" 
                                                   class="hover:text-iri-accent transition-colors duration-200 hover:underline">
                                                    {{ $evenement->titre }}
                                                </a>
                                            </h4>
                                            <p class="text-xs text-gray-500 drop-shadow-sm">
                                                {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d M Y') }}
                                                @if($evenement->lieu)
                                                    • {{ $evenement->lieu }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <!-- Fallback si aucun événement -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-iri-primary rounded-lg flex items-center justify-center text-white">
                                            <span class="text-xs font-bold">JUL</span>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-sm drop-shadow-sm">Conférence IRI 2025</h4>
                                            <p class="text-xs text-gray-500 drop-shadow-sm">25 Juillet 2025</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-iri-secondary rounded-lg flex items-center justify-center text-white">
                                            <span class="text-xs font-bold">AUG</span>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-sm drop-shadow-sm">Formation Recherche</h4>
                                            <p class="text-xs text-gray-500 drop-shadow-sm">15 Août 2025</p>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Section Twitter -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fab fa-twitter text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-blue-900 text-sm drop-shadow-sm">Actualités Twitter</h4>
                                    <p class="text-xs text-blue-600 drop-shadow-sm">Suivez-nous @IRI_UCBC</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="bg-white rounded-lg p-3 shadow-sm border border-blue-100">
                                    <p class="text-xs text-gray-700 leading-relaxed drop-shadow-sm">
                                        "Nouveau partenariat stratégique avec l'Université de Kinshasa 🤝 #Innovation #Recherche"
                                    </p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-blue-500 font-medium drop-shadow-sm">@IRI_UCBC</span>
                                        <span class="text-xs text-gray-400 drop-shadow-sm">Il y a 2h</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <a href="https://twitter.com/IRI_UCBC" target="_blank" 
                                   class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                                    <i class="fab fa-twitter mr-1"></i>
                                    Suivre sur Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

    </section>

    <!-- Section partenaires -->
    <section class="bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-center text-2xl font-semibold text-gray-800 mb-6">Nos partenaires</h2>
            @if(optional($partenaires)->count() > 0)
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
                
                <!-- CSS supplémentaire pour les partenaires -->
                <style>
                    .partner-logo:hover {
                        filter: grayscale(0%) !important;
                        transform: scale(1.05);
                    }
                </style>
                
                <script>
                    // JavaScript pour la gestion des partenaires
                    document.addEventListener('DOMContentLoaded', function() {
                        const partnersContainer = document.querySelector('.animate-scroll-infinite');
                        
                        if (partnersContainer) {
                            const partnerLogos = partnersContainer.querySelectorAll('.partner-logo');
                            let validLogosCount = 0;
                            let totalLogos = partnerLogos.length;
                            
                            partnerLogos.forEach(function(img) {
                                // Test de chargement d'image amélioré
                                const testImg = new Image();
                                testImg.onload = function() {
                                    img.style.opacity = '1';
                                    validLogosCount++;
                                    
                                    // Si tous les logos sont chargés, vérifier s'il y en a assez pour l'animation
                                    if (validLogosCount + (totalLogos - validLogosCount) === totalLogos) {
                                        if (validLogosCount === 0) {
                                            // Aucun logo valide, masquer la section
                                            partnersContainer.closest('section').style.display = 'none';
                                        } else if (validLogosCount < 3) {
                                            // Peu de logos, ralentir l'animation
                                            partnersContainer.style.animationDuration = '60s';
                                        }
                                    }
                                };
                                
                                testImg.onerror = function() {
                                    img.style.display = 'none';
                                    console.log('Logo invalide pour:', img.alt);
                                };
                                
                                testImg.src = img.src;
                                
                                // Timeout de sécurité
                                setTimeout(function() {
                                    if (img.style.opacity === '0') {
                                        img.style.display = 'none';
                                    }
                                }, 3000);
                            });
                        }
                    });
                </script>
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
</div> <!-- Fermeture du conteneur principal -->

@endsection

@push('scripts')
<!-- CSS Dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

<!-- JavaScript Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Splide('#project-carousel', {
        type: 'loop',
        perPage: 3,
        gap: '1rem',
        autoplay: true,
        interval: 3000,
        pagination: false,
        breakpoints: {
            1024: { perPage: 2 },
            640 : { perPage: 1 },
        }
    }).mount();

    // PDF rendering code amélioré
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        // Gestion des canvas pour publications et rapports
        document.querySelectorAll('canvas[data-pdf-url]').forEach(canvas => {
            const url = canvas.getAttribute('data-pdf-url');
            const ctx = canvas.getContext('2d');
            const containerWidth = canvas.parentElement.offsetWidth;
            const containerHeight = canvas.parentElement.offsetHeight;

            pdfjsLib.getDocument(url).promise.then(pdf => {
                return pdf.getPage(1);
            }).then(page => {
                const viewport = page.getViewport({ scale: 1 });
                
                // Calculer l'échelle pour remplir le conteneur
                const scaleX = containerWidth / viewport.width;
                const scaleY = containerHeight / viewport.height;
                const scale = Math.max(scaleX, scaleY); // Utiliser la plus grande échelle pour couvrir
                
                const scaledViewport = page.getViewport({ scale });

                canvas.width = containerWidth;
                canvas.height = containerHeight;

                // Centrer le PDF dans le canvas
                const offsetX = (containerWidth - scaledViewport.width) / 2;
                const offsetY = (containerHeight - scaledViewport.height) / 2;

                ctx.save();
                ctx.translate(offsetX, offsetY);
                
                return page.render({ 
                    canvasContext: ctx, 
                    viewport: scaledViewport 
                }).promise;
            }).then(() => {
                ctx.restore();
            }).catch(err => {
                console.error("Erreur lors du rendu du PDF :", err);
                // Afficher une image de fallback
                ctx.fillStyle = '#f3f4f6';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#6b7280';
                ctx.font = '16px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('Document PDF', canvas.width / 2, canvas.height / 2);
            });
        });
    }

    // Simple modal functions (optional)
    window.showModal = function(title, content) {
        const modal = document.getElementById('simple-modal');
        if (modal) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-content').textContent = content;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function() {
        const modal = document.getElementById('simple-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});
</script>

<!-- Simple Modal (hidden by default) -->
<div id="simple-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg max-w-md w-full mx-4 p-6 relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h3 id="modal-title" class="text-lg font-semibold mb-4 text-iri-primary"></h3>
        <p id="modal-content" class="text-gray-600 mb-6"></p>
        <div class="flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-iri-primary text-white rounded hover:bg-iri-secondary transition">
                Fermer
            </button>
        </div>
    </div>
</div>
@endpush
