@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.actualite.index') }}" class="text-white/70 hover:text-white">Actualités</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ Str::limit($actualite->titre, 30) }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- En-tête de l'actualité -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h1 class="text-2xl font-bold text-white">{{ $actualite->titre }}</h1>
                </div>
                <div class="p-6">
                    @if($actualite->image)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $actualite->image) }}" 
                             alt="{{ $actualite->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <div class="space-y-4">
                            <div class="text-sm text-iri-gray flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Publié le {{ $actualite->created_at->format('d/m/Y à H:i') }}
                            </div>
                            
                            @if($actualite->texte)
                            <div class="text-iri-dark leading-relaxed">
                                {!! nl2br(e($actualite->texte)) !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails de l'actualité -->
            @if($actualite->resume || $actualite->auteur)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Détails de l'actualité
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($actualite->auteur)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-user-edit mr-2"></i>Auteur
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $actualite->auteur }}
                            </div>
                        </div>
                        @endif

                        @if($actualite->resume)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-clipboard-list mr-2"></i>Résumé
                            </label>
                            <div class="p-3 bg-gradient-to-r from-iri-accent/10 to-iri-gold/10 rounded-lg border border-iri-accent/20">
                                <em class="text-iri-dark">{{ $actualite->resume }}</em>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Statut et métadonnées -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        Statut & Métadonnées
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- À la une -->
                    @if(isset($actualite->a_la_une))
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-star mr-3 text-iri-gray"></i>
                            <span class="font-medium">À la une</span>
                        </div>
                        @if($actualite->a_la_une)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-check mr-1"></i>Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fas fa-times mr-1"></i>Non
                            </span>
                        @endif
                    </div>
                    @endif

                    <!-- Date de création -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus mr-3 text-iri-gray"></i>
                            <span class="font-medium">Créé le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $actualite->created_at->format('d/m/Y à H:i') }}</span>
                    </div>

                    <!-- Dernière modification -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-edit mr-3 text-iri-gray"></i>
                            <span class="font-medium">Modifié le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $actualite->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions de modération -->
            @can('moderate actualites')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-3"></i>
                        Actions de Modération
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($actualite->statut !== 'publie')
                        @can('publish actualites')
                        <form action="{{ route('admin.actualite.publish', $actualite) }}" method="POST" class="w-full">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-check-circle mr-2"></i>
                                Publier l'actualité
                            </button>
                        </form>
                        @endcan
                    @else
                        @can('unpublish actualites')
                        <form action="{{ route('admin.actualite.unpublish', $actualite) }}" method="POST" class="w-full">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye-slash mr-2"></i>
                                Dépublier l'actualité
                            </button>
                        </form>
                        @endcan
                    @endif

                    @can('moderate actualites')
                    @if(isset($actualite->a_la_une) && !$actualite->a_la_une)
                        <form action="{{ route('admin.actualite.toggle-une', $actualite) }}" method="POST" class="w-full">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-star mr-2"></i>
                                Mettre à la une
                            </button>
                        </form>
                    @elseif(isset($actualite->a_la_une) && $actualite->a_la_une)
                        <form action="{{ route('admin.actualite.toggle-une', $actualite) }}" method="POST" class="w-full">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-star-half-alt mr-2"></i>
                                Retirer de la une
                            </button>
                        </form>
                    @endif
                    @endcan
                </div>
            </div>
            @endcan

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @can('view actualites')
                    <a href="{{ route('actualite.show', $actualite) }}" target="_blank" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir sur le site
                    </a>
                    @endcan
                    
                    @can('update actualites')
                    <a href="{{ route('admin.actualite.edit', $actualite) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier cette actualité
                    </a>
                    @endcan
                    
                    @can('delete actualites')
                    <form action="{{ route('admin.actualite.destroy', $actualite) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer cette actualité
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
