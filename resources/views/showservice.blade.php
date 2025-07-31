@extends('layouts.iri')

@section('title', $service->exists ? 'Service - ' . $service->nom : 'Service introuvable')

@section('breadcrumb')
@if($service->exists)
    <x-breadcrumb-overlay :items="[
        ['title' => 'Services', 'url' => route('site.services')],
        ['title' => Str::limit($service->nom, 50), 'url' => null]
    ]" />
@endif
@endsection

@section('content')
@if($service->exists)
    <!-- Main Content -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <!-- Hero Section -->
        <section class="relative">
            <div class="relative h-96 bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent overflow-hidden">
                @if($service->image)
                    <img src="{{ asset('storage/'.$service->image) }}" 
                         alt="{{ $service->nom }}" 
                         class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
                @endif
                <div class="absolute inset-0 bg-black/20"></div>
                
                <div class="relative z-10 flex items-center justify-center h-full">
                    <div class="text-center text-white px-6 max-w-4xl mx-auto">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 drop-shadow-2xl">
                            {{ $service->nom }}
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Service Description -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">À propos de ce service</h2>
                            
                            @if($service->resume)
                                <p class="text-xl md:text-2xl text-gray-700 leading-relaxed mb-8 font-medium border-l-4 border-iri-primary pl-6 bg-gray-50 py-4 rounded-r-lg">
                                    {{ $service->resume }}
                                </p>
                            @endif
                            
                            @if($service->contenu)
                                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                    {!! $service->contenu !!}
                                </div>
                            @elseif($service->description)
                                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                    {!! $service->description !!}
                                </div>
                            @else
                                <div class="text-gray-700 text-lg leading-relaxed space-y-4">
                                    <p>Ce service fait partie intégrante de notre mission d'accompagnement et de développement. Notre équipe expérimentée met tout en œuvre pour fournir des solutions adaptées et de qualité.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                            <!-- Back Button -->
                            <div class="mb-6">
                                <a href="{{ route('site.services') }}" 
                                   class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Retour aux services
                                </a>
                            </div>

                            <!-- Actualités liées au service -->
                            @if(optional($service->actualites)->count() > 0)
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actualités récentes</h3>
                                    <div class="space-y-4">
                                        @php
                                            // Trier les actualités : à la une et vedette > vedette > autres
                                            $actualitesTriees = $service->actualites->sortByDesc(function($actualite) {
                                                if($actualite->a_la_une && $actualite->en_vedette) return 3;
                                                if($actualite->en_vedette) return 2;
                                                return 1;
                                            })->take(3);
                                        @endphp
                                        
                                        @foreach($actualitesTriees as $actualite)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                                @if($actualite->a_la_une && $actualite->en_vedette)
                                                    <div class="flex items-center mb-2">
                                                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full mr-2">
                                                            <i class="fas fa-star mr-1"></i>À la une
                                                        </span>
                                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                                            <i class="fas fa-bookmark mr-1"></i>Vedette
                                                        </span>
                                                    </div>
                                                @elseif($actualite->en_vedette)
                                                    <div class="mb-2">
                                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                                            <i class="fas fa-bookmark mr-1"></i>En vedette
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                <h4 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2">
                                                    {{ $actualite->titre }}
                                                </h4>
                                                <p class="text-xs text-gray-500 mb-2">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    {{ $actualite->created_at->format('d M Y') }}
                                                </p>
                                                @if($actualite->slug)
                                                    <a href="{{ route('site.actualite.show', $actualite->slug) }}" 
                                                       class="text-iri-primary hover:text-iri-secondary text-xs font-semibold">
                                                        Lire plus →
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if(optional($service->actualites)->count() > 3)
                                        <div class="mt-4 text-center">
                                            <a href="{{ route('site.actualites') }}" 
                                               class="text-iri-primary hover:text-iri-secondary text-sm font-semibold">
                                                Voir toutes les actualités →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Service Stats -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-gray-900">Statistiques</h3>
                                
                                <div class="space-y-4">
                                    @if(optional($service->projets)->count() > 0)
                                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                                            <div class="flex items-center">
                                                <div class="bg-blue-500 p-2 rounded-lg mr-3">
                                                    <i class="fas fa-project-diagram text-white"></i>
                                                </div>
                                                <span class="text-gray-700 font-medium">Projets</span>
                                            </div>
                                            <span class="text-blue-600 font-bold text-lg">{{ optional($service->projets)->count() ?? 0 }}</span>
                                        </div>
                                    @endif

                                    @if(optional($service->actualites)->count() > 0)
                                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                                            <div class="flex items-center">
                                                <div class="bg-green-500 p-2 rounded-lg mr-3">
                                                    <i class="fas fa-newspaper text-white"></i>
                                                </div>
                                                <span class="text-gray-700 font-medium">Actualités</span>
                                            </div>
                                            <span class="text-green-600 font-bold text-lg">{{ optional($service->actualites)->count() ?? 0 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Contact for Service -->
                            <div class="mt-8 p-4 bg-gradient-to-r from-iri-primary to-iri-secondary rounded-lg text-white">
                                <h4 class="font-bold mb-2">Intéressé par ce service ?</h4>
                                <p class="text-sm mb-4 text-white/90">Contactez-nous pour plus d'informations</p>
                                <a href="{{ route('site.contact') }}" 
                                   class="inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-2 px-4 rounded-lg hover:bg-white/30 transition-all duration-200 w-full justify-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Nous contacter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Projects -->
        @php
            $projetsEnCours = $service->projets->where('etat', 'en cours');
        @endphp
        @if($projetsEnCours->count() > 0)
            <section class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Projets en cours associés à ce service
                        </h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                            Découvrez les projets actuellement en cours de réalisation dans le cadre de ce service
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($projetsEnCours->take(6) as $projet)
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                                @if($projet->image)
                                    <div class="h-48 overflow-hidden">
                                        <img src="{{ asset('storage/'.$projet->image) }}" 
                                             alt="{{ $projet->nom }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                @else
                                    <div class="h-48 bg-gradient-to-br from-iri-primary to-iri-secondary flex items-center justify-center">
                                        <i class="fas fa-project-diagram text-white text-3xl opacity-70"></i>
                                    </div>
                                @endif

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $projet->nom }}
                                    </h3>
                                    
                                    @if($projet->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                            {{ Str::limit(strip_tags($projet->description), 100) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $projet->created_at->format('Y') }}
                                        </span>
                                        
                                        @if($projet->slug)
                                            <a href="{{ route('site.projet.show', $projet->slug) }}" 
                                               class="text-iri-primary hover:text-iri-secondary font-semibold text-sm transition-colors duration-200">
                                                Voir plus →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(optional($service->projets)->count() > 6)
                        <div class="text-center mt-12">
                            <a href="{{ route('site.projets') }}" 
                               class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-8 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Voir tous les projets
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Projets en cours pour ce service -->
        @if(optional($service->projets)->where('statut', 'en_cours')->count() > 0)
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Projets en cours
                        </h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                            Découvrez les projets actuellement en développement dans le cadre de ce service
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($service->projets->where('statut', 'en_cours')->take(6) as $projet)
                            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                                @if($projet->image)
                                    <div class="h-48 overflow-hidden relative">
                                        <img src="{{ asset('storage/'.$projet->image) }}" 
                                             alt="{{ $projet->nom }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                        <div class="absolute top-4 left-4">
                                            <span class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">
                                                <i class="fas fa-play mr-1"></i>En cours
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center relative">
                                        <i class="fas fa-project-diagram text-white text-4xl opacity-80"></i>
                                        <div class="absolute top-4 left-4">
                                            <span class="bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full border border-white/30">
                                                <i class="fas fa-play mr-1"></i>En cours
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-iri-primary transition-colors">
                                        {{ $projet->nom }}
                                    </h3>
                                    
                                    @if($projet->resume)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                                            {!! Str::limit(strip_tags($projet->resume), 120) !!}
                                        </p>
                                    @elseif($projet->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                                            {!! Str::limit(strip_tags($projet->description), 120) !!}
                                        </p>
                                    @endif

                                    <!-- Informations du projet -->
                                    <div class="space-y-2 mb-4">
                                        @if($projet->date_debut)
                                            <div class="flex items-center text-xs text-gray-500">
                                                <i class="fas fa-calendar-start mr-2 text-iri-primary"></i>
                                                <span>Début : {{ \Carbon\Carbon::parse($projet->date_debut)->format('M Y') }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($projet->date_fin)
                                            <div class="flex items-center text-xs text-gray-500">
                                                <i class="fas fa-calendar-check mr-2 text-iri-accent"></i>
                                                <span>Fin prévue : {{ \Carbon\Carbon::parse($projet->date_fin)->format('M Y') }}</span>
                                            </div>
                                        @endif

                                        @if($projet->budget)
                                            <div class="flex items-center text-xs text-gray-500">
                                                <i class="fas fa-dollar-sign mr-2 text-iri-gold"></i>
                                                <span>Budget : {{ number_format($projet->budget, 0, ',', ' ') }} USD</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Progress bar si disponible -->
                                    @if(isset($projet->progression))
                                        <div class="mb-4">
                                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                                <span>Progression</span>
                                                <span>{{ $projet->progression }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-iri-primary to-iri-secondary h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $projet->progression }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <span class="text-xs text-gray-500 bg-gray-50 px-3 py-1 rounded-full">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $projet->beneficiaires_total ?? 'N/A' }} bénéficiaires
                                        </span>
                                        
                                        @if($projet->slug)
                                            <a href="{{ route('site.projet.show', $projet->slug) }}" 
                                               class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold text-sm transition-colors duration-200 group">
                                                Voir détails 
                                                <i class="fas fa-arrow-right ml-1 transition-transform duration-200 group-hover:translate-x-1"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(optional($service->projets)->where('statut', 'en_cours')->count() > 6)
                        <div class="text-center mt-12">
                            <a href="{{ route('site.projets') }}?statut=en_cours" 
                               class="inline-flex items-center bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold py-3 px-8 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-play mr-2"></i>
                                Voir tous les projets en cours
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Related News -->
        @if(optional($service->actualites)->count() > 0)
            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Actualités liées à ce service
                        </h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                            Restez informé des dernières nouvelles concernant ce service
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($service->actualites->take(6) as $actualite)
                            <article class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                                @if($actualite->image)
                                    <div class="h-48 overflow-hidden">
                                        <img src="{{ asset('storage/'.$actualite->image) }}" 
                                             alt="{{ $actualite->titre }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                @else
                                    <div class="h-48 bg-gradient-to-br from-iri-accent to-iri-gold flex items-center justify-center">
                                        <i class="fas fa-newspaper text-white text-3xl opacity-70"></i>
                                    </div>
                                @endif

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $actualite->titre }}
                                    </h3>
                                    
                                    @if($actualite->contenu)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                            {{ Str::limit(strip_tags($actualite->contenu), 120) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $actualite->created_at->format('d M Y') }}
                                        </span>
                                        
                                        @if($actualite->slug)
                                            <a href="{{ route('site.actualite.show', $actualite->slug) }}" 
                                               class="text-iri-primary hover:text-iri-secondary font-semibold text-sm transition-colors duration-200">
                                                Lire plus →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if(optional($service->actualites)->count() > 6)
                        <div class="text-center mt-12">
                            <a href="{{ route('site.actualites') }}" 
                               class="inline-flex items-center bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold py-3 px-8 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-newspaper mr-2"></i>
                                Voir toutes les actualités
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    </div>

@else
    <!-- Service Not Found -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex items-center justify-center">
        <div class="max-w-md mx-auto text-center px-4">
            <div class="bg-red-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Service introuvable</h1>
            <p class="text-gray-600 mb-8">
                Le service que vous recherchez n'existe pas ou a été supprimé.
            </p>
            <div class="space-y-4">
                <a href="{{ route('site.services') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-cogs mr-2"></i>
                    Voir tous les services
                </a>
                <div>
                    <a href="{{ route('site.home') }}" 
                       class="text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prose {
        color: #374151;
        max-width: none;
    }
    .prose p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }
    .prose h1, .prose h2, .prose h3, .prose h4 {
        color: #111827;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
</style>
@endsection
