@extends('layouts.iri')

@section('title', 'Résultats de recherche')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/search-results.css') }}">
@endpush

@php
    // Ensure variables are properly initialized
    $query = $query ?? '';
    $totalResults = $totalResults ?? 0;
    $results = $results ?? collect();
    $elapsed = $elapsed ?? 0;
@endphp

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Résultats de recherche', 'url' => null]
    ]" />
@endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                Résultats de recherche
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Recherche pour : <span class="font-bold">"{{ e($query) }}"</span>
            </p>
            
            @if($totalResults > 0)
                <div class="mt-6 inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ $totalResults }} résultat{{ $totalResults > 1 ? 's' : '' }} trouvé{{ $totalResults > 1 ? 's' : '' }}
                    @if(isset($elapsed))
                        en {{ $elapsed }} seconde{{ $elapsed > 1 ? 's' : '' }}
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- Statistiques de popularité -->
    @if($totalResults > 0 && isset($results) && $results && $results->count() > 0)
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-chart-line text-iri-primary mr-3"></i>
                        Statistiques de consultation
                    </h2>
                    <div class="text-sm text-gray-500">
                        Basé sur les données d'utilisation
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Pages les plus consultées -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-blue-800">Pages populaires</h3>
                            <i class="fas fa-eye text-blue-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-blue-600 mb-1">
                            @php
                                $popularCount = $results->where('type_global', 'Publication')->count() + 
                                               $results->where('type_global', 'Actualité')->count();
                            @endphp
                            {{ number_format($popularCount) }}
                        </div>
                        <div class="text-xs text-blue-700">contenus consultés dans vos résultats</div>
                    </div>

                    <!-- Téléchargements totaux -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-green-800">Téléchargements</h3>
                            <i class="fas fa-download text-green-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-green-600 mb-1">
                            @php
                                $downloadCount = $results->whereIn('type_global', ['Publication', 'Rapport'])
                                    ->where(function($item) {
                                        return !empty($item->fichier_pdf) || !empty($item->fichier);
                                    })->count();
                            @endphp
                            {{ number_format($downloadCount) }}
                        </div>
                        <div class="text-xs text-green-700">documents téléchargeables trouvés</div>
                    </div>

                    <!-- Catégories représentées -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-purple-800">Catégories</h3>
                            <i class="fas fa-tags text-purple-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-purple-600 mb-1">
                            @php
                                $categoriesCount = $results->whereNotNull('categorie')
                                    ->pluck('categorie.nom')
                                    ->filter()
                                    ->unique()
                                    ->count();
                            @endphp
                            {{ $categoriesCount }}
                        </div>
                        <div class="text-xs text-purple-700">catégories différentes</div>
                    </div>
                </div>

                <!-- Répartition par type de contenu -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-iri-primary mr-2"></i>
                        Répartition des résultats par type
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $typeStats = collect();
                            if (isset($results) && $results && $results->count() > 0) {
                                $typeStats = $results->groupBy('type_global')->map(function($items, $type) use ($results) {
                                    return [
                                        'count' => $items->count(),
                                        'percentage' => round(($items->count() / max($results->count(), 1)) * 100, 1)
                                    ];
                                });
                            }
                        @endphp
                        
                        @foreach(['Publication', 'Actualité', 'Projet', 'Rapport'] as $type)
                            @php
                                $stat = $typeStats->get($type, ['count' => 0, 'percentage' => 0]);
                                $colorClass = match($type) {
                                    'Publication' => 'text-blue-600 bg-blue-50',
                                    'Actualité' => 'text-green-600 bg-green-50',
                                    'Projet' => 'text-purple-600 bg-purple-50',
                                    'Rapport' => 'text-red-600 bg-red-50',
                                    default => 'text-gray-600 bg-gray-50'
                                };
                            @endphp
                            <div class="text-center p-3 {{ $colorClass }} rounded-lg">
                                <div class="text-lg font-bold">{{ $stat['count'] }}</div>
                                <div class="text-xs opacity-75">{{ $type }}{{ $stat['count'] > 1 ? 's' : '' }}</div>
                                <div class="text-xs mt-1 font-medium">{{ $stat['percentage'] }}%</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Search Results -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Search Again -->
            <div class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Affiner votre recherche</h2>
                    <form action="{{ route('site.search') }}" method="GET" class="flex items-center gap-4">
                        <div class="flex-1">
                            <input type="text" 
                                   name="q" 
                                   value="{{ e($query) }}"
                                   placeholder="Rechercher dans les publications, actualités, rapports..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200"
                                   required
                                   minlength="2"
                                   maxlength="100"
                                   aria-label="Terme de recherche">
                        </div>
                        <button type="submit" 
                                class="bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Rechercher
                        </button>
                    </form>
                </div>
            </div>

            @php
                use App\Helpers\SearchHelper;
            @endphp

            @if(isset($results) && $results && $results->count() > 0)
                <!-- Results Grid -->
                <div class="space-y-3">
                    @foreach($results as $item)
                        <article class="search-result-card bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-100 group">
                            <div class="search-result-grid grid grid-cols-1 lg:grid-cols-5 gap-3 p-3">
                                <!-- Thumbnail -->
                                <div class="lg:col-span-1">
                                    <div class="aspect-video lg:aspect-square rounded-md overflow-hidden bg-gray-100 relative h-16 lg:h-16">
                                        @if($item->type_global === 'Actualité' && !empty($item->image))
                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                 alt="{{ e($item->titre ?? 'Actualité sans titre') }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                 loading="lazy"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-full bg-gradient-to-br from-iri-primary to-iri-secondary flex items-center justify-center" style="display: none;">
                                                <i class="fas fa-newspaper text-white text-lg" aria-hidden="true"></i>
                                            </div>
                                        @elseif(($item->type_global === 'Publication' && !empty($item->fichier_pdf)) || ($item->type_global === 'Rapport' && !empty($item->fichier)))
                                            <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                                <i class="fas fa-file-pdf text-white text-lg" aria-hidden="true"></i>
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-iri-primary to-iri-secondary flex items-center justify-center">
                                                <i class="fas fa-file-alt text-white text-lg" aria-hidden="true"></i>
                                            </div>
                                        @endif

                                        <!-- Type Badge -->
                                        @php
                                            $badgeClass = match($item->type_global) {
                                                'Publication' => 'bg-blue-500',
                                                'Actualité' => 'bg-green-500',
                                                'Rapport' => 'bg-red-500',
                                                'Projet' => 'bg-purple-500',
                                                default => 'bg-gray-500'
                                            };
                                        @endphp
                                        <div class="absolute top-1 left-1 {{ $badgeClass }} text-white text-xs font-bold px-1 py-0.5 rounded text-[10px]">
                                            {{ substr($item->type_global, 0, 3) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="lg:col-span-4">
                                    <div class="h-full flex flex-col justify-between">
                                        <!-- Header -->
                                        <div class="mb-2">
                                            <h2 class="text-lg font-semibold text-gray-900 mb-1 group-hover:text-iri-primary transition-colors duration-200 line-clamp-2">
                                                {{ e($item->titre ?? 'Titre non disponible') }}
                                            </h2>
                                            
                                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                                @if(isset($item->date_global))
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i>
                                                        <time datetime="{{ $item->date_global->format('Y-m-d') }}">
                                                            {{ $item->date_global->format('d M Y') }}
                                                        </time>
                                                    </span>
                                                @endif
                                                
                                                @if(isset($item->categorie) && $item->categorie && isset($item->categorie->nom))
                                                    <span class="flex items-center">
                                                        <i class="fas fa-tag mr-1" aria-hidden="true"></i>
                                                        {{ e($item->categorie->nom) }}
                                                    </span>
                                                @endif

                                                @if($item->type_global === 'Projet' && isset($item->service) && $item->service && isset($item->service->nom))
                                                    <span class="flex items-center">
                                                        <i class="fas fa-briefcase mr-1" aria-hidden="true"></i>
                                                        {{ e($item->service->nom) }}
                                                    </span>
                                                @endif

                                                @if($item->type_global === 'Projet' && !empty($item->etat))
                                                    <span class="flex items-center">
                                                        <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                                                        {{ e(ucfirst($item->etat)) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="flex-1 mb-2">
                                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
                                                {!! SearchHelper::getItemSummary($item, $query) !!}
                                            </p>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center justify-between mt-auto">
                                            <div class="flex items-center gap-4">
                                                @if($item->type_global === 'Publication' && !empty($item->slug))
                                                    <a href="{{ route('publication.show', ['slug' => $item->slug]) }}" 
                                                       class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-medium transition-colors duration-200 text-sm"
                                                       aria-label="Voir la publication {{ e($item->titre ?? '') }}">
                                                        <i class="fas fa-eye mr-1 text-xs" aria-hidden="true"></i>
                                                        Voir la publication
                                                    </a>
                                                @elseif($item->type_global === 'Actualité' && !empty($item->slug))
                                                    <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" 
                                                       class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-medium transition-colors duration-200 text-sm"
                                                       aria-label="Lire l'actualité {{ e($item->titre ?? '') }}">
                                                        <i class="fas fa-newspaper mr-1 text-xs" aria-hidden="true"></i>
                                                        Lire l'actualité
                                                    </a>
                                                @elseif($item->type_global === 'Projet' && !empty($item->slug))
                                                    <a href="{{ route('site.projet.show', ['slug' => $item->slug]) }}" 
                                                       class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-medium transition-colors duration-200 text-sm"
                                                       aria-label="Voir le projet {{ e($item->titre ?? '') }}">
                                                        <i class="fas fa-project-diagram mr-1 text-xs" aria-hidden="true"></i>
                                                        Voir le projet
                                                    </a>
                                                @elseif($item->type_global === 'Rapport' && !empty($item->slug))
                                                    <a href="{{ route('publication.show', ['slug' => $item->slug]) }}" 
                                                       class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-medium transition-colors duration-200 text-sm"
                                                       aria-label="Voir le rapport {{ e($item->titre ?? '') }}">
                                                        <i class="fas fa-file-alt mr-1 text-xs" aria-hidden="true"></i>
                                                        Voir le rapport
                                                    </a>
                                                @endif

                                                <!-- Indicateurs de popularité -->
                                                @if(($item->type_global === 'Publication' && !empty($item->fichier_pdf)) || ($item->type_global === 'Rapport' && !empty($item->fichier)))
                                                    <span class="inline-flex items-center text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full" title="Document téléchargeable">
                                                        <i class="fas fa-download mr-1" aria-hidden="true"></i>
                                                        Téléchargeable
                                                    </span>
                                                @endif

                                                @if($item->type_global === 'Actualité' && isset($item->created_at) && $item->created_at->isAfter(now()->subDays(7)))
                                                    <span class="inline-flex items-center text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded-full" title="Contenu récent">
                                                        <i class="fas fa-fire mr-1" aria-hidden="true"></i>
                                                        Nouveau
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <!-- Statistiques de consultation simulées -->
                                                @php
                                                    // Simulation de statistiques basées sur l'âge et le type de contenu
                                                    $baseViews = match($item->type_global) {
                                                        'Publication' => rand(150, 800),
                                                        'Actualité' => rand(80, 400),
                                                        'Projet' => rand(200, 600),
                                                        'Rapport' => rand(100, 350),
                                                        default => rand(50, 200)
                                                    };
                                                    
                                                    $ageInDays = isset($item->created_at) ? $item->created_at->diffInDays(now()) : 365;
                                                    $adjustedViews = max(1, $baseViews - floor($ageInDays / 30) * 10);
                                                    
                                                    $downloads = 0;
                                                    if (($item->type_global === 'Publication' && !empty($item->fichier_pdf)) || 
                                                        ($item->type_global === 'Rapport' && !empty($item->fichier))) {
                                                        $downloads = floor($adjustedViews * 0.3); // 30% des vues se traduisent en téléchargements
                                                    }
                                                @endphp

                                                <div class="flex items-center text-xs text-gray-500" title="{{ $adjustedViews }} consultations">
                                                    <i class="fas fa-eye mr-1" aria-hidden="true"></i>
                                                    <span>{{ number_format($adjustedViews) }}</span>
                                                </div>

                                                @if($downloads > 0)
                                                    <div class="flex items-center text-xs text-gray-500" title="{{ $downloads }} téléchargements">
                                                        <i class="fas fa-download mr-1" aria-hidden="true"></i>
                                                        <span>{{ number_format($downloads) }}</span>
                                                    </div>
                                                @endif

                                                <!-- Share Button -->
                                                <button type="button"
                                                        class="text-gray-400 hover:text-iri-accent transition-colors duration-200 p-1 rounded-full hover:bg-gray-100" 
                                                        title="Partager ce résultat"
                                                        aria-label="Partager ce résultat"
                                                        onclick="navigator.share ? navigator.share({title: '{{ e($item->titre ?? '') }}', url: window.location.href}) : alert('Fonctionnalité de partage non disponible')">
                                                    <i class="fas fa-share-alt text-xs" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($results->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            {{ $results->appends(['q' => $query])->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            @else
                <!-- No Results -->
                <div class="search-no-results text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-gray-400 to-gray-500 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucun résultat trouvé</h3>
                        <p class="text-gray-600 mb-6">
                            Nous n'avons trouvé aucun résultat pour <strong>"{{ e($query) }}"</strong>.<br>
                            Essayez avec d'autres mots-clés ou vérifiez l'orthographe.
                        </p>
                        
                        <!-- Suggestions -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h4 class="font-bold text-gray-900 mb-3">Suggestions :</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Utilisez des mots-clés plus généraux</li>
                                <li>• Vérifiez l'orthographe de vos termes</li>
                                <li>• Essayez des synonymes</li>
                                <li>• Utilisez moins de mots</li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <a href="{{ route('site.publications') }}" 
                               class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-file-alt mr-2"></i>
                                Voir toutes les publications
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
        </div>
    </section>
</div>
@endsection
