@extends('layouts.admin')

@section('title', 'Détail du Projet')
@section('subtitle', 'Visualisation du projet : ' . $projet->nom)

@can('view', $projet)

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.projets.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-project-diagram mr-2 group-hover:rotate-12 transition-transform duration-200"></i>
            Projets
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium">{{ Str::limit($projet->nom, 30) }}</span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Messages d'alerte -->
    @if(session('alert'))
        <div class="mb-6 p-4 bg-white rounded-lg border-l-4 border-iri-primary shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-iri-primary"></i>
                </div>
                <div class="ml-3">
                    <div class="text-sm">
                        {!! session('alert') !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-iri-dark flex items-center">
                <i class="fas fa-project-diagram text-iri-primary mr-3"></i>
                {{ $projet->nom }}
            </h1>
            <p class="text-iri-gray mt-2">
                <i class="fas fa-info-circle mr-2"></i>
                Détails complets du projet
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-3">
            @can('update', $projet)
                <a href="{{ route('admin.projets.edit', $projet) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
            @endcan
            
            <a href="{{ route('admin.projets.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-iri-gray/10 text-iri-gray rounded-lg hover:bg-iri-gray/20 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
            <!-- Statistiques principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <!-- Total bénéficiaires -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total bénéficiaires</p>
                            <p class="text-3xl font-bold">{{ number_format($projet->beneficiaires_total ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-blue-400/30 rounded-lg">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Budget -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Budget (USD)</p>
                            <p class="text-3xl font-bold">
                                @if($projet->budget)
                                    ${{ number_format($projet->budget, 0) }}
                                @else
                                    Non défini
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-green-400/30 rounded-lg">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Durée du projet -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Durée</p>
                            <p class="text-2xl font-bold">
                                @if($projet->duree_en_mois)
                                    {{ $projet->duree_formatee }}
                                @else
                                    Non définie
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-purple-400/30 rounded-lg">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- État du projet -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">État</p>
                            <p class="text-2xl font-bold">{{ ucfirst($projet->etat ?? 'Non défini') }}</p>
                        </div>
                        <div class="p-3 bg-orange-400/30 rounded-lg">
                            <i class="fas fa-flag text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations Principales
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-tag mr-2"></i>Nom du projet
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $projet->nom }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-link mr-2"></i>Slug
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $projet->slug }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-flag mr-2"></i>État
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($projet->etat == 'actif') bg-iri-accent/20 text-iri-accent border border-iri-accent/30
                                    @elseif($projet->etat == 'termine') bg-iri-secondary/20 text-iri-secondary border border-iri-secondary/30
                                    @else bg-iri-gold/20 text-iri-gold border border-iri-gold/30 @endif">
                                    <i class="fas fa-circle mr-1 text-xs"></i>{{ ucfirst($projet->etat) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-plus mr-2"></i>Date de création
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $projet->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        
                        @if($projet->date_debut)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-play mr-2"></i>Date de début
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}
                            </div>
                        </div>
                        @endif
                        
                        @if($projet->date_fin)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-stop mr-2"></i>Date de fin
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Résumé du projet (affiché avant la description) -->
            @if($projet->resume)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Résumé du projet
                    </h2>
                </div>
                <div class="p-6">
                    <div class="p-4 bg-gradient-to-r from-iri-accent/10 to-iri-gold/10 rounded-lg border border-iri-accent/20">
                        <em class="text-iri-dark leading-relaxed text-lg">{{ $projet->resume }}</em>
                    </div>
                </div>
            </div>
            @endif

            <!-- Description Rich Text -->
            @if($projet->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-align-left mr-3"></i>
                        Description
                    </h2>
                </div>
                <div class="p-6">
                    <x-rich-text-display :content="$projet->description" />
                </div>
            </div>
            @endif

            <!-- Médias associés -->
            @if(optional($projet->medias)->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-images mr-3"></i>
                        Médias Associés ({{ $projet->medias->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($projet->medias as $media)
                            <div class="relative group rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                                @if($media->type == 'image')
                                    <img src="{{ asset('storage/' . $media->chemin) }}" 
                                         class="w-full h-24 object-cover" 
                                         alt="{{ $media->nom }}">
                                @else
                                    <div class="w-full h-24 bg-gradient-to-br from-iri-gray/10 to-iri-gray/20 flex items-center justify-center">
                                        <i class="fas fa-file text-iri-gray text-2xl"></i>
                                    </div>
                                @endif
                                <div class="p-2">
                                    <p class="text-xs text-gray-600 truncate">{{ $media->nom }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Image principale -->
            @if($projet->image)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-gold to-iri-accent">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-image mr-3"></i>
                        Image Principale
                    </h2>
                </div>
                <div class="p-6">
                    <div class="relative rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ asset('storage/' . $projet->image) }}" 
                             class="w-full h-auto object-cover" 
                             alt="Image du projet {{ $projet->nom }}">
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions de Modération -->
            @can('moderate', $projet)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-gavel mr-3"></i>
                        Actions de Modération
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Historique et statut de modération -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Statut actuel -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Statut de modération
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">État:</span>
                                    @if($projet->is_published)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Publié
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> En attente
                                        </span>
                                    @endif
                                </div>
                                
                                @if($projet->published_at)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Date de publication:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $projet->published_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                @endif
                                
                                @if($projet->publishedBy)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Modéré par:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $projet->publishedBy->name }}</span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Créé le:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $projet->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Dernière modification:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $projet->updated_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations de l'auteur -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-user mr-2 text-purple-500"></i>
                                Informations de l'auteur
                            </h4>
                            <div class="space-y-2">
                                @if($projet->service)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Service:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $projet->service->nom }}</span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">État du projet:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $projet->etat === 'en cours' ? 'bg-blue-100 text-blue-800' : 
                                           ($projet->etat === 'terminé' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        @if($projet->etat === 'en cours')
                                            <i class="fas fa-play mr-1"></i>
                                        @elseif($projet->etat === 'terminé')
                                            <i class="fas fa-check mr-1"></i>
                                        @else
                                            <i class="fas fa-pause mr-1"></i>
                                        @endif
                                        {{ ucfirst($projet->etat) }}
                                    </span>
                                </div>
                                
                                @if($projet->budget)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Budget:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($projet->budget, 2, ',', ' ') }} €</span>
                                </div>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Visibilité:</span>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $projet->is_published ? 'Public' : 'Privé' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commentaire de modération actuel -->
                    @if($projet->moderation_comment)
                    <div class="mb-4 p-4 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-comment-dots text-yellow-600 mt-1"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h4 class="text-yellow-800 font-medium mb-2">Commentaire de modération</h4>
                                <div class="bg-white/60 rounded-lg p-3 border border-yellow-200">
                                    <p class="text-yellow-700 text-sm whitespace-pre-wrap">{{ $projet->moderation_comment }}</p>
                                </div>
                                @if($projet->publishedBy || $projet->published_at)
                                <div class="mt-3 flex items-center justify-between text-xs text-yellow-600">
                                    @if($projet->publishedBy)
                                    <span class="flex items-center">
                                        <i class="fas fa-user-shield mr-1"></i>
                                        Par {{ $projet->publishedBy->name }}
                                    </span>
                                    @endif
                                    @if($projet->published_at)
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $projet->published_at->format('d/m/Y à H:i') }}
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-gray-400 mr-3"></i>
                            <span class="text-gray-600 text-sm">Aucun commentaire de modération</span>
                        </div>
                    </div>
                    @endif

                    <!-- Boutons d'action -->
                    @if(auth()->user()->canModerate())
                        @if(!$projet->is_published)
                            <button onclick="moderateProjet('publish')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-2"></i>
                                Publier
                            </button>
                        @endif
                        
                        @if($projet->is_published)
                            <button onclick="moderateProjet('unpublish')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye-slash mr-2"></i>
                                Dépublier
                            </button>
                        @endif
                        
                        @can('delete', $projet)
                            <button onclick="moderateProjet('delete')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-trash mr-2"></i>
                                Supprimer
                            </button>
                        @endcan
                    @else
                        <div class="p-4 bg-gray-50 rounded-lg border">
                            <p class="text-gray-600 text-sm text-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Vous n'avez pas les permissions nécessaires pour modérer ce contenu.
                            </p>
                        </div>
                    @endif
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
                    @can('update', $projet)
                        <a href="{{ route('admin.projets.edit', $projet) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i>
                            Modifier ce projet
                        </a>
                    @endcan
                    
                    <!-- Lien vers la liste des projets -->
                    <a href="{{ route('admin.projets.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-50 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Liste des projets
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modération unifié -->
@can('moderate', $projet)
<div id="moderationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-gavel mr-3"></i>
                    <span id="modalTitle">Action de modération</span>
                </h3>
                <button onclick="closeModerationModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="moderationForm" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4" id="commentSection">
                <label for="moderation_comment" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment mr-2"></i>Commentaire
                </label>
                <textarea name="moderation_comment" 
                          id="moderation_comment" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                          placeholder="Ajoutez un commentaire de modération...">{{ $projet->moderation_comment ?? '' }}</textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        id="confirmButton"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-check mr-2"></i>
                    Confirmer
                </button>
                <button type="button" 
                        onclick="closeModerationModal()" 
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
@endcan

<script>
let currentAction = null;
let currentProjetId = {{ $projet->id }};

function moderateProjet(action) {
    currentAction = action;
    const modal = document.getElementById('moderationModal');
    const form = document.getElementById('moderationForm');
    const title = document.getElementById('modalTitle');
    const button = document.getElementById('confirmButton');
    const commentSection = document.getElementById('commentSection');
    
    // Configuration selon l'action
    switch(action) {
        case 'publish':
            form.action = '{{ route("admin.projets.publish", $projet) }}';
            title.textContent = 'Publier le projet';
            button.innerHTML = '<i class="fas fa-eye mr-2"></i>Publier';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'block';
            break;
            
        case 'unpublish':
            form.action = '{{ route("admin.projets.unpublish", $projet) }}';
            title.textContent = 'Dépublier le projet';
            button.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Dépublier';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'block';
            break;
            
        case 'delete':
            form.action = '{{ route("admin.projets.destroy", $projet) }}';
            form.querySelector('input[name="_method"]').value = 'DELETE';
            title.textContent = 'Supprimer le projet';
            button.innerHTML = '<i class="fas fa-trash mr-2"></i>Supprimer';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'none';
            break;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function confirmModerationAction() {
    const form = document.getElementById('moderationForm');
    
    // Envoi AJAX pour une UX fluide
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Notification de succès
            showNotification(data.message || 'Action effectuée avec succès', 'success');
            
            // Fermer le modal
            closeModerationModal();
            
            // Recharger la page pour refléter les changements
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Une erreur est survenue', 'error');
    });
}

function closeModerationModal() {
    document.getElementById('moderationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentAction = null;
}

function showNotification(message, type = 'info') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-circle' : 
                'fa-info-circle'
            } mr-3"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animer l'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Gestion des événements
document.addEventListener('DOMContentLoaded', function() {
    // Soumettre le formulaire avec AJAX
    document.getElementById('moderationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        confirmModerationAction();
    });
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModerationModal();
        }
    });
    
    // Fermer en cliquant à l'extérieur
    document.getElementById('moderationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModerationModal();
        }
    });
});
</script>
@endsection

@else
<div class="container-fluid px-4 py-6">
    <div class="max-w-lg mx-auto text-center">
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-8">
            <div class="mb-4">
                <i class="fas fa-lock text-red-400 text-4xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Accès refusé</h2>
            <p class="text-gray-600 mb-6">
                Vous n'avez pas les permissions nécessaires pour consulter ce projet.
            </p>
            <a href="{{ route('admin.projets.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>
</div>
@endcan
