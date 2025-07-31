@extends('layouts.admin')

@section('title', 'Gestion des Actualités')

@section('breadcrumbs')
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">Actualités</span>
        </div>
    </li>
@endsection
</nav>


@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Messages Flash -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.style.display='none'" 
                                class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100">
                            <i class="fas fa-times h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.style.display='none'" 
                                class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100">
                            <i class="fas fa-times h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- En-tête avec actions -->
    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">
                    <i class="fas fa-bullhorn mr-3"></i>Gestion des Actualités
                </h1>
                <p class="text-green-100">Gérez les actualités et informations importantes</p>
            </div>
            <div class="mt-4 sm:mt-0">
                @can('create actualites')
                <a href="{{ route('admin.actualite.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-iri-primary font-semibold rounded-lg shadow-md hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle Actualité
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-bullhorn text-blue-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $actualites->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Publiées</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $actualites->where('is_published', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-clock text-orange-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">En attente</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $actualites->where('is_published', false)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-star text-yellow-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">En vedette</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $actualites->where('en_vedette', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Urgentes</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $actualites->where('urgent', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-calendar-week text-purple-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Cette semaine</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $actualites->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et vues -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" 
                       id="search-actualites" 
                       placeholder="Rechercher une actualité..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
            </div>
            <div class="flex gap-2">
                <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
                    <option value="">Tous les statuts</option>
                    <option value="published">Publiées</option>
                    <option value="pending">En attente</option>
                    <option value="urgent">Urgentes</option>
                </select>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button id="timeline-view" class="px-3 py-1 rounded-md bg-white text-iri-primary font-medium shadow-sm">
                        <i class="fas fa-stream"></i>
                    </button>
                    <button id="grid-view" class="px-3 py-1 rounded-md text-gray-600 hover:text-iri-primary">
                        <i class="fas fa-th"></i>
                    </button>
                    <button id="list-view" class="px-3 py-1 rounded-md text-gray-600 hover:text-iri-primary">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vue Timeline (par défaut) -->
    <div id="timeline-container" class="relative">
        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-iri-primary to-iri-secondary"></div>
        
        <div class="space-y-6">
            @foreach($actualites as $index => $actualite)
            <div class="actualite-card relative pl-12">
                <!-- Point sur la timeline -->
                <div class="absolute left-2 top-6 w-4 h-4 rounded-full border-2 border-white shadow-md
                    {{ $actualite->urgent ? 'bg-red-500' : ($actualite->is_published ? 'bg-green-500' : 'bg-orange-500') }}">
                </div>
                
                <!-- Card de l'actualité -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <!-- En-tête avec badges -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if($actualite->urgent)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>URGENT
                                        </span>
                                    @endif
                                    
                                    @if($actualite->is_published)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Publiée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-clock mr-1"></i>En attente
                                        </span>
                                    @endif
                                    
                                    @if($actualite->en_vedette)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1"></i>En vedette
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $actualite->titre }}</h3>
                                
                                @if($actualite->resume)
                                    <p class="text-gray-600 mb-4">{{ $actualite->resume }}</p>
                                @endif
                            </div>
                            
                            <!-- Menu actions -->
                            <div class="relative ml-4" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                
                                <div x-show="open" @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    @can('view actualites')
                                    <a href="{{ route('admin.actualite.show', $actualite) }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-eye mr-2"></i>Voir
                                    </a>
                                    @endcan
                                    @can('update actualites')
                                    <a href="{{ route('admin.actualite.edit', $actualite) }}" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-edit mr-2"></i>Modifier
                                    </a>
                                    @endcan
                                    @can('delete actualites')
                                    <hr class="my-1">
                                    <button onclick="deleteActualite('{{ $actualite->slug }}')"
                                            class="w-full flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                        <i class="fas fa-trash mr-2"></i>Supprimer
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        
                        <!-- Métadonnées -->
                        <div class="flex items-center justify-between text-sm text-gray-500 pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-4">
                                <span class="flex items-center">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $actualite->auteur->nom ?? 'Anonyme' }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $actualite->created_at ? $actualite->created_at->format('d/m/Y H:i') : 'Date inconnue' }}
                                </span>
                                @if($actualite->categorie)
                                    <span class="flex items-center">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $actualite->categorie->nom }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Vue en grille (masquée par défaut) -->
    <div id="grid-container" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($actualites as $actualite)
        <div class="actualite-card bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <!-- En-tête avec badges -->
            <div class="p-4 border-b border-gray-100">
                <div class="flex flex-wrap gap-2 mb-3">
                    @if($actualite->urgent)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                            <i class="fas fa-exclamation-triangle mr-1"></i>URGENT
                        </span>
                    @endif
                    
                    @if($actualite->is_published)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Publiée
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <i class="fas fa-clock mr-1"></i>En attente
                        </span>
                    @endif
                    
                    @if($actualite->en_vedette)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i>Vedette
                        </span>
                    @endif
                </div>
                
                <h3 class="font-semibold text-gray-900 mb-2">{{ $actualite->titre }}</h3>
            </div>
            
            <!-- Contenu -->
            <div class="p-4">
                @if($actualite->resume)
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($actualite->resume, 120) }}</p>
                @endif
                
                <!-- Métadonnées -->
                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-user mr-1"></i>
                        {{ $actualite->auteur->nom ?? 'Anonyme' }}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $actualite->created_at ? $actualite->created_at->format('d/m/Y') : 'Date inconnue' }}
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        @can('view actualites')
                        <a href="{{ route('admin.actualite.show', $actualite) }}" 
                           class="text-iri-primary hover:text-iri-secondary p-1 rounded-md hover:bg-iri-primary/10 transition-colors duration-200"
                           title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endcan
                        @can('update actualites')
                        <a href="{{ route('admin.actualite.edit', $actualite) }}" 
                           class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                           title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('delete actualites')
                        <button onclick="deleteActualite('{{ $actualite->slug }}')" 
                                class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Vue en liste (masquée par défaut) -->
    <div id="list-container" class="hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-bullhorn mr-2"></i>Actualité
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Auteur
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Statut
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-fire mr-2"></i>Priorité
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($actualites as $actualite)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-16 w-16">
                                    @if($actualite->image)
                                        <img class="h-16 w-16 rounded-xl object-cover shadow-sm border border-gray-200" 
                                             src="{{ Storage::url($actualite->image) }}" 
                                             alt="{{ $actualite->titre }}">
                                    @else
                                        <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center border border-blue-200">
                                            <i class="fas fa-bullhorn text-blue-500 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="text-sm font-semibold text-gray-900 mb-1 line-clamp-2">
                                        {{ $actualite->titre }}
                                    </div>
                                    @if($actualite->resume)
                                        <div class="text-sm text-gray-600 line-clamp-2 mb-2">
                                            {{ Str::limit($actualite->resume, 80) }}
                                        </div>
                                    @endif
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span class="mr-3">Publié {{ $actualite->created_at->diffForHumans() }}</span>
                                        @if($actualite->date_expiration)
                                            <i class="fas fa-hourglass-end mr-1"></i>
                                            <span>Expire le {{ $actualite->date_expiration->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($actualite->auteur && $actualite->auteur->avatar)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ Storage::url($actualite->auteur->avatar) }}" 
                                             alt="{{ $actualite->auteur->nom }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-iri-primary flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ substr($actualite->auteur->nom ?? 'A', 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $actualite->auteur->nom ?? 'Anonyme' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $actualite->auteur->role ?? 'Contributeur' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-2">
                                @if($actualite->urgent)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200 animate-pulse">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                    </span>
                                @endif
                                
                                @if($actualite->is_published)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>Publiée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @endif
                                
                                @if($actualite->en_vedette)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <i class="fas fa-star mr-1"></i>Vedette
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ $actualite->created_at ? $actualite->created_at->format('d/m/Y') : 'Date inconnue' }}</div>
                                <div class="text-xs text-gray-500">{{ $actualite->created_at ? $actualite->created_at->format('H:i') : '--:--' }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $actualite->created_at ? $actualite->created_at->diffForHumans() : 'Date inconnue' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center">
                                @if($actualite->urgent)
                                    <div class="flex items-center text-red-600 mb-1">
                                        <i class="fas fa-fire mr-1"></i>
                                        <span class="text-xs font-bold">URGENT</span>
                                    </div>
                                @endif
                                <div class="w-3 h-3 rounded-full {{ $actualite->urgent ? 'bg-red-500 animate-pulse' : ($actualite->is_published ? 'bg-green-500' : 'bg-orange-500') }}"></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $actualite->urgent ? 'Haute' : ($actualite->en_vedette ? 'Élevée' : 'Normale') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                @can('view actualites')
                                <a href="{{ route('admin.actualite.show', $actualite) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-iri-primary text-white text-xs font-medium rounded-lg hover:bg-iri-secondary transition-colors duration-200"
                                   title="Voir les détails">
                                    <i class="fas fa-eye mr-1"></i>Voir
                                </a>
                                @endcan
                                @can('update actualites')
                                <a href="{{ route('admin.actualite.edit', $actualite) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200"
                                   title="Modifier">
                                    <i class="fas fa-edit mr-1"></i>Modifier
                                </a>
                                @endcan
                                
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="inline-flex items-center px-2 py-1.5 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200"
                                            title="Plus d'actions">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95">
                                        @can('update actualites')
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-copy mr-2"></i>Dupliquer
                                        </a>
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-share mr-2"></i>Partager
                                        </a>
                                        @endcan
                                        @can('delete actualites')
                                        <hr class="my-1">
                                        <button onclick="deleteActualite('{{ $actualite->slug }}')"
                                                class="w-full flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                            <i class="fas fa-trash mr-2"></i>Supprimer
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $actualites->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle entre les vues
    const timelineView = document.getElementById('timeline-view');
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const timelineContainer = document.getElementById('timeline-container');
    const gridContainer = document.getElementById('grid-container');
    const listContainer = document.getElementById('list-container');
    
    function resetViewButtons() {
        [timelineView, gridView, listView].forEach(btn => {
            btn.classList.remove('bg-white', 'text-iri-primary', 'shadow-sm');
            btn.classList.add('text-gray-600');
        });
    }
    
    timelineView.addEventListener('click', function() {
        resetViewButtons();
        timelineContainer.classList.remove('hidden');
        gridContainer.classList.add('hidden');
        listContainer.classList.add('hidden');
        timelineView.classList.add('bg-white', 'text-iri-primary', 'shadow-sm');
        timelineView.classList.remove('text-gray-600');
    });
    
    gridView.addEventListener('click', function() {
        resetViewButtons();
        gridContainer.classList.remove('hidden');
        timelineContainer.classList.add('hidden');
        listContainer.classList.add('hidden');
        gridView.classList.add('bg-white', 'text-iri-primary', 'shadow-sm');
        gridView.classList.remove('text-gray-600');
    });
    
    listView.addEventListener('click', function() {
        resetViewButtons();
        listContainer.classList.remove('hidden');
        timelineContainer.classList.add('hidden');
        gridContainer.classList.add('hidden');
        listView.classList.add('bg-white', 'text-iri-primary', 'shadow-sm');
        listView.classList.remove('text-gray-600');
    });
    
    // Recherche en temps réel
    const searchInput = document.getElementById('search-actualites');
    const statusFilter = document.getElementById('status-filter');
    
    function filterActualites() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const cards = document.querySelectorAll('.actualite-card');
        const rows = document.querySelectorAll('#list-container tbody tr');
        
        // Filtrer les cards (vue timeline et grille)
        cards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const content = card.querySelector('p') ? card.querySelector('p').textContent.toLowerCase() : '';
            const badges = card.querySelectorAll('span');
            let status = '';
            
            badges.forEach(badge => {
                if (badge.textContent.includes('Publiée')) status = 'published';
                if (badge.textContent.includes('En attente')) status = 'pending';
                if (badge.textContent.includes('Brouillon')) status = 'draft';
                if (badge.textContent.includes('Urgent')) status = 'urgent';
            });
            
            const matchesSearch = title.includes(searchTerm) || content.includes(searchTerm);
            const matchesStatus = statusValue === '' || status === statusValue;
            
            if (matchesSearch && matchesStatus) {
                card.style.display = 'block';
                if (card.parentElement) card.parentElement.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Filtrer les lignes (vue liste)
        rows.forEach(row => {
            const title = row.querySelector('.text-sm.font-medium') ? row.querySelector('.text-sm.font-medium').textContent.toLowerCase() : '';
            const content = row.querySelector('.text-sm.text-gray-500') ? row.querySelector('.text-sm.text-gray-500').textContent.toLowerCase() : '';
            const badges = row.querySelectorAll('span');
            let status = '';
            
            badges.forEach(badge => {
                if (badge.textContent.includes('Publiée')) status = 'published';
                if (badge.textContent.includes('En attente')) status = 'pending';
                if (badge.textContent.includes('Brouillon')) status = 'draft';
                if (badge.textContent.includes('Urgent')) status = 'urgent';
            });
            
            const matchesSearch = title.includes(searchTerm) || content.includes(searchTerm);
            const matchesStatus = statusValue === '' || status === statusValue;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterActualites);
    statusFilter.addEventListener('change', filterActualites);
});

// Fonctions pour les actions
function deleteActualite(slug) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette actualité ? Cette action est irréversible.')) {
        fetch('/admin/actualite/' + slug, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erreur lors de la suppression');
            console.error('Error:', error);
        });
    }
}
</script>
@endsection