@extends('layouts.admin')

@section('title', 'Actualités')
@section('subtitle', 'Gestion des actualités IRI-UCBC')

@section('content')

<!-- Header avec bouton d'ajout -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Actualités</h2>
        <p class="text-gray-600">{{ $actualites->count() }} actualité(s) publiée(s)</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('admin.actualite.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-coral text-white text-sm font-medium rounded-md hover:bg-coral-dark transition-colors duration-200">
            <i class="bi bi-plus-circle mr-2"></i>
            Nouvelle Actualité
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Actualités -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-coral bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-newspaper text-coral text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $actualites->count() }}</p>
            </div>
        </div>
    </div>

    <!-- À la Une -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-star text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">À la Une</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $actualites->where('a_la_une', 1)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- En Vedette -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-olive bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-gem text-olive text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">En Vedette</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $actualites->where('en_vedette', 1)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Récentes -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Ce mois</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $actualites->where('created_at', '>=', now()->subMonth())->count() }}</p>
            </div>
        </div>
    </div>

</div>

<!-- Filtres et recherche -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="bi bi-funnel mr-2"></i>
            Filtres et recherche
        </h3>
    </div>
    <div class="p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Recherche -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ request('search') }}"
                       placeholder="Titre, contenu..."
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-coral focus:ring-coral">
            </div>

            <!-- Filtre Statut -->
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="statut" id="statut" class="w-full border-gray-300 rounded-md shadow-sm focus:border-coral focus:ring-coral">
                    <option value="">-- Tous --</option>
                    <option value="une" {{ request('statut') == 'une' ? 'selected' : '' }}>À la Une</option>
                    <option value="vedette" {{ request('statut') == 'vedette' ? 'selected' : '' }}>En Vedette</option>
                    <option value="standard" {{ request('statut') == 'standard' ? 'selected' : '' }}>Standard</option>
                </select>
            </div>

            <!-- Filtre Date -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                <select name="date" id="date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-coral focus:ring-coral">
                    <option value="">-- Toutes --</option>
                    <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Ce mois</option>
                </select>
            </div>

            <!-- Bouton de filtre -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-coral text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-coral-dark transition-colors duration-200">
                    <i class="bi bi-search mr-2"></i>
                    Rechercher
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Liste des actualités -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="bi bi-list mr-2"></i>
            Liste des Actualités
        </h3>
    </div>
    
    @if($actualites->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($actualites as $actualite)
            <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-start space-x-4">
                    <!-- Image -->
                    <div class="flex-shrink-0">
                        @if($actualite->thundernail)
                            <img src="{{ asset('storage/' . $actualite->thundernail) }}" 
                                 alt="Thumbnail" 
                                 class="w-20 h-20 object-cover rounded-lg shadow-sm">
                        @else
                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-image text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Contenu -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Titre -->
                                <h4 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ $actualite->title }}
                                </h4>
                                
                                <!-- Sous-titre -->
                                @if($actualite->sub_title)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-1">
                                        {{ $actualite->sub_title }}
                                    </p>
                                @endif

                                <!-- Extrait du contenu -->
                                @if($actualite->content)
                                    <p class="text-sm text-gray-700 mb-4 line-clamp-2">
                                        {{ Str::limit(strip_tags($actualite->content), 150) }}
                                    </p>
                                @endif

                                <!-- Métadonnées -->
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <i class="bi bi-calendar mr-1"></i>
                                        {{ $actualite->created_at->format('d/m/Y à H:i') }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="bi bi-eye mr-1"></i>
                                        {{ $actualite->views ?? 0 }} vues
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('admin.actualite.show', $actualite->slug) }}" 
                                   class="text-coral hover:text-coral-dark p-2" 
                                   title="Voir">
                                    <i class="bi bi-eye text-lg"></i>
                                </a>
                                <a href="{{ route('admin.actualite.edit', $actualite) }}" 
                                   class="text-olive hover:text-olive-dark p-2" 
                                   title="Modifier">
                                    <i class="bi bi-pencil text-lg"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.actualite.destroy', $actualite) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 p-2" 
                                            title="Supprimer">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Badges de statut -->
                        <div class="flex items-center space-x-2 mt-3">
                            @if($actualite->a_la_une)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="bi bi-star mr-1"></i>À la Une
                                </span>
                            @endif
                            @if($actualite->en_vedette)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="bi bi-gem mr-1"></i>En Vedette
                                </span>
                            @endif
                            @if(!$actualite->a_la_une && !$actualite->en_vedette)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Standard
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($actualites, 'hasPages') && $actualites->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Affichage de {{ $actualites->firstItem() }} à {{ $actualites->lastItem() }} 
                    sur {{ $actualites->total() }} résultats
                </div>
                <div>
                    {{ $actualites->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif

    @else
        <!-- État vide -->
        <div class="px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <i class="bi bi-newspaper text-gray-300 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune actualité trouvée</h3>
                <p class="text-gray-500 mb-4">
                    @if(request()->hasAny(['search', 'statut', 'date']))
                        Aucune actualité ne correspond à vos critères de recherche.
                    @else
                        Commencez par créer votre première actualité.
                    @endif
                </p>
                <div class="flex space-x-3">
                    @if(request()->hasAny(['search', 'statut', 'date']))
                        <a href="{{ route('admin.actualite.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            Réinitialiser les filtres
                        </a>
                    @endif
                    <a href="{{ route('admin.actualite.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-coral text-white text-sm font-medium rounded-md hover:bg-coral-dark transition-colors duration-200">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Nouvelle actualité
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
