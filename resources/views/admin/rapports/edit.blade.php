@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.rapports.index') }}" class="text-white/70 hover:text-white transition-colors duration-200">Rapports</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white font-medium">Modifier le Rapport</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Modifier le Rapport')
@section('subtitle', 'Modification de ' . $rapport->titre)

@section('content')

<!-- Container principal avec design moderne IRI -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête moderne avec gradient IRI -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-white/20">
                <div class="bg-gradient-to-r from-iri-secondary via-iri-primary to-iri-accent px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-edit text-white text-2xl"></i>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white font-poppins">Modifier le Rapport</h1>
                                <p class="text-white/80 text-lg">{{ $rapport->titre }}</p>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-white/80 text-sm text-right">
                                <i class="fas fa-calendar mr-2"></i>{{ now()->format('d/m/Y') }}
                                <br>
                                <span class="text-xs">Créé le {{ $rapport->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur avec design moderne -->
        @if ($errors->any())
            <div class="mb-6">
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-red-800 font-semibold text-lg">Erreurs détectées</h3>
                            <p class="text-red-700 text-sm mt-1 mb-3">Veuillez corriger les erreurs suivantes :</p>
                            <ul class="space-y-1 bg-white/60 rounded-lg p-3">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-center text-sm text-red-600">
                                        <i class="fas fa-circle text-xs mr-2"></i>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire principal -->
        <form action="{{ route('admin.rapports.update', $rapport) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Informations générales -->
            <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white font-poppins">Informations Générales</h3>
                            <p class="text-white/80 text-sm">Détails principaux du rapport</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Titre du rapport -->
                    <div>
                        <label for="titre" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                            <i class="fas fa-heading text-iri-accent mr-2"></i>
                            Titre du rapport
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" 
                               name="titre" 
                               id="titre" 
                               value="{{ old('titre', $rapport->titre) }}"
                               class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('titre') border-red-500 bg-red-50 @enderror"
                               placeholder="Entrez le titre du rapport" 
                               required>
                        @error('titre')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                            <i class="fas fa-align-left text-iri-accent mr-2"></i>
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('description') border-red-500 bg-red-50 @enderror"
                                  placeholder="Décrivez le contenu du rapport">{{ old('description', $rapport->description) }}</textarea>
                        <p class="text-xs text-iri-gray mt-1">Résumé du contenu du rapport</p>
                        @error('description')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Classification et dates -->
            <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-iri-accent to-iri-gold px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tags text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white font-poppins">Classification et Dates</h3>
                            <p class="text-white/80 text-sm">Catégorie et informations temporelles</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Catégorie -->
                        <div>
                            <label for="categorie_id" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                                <i class="fas fa-folder text-iri-accent mr-2"></i>
                                Catégorie
                            </label>
                            <select name="categorie_id" 
                                    id="categorie_id"
                                    class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('categorie_id') border-red-500 bg-red-50 @enderror">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}" {{ old('categorie_id', $rapport->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                        {{ $categorie->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-iri-gray mt-1">Catégorie du rapport</p>
                            @error('categorie_id')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Date de publication -->
                        <div>
                            <label for="date_publication" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                                <i class="fas fa-calendar text-iri-accent mr-2"></i>
                                Date de publication
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="date" 
                                   name="date_publication" 
                                   id="date_publication" 
                                   value="{{ old('date_publication', $rapport->date_publication ? \Carbon\Carbon::parse($rapport->date_publication)->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('date_publication') border-red-500 bg-red-50 @enderror"
                                   required>
                            @error('date_publication')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Document PDF -->
            <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-pdf text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white font-poppins">Document PDF</h3>
                            <p class="text-white/80 text-sm">Fichier du rapport</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($rapport->fichier)
                        <div class="mb-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-pdf text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-iri-primary">Fichier actuel:</h4>
                                        <p class="text-sm text-iri-gray">{{ basename($rapport->fichier) }}</p>
                                        @if(file_exists(public_path($rapport->fichier)))
                                            @php
                                                $fileSize = filesize(public_path($rapport->fichier));
                                                $fileSizeFormatted = $fileSize > 1024 * 1024 
                                                    ? round($fileSize / (1024 * 1024), 1) . ' MB'
                                                    : round($fileSize / 1024, 1) . ' KB';
                                            @endphp
                                            <p class="text-xs text-iri-gray">Taille: {{ $fileSizeFormatted }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ asset($rapport->fichier) }}" target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-300 shadow-md">
                                        <i class="fas fa-eye mr-2"></i>
                                        Visualiser
                                    </a>
                                    <a href="{{ asset($rapport->fichier) }}" download
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold rounded-lg hover:from-iri-gold hover:to-iri-accent transition-all duration-300 shadow-md">
                                        <i class="fas fa-download mr-2"></i>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div>
                        <label for="fichier" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                            <i class="fas fa-upload text-iri-accent mr-2"></i>
                            {{ $rapport->fichier ? 'Remplacer le fichier PDF' : 'Fichier PDF' }}
                        </label>
                        <input type="file" 
                               name="fichier" 
                               id="fichier" 
                               accept=".pdf"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-iri-primary/10 file:text-iri-primary hover:file:bg-iri-primary/20 @error('fichier') border-red-500 bg-red-50 @enderror">
                        <p class="text-xs text-iri-gray mt-1">{{ $rapport->fichier ? 'Sélectionnez un nouveau fichier PDF pour remplacer l\'existant' : 'Sélectionnez le fichier PDF du rapport' }} (max 50MB)</p>
                        @error('fichier')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Métadonnées -->
            <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-iri-secondary to-iri-primary px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-database text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white font-poppins">Métadonnées</h3>
                            <p class="text-white/80 text-sm">Informations complémentaires</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Auteur -->
                        <div>
                            <label for="auteur" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                                <i class="fas fa-user-edit text-iri-accent mr-2"></i>
                                Auteur(s)
                            </label>
                            <input type="text" 
                                   name="auteur" 
                                   id="auteur" 
                                   value="{{ old('auteur', $rapport->auteur) }}"
                                   class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('auteur') border-red-500 bg-red-50 @enderror"
                                   placeholder="Nom de l'auteur ou des auteurs">
                            <p class="text-xs text-iri-gray mt-1">Auteur(s) du rapport</p>
                            @error('auteur')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Langue -->
                        <div>
                            <label for="langue" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                                <i class="fas fa-globe text-iri-accent mr-2"></i>
                                Langue
                            </label>
                            <select name="langue" 
                                    id="langue"
                                    class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('langue') border-red-500 bg-red-50 @enderror">
                                <option value="fr" {{ old('langue', $rapport->langue ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ old('langue', $rapport->langue) == 'en' ? 'selected' : '' }}>Anglais</option>
                                <option value="es" {{ old('langue', $rapport->langue) == 'es' ? 'selected' : '' }}>Espagnol</option>
                                <option value="de" {{ old('langue', $rapport->langue) == 'de' ? 'selected' : '' }}>Allemand</option>
                            </select>
                            @error('langue')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Nombre de pages -->
                        <div>
                            <label for="pages" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                                <i class="fas fa-file-alt text-iri-accent mr-2"></i>
                                Nombre de pages
                            </label>
                            <input type="number" 
                                   name="pages" 
                                   id="pages" 
                                   value="{{ old('pages', $rapport->pages) }}"
                                   min="1"
                                   class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('pages') border-red-500 bg-red-50 @enderror"
                                   placeholder="0">
                            @error('pages')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Taille du fichier -->
                        <div>
                            <label for="taille_fichier" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                                <i class="fas fa-hdd text-iri-accent mr-2"></i>
                                Taille du fichier
                            </label>
                            <input type="text" 
                                   name="taille_fichier" 
                                   id="taille_fichier" 
                                   value="{{ old('taille_fichier', $rapport->taille_fichier) }}"
                                   class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('taille_fichier') border-red-500 bg-red-50 @enderror"
                                   placeholder="Ex: 2.5 MB">
                            <p class="text-xs text-iri-gray mt-1">Taille approximative du fichier</p>
                            @error('taille_fichier')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Mots-clés -->
                    <div class="mt-6">
                        <label for="mots_cles" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                            <i class="fas fa-hashtag text-iri-accent mr-2"></i>
                            Mots-clés
                        </label>
                        <input type="text" 
                               name="mots_cles" 
                               id="mots_cles" 
                               value="{{ old('mots_cles', $rapport->mots_cles) }}"
                               class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('mots_cles') border-red-500 bg-red-50 @enderror"
                               placeholder="Séparez les mots-clés par des virgules">
                        <p class="text-xs text-iri-gray mt-1">Mots-clés pour faciliter la recherche</p>
                        @error('mots_cles')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Résumé exécutif -->
                    <div class="mt-6">
                        <label for="resume_executif" class="flex items-center text-sm font-semibold text-iri-primary mb-2">
                            <i class="fas fa-clipboard-list text-iri-accent mr-2"></i>
                            Résumé exécutif
                        </label>
                        <textarea name="resume_executif" 
                                  id="resume_executif" 
                                  rows="3"
                                  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('resume_executif') border-red-500 bg-red-50 @enderror"
                                  placeholder="Résumé exécutif du rapport">{{ old('resume_executif', $rapport->resume_executif) }}</textarea>
                        <p class="text-xs text-iri-gray mt-1">Résumé concis des principales conclusions</p>
                        @error('resume_executif')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 5: État et modération -->
            <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white font-poppins">État et Modération</h3>
                            <p class="text-white/80 text-sm">Paramètres de visibilité et modération</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <!-- Rapport public -->
                            <div class="flex items-center space-x-3">
                                <input type="hidden" name="is_public" value="0">
                                <input type="checkbox" 
                                       name="is_public" 
                                       id="is_public" 
                                       value="1"
                                       {{ old('is_public', $rapport->is_public ?? true) ? 'checked' : '' }}
                                       class="w-5 h-5 text-iri-primary bg-gray-100 border-gray-300 rounded focus:ring-iri-primary focus:ring-2">
                                <label for="is_public" class="text-sm font-medium text-iri-primary">
                                    <i class="fas fa-users text-iri-accent mr-2"></i>
                                    Rapport public
                                </label>
                            </div>
                            <p class="text-xs text-iri-gray ml-8">Le rapport peut être téléchargé librement</p>

                            @if(auth()->check() && auth()->user()->canModerate())
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                <span class="font-medium">Information :</span>
                                                Le rapport sera disponible dans la liste après sauvegarde.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mt-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                <span class="font-medium">Information :</span>
                                                Le rapport sera disponible dans la liste après sauvegarde.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>


                    </div>
                </div>
            </div>



            <!-- Section 6: Actions -->
            <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4 justify-end">
                        <a href="{{ route('admin.rapports.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border-2 border-iri-gray text-iri-gray font-medium rounded-xl hover:bg-iri-gray hover:text-white focus:ring-4 focus:ring-iri-gray/20 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                        
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-xl hover:shadow-lg focus:ring-4 focus:ring-iri-primary/20 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Mettre à jour le rapport
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection
