@extends('layouts.admin')

@section('title', 'Détail du Projet')
@section('subtitle', 'Visualisation du projet : ' . $projet->nom)

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
            <a href="{{ route('admin.projets.edit', $projet) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
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

            <!-- Description -->
            @if($projet->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-align-left mr-3"></i>
                        Description
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($projet->description)) !!}
                    </div>
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

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.projets.edit', $projet) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier ce projet
                    </a>
                    
                    <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer ce projet
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
