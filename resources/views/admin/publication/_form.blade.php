
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- En-tête du formulaire -->
    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-8 py-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-white">
                    {{ isset($publication) ? 'Modifier la publication' : 'Nouvelle publication' }}
                </h2>
                <p class="text-iri-light/80 text-sm mt-1">
                    {{ isset($publication) ? 'Modifiez les informations de cette publication' : 'Créez une nouvelle publication scientifique' }}
                </p>
            </div>
        </div>
    </div>

    <form id="publicationForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="p-8">
        @csrf
        @if(isset($publication)) @method('PUT') @endif

        <!-- Section Informations Principales -->
        <div class="space-y-8">
            <!-- Titre -->
            <div class="group">
                <label for="titre" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Titre de la publication *
                </label>
                <div class="relative">
                    <input type="text" 
                           id="titre" 
                           name="titre" 
                           value="{{ old('titre', $publication->titre ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white placeholder-gray-400 @error('titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="Saisir le titre de la publication..."
                           required 
                           maxlength="255">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-iri-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </div>
                </div>
                @error('titre')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Résumé -->
            <div class="group">
                <label for="resume" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Résumé *
                </label>
                <div class="relative">
                    <textarea name="resume" 
                              id="resume" 
                              rows="4" 
                              required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white placeholder-gray-400 resize-none @error('resume') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                              placeholder="Rédigez un résumé détaillé de la publication...">{{ old('resume', $publication->resume ?? '') }}</textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                        <span id="resume-count">0</span> caractères
                    </div>
                </div>
                @error('resume')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Citation -->
            <div class="group">
                <label for="citation" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Citation
                </label>
                <div class="relative">
                    <textarea name="citation" 
                              id="citation" 
                              rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white placeholder-gray-400 resize-none @error('citation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                              placeholder="Citation académique de la publication...">{{ old('citation', $publication->citation ?? '') }}</textarea>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Format recommandé : Nom, P. (Année). Titre. Revue, Volume(Numéro), pages.
                </p>
                @error('citation')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Auteurs -->
            <div class="group">
                <div class="flex items-center justify-between mb-3">
                    <label for="auteurs" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Auteurs *
                    </label>
                    <div class="flex space-x-2">
                        <button type="button" 
                                id="searchAuthorBtn"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-iri-primary bg-iri-light border border-iri-primary rounded-md hover:bg-iri-primary hover:text-white transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                        <button type="button" 
                                id="createAuthorBtn"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 border border-green-600 rounded-md hover:bg-green-700 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Créer
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <select name="auteurs[]" 
                            id="auteurs" 
                            multiple 
                            required
                            class="w-full min-h-[120px] px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white @error('auteurs') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @foreach($auteurs as $auteur)
                            <option value="{{ $auteur->id }}" 
                                    class="py-2 px-3 hover:bg-iri-light"
                                    {{ in_array($auteur->id, old('auteurs', isset($publication) ? $publication->auteurs->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                {{ $auteur->prenom ? $auteur->prenom . ' ' . $auteur->nom : $auteur->nom }}
                                @if($auteur->institution)
                                    <span class="text-xs text-gray-500 block">{{ $auteur->institution }}</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-2 flex flex-wrap gap-2">
                    <button type="button" id="selectAllAuthors" class="text-xs bg-iri-primary text-white px-3 py-1 rounded-full hover:bg-iri-secondary transition-colors">
                        Tout sélectionner
                    </button>
                    <button type="button" id="clearAllAuthors" class="text-xs bg-gray-500 text-white px-3 py-1 rounded-full hover:bg-gray-600 transition-colors">
                        Tout désélectionner
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs auteurs
                </p>
                @error('auteurs')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Catégorie -->
            <div class="group">
                <div class="flex items-center justify-between mb-3">
                    <label for="categorie_id" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Catégorie *
                    </label>
                    @can('create_publications')
                    <button type="button" 
                            id="createCategoryBtn"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 border border-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nouvelle catégorie
                    </button>
                    @endcan
                </div>
                <div class="relative">
                    <select name="categorie_id" 
                            id="categorie_id" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white @error('categorie_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        <option value="" disabled {{ old('categorie_id', $publication->categorie_id ?? '') == '' ? 'selected' : '' }}>
                            Sélectionner une catégorie
                        </option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('categorie_id', $publication->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @error('categorie_id')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Fichier -->
            <div class="group">
                <label for="fichier_pdf" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Fichier de la publication *
                    @if(!isset($publication))
                        <span class="ml-2 text-red-500 text-xs">(Obligatoire)</span>
                    @endif
                </label>
                <div class="space-y-4">
                    <!-- Zone de dépôt de fichier -->
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-iri-primary transition-colors duration-200 group-hover:border-iri-primary @error('fichier_pdf') border-red-300 @enderror">
                        <input type="file" 
                               id="fichier_pdf" 
                               name="fichier_pdf" 
                               accept=".pdf"
                               @if(!isset($publication)) required @endif
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-iri-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium text-iri-primary hover:text-iri-secondary cursor-pointer">Cliquez pour sélectionner</span> ou glissez un fichier
                                </p>
                                <p class="text-xs text-gray-500 mt-1">PDF uniquement, jusqu'à 50MB</p>
                                <!-- Information sur les miniatures automatiques -->
                                <div class="mt-3 p-2 bg-blue-50 rounded-md border border-blue-200">
                                    <p class="text-xs text-blue-700 flex items-center justify-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        La miniature de la première page sera générée automatiquement
                                    </p>
                                </div>
                                @if(!isset($publication))
                                    <p class="text-xs text-red-500 mt-1 font-medium">* Fichier obligatoire pour créer une publication</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Fichier actuel (si il existe) -->
                    @if(isset($publication) && $publication->fichier_pdf)
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @php
                                        $extension = strtolower(pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION));
                                        $iconColor = $extension === 'pdf' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <div class="w-12 h-12 {{ $iconColor }} rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-green-700">Fichier actuel</span>
                                        @php
                                            $extension = strtoupper(pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION));
                                        @endphp
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            {{ $extension }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 truncate" title="{{ $publication->fichier_pdf }}">
                                        {{ pathinfo($publication->fichier_pdf, PATHINFO_FILENAME) }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Format : <span class="font-medium">{{ $extension }}</span> • Sélectionnez un nouveau fichier PDF pour le remplacer
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ asset('storage/' . $publication->fichier_pdf) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @error('fichier_pdf')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Options de publication -->
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                    </svg>
                    Options de publication
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- À la une -->
                    <div class="relative">
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-white hover:shadow-sm transition-all duration-200 bg-white" 
                             onclick="toggleCheckbox('a_la_une')" id="label_a_la_une">
                            <input type="checkbox" 
                                   name="a_la_une" 
                                   id="a_la_une" 
                                   class="hidden" 
                                   value="1"
                                   {{ old('a_la_une', $publication->a_la_une ?? false) ? 'checked' : '' }}>
                            <div class="flex-shrink-0">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center" id="visual_a_la_une">
                                    <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20" id="icon_a_la_une">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">À la une</span>
                                <p class="text-xs text-gray-500">Afficher en première position</p>
                            </div>
                        </div>
                    </div>

                    <!-- En vedette -->
                    <div class="relative">
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-white hover:shadow-sm transition-all duration-200 bg-white" 
                             onclick="toggleCheckbox('en_vedette')" id="label_en_vedette">
                            <input type="checkbox" 
                                   name="en_vedette" 
                                   id="en_vedette" 
                                   class="hidden" 
                                   value="1"
                                   {{ old('en_vedette', $publication->en_vedette ?? false) ? 'checked' : '' }}>
                            <div class="flex-shrink-0">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center" id="visual_en_vedette">
                                    <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20" id="icon_en_vedette">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">En vedette</span>
                                <p class="text-xs text-gray-500">Mettre en valeur cette publication</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.publication.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Annuler
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 border border-transparent rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($publication) ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modal de recherche/création d'auteurs -->
<div id="authorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- En-tête du modal -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Gestion des auteurs
                </h3>
                <button type="button" id="closeAuthorModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Onglets -->
            <div class="mt-4">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button type="button" id="searchAuthorTab" class="author-tab active py-2 px-1 border-b-2 border-iri-primary text-iri-primary font-medium text-sm">
                            Rechercher
                        </button>
                        <button type="button" id="createAuthorTab" class="author-tab py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                            Créer nouveau
                        </button>
                    </nav>
                </div>

                <!-- Contenu recherche -->
                <div id="searchAuthorContent" class="author-content mt-4">
                    <div class="mb-4">
                        <label for="authorSearch" class="block text-sm font-medium text-gray-700 mb-2">Rechercher un auteur</label>
                        <div class="relative">
                            <input type="text" 
                                   id="authorSearch" 
                                   placeholder="Tapez le nom de l'auteur..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="authorSearchResults" class="max-h-60 overflow-y-auto border border-gray-200 rounded-md">
                        <div class="p-4 text-center text-gray-500">
                            Commencez à taper pour rechercher des auteurs...
                        </div>
                    </div>
                </div>

                <!-- Contenu création -->
                <div id="createAuthorContent" class="author-content mt-4 hidden">
                    <form id="createAuthorForm" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="authorNom" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                <input type="text" 
                                       id="authorNom" 
                                       name="nom" 
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary">
                            </div>
                            <div>
                                <label for="authorPrenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                <input type="text" 
                                       id="authorPrenom" 
                                       name="prenom" 
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary">
                            </div>
                        </div>
                        <div>
                            <label for="authorEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" 
                                   id="authorEmail" 
                                   name="email"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary">
                        </div>
                        <div>
                            <label for="authorInstitution" class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                            <input type="text" 
                                   id="authorInstitution" 
                                   name="institution"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary">
                        </div>
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button type="button" id="cancelCreateAuthor" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-iri-primary border border-transparent rounded-md hover:bg-iri-secondary">
                                Créer l'auteur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création de catégorie -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- En-tête du modal -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Nouvelle catégorie
                </h3>
                <button type="button" id="closeCategoryModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Formulaire de création -->
            <form id="createCategoryForm" class="mt-4 space-y-4">
                <div>
                    <label for="categoryNom" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie *</label>
                    <input type="text" 
                           id="categoryNom" 
                           name="nom" 
                           required
                           placeholder="Ex: Recherche en Intelligence Artificielle"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary">
                </div>
                <div>
                    <label for="categoryDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="categoryDescription" 
                              name="description" 
                              rows="3"
                              placeholder="Description optionnelle de la catégorie..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-iri-primary focus:border-iri-primary resize-none"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelCreateCategory" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-iri-primary border border-transparent rounded-md hover:bg-iri-secondary">
                        Créer la catégorie
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('publicationForm');
    const titreField = document.getElementById('titre');
    const resumeField = document.getElementById('resume');
    const citationField = document.getElementById('citation');
    const fichierField = document.getElementById('fichier_pdf');
    const auteursSelect = document.getElementById('auteurs');
    
    // Compteur de caractères pour le résumé
    function updateCharacterCount(field, countElement) {
        const count = field.value.length;
        const countSpan = document.getElementById(countElement);
        if (countSpan) {
            countSpan.textContent = count;
        }
    }
    
    // Initialiser le compteur de résumé
    if (resumeField) {
        updateCharacterCount(resumeField, 'resume-count');
        resumeField.addEventListener('input', () => updateCharacterCount(resumeField, 'resume-count'));
    }
    
    // Fonction simple pour gérer les checkboxes
    function toggleCheckbox(checkboxId) {
        console.log('Toggle checkbox:', checkboxId);
        
        const checkbox = document.getElementById(checkboxId);
        const label = document.getElementById('label_' + checkboxId);
        const visual = document.getElementById('visual_' + checkboxId);
        const icon = document.getElementById('icon_' + checkboxId);
        
        if (!checkbox || !label || !visual || !icon) {
            console.error('Éléments non trouvés pour:', checkboxId);
            return;
        }
        
        // Inverser l'état du checkbox
        checkbox.checked = !checkbox.checked;
        console.log('Nouvel état:', checkbox.checked);
        
        // Mettre à jour l'apparence
        updateCheckboxAppearance(checkboxId);
    }
    
    function updateCheckboxAppearance(checkboxId) {
        const checkbox = document.getElementById(checkboxId);
        const label = document.getElementById('label_' + checkboxId);
        const visual = document.getElementById('visual_' + checkboxId);
        const icon = document.getElementById('icon_' + checkboxId);
        
        if (!checkbox || !label || !visual || !icon) return;
        
        if (checkbox.checked) {
            // État coché
            label.style.backgroundColor = '#e0f2fe';
            label.style.borderColor = '#0891b2';
            visual.style.backgroundColor = '#0891b2';
            visual.style.borderColor = '#0891b2';
            icon.style.display = 'block';
            icon.classList.remove('hidden');
        } else {
            // État non coché
            label.style.backgroundColor = '#ffffff';
            label.style.borderColor = '#d1d5db';
            visual.style.backgroundColor = 'transparent';
            visual.style.borderColor = '#d1d5db';
            icon.style.display = 'none';
            icon.classList.add('hidden');
        }
        
        console.log('Apparence mise à jour pour:', checkboxId, 'État:', checkbox.checked);
    }
    
    // Initialiser l'apparence des checkboxes au chargement
    function initializeCheckboxes() {
        console.log('Initialisation des checkboxes...');
        updateCheckboxAppearance('a_la_une');
        updateCheckboxAppearance('en_vedette');
    }
    
    // Rendre les fonctions globales
    window.toggleCheckbox = toggleCheckbox;
    window.updateCheckboxAppearance = updateCheckboxAppearance;
    
    // Initialiser après le chargement
    initializeCheckboxes();
    
    // Gestion des boutons de sélection des auteurs
    const selectAllBtn = document.getElementById('selectAllAuthors');
    const clearAllBtn = document.getElementById('clearAllAuthors');
    
    if (selectAllBtn && auteursSelect) {
        selectAllBtn.addEventListener('click', function() {
            Array.from(auteursSelect.options).forEach(option => option.selected = true);
        });
    }
    
    if (clearAllBtn && auteursSelect) {
        clearAllBtn.addEventListener('click', function() {
            Array.from(auteursSelect.options).forEach(option => option.selected = false);
        });
    }
    
    // Gestion du drag & drop pour le fichier
    const fileDropZone = fichierField.closest('.border-dashed');
    
    if (fileDropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            fileDropZone.classList.add('border-iri-primary', 'bg-iri-light');
            fileDropZone.classList.remove('border-gray-300');
        }
        
        function unhighlight() {
            fileDropZone.classList.remove('border-iri-primary', 'bg-iri-light');
            fileDropZone.classList.add('border-gray-300');
        }
        
        fileDropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const file = files[0];
                
                // Vérifier que c'est un PDF
                if (!isPDFFile(file)) {
                    showFileError('Seuls les fichiers PDF sont acceptés.');
                    return;
                }
                
                // Vérifier la taille du fichier (50MB = 52428800 bytes)
                if (file.size > 52428800) {
                    showFileError('Le fichier ne doit pas dépasser 50 MB.');
                    return;
                }
                
                // Créer un objet FileList pour assigner au input
                const dt2 = new DataTransfer();
                dt2.items.add(file);
                fichierField.files = dt2.files;
                
                clearFileError();
                updateFilePreview(file);
            }
        }
    }
    
    // Prévisualisation du fichier
    fichierField.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            
            // Vérifier que c'est un PDF
            if (!isPDFFile(file)) {
                // Afficher une erreur et réinitialiser le champ
                showFileError('Seuls les fichiers PDF sont acceptés.');
                e.target.value = '';
                clearFilePreview();
                return;
            }
            
            // Vérifier la taille du fichier (50MB = 52428800 bytes)
            if (file.size > 52428800) {
                showFileError('Le fichier ne doit pas dépasser 50 MB.');
                e.target.value = '';
                clearFilePreview();
                return;
            }
            
            clearFileError();
            updateFilePreview(file);
        }
    });
    
    // Fonction pour vérifier si c'est un PDF
    function isPDFFile(file) {
        const allowedTypes = ['application/pdf'];
        const allowedExtensions = ['.pdf'];
        
        // Vérifier le type MIME
        if (allowedTypes.includes(file.type)) {
            return true;
        }
        
        // Vérifier l'extension comme fallback
        const fileName = file.name.toLowerCase();
        return allowedExtensions.some(ext => fileName.endsWith(ext));
    }
    
    // Fonction pour afficher une erreur de fichier
    function showFileError(message) {
        const fileContainer = fichierField.closest('.group');
        
        // Supprimer l'ancien message d'erreur s'il existe
        const existingError = fileContainer.querySelector('.file-validation-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Créer le nouveau message d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'file-validation-error text-red-500 text-sm mt-2 flex items-center';
        errorDiv.innerHTML = `
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            ${message}
        `;
        
        // Ajouter après la zone de fichier
        const fileDropZone = fileContainer.querySelector('.border-dashed');
        fileDropZone.insertAdjacentElement('afterend', errorDiv);
        
        // Ajouter un style d'erreur à la zone de dépôt
        fileDropZone.classList.add('border-red-300', 'bg-red-50');
        fileDropZone.classList.remove('border-gray-300');
    }
    
    // Fonction pour supprimer l'erreur de fichier
    function clearFileError() {
        const fileContainer = fichierField.closest('.group');
        const existingError = fileContainer.querySelector('.file-validation-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Retirer le style d'erreur
        const fileDropZone = fileContainer.querySelector('.border-dashed');
        fileDropZone.classList.remove('border-red-300', 'bg-red-50');
        fileDropZone.classList.add('border-gray-300');
    }
    
    // Fonction pour supprimer la prévisualisation
    function clearFilePreview() {
        const fileContainer = fichierField.closest('.group').querySelector('.space-y-4');
        const existingPreview = fileContainer.querySelector('.file-preview');
        if (existingPreview) {
            existingPreview.remove();
        }
    }
    
    function updateFilePreview(file) {
        if (file) {
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // Taille en MB
            const preview = document.createElement('div');
            preview.className = 'file-preview bg-green-50 rounded-lg border border-green-200 p-4 mt-4';
            preview.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-green-700">Fichier sélectionné</span>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                PDF
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 truncate" title="${file.name}">
                            ${file.name}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Taille : <span class="font-medium">${fileSize} MB</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="clearSelectedFile()" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            const fileContainer = fichierField.closest('.group').querySelector('.space-y-4');
            
            const existingPreview = fileContainer.querySelector('.file-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            fileContainer.appendChild(preview);
        }
    }
    
    // Fonction pour supprimer le fichier sélectionné
    window.clearSelectedFile = function() {
        fichierField.value = '';
        clearFilePreview();
        clearFileError();
    }
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validation du résumé
        if (!resumeField.value.trim()) {
            resumeField.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            resumeField.classList.remove('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
            isValid = false;
        } else {
            resumeField.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            resumeField.classList.add('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
        }
        
        // Validation du titre
        if (!titreField.value.trim()) {
            titreField.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            titreField.classList.remove('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
            isValid = false;
        } else {
            titreField.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            titreField.classList.add('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
        }
        
        // Validation du fichier PDF (obligatoire pour création uniquement)
        @if(!isset($publication))
        if (!fichierField.files || fichierField.files.length === 0) {
            const fileContainer = fichierField.closest('.relative');
            fileContainer.classList.add('border-red-300');
            
            // Ajouter un message d'erreur si pas déjà présent
            let errorMessage = fileContainer.querySelector('.file-error-message');
            if (!errorMessage) {
                errorMessage = document.createElement('p');
                errorMessage.className = 'file-error-message text-red-500 text-sm mt-2';
                errorMessage.textContent = 'Le fichier PDF est obligatoire pour créer une publication.';
                fileContainer.appendChild(errorMessage);
            }
            isValid = false;
        } else {
            // Vérifier que le fichier sélectionné est bien un PDF
            const file = fichierField.files[0];
            if (!isPDFFile(file)) {
                showFileError('Seuls les fichiers PDF sont acceptés.');
                isValid = false;
            } else {
                const fileContainer = fichierField.closest('.relative');
                fileContainer.classList.remove('border-red-300');
                
                // Supprimer le message d'erreur s'il existe
                const errorMessage = fileContainer.querySelector('.file-error-message');
                if (errorMessage) {
                    errorMessage.remove();
                }
            }
        }
        @else
        // Pour les modifications, vérifier le type seulement si un nouveau fichier est sélectionné
        if (fichierField.files && fichierField.files.length > 0) {
            const file = fichierField.files[0];
            if (!isPDFFile(file)) {
                showFileError('Seuls les fichiers PDF sont acceptés.');
                isValid = false;
            }
        }
        @endif
        
        // Validation des auteurs
        const selectedAuthors = Array.from(auteursSelect.selectedOptions);
        if (selectedAuthors.length === 0) {
            auteursSelect.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            auteursSelect.classList.remove('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
            isValid = false;
        } else {
            auteursSelect.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            auteursSelect.classList.add('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
        }
        
        if (!isValid) {
            e.preventDefault();
            
            // Scroll vers la première erreur
            const firstError = document.querySelector('.border-red-500, .border-red-300');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (firstError.focus) {
                    firstError.focus();
                }
            }
            
            // Afficher une notification d'erreur
            showNotification('Veuillez corriger les erreurs dans le formulaire.', 'error');
            
        } else {
            // Afficher un indicateur de chargement
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Enregistrement...
            `;
            
            // Restaurer le bouton après 10 secondes en cas de problème
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }, 10000);
        }
    });
    
    
    // Animation d'entrée
    const formContainer = document.querySelector('.max-w-4xl');
    formContainer.style.opacity = '0';
    formContainer.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        formContainer.style.transition = 'all 0.5s ease-out';
        formContainer.style.opacity = '1';
        formContainer.style.transform = 'translateY(0)';
    }, 100);

    // ===== GESTION DES MODALS =====
    
    // Fonction utilitaire pour récupérer le token CSRF
    function getCsrfToken() {
        // Méthode 1: Meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            return csrfToken.getAttribute('content');
        }
        
        // Méthode 2: Input hidden du formulaire
        const csrfInput = document.querySelector('input[name="_token"]');
        if (csrfInput) {
            return csrfInput.value;
        }
        
        // Méthode 3: Fallback Laravel Blade
        return '{{ csrf_token() }}';
    }
    
    // Modal des auteurs
    const authorModal = document.getElementById('authorModal');
    const searchAuthorBtn = document.getElementById('searchAuthorBtn');
    const createAuthorBtn = document.getElementById('createAuthorBtn');
    const closeAuthorModal = document.getElementById('closeAuthorModal');
    
    // Modal des catégories
    const categoryModal = document.getElementById('categoryModal');
    const createCategoryBtn = document.getElementById('createCategoryBtn');
    const closeCategoryModal = document.getElementById('closeCategoryModal');
    
    // Ouvrir modal auteur (recherche par défaut)
    if (searchAuthorBtn) {
        searchAuthorBtn.addEventListener('click', function() {
            console.log('Bouton recherche auteur cliqué');
            openAuthorModal('search');
        });
    }
    
    if (createAuthorBtn) {
        createAuthorBtn.addEventListener('click', function() {
            console.log('Bouton création auteur cliqué');
            openAuthorModal('create');
        });
    }
    
    // Ouvrir modal catégorie
    if (createCategoryBtn) {
        createCategoryBtn.addEventListener('click', function() {
            console.log('Bouton création catégorie cliqué');
            openCategoryModal();
        });
    }
    
    // Fermer modals
    if (closeAuthorModal) {
        closeAuthorModal.addEventListener('click', function() {
            closeModal(authorModal);
        });
    }
    
    if (closeCategoryModal) {
        closeCategoryModal.addEventListener('click', function() {
            closeModal(categoryModal);
        });
    }
    
    // Fermer modal en cliquant à l'extérieur
    window.addEventListener('click', function(e) {
        if (e.target === authorModal) {
            closeModal(authorModal);
        }
        if (e.target === categoryModal) {
            closeModal(categoryModal);
        }
    });
    
    // Fonctions utilitaires pour les modals
    function openAuthorModal(tab = 'search') {
        console.log('Ouverture modal auteur, onglet:', tab);
        authorModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Activer l'onglet approprié
        if (tab === 'create') {
            switchAuthorTab('create');
        } else {
            switchAuthorTab('search');
        }
    }
    
    function openCategoryModal() {
        console.log('Ouverture modal catégorie');
        categoryModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('categoryNom').focus();
    }
    
    function closeModal(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Reset des formulaires
        if (modal === authorModal) {
            document.getElementById('createAuthorForm').reset();
            document.getElementById('authorSearch').value = '';
            document.getElementById('authorSearchResults').innerHTML = '<div class="p-4 text-center text-gray-500">Commencez à taper pour rechercher des auteurs...</div>';
        }
        if (modal === categoryModal) {
            document.getElementById('createCategoryForm').reset();
        }
    }
    
    // ===== GESTION DES ONGLETS AUTEUR =====
    
    const searchAuthorTab = document.getElementById('searchAuthorTab');
    const createAuthorTab = document.getElementById('createAuthorTab');
    const searchAuthorContent = document.getElementById('searchAuthorContent');
    const createAuthorContent = document.getElementById('createAuthorContent');
    
    if (searchAuthorTab) {
        searchAuthorTab.addEventListener('click', function() {
            switchAuthorTab('search');
        });
    }
    
    if (createAuthorTab) {
        createAuthorTab.addEventListener('click', function() {
            switchAuthorTab('create');
        });
    }
    
    function switchAuthorTab(tab) {
        // Reset tabs
        document.querySelectorAll('.author-tab').forEach(t => {
            t.classList.remove('active', 'border-iri-primary', 'text-iri-primary');
            t.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Reset content
        document.querySelectorAll('.author-content').forEach(c => {
            c.classList.add('hidden');
        });
        
        if (tab === 'search') {
            searchAuthorTab.classList.add('active', 'border-iri-primary', 'text-iri-primary');
            searchAuthorTab.classList.remove('border-transparent', 'text-gray-500');
            searchAuthorContent.classList.remove('hidden');
            document.getElementById('authorSearch').focus();
        } else {
            createAuthorTab.classList.add('active', 'border-iri-primary', 'text-iri-primary');
            createAuthorTab.classList.remove('border-transparent', 'text-gray-500');
            createAuthorContent.classList.remove('hidden');
            document.getElementById('authorNom').focus();
        }
    }
    
    // ===== RECHERCHE D'AUTEURS =====
    
    const authorSearch = document.getElementById('authorSearch');
    const authorSearchResults = document.getElementById('authorSearchResults');
    let searchTimeout;
    
    if (authorSearch) {
        authorSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                authorSearchResults.innerHTML = '<div class="p-4 text-center text-gray-500">Tapez au moins 2 caractères pour rechercher...</div>';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchAuthors(query);
            }, 300);
        });
    }
    
    function searchAuthors(query) {
        authorSearchResults.innerHTML = '<div class="p-4 text-center text-gray-500">Recherche en cours...</div>';
        
        fetch(`/admin/auteurs/search?q=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Erreur de recherche:', error);
            authorSearchResults.innerHTML = '<div class="p-4 text-center text-red-500">Erreur lors de la recherche</div>';
        });
    }
    
    function displaySearchResults(authors) {
        if (authors.length === 0) {
            authorSearchResults.innerHTML = '<div class="p-4 text-center text-gray-500">Aucun auteur trouvé</div>';
            return;
        }
        
        let html = '';
        authors.forEach(author => {
            const isSelected = Array.from(auteursSelect.selectedOptions).some(option => option.value == author.id);
            html += `
                <div class="flex items-center justify-between p-3 border-b border-gray-100 hover:bg-gray-50">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">${author.nom}</div>
                        ${author.email ? `<div class="text-sm text-gray-500">${author.email}</div>` : ''}
                        ${author.institution ? `<div class="text-xs text-gray-400">${author.institution}</div>` : ''}
                    </div>
                    <button type="button" 
                            onclick="toggleAuthorSelection(${author.id}, '${author.nom}', this)"
                            class="px-3 py-1 text-xs font-medium rounded-md ${isSelected ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-iri-primary text-white hover:bg-iri-secondary'}">
                        ${isSelected ? 'Retirer' : 'Sélectionner'}
                    </button>
                </div>
            `;
        });
        
        authorSearchResults.innerHTML = html;
    }
    
    // Fonction globale pour toggle la sélection d'auteur
    window.toggleAuthorSelection = function(authorId, authorName, button) {
        const option = auteursSelect.querySelector(`option[value="${authorId}"]`);
        
        if (option) {
            if (option.selected) {
                option.selected = false;
                button.textContent = 'Sélectionner';
                button.className = 'px-3 py-1 text-xs font-medium rounded-md bg-iri-primary text-white hover:bg-iri-secondary';
            } else {
                option.selected = true;
                button.textContent = 'Retirer';
                button.className = 'px-3 py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 hover:bg-red-200';
            }
        }
    };
    
    // ===== CRÉATION D'AUTEUR =====
    
    const createAuthorForm = document.getElementById('createAuthorForm');
    const cancelCreateAuthor = document.getElementById('cancelCreateAuthor');
    
    if (cancelCreateAuthor) {
        cancelCreateAuthor.addEventListener('click', function() {
            switchAuthorTab('search');
        });
    }
    
    if (createAuthorForm) {
        createAuthorForm.addEventListener('submit', function(e) {
            console.log('Soumission formulaire création auteur');
            e.preventDefault();
            
            // Récupération des données du formulaire
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Validation basique côté client
            const nom = formData.get('nom');
            const prenom = formData.get('prenom');
            
            if (!nom || !prenom) {
                showNotification('Veuillez remplir le nom et le prénom', 'error');
                return;
            }
            
            console.log('Données du formulaire:', Object.fromEntries(formData));
            
            // Désactiver le bouton et changer le texte
            submitBtn.disabled = true;
            submitBtn.textContent = 'Création...';
            
            fetch('/admin/auteurs', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Réponse reçue:', response.status, response.statusText);
                
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data);
                
                if (data.success && data.auteur) {
                    // Créer une nouvelle option dans le select
                    const option = document.createElement('option');
                    option.value = data.auteur.id;
                    option.textContent = data.auteur.nom_complet || `${data.auteur.prenom} ${data.auteur.nom}`;
                    option.selected = true;
                    auteursSelect.appendChild(option);
                    
                    // Réinitialiser le formulaire
                    this.reset();
                    
                    // Fermer le modal
                    closeModal(authorModal);
                    
                    // Notification de succès
                    showNotification('Auteur créé avec succès!', 'success');
                } else {
                    throw new Error(data.message || 'Réponse invalide du serveur');
                }
            })
            .catch(error => {
                console.error('Erreur création auteur:', error);
                
                // Afficher l'erreur spécifique
                let errorMessage = 'Erreur lors de la création de l\'auteur';
                if (error.message) {
                    errorMessage += ': ' + error.message;
                }
                
                showNotification(errorMessage, 'error');
            })
            .finally(() => {
                // Restaurer le bouton
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
    
    // ===== CRÉATION DE CATÉGORIE =====
    
    const createCategoryForm = document.getElementById('createCategoryForm');
    const cancelCreateCategory = document.getElementById('cancelCreateCategory');
    const categorieSelect = document.getElementById('categorie_id');
    
    if (cancelCreateCategory) {
        cancelCreateCategory.addEventListener('click', function() {
            closeModal(categoryModal);
        });
    }
    
    if (createCategoryForm) {
        createCategoryForm.addEventListener('submit', function(e) {
            console.log('Soumission formulaire création catégorie');
            e.preventDefault();
            
            // Récupération des données du formulaire
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Validation basique côté client
            const nom = formData.get('nom');
            
            if (!nom) {
                showNotification('Veuillez remplir le nom de la catégorie', 'error');
                return;
            }
            
            console.log('Données du formulaire catégorie:', Object.fromEntries(formData));
            
            // Désactiver le bouton et changer le texte
            submitBtn.disabled = true;
            submitBtn.textContent = 'Création...';
            
            fetch('/admin/categories', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Réponse catégorie reçue:', response.status, response.statusText);
                
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Données catégorie reçues:', data);
                
                if (data.success && data.categorie) {
                    // Créer une nouvelle option dans le select
                    const option = document.createElement('option');
                    option.value = data.categorie.id;
                    option.textContent = data.categorie.nom;
                    option.selected = true;
                    categorieSelect.appendChild(option);
                    
                    // Réinitialiser le formulaire
                    this.reset();
                    
                    // Fermer le modal
                    closeModal(categoryModal);
                    
                    // Notification de succès
                    showNotification('Catégorie créée avec succès!', 'success');
                } else {
                    throw new Error(data.message || 'Réponse invalide du serveur');
                }
            })
            .catch(error => {
                console.error('Erreur création catégorie:', error);
                
                // Afficher l'erreur spécifique
                let errorMessage = 'Erreur lors de la création de la catégorie';
                if (error.message) {
                    errorMessage += ': ' + error.message;
                }
                
                showNotification(errorMessage, 'error');
            })
            .finally(() => {
                // Restaurer le bouton
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
    
    // ===== GESTION DES CHECKBOXES PERSONNALISÉES =====
    
    function setupCustomCheckboxes() {
        const checkboxes = ['a_la_une', 'en_vedette'];
        
        checkboxes.forEach(checkboxName => {
            const checkbox = document.getElementById(checkboxName);
            const label = checkbox.closest('label');
            const visualCheckbox = label.querySelector('.w-5');
            const checkIcon = visualCheckbox.querySelector('svg');
            
            // Fonction pour mettre à jour l'apparence
            function updateCheckboxAppearance() {
                if (checkbox.checked) {
                    visualCheckbox.classList.add('bg-iri-primary', 'border-iri-primary');
                    visualCheckbox.classList.remove('border-gray-300');
                    checkIcon.classList.remove('hidden');
                    checkIcon.classList.add('block');
                    label.classList.add('bg-iri-light', 'border-iri-primary');
                    label.classList.remove('bg-white');
                } else {
                    visualCheckbox.classList.remove('bg-iri-primary', 'border-iri-primary');
                    visualCheckbox.classList.add('border-gray-300');
                    checkIcon.classList.add('hidden');
                    checkIcon.classList.remove('block');
                    label.classList.remove('bg-iri-light', 'border-iri-primary');
                    label.classList.add('bg-white');
                }
            }
            
            // Gestionnaire de clic sur le label
            label.addEventListener('click', function(e) {
                e.preventDefault();
                checkbox.checked = !checkbox.checked;
                updateCheckboxAppearance();
                
                // Déclencher l'événement change pour la validation
                checkbox.dispatchEvent(new Event('change'));
            });
            
            // Initialiser l'apparence au chargement
            updateCheckboxAppearance();
            
            // Écouter les changements programmatiques
            checkbox.addEventListener('change', updateCheckboxAppearance);
        });
    }
    
    // Initialiser les checkboxes
    setupCustomCheckboxes();
    
    // ===== SYSTÈME DE NOTIFICATIONS =====
    
    function showNotification(message, type = 'info') {
        // Créer l'élément de notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-md shadow-lg transition-all duration-300 transform translate-x-full`;
        
        if (type === 'success') {
            notification.className += ' bg-green-100 border border-green-200 text-green-700';
        } else if (type === 'error') {
            notification.className += ' bg-red-100 border border-red-200 text-red-700';
        } else {
            notification.className += ' bg-blue-100 border border-blue-200 text-blue-700';
        }
        
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'success' ? 
                        '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>' :
                        type === 'error' ?
                        '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>' :
                        '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>'
                    }
                </svg>
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animer l'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Supprimer après 4 secondes
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }
});
</script>
