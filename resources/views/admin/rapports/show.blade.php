@extends('layouts.admin')

@section('title', 'Détail du Rapport')
@section('subtitle', 'Visualisation du rapport : ' . $rapport->titre)

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.rapports.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-file-alt mr-2 group-hover:rotate-12 transition-transform duration-200"></i>
            Rapports
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium">{{ Str::limit($rapport->titre, 30) }}</span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-iri-dark flex items-center">
                <i class="fas fa-file-alt text-iri-primary mr-3"></i>
                {{ $rapport->titre }}
            </h1>
            <p class="text-iri-gray mt-2">
                <i class="fas fa-info-circle mr-2"></i>
                Détails complets du rapport
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-3">
            <a href="{{ route('admin.rapports.edit', $rapport) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('admin.rapports.index') }}" 
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
                                <i class="fas fa-heading mr-2"></i>Titre du rapport
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $rapport->titre }}
                            </div>
                        </div>
                        
                        @if($rapport->auteur)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-user mr-2"></i>Auteur
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $rapport->auteur }}
                            </div>
                        </div>
                        @endif
                        
                        @if($rapport->type)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-tag mr-2"></i>Type
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                    <i class="fas fa-circle mr-1 text-xs"></i>{{ ucfirst($rapport->type) }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-plus mr-2"></i>Date de création
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $rapport->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        
                        @if($rapport->date_publication)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-check mr-2"></i>Date de publication
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-edit mr-2"></i>Dernière modification
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $rapport->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé -->
            @if($rapport->resume)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Résumé
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($rapport->resume)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Contenu -->
            @if($rapport->contenu)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Contenu du Rapport
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {!! $rapport->contenu !!}
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
                        @if(isset($rapport->is_published) && $rapport->is_published)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                <i class="fas fa-check-circle mr-1"></i>Publié
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-clock mr-1"></i>Brouillon
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fichier PDF -->
            @if($rapport->fichier_pdf)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-gold to-iri-accent">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-file-pdf mr-3"></i>
                        Fichier PDF
                    </h2>
                </div>
                <div class="p-6">
                    <a href="{{ asset('storage/' . $rapport->fichier_pdf) }}" target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i>
                        Télécharger le PDF
                    </a>
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
                    <a href="{{ route('admin.rapports.edit', $rapport) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier ce rapport
                    </a>
                    
                    <form action="{{ route('admin.rapports.destroy', $rapport) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer ce rapport
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
