@extends('layouts.admin')

@section('title', 'Projets de Recherche')
@section('subtitle', 'Gestion complète des projets de recherche et d\'innovation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- En-tête avec statistiques avancées -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Titre et actions principales -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="h-8 w-8 text-iri-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Projets de Recherche
                    </h1>
                    <p class="text-gray-600 mt-2">Gestion complète des projets de recherche et d'innovation</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button id="toggle-view" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg id="grid-icon" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <svg id="list-icon" class="h-4 w-4 mr-2 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span id="view-text">Vue grille</span>
                    </button>
                    @can('create projets')
                        <a href="{{ route('admin.projets.create') }}" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nouveau Projet
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Statistiques en cartes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 mb-8">
                <!-- Total Projets -->
                <div class="bg-gradient-to-br from-iri-primary to-iri-secondary rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Total Projets</p>
                            <p class="text-2xl font-bold">{{ $projets->total() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Budget Total -->
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Budget Total</p>
                            <p class="text-2xl font-bold">${{ number_format($budgetTotal, 0) }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Publiés -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Publiés</p>
                            <p class="text-2xl font-bold">{{ $projets->where('is_published', true)->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- En Attente -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">En Attente</p>
                            <p class="text-2xl font-bold">{{ $projets->where('is_published', false)->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- En Cours -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">En Cours</p>
                            <p class="text-2xl font-bold">{{ $projets->where('etat', 'en cours')->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v10a2 2 0 002 2h2a2 2 0 002-2V10M9 10H7a2 2 0 00-2 2v8a2 2 0 002 2h2M9 10h6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Terminés -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Terminés</p>
                            <p class="text-2xl font-bold">{{ $projets->where('etat', 'terminé')->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Suspendus -->
                <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Suspendus</p>
                            <p class="text-2xl font-bold">{{ $projets->where('etat', 'suspendu')->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zone de contenu principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Barre de recherche et filtres avancés -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('admin.projets.index') }}" id="filter-form">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                    <!-- Recherche textuelle -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary"
                                   placeholder="Rechercher par nom, description...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- État -->
                    <div>
                        <label for="etat" class="block text-sm font-medium text-gray-700 mb-2">État</label>
                        <select name="etat" id="etat" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Tous les états</option>
                            <option value="en cours" {{ request('etat') === 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminé" {{ request('etat') === 'terminé' ? 'selected' : '' }}>Terminé</option>
                            <option value="suspendu" {{ request('etat') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        </select>
                    </div>

                    <!-- Publication -->
                    <div>
                        <label for="is_published" class="block text-sm font-medium text-gray-700 mb-2">Publication</label>
                        <select name="is_published" id="is_published" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Tous</option>
                            <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Publiés</option>
                            <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Non publiés</option>
                        </select>
                    </div>

                    <!-- Service -->
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                        <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Tous les services</option>
                            @foreach($services ?? [] as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrer
                        </button>
                        <a href="{{ route('admin.projets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </a>
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        {{ $projets->total() }} projet(s) trouvé(s)
                    </div>
                </div>
            </form>
        </div>

        <!-- Vue en grille (par défaut) -->
        <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projets as $projet)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 group">
                    <!-- En-tête de la carte -->
                    <div class="relative p-6 pb-4">
                        <!-- Badge d'état -->
                        <div class="absolute top-4 right-4 z-10">
                            @php
                                $etatConfig = [
                                    'en cours' => ['class' => 'bg-blue-100 text-blue-700 border-blue-200', 'icon' => '🔄'],
                                    'terminé' => ['class' => 'bg-green-100 text-green-700 border-green-200', 'icon' => '✅'],
                                    'suspendu' => ['class' => 'bg-red-100 text-red-700 border-red-200', 'icon' => '⏸️'],
                                ];
                                $config = $etatConfig[$projet->etat] ?? ['class' => 'bg-gray-100 text-gray-700 border-gray-200', 'icon' => '❓'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $config['class'] }}">
                                <span class="mr-1">{{ $config['icon'] }}</span>
                                {{ ucfirst($projet->etat) }}
                            </span>
                        </div>

                        <!-- Icône et titre -->
                        <div class="flex items-start space-x-3 mb-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-iri-primary transition-colors duration-200">
                                    {{ Str::limit($projet->nom, 45) }}
                                </h3>
                                @if($projet->service)
                                    <p class="text-sm text-gray-500 mt-1">{{ $projet->service->nom }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Résumé ou description -->
                        <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                            {{ Str::limit($projet->resume ?: $projet->description, 120) }}
                        </p>

                        <!-- Statistiques du projet -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            @if($projet->budget)
                                <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-green-700">Budget</span>
                                    </div>
                                    <p class="text-sm font-semibold text-green-900 mt-1">${{ number_format($projet->budget, 0) }}</p>
                                </div>
                            @endif

                            @if($projet->beneficiaires_total > 0)
                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-blue-700">Bénéficiaires</span>
                                    </div>
                                    <p class="text-sm font-semibold text-blue-900 mt-1">{{ number_format($projet->beneficiaires_total) }}</p>
                                </div>
                            @endif

                            @if($projet->duree_en_mois)
                                <div class="bg-purple-50 rounded-lg p-3 border border-purple-100 {{ $projet->budget && $projet->beneficiaires_total > 0 ? 'col-span-2' : '' }}">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-purple-700">Durée</span>
                                    </div>
                                    <p class="text-sm font-semibold text-purple-900 mt-1">{{ $projet->duree_formatee }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Status de publication -->
                        <div class="flex items-center justify-between mb-4">
                            @if($projet->is_published)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Publié
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    En attente
                                </span>
                            @endif

                            <div class="text-xs text-gray-500">
                                {{ $projet->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions et modération -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
                        <div class="flex items-center justify-end">
                            <!-- Actions principales -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.projets.show', $projet) }}" 
                                   class="inline-flex items-center p-2 text-gray-600 hover:text-iri-primary hover:bg-iri-primary/5 rounded-md transition-colors duration-200"
                                   title="Voir les détails">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @can('update projets')
                                    <a href="{{ route('admin.projets.edit', $projet) }}" 
                                       class="inline-flex items-center p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors duration-200"
                                       title="Modifier">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                @endcan
                                @can('delete projets')
                                    <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200"
                                                title="Supprimer">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun projet trouvé</h3>
                        <p class="text-gray-500 mb-6">Aucun projet ne correspond à vos critères de recherche.</p>
                        @can('create projets')
                            <a href="{{ route('admin.projets.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Créer votre premier projet
                            </a>
                        @endcan
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Vue en liste (masquée par défaut) -->
        <div id="list-view" class="hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($projets as $projet)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($projet->nom, 50) }}</div>
                                                <div class="text-sm text-gray-500">
                                                    @if($projet->service)
                                                        {{ $projet->service->nom }}
                                                    @else
                                                        Service non défini
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @if($projet->budget)
                                                <div class="font-medium text-green-600 mb-1">
                                                    💰 ${{ number_format($projet->budget, 0) }}
                                                </div>
                                            @endif
                                            @if($projet->beneficiaires_total > 0)
                                                <div class="text-blue-600 mb-1">
                                                    👥 {{ number_format($projet->beneficiaires_total) }} bénéficiaires
                                                </div>
                                            @endif
                                            @if($projet->duree_en_mois)
                                                <div class="text-purple-600">
                                                    ⏱️ {{ $projet->duree_formatee }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $etatConfig = [
                                                'en cours' => ['class' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => '🔄'],
                                                'terminé' => ['class' => 'bg-green-100 text-green-800 border-green-200', 'icon' => '✅'],
                                                'suspendu' => ['class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => '⏸️'],
                                            ];
                                            $config = $etatConfig[$projet->etat] ?? ['class' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => '❓'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $config['class'] }}">
                                            {{ $config['icon'] }} {{ ucfirst($projet->etat) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($projet->is_published)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                👁️ Publié
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                ⏳ En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.projets.show', $projet) }}" 
                                               class="inline-flex items-center p-2 text-gray-600 hover:text-iri-primary hover:bg-iri-primary/5 rounded-md transition-colors duration-200"
                                               title="Voir">
                                                👁️
                                            </a>
                                            @can('update projets')
                                                <a href="{{ route('admin.projets.edit', $projet) }}" 
                                                   class="inline-flex items-center p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors duration-200"
                                                   title="Modifier">
                                                    ✏️
                                                </a>
                                            @endcan
                                            @can('delete projets')
                                                <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200"
                                                            title="Supprimer">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun projet trouvé</h3>
                                            <p class="text-gray-500 mb-6">Aucun projet ne correspond à vos critères de recherche.</p>
                                            @can('create projets')
                                                <a href="{{ route('admin.projets.create') }}" 
                                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Créer votre premier projet
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($projets->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                    {{ $projets->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Scripts pour le basculement de vue -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-view');
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const gridIcon = document.getElementById('grid-icon');
    const listIcon = document.getElementById('list-icon');
    const viewText = document.getElementById('view-text');
    
    let isGridView = true;
    
    // Récupérer la préférence depuis localStorage
    const savedView = localStorage.getItem('projets-view');
    if (savedView === 'list') {
        isGridView = false;
        switchToListView();
    }
    
    toggleBtn.addEventListener('click', function() {
        if (isGridView) {
            switchToListView();
            localStorage.setItem('projets-view', 'list');
        } else {
            switchToGridView();
            localStorage.setItem('projets-view', 'grid');
        }
        isGridView = !isGridView;
    });
    
    function switchToGridView() {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridIcon.classList.remove('hidden');
        listIcon.classList.add('hidden');
        viewText.textContent = 'Vue grille';
    }
    
    function switchToListView() {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        gridIcon.classList.add('hidden');
        listIcon.classList.remove('hidden');
        viewText.textContent = 'Vue liste';
    }
    
    // Recherche en temps réel
    const searchInput = document.getElementById('search');
    const filterForm = document.getElementById('filter-form');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            filterForm.submit();
        }, 500);
    });
    
    // Auto-submit des filtres
    const filterSelects = document.querySelectorAll('#etat, #is_published, #service_id');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            filterForm.submit();
        });
    });
});
</script>

@endsection

