@extends('layouts.admin')

@section('title', 'Détail du Service')
@section('subtitle', 'Visualisation du service : ' . $service->nom)

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.service.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-cogs mr-2 group-hover:rotate-45 transition-transform duration-200"></i>
            Services
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium">{{ $service->nom }}</span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-iri-dark flex items-center">
                <i class="fas fa-cog text-iri-primary mr-3"></i>
                {{ $service->nom }}
            </h1>
            <p class="text-iri-gray mt-2">
                <i class="fas fa-info-circle mr-2"></i>
                Détails complets du service
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-3">
            <a href="{{ route('admin.service.edit', $service) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('admin.service.index') }}" 
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
                                <i class="fas fa-tag mr-2"></i>Nom du service
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $service->nom }}
                            </div>
                        </div>
                        
                        @if($service->nom_menu)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-bars mr-2"></i>Nom dans le menu
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $service->nom_menu }}
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-plus mr-2"></i>Date de création
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $service->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-edit mr-2"></i>Dernière modification
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $service->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($service->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-align-left mr-3"></i>
                        Description
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Statut et visibilité -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        Statut & Visibilité
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Statut de publication -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-globe mr-3 text-iri-gray"></i>
                            <span class="font-medium">Publication</span>
                        </div>
                        @if($service->is_published)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                <i class="fas fa-check-circle mr-1"></i>Publié
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-clock mr-1"></i>Brouillon
                            </span>
                        @endif
                    </div>

                    <!-- Présence dans le menu -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-bars mr-3 text-iri-gray"></i>
                            <span class="font-medium">Menu</span>
                        </div>
                        @if($service->nom_menu)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-secondary/20 text-iri-secondary border border-iri-secondary/30">
                                <i class="fas fa-check mr-1"></i>Activé
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fas fa-times mr-1"></i>Désactivé
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Image du service -->
            @if($service->image)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-gold to-iri-accent">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-image mr-3"></i>
                        Image
                    </h2>
                </div>
                <div class="p-6">
                    <div class="relative rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             class="w-full h-auto object-cover" 
                             alt="Image du service {{ $service->nom }}">
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.service.edit', $service) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier ce service
                    </a>
                    
                    <form action="{{ route('admin.service.destroy', $service) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer ce service
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
