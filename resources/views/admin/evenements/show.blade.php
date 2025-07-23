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
                <a href="{{ route('admin.evenements.index') }}" class="text-white/70 hover:text-white">Événements</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ Str::limit($evenement->titre, 30) }}</span>
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
            <!-- En-tête de l'événement -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h1 class="text-2xl font-bold text-white">{{ $evenement->titre }}</h1>
                </div>
                <div class="p-6">
                    @if($evenement->image)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $evenement->image) }}" 
                             alt="{{ $evenement->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <div class="space-y-4">
                            @if($evenement->resume)
                            <div>
                                <label class="block text-sm font-medium text-iri-gray mb-2">
                                    <i class="fas fa-clipboard-list mr-2"></i>Résumé
                                </label>
                                <div class="p-3 bg-gradient-to-r from-iri-accent/10 to-iri-gold/10 rounded-lg border border-iri-accent/20">
                                    <em class="text-iri-dark">{{ $evenement->resume }}</em>
                                </div>
                            </div>
                            @endif
                            
                            @if($evenement->description)
                            <div>
                                <label class="block text-sm font-medium text-iri-gray mb-2">
                                    <i class="fas fa-file-alt mr-2"></i>Description
                                </label>
                                <div class="text-iri-dark leading-relaxed p-3 bg-gray-50 rounded-lg border">
                                    {!! nl2br(e($evenement->description)) !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations complémentaires
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($evenement->lieu)
                    <div class="flex items-start p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-red-500"></i>
                            <span class="font-medium">Lieu :</span>
                        </div>
                        <span class="ml-auto text-iri-gray">{{ $evenement->lieu }}</span>
                    </div>
                    @endif

                    @if($evenement->rapport_url)
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf mr-3 text-iri-gray"></i>
                            <span class="font-medium">Rapport</span>
                        </div>
                        <a href="{{ $evenement->rapport_url }}" target="_blank" 
                           class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                            <i class="fas fa-external-link-alt mr-1"></i>Consulter
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- État et planification -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-clock mr-3"></i>
                        État et planification
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- État actuel -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-circle mr-3 text-iri-gray"></i>
                            <span class="font-medium">État actuel</span>
                        </div>
                        @if($evenement->est_a_venir)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <i class="fas fa-clock mr-1"></i>À venir
                            </span>
                        @elseif($evenement->est_passe)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fas fa-check mr-1"></i>Passé
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-play mr-1"></i>En cours
                            </span>
                        @endif
                    </div>

                    <!-- Date de début -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus mr-3 text-iri-primary"></i>
                            <span class="font-medium">Date de début</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-iri-gray">{{ \Carbon\Carbon::parse($evenement->date_debut)->format('d/m/Y à H:i') }}</div>
                            <div class="text-xs text-iri-gray/70">{{ \Carbon\Carbon::parse($evenement->date_debut)->diffForHumans() }}</div>
                        </div>
                    </div>

                    <!-- Date de fin -->
                    @if($evenement->date_fin)
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-minus mr-3 text-iri-secondary"></i>
                            <span class="font-medium">Date de fin</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-iri-gray">{{ \Carbon\Carbon::parse($evenement->date_fin)->format('d/m/Y à H:i') }}</div>
                            <div class="text-xs text-iri-gray/70">{{ \Carbon\Carbon::parse($evenement->date_fin)->diffForHumans() }}</div>
                        </div>
                    </div>

                    <!-- Durée -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-hourglass-half mr-3 text-iri-accent"></i>
                            <span class="font-medium">Durée</span>
                        </div>
                        <div class="text-sm text-iri-gray">
                            @php
                                $duree = \Carbon\Carbon::parse($evenement->date_debut)->diff(\Carbon\Carbon::parse($evenement->date_fin));
                            @endphp
                            @if($duree->days > 0)
                                {{ $duree->days }} jour{{ $duree->days > 1 ? 's' : '' }}
                                @if($duree->h > 0 || $duree->i > 0)
                                    {{ $duree->h }}h{{ $duree->i > 0 ? sprintf('%02d', $duree->i) : '' }}
                                @endif
                            @else
                                {{ $duree->h }}h{{ sprintf('%02d', $duree->i) }}
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Métadonnées -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cog mr-3"></i>
                        Métadonnées
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus mr-3 text-iri-gray"></i>
                            <span class="font-medium">Créé le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $evenement->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-edit mr-3 text-iri-gray"></i>
                            <span class="font-medium">Modifié le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $evenement->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('site.evenement.show', $evenement->id) }}" target="_blank" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir sur le site
                    </a>
                    
                    <a href="{{ route('admin.evenements.edit', $evenement) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier cet événement
                    </a>
                    
                    <form action="{{ route('admin.evenements.destroy', $evenement) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer cet événement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
