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
                <a href="{{ route('admin.auteur.index') }}" class="text-white/70 hover:text-white">Auteurs</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ Str::limit($auteur->nom, 30) }}</span>
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
            <!-- Profil de l'auteur -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-user-edit mr-3"></i>
                        Profil de l'auteur
                    </h1>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-6 mb-6">
                        @if ($auteur->photo)
                            <img src="{{ asset('storage/' . $auteur->photo) }}" 
                                 alt="Photo de {{ $auteur->nom }}" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-iri-primary/20">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-iri-primary to-iri-secondary flex items-center justify-center text-white text-2xl font-semibold">
                                {{ strtoupper(substr($auteur->nom, 0, 1)) }}
                            </div>
                        @endif

                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-iri-dark">{{ $auteur->nom }}</h2>
                            <p class="text-iri-gray flex items-center mt-1">
                                <i class="fas fa-envelope mr-2"></i>{{ $auteur->email }}
                            </p>
                        </div>
                    </div>

                    <!-- Biographie -->
                    @if($auteur->biographie)
                    <div>
                        <label class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-user-circle mr-2"></i>Biographie
                        </label>
                        <div class="p-4 bg-gradient-to-r from-iri-accent/10 to-iri-gold/10 rounded-lg border border-iri-accent/20">
                            <p class="text-iri-dark leading-relaxed">{{ $auteur->biographie }}</p>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto bg-gradient-to-br from-iri-primary/20 to-iri-secondary/20 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-user-circle text-iri-gray text-xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-iri-dark mb-2">Aucune biographie</h4>
                        <p class="text-iri-gray">Cet auteur n'a pas encore renseigné sa biographie.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Publications -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-book mr-3"></i>
                            Publications ({{ optional($auteur->publications)->count() ?? 0 }})
                        </h2>
                        <a href="{{ route('admin.publication.create') }}" 
                           class="inline-flex items-center px-3 py-1 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-all duration-200">
                            <i class="fas fa-plus mr-1"></i>Ajouter
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($auteur->publications && optional($auteur->publications)->count() > 0)
                        <div class="space-y-4">
                            @foreach ($auteur->publications as $publication)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border hover:bg-iri-primary/5 transition-colors duration-200">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-iri-dark">{{ $publication->titre }}</h4>
                                        <div class="flex items-center text-sm text-iri-gray mt-1">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <span>{{ $publication->created_at->format('d/m/Y') }}</span>
                                            @if($publication->journal)
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-newspaper mr-1"></i>
                                                <span>{{ $publication->journal }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.publication.show', $publication) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                                        <i class="fas fa-eye mr-1"></i>Voir
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 mx-auto bg-gradient-to-br from-iri-accent/20 to-iri-gold/20 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-book text-iri-gray text-xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-iri-dark mb-2">Aucune publication</h4>
                            <p class="text-iri-gray mb-4">Cet auteur n'a pas encore de publications enregistrées.</p>
                            <a href="{{ route('admin.publication.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-accent to-iri-gold text-white rounded-lg hover:from-iri-gold hover:to-iri-accent transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>Ajouter une publication
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Statistiques -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-book mr-3 text-iri-gray"></i>
                            <span class="font-medium">Publications</span>
                        </div>
                        <span class="text-lg font-semibold text-iri-primary">{{ optional($auteur->publications)->count() ?? 0 }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus mr-3 text-iri-gray"></i>
                            <span class="font-medium">Membre depuis</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $auteur->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-edit mr-3 text-iri-gray"></i>
                            <span class="font-medium">Dernière modification</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $auteur->updated_at->format('d/m/Y') }}</span>
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
                    <a href="{{ route('admin.auteur.edit', $auteur) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier le profil
                    </a>
                    
                    <a href="{{ route('admin.auteur.edit', $auteur) }}#photo" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-accent to-iri-gold text-white rounded-lg hover:from-iri-gold hover:to-iri-accent transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-camera mr-2"></i>
                        Changer la photo
                    </a>
                    
                    <a href="{{ route('admin.publication.create') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Nouvelle publication
                    </a>
                    
                    <form action="{{ route('admin.auteur.destroy', $auteur) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet auteur ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer cet auteur
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
