<!-- Meta CSRF Token pour les requêtes AJAX -->
@if(!isset($metaCsrfDefined))
<meta name="csrf-token" content="{{ csrf_token() }}">
@endif

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- En-tête du formulaire -->
    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-8 py-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold text-white">
                    {{ isset($actualite) ? 'Modifier l\'actualité' : 'Nouvelle actualité' }}
                </h1>
                <p class="text-iri-light/80 text-sm mt-1">
                    {{ isset($actualite) ? 'Modifiez les informations de cette actualité' : 'Créez une nouvelle actualité pour votre site' }}
                </p>
            </div>
        </div>
    </div>

    <form id="actualiteForm" 
          action="{{ $formAction ?? (isset($actualite) ? route('admin.actualite.update', $actualite) : route('admin.actualite.store')) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="p-8"
          onsubmit="console.log('=== FORM ONSUBMIT DIRECT ==='); console.log('Action:', this.action); return true;">
        @csrf
        @if(isset($actualite)) @method('PUT') @endif

        <!-- Section Contenu Principal -->
        <div class="space-y-8">
            <!-- Titre -->
            <div class="group">
                <label for="titre" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Titre de l'actualité *
                </label>
                <div class="relative">
                    <input type="text" 
                           id="titre" 
                           name="titre" 
                           value="{{ old('titre', $actualite->titre ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white placeholder-gray-400"
                           placeholder="Saisir le titre de l'actualité..."
                           required
                           aria-describedby="titre-description error-titre"
                           maxlength="255">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-iri-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </div>
                </div>
                <p id="titre-description" class="text-xs text-gray-500 mt-1">Le titre de votre actualité (obligatoire, max 255 caractères)</p>
                <p class="text-red-500 text-sm mt-2 hidden flex items-center" id="error-titre" role="alert" aria-live="polite">
                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Ce champ est requis.
                </p>
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
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Résumé
                    <span class="text-xs font-normal text-gray-500 ml-2">(optionnel)</span>
                </label>
                <div class="relative">
                    <textarea name="resume" 
                              id="resume" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white placeholder-gray-400"
                              placeholder="Rédigez un résumé de l'actualité en deux paragraphes pour donner un aperçu du contenu..."
                              aria-describedby="resume-description error-resume">{{ old('resume', $actualite->resume ?? '') }}</textarea>
                </div>
                <p id="resume-description" class="text-xs text-gray-500 mt-2">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <strong>Recommandation :</strong> Rédigez environ deux paragraphes pour présenter l'essentiel de votre actualité. Ce résumé apparaîtra dans les listes et aperçus.
                </p>
                <p class="text-red-500 text-sm mt-2 hidden flex items-center" id="error-resume" role="alert" aria-live="polite">
                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span id="error-resume-message">Ce champ contient des erreurs.</span>
                </p>
                @error('resume')
                    <p class="text-red-500 text-sm mt-2 flex items-center" role="alert">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Contenu Principal -->
            <div class="group">
                <label for="texte" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    Contenu de l'actualité *
                </label>
                
                <!-- Bouton pour ouvrir la médiathèque -->
                <div class="mb-3 flex justify-end">
                    <button type="button" 
                            onclick="openMediaModal()" 
                            class="inline-flex items-center px-3 py-2 border border-iri-primary text-sm font-medium rounded-md text-iri-primary bg-white hover:bg-iri-primary hover:text-white transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Insérer un média
                    </button>
                </div>
                
                <div class="relative">
                    <textarea name="texte" 
                              id="texte" 
                              class="wysiwyg w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white placeholder-gray-400 lg:min-h-[400px]"
                              rows="12"
                              placeholder="Rédigez le contenu principal de votre actualité..."
                              aria-describedby="texte-description error-texte">{{ old('texte', $actualite->texte ?? '') }}</textarea>
                </div>
                <p id="texte-description" class="text-xs text-gray-500 mt-2">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Utilisez l'éditeur de texte enrichi pour formatter votre contenu avec des titres, listes, liens, etc. (obligatoire)
                </p>
                <p class="text-red-500 text-sm mt-2 hidden flex items-center" id="error-texte" role="alert" aria-live="polite">
                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Ce champ est requis.
                </p>
                @error('texte')
                    <p class="text-red-500 text-sm mt-2 flex items-center" role="alert">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Image avec Drag & Drop amélioré -->
            <div class="group">
                <label for="image" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Image d'illustration
                    <span class="text-xs font-normal text-gray-500 ml-2">(optionnelle)</span>
                </label>
                
                <div class="flex items-start space-x-6">
                    <div class="flex-1">
                        <div id="image-drop-zone" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-iri-primary transition-colors duration-200 bg-gray-50">
                            <input type="file" 
                                   id="image"
                                   name="image" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   accept="image/jpeg,image/png,image/gif,image/webp"
                                   aria-describedby="image-description">
                            
                            <div class="space-y-3">
                                <svg class="w-8 h-8 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600 font-medium">Cliquez pour choisir ou glissez-déposez</p>
                                    <p class="text-xs text-gray-500">JPG, PNG, GIF ou WebP (max 10MB)</p>
                                </div>
                            </div>
                        </div>
                        
                        <p id="image-description" class="text-xs text-gray-500 mt-2">
                            L'image sera automatiquement compressée et optimisée pour le web
                        </p>
                    </div>
                    
                    <!-- Aperçu de l'image amélioré -->
                    <div class="flex-shrink-0">
                        @if(isset($actualite) && $actualite->image && Storage::disk('public')->exists($actualite->image))
                            <div class="relative group">
                                <img id="image-preview-actualite" 
                                     src="{{ asset('storage/' . $actualite->image) }}" 
                                     class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm" 
                                     alt="Aperçu de l'image actuelle"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <button type="button" onclick="removeImagePreview()" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors duration-200"
                                        title="Supprimer l'image">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <div id="image-placeholder-actualite" class="w-32 h-32 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <img id="image-preview-actualite" 
                                 class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm hidden" 
                                 alt="Aperçu de l'image sélectionnée">
                        @endif
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-2 flex items-center" role="alert">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Options de publication -->
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                    </svg>
                    Options de publication
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- À la une -->
                    <div class="relative">
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-white hover:shadow-sm transition-all duration-200 {{ old('a_la_une', $actualite->a_la_une ?? false) ? 'bg-iri-light border-iri-primary' : 'bg-white' }}">
                            <input type="checkbox" 
                                   name="a_la_une" 
                                   id="a_la_une" 
                                   class="sr-only" 
                                   value="1"
                                   {{ old('a_la_une', $actualite->a_la_une ?? false) ? 'checked' : '' }}>
                            <div class="flex-shrink-0">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center {{ old('a_la_une', $actualite->a_la_une ?? false) ? 'bg-iri-primary border-iri-primary' : '' }}">
                                    <svg class="w-3 h-3 text-white {{ old('a_la_une', $actualite->a_la_une ?? false) ? 'block' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">À la une</span>
                                <p class="text-xs text-gray-500">Afficher en première position</p>
                            </div>
                        </label>
                    </div>

                    <!-- En vedette -->
                    <div class="relative">
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-white hover:shadow-sm transition-all duration-200 {{ old('en_vedette', $actualite->en_vedette ?? false) ? 'bg-iri-light border-iri-primary' : 'bg-white' }}">
                            <input type="checkbox" 
                                   name="en_vedette" 
                                   id="en_vedette" 
                                   class="sr-only" 
                                   value="1"
                                   {{ old('en_vedette', $actualite->en_vedette ?? false) ? 'checked' : '' }}>
                            <div class="flex-shrink-0">
                                <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center {{ old('en_vedette', $actualite->en_vedette ?? false) ? 'bg-iri-primary border-iri-primary' : '' }}">
                                    <svg class="w-3 h-3 text-white {{ old('en_vedette', $actualite->en_vedette ?? false) ? 'block' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">En vedette</span>
                                <p class="text-xs text-gray-500">Mettre en valeur cette actualité</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.actualite.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Annuler
                </a>
                
                <button type="submit" 
                        style="pointer-events: auto !important; z-index: 999 !important;"
                        class="inline-flex items-center px-8 py-3 border border-transparent rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ isset($actualite) ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modal de sélection de médias pour CKEditor -->
<style>
#mediaModal {
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    animation: fadeIn 0.3s ease-out;
}

#mediaModal.hidden {
    display: none !important;
}

#mediaModal .bg-white {
    animation: slideIn 0.3s ease-out;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-open {
    position: fixed !important;
    width: 100% !important;
    overflow: hidden !important;
}
</style>

<!-- Modale Médiathèque -->
<div id="mediaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b flex items-center justify-between bg-gradient-to-r from-iri-primary to-iri-secondary flex-shrink-0">
            <h3 id="modal-title" class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Médiathèque
            </h3>
            <button type="button" 
                    id="modal-close-btn"
                    class="text-white hover:text-gray-200 transition-colors p-1 rounded focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Fermer la médiathèque">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6 flex-1 overflow-y-auto">
            <!-- Upload d'image avec barre de progression -->
            <div class="mb-6">
                <div id="upload-drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-iri-primary transition-colors">
                    <input type="file" 
                           id="mediaUploadInput" 
                           accept="image/jpeg,image/png,image/gif,image/webp" 
                           multiple
                           class="hidden"
                           aria-describedby="upload-description">
                    <button type="button" 
                            id="media-upload-btn"
                            class="w-full p-4 text-center hover:bg-gray-50 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-iri-primary">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm text-gray-600 font-medium">Télécharger des images</p>
                        <p id="upload-description" class="text-xs text-gray-500">JPG, PNG, GIF ou WebP (max 10MB par fichier)</p>
                    </button>
                </div>
                
                <!-- Barre de progression pour upload -->
                <div id="upload-progress" class="hidden mt-3">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                        <span id="upload-status">Upload en cours...</span>
                        <span id="upload-percentage">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="upload-bar" class="bg-iri-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Liste des images existantes avec pagination -->
            <div class="border-t border-gray-200 pt-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-medium text-gray-700">Images disponibles</h4>
                    <div class="flex items-center space-x-2">
                        <input type="text" 
                               id="media-search" 
                               placeholder="Rechercher..." 
                               class="px-3 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-iri-primary focus:border-iri-primary"
                               aria-label="Rechercher dans les médias">
                        <button type="button" 
                                id="media-refresh-btn"
                                class="p-1 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-iri-primary rounded"
                                title="Actualiser la liste">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div id="mediaList" class="grid grid-cols-2 md:grid-cols-4 gap-3" role="grid" aria-label="Liste des images disponibles">
                    <div class="col-span-full text-center text-gray-500 py-8">
                        <svg class="w-8 h-8 animate-spin mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <p>Chargement des médias...</p>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div id="media-pagination" class="hidden mt-4 flex justify-center">
                    <nav class="flex items-center space-x-2" aria-label="Pagination de la médiathèque">
                        <!-- Sera rempli dynamiquement -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CKEditor Configuration (doit être chargé avant le script du formulaire) -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/translations/fr.js"></script>

<!-- Script de compression d'images -->
<script src="{{ asset('assets/js/image-compressor.js') }}"></script>

<script>
// Configuration CKEditor spécifique pour le formulaire actualité
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initialisation CKEditor pour actualité...');
    
    const texteElement = document.querySelector('#texte');
    
    if (!texteElement) {
        console.error('Élément #texte non trouvé pour CKEditor');
        return;
    }
    
    ClassicEditor
        .create(texteElement, {
            language: 'fr',
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'undo', 'redo', '|',
                'link', 'insertTable', '|',
                'alignment', '|',
                'fontColor', 'fontBackgroundColor', '|',
                'fontSize', '|',
                'specialCharacters'
            ],
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side'
                ]
            }
        })
        .then(editor => {
            console.log('CKEditor initialisé avec succès pour Actualité');
            
            // Associer l'éditeur à l'élément
            texteElement.ckeditorInstance = editor;
            window.texteEditor = editor;
            
            // Style personnalisé pour CKEditor
            editor.editing.view.change(writer => {
                writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
            });

            // Ajouter le bouton de médiathèque à la toolbar
            addMediaLibraryButton(editor);

            // Configurer l'adaptateur d'upload personnalisé
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MediaUploadAdapter(loader);
            };
        })
        .catch(error => {
            console.error('Erreur CKEditor:', error);
        });
});
// Ajouter un bouton personnalisé pour la médiathèque dans CKEditor
function addMediaLibraryButton(editor) {
    const toolbar = editor.ui.view.toolbar;
    const mediaButton = document.createElement('button');
    mediaButton.type = 'button';
    mediaButton.className = 'ck ck-button ck-off';
    mediaButton.innerHTML = `
        <svg class="ck ck-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="ck ck-button__label">Médiathèque</span>
    `;
    mediaButton.title = 'Médiathèque';
    
    mediaButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        openMediaModal(function(url) {
            try {
                editor.model.change(writer => {
                    const imageElement = writer.createElement('imageBlock', { src: url });
                    editor.model.insertContent(imageElement, editor.model.document.selection);
                });
            } catch (error) {
                console.error('Erreur lors de l\'insertion:', error);
            }
        });
        
        return false;
    });
    
    // Insérer le bouton dans la toolbar
    setTimeout(() => {
        const toolbarElement = toolbar.element.querySelector('.ck-toolbar__items');
        if (toolbarElement) {
            // Ajouter un séparateur
            const separator = document.createElement('span');
            separator.className = 'ck ck-toolbar__separator';
            toolbarElement.appendChild(separator);
            
            toolbarElement.appendChild(mediaButton);
        }
    }, 100);
}

// Ouvrir la modal de médiathèque
function openMediaModal(callback) {
    console.log('=== DÉBUT openMediaModal ===');
    
    // Sauvegarder le callback globalement pour l'upload
    window.currentMediaCallback = callback;
    
    const modal = document.getElementById('mediaModal');
    if (!modal) {
        console.error('Modal mediaModal non trouvé !');
        return;
    }
    
    console.log('Modal trouvé, ouverture...');
    
    // Sauvegarder la position de scroll actuelle
    const scrollY = window.scrollY;
    
    // S'assurer que le modal est centré par rapport au viewport
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '9999';
    modal.style.margin = '0';
    modal.style.padding = '1rem';
    
    // Empêcher le scroll du body
    document.body.classList.add('modal-open');
    document.body.style.top = `-${scrollY}px`;
    
    modal.classList.remove('hidden');
    window._mediaInsertCallback = callback;
    
    // Empêcher la fermeture automatique du modal
    if (window.event) {
        window.event.stopPropagation();
        window.event.preventDefault();
    }
    
    console.log('Chargement de la liste des médias...');
    loadMediaList(callback);
    console.log('=== FIN openMediaModal ===');
}

function closeMediaModal() {
    console.log('=== FERMETURE du modal ===');
    const modal = document.getElementById('mediaModal');
    if (modal) {
        // Récupérer la position de scroll sauvegardée
        const scrollY = document.body.style.top;
        
        modal.classList.add('hidden');
        modal.style.display = 'none';
        
        // Restaurer le scroll du body
        document.body.classList.remove('modal-open');
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        document.body.style.overflow = '';
        
        // Restaurer la position de scroll
        if (scrollY) {
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
        }
        
        window._mediaInsertCallback = null;
    }
}

// Charger la liste des images depuis le backend




// Adaptateur d'upload personnalisé pour CKEditor
class MediaUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('image', file);
                
                // Récupérer le token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value 
                    || '{{ csrf_token() }}';
                
                formData.append('_token', csrfToken);
                
                fetch('{{ route("admin.media.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        resolve({
                            default: result.url
                        });
                    } else {
                        reject(result.message || 'Erreur lors de l\'upload');
                    }
                })
                .catch(error => {
                    console.error('Erreur upload:', error);
                    reject('Erreur réseau lors de l\'upload');
                });
            }));
    }

    abort() {
        // Gérer l'annulation de l'upload si nécessaire
    }
}

// Gestion de l'upload dans la modal
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('mediaUploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = this.querySelector('input[name="image"]');
            const originalFile = fileInput.files[0];
            
            if (!originalFile) {
                alert('Veuillez sélectionner un fichier');
                return;
            }
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            try {
                // Désactiver le bouton et afficher l'état de compression
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Compression...';
                
                // Compresser l'image si c'est une image
                let fileToUpload = originalFile;
                if (originalFile.type.startsWith('image/')) {
                    fileToUpload = await defaultCompressor.compressImage(originalFile);
                    console.log('Compression terminée:', {
                        originalSize: formatFileSize(originalFile.size),
                        compressedSize: formatFileSize(fileToUpload.size)
                    });
                }
                
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Upload...';
                
                const formData = new FormData();
                formData.append('image', fileToUpload);
                formData.append('_token', '{{ csrf_token() }}');
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Recharger la liste des médias
                    loadMediaList(window.currentMediaCallback);
                    // Réinitialiser le formulaire
                    this.reset();
                    
                    // Message de succès avec info de compression
                    let message = 'Image uploadée avec succès !';
                    if (originalFile.type.startsWith('image/') && fileToUpload !== originalFile) {
                        const info = defaultCompressor.getCompressionInfo(originalFile, fileToUpload);
                        message += ` (Compressée: ${info.compressionRatio.toFixed(1)}% d'économie)`;
                    }
                    alert(message);
                } else {
                    alert('Erreur lors de l\'upload: ' + result.message);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'upload');
            } finally {
                // Réactiver le bouton
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });
    }

    // Gestionnaire d'événements pour fermer le modal media
    const modalCloseBtn = document.getElementById('modal-close-btn');
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', function() {
            closeMediaModal();
        });
    }

    // Fermer le modal en cliquant à l'extérieur
    const modal = document.getElementById('mediaModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeMediaModal();
            }
        });
    }

    // Fermer le modal avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('mediaModal').classList.contains('hidden')) {
            closeMediaModal();
        }
    });

    // Gestion des checkboxes personnalisés
    function initCustomCheckboxes() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"].sr-only');
        
        checkboxes.forEach(function(checkbox) {
            const label = checkbox.closest('label');
            const visualCheckbox = label.querySelector('div > div');
            const checkIcon = label.querySelector('svg');
            
            // Fonction pour mettre à jour l'apparence visuelle
            function updateVisualState() {
                if (checkbox.checked) {
                    label.classList.add('bg-iri-light', 'border-iri-primary');
                    label.classList.remove('bg-white');
                    visualCheckbox.classList.add('bg-iri-primary', 'border-iri-primary');
                    visualCheckbox.classList.remove('border-gray-300');
                    checkIcon.classList.remove('hidden');
                    checkIcon.classList.add('block');
                } else {
                    label.classList.remove('bg-iri-light', 'border-iri-primary');
                    label.classList.add('bg-white');
                    visualCheckbox.classList.remove('bg-iri-primary', 'border-iri-primary');
                    visualCheckbox.classList.add('border-gray-300');
                    checkIcon.classList.add('hidden');
                    checkIcon.classList.remove('block');
                }
            }
            
            // Mettre à jour l'état initial
            updateVisualState();
            
            // Écouter les clics sur le label
            label.addEventListener('click', function(e) {
                e.preventDefault();
                checkbox.checked = !checkbox.checked;
                updateVisualState();
                
                // Déclencher l'événement change pour la compatibilité avec d'autres scripts
                const changeEvent = new Event('change', { bubbles: true });
                checkbox.dispatchEvent(changeEvent);
            });
            
            // Écouter les changements programmatiques
            checkbox.addEventListener('change', function() {
                updateVisualState();
            });
        });
    }
    
    // Initialiser les checkboxes
    initCustomCheckboxes();
});

// Fonction pour charger la liste des médias
function loadMediaList(callback) {
    fetch('{{ route("admin.media.list") }}')
        .then(response => response.json())
        .then(medias => {
            const grid = document.getElementById('mediaList');
            grid.innerHTML = '';

            if (medias.length === 0) {
                grid.innerHTML = '<p class="text-gray-500 text-center col-span-full">Aucun média disponible.</p>';
                return;
            }

            medias.forEach(media => {
                const div = document.createElement('div');
                div.className = 'relative cursor-pointer border-2 border-gray-200 rounded-lg overflow-hidden hover:border-iri-primary transition-colors';
                div.onclick = () => {
                    if (callback) {
                        callback(media.url);
                        closeMediaModal();
                    }
                };

                div.innerHTML = `
                    <img src="${media.url}" alt="${media.alt || media.name}" class="w-full h-24 object-cover">
                    <div class="p-2">
                        <p class="text-xs text-gray-600 truncate">${media.name}</p>
                    </div>
                `;

                grid.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des médias:', error);
            document.getElementById('mediaList').innerHTML = '<p class="text-red-500 text-center col-span-full">Erreur lors du chargement.</p>';
        });
}

// Gestion de l'aperçu d'image instantané
function setupImagePreview() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview-actualite');
    const imagePlaceholder = document.getElementById('image-placeholder-actualite');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    if (imagePlaceholder) {
                        imagePlaceholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// Gestion drag & drop pour l'image
function setupDragDrop() {
    const dropZone = document.getElementById('image-drop-zone');
    const imageInput = document.getElementById('image');
    
    if (dropZone && imageInput) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('border-iri-primary', 'bg-iri-light');
        });
        
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-iri-primary', 'bg-iri-light');
        });
        
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-iri-primary', 'bg-iri-light');
            
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type.startsWith('image/')) {
                imageInput.files = files;
                // Déclencher l'événement change pour l'aperçu
                const event = new Event('change', { bubbles: true });
                imageInput.dispatchEvent(event);
            }
        });
    }
}

// Fonction pour supprimer l'aperçu d'image
function removeImagePreview() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview-actualite');
    const imagePlaceholder = document.getElementById('image-placeholder-actualite');
    
    if (imageInput) imageInput.value = '';
    if (imagePreview) {
        imagePreview.src = '';
        imagePreview.classList.add('hidden');
    }
    if (imagePlaceholder) {
        imagePlaceholder.classList.remove('hidden');
    }
}

// Validation du formulaire en temps réel
function setupFormValidation() {
    const form = document.getElementById('actualiteForm');
    const titreInput = document.getElementById('titre');
    const texteInput = document.getElementById('texte');
    const submitButton = document.querySelector('button[type="submit"]');
    
    console.log('Setup validation du formulaire:', {form: !!form, titreInput: !!titreInput, submitButton: !!submitButton});
    
    // Ajouter un listener sur le bouton submit pour debug
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            console.log('=== CLIC SUR BOUTON ENREGISTRER ===');
            console.log('Événement:', e);
        });
    }
    
    if (form) {
        // Validation en temps réel du titre
        if (titreInput) {
            titreInput.addEventListener('blur', function() {
                const errorElement = document.getElementById('error-titre');
                if (!this.value.trim()) {
                    errorElement.classList.remove('hidden');
                } else {
                    errorElement.classList.add('hidden');
                }
            });
        }
        
        // Validation à la soumission
        form.addEventListener('submit', function(e) {
            console.log('=== SOUMISSION DU FORMULAIRE ===');
            let isValid = true;
            let errors = [];
            
            // Vérifier le titre
            if (titreInput && !titreInput.value.trim()) {
                document.getElementById('error-titre').classList.remove('hidden');
                errors.push('Titre requis');
                isValid = false;
            } else {
                document.getElementById('error-titre').classList.add('hidden');
            }
            
            // Vérifier le contenu CKEditor (moins strict)
            if (window.texteEditor) {
                const content = window.texteEditor.getData();
                console.log('Contenu CKEditor:', content.substring(0, 100) + '...');
                if (!content.trim() || content.trim() === '<p>&nbsp;</p>' || content.trim() === '<p></p>') {
                    document.getElementById('error-texte').classList.remove('hidden');
                    errors.push('Contenu requis');
                    isValid = false;
                } else {
                    document.getElementById('error-texte').classList.add('hidden');
                }
            } else {
                console.log('CKEditor non initialisé, vérification du champ texte standard');
                // Fallback sur le textarea standard si CKEditor n'est pas initialisé
                const texteTextarea = document.getElementById('texte');
                if (texteTextarea && !texteTextarea.value.trim()) {
                    errors.push('Contenu requis (textarea)');
                    isValid = false;
                } else {
                    console.log('Textarea contenu OK:', texteTextarea?.value.substring(0, 50) + '...');
                    // Si on a du contenu dans le textarea, on considère que c'est valide
                    document.getElementById('error-texte').classList.add('hidden');
                }
            }
            
            console.log('Validation résultat:', {isValid, errors});
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez corriger les erreurs:\n- ' + errors.join('\n- '));
                console.log('Soumission bloquée par validation');
                return false;
            }
            
            // AJOUT: Forcer la soumission si CKEditor n'est pas initialisé
            if (!window.texteEditor && document.getElementById('texte').value.trim()) {
                console.log('Forçage soumission sans CKEditor');
                return true; // Laisser la soumission continuer
            }
            
            console.log('Validation OK, soumission autorisée');
            // Laisser le formulaire se soumettre normalement
        });
    }
}

// Initialisation complète
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== INITIALISATION FORMULAIRE ACTUALITÉ ===');
    
    try {
        // Debug des éléments principaux
        const form = document.getElementById('actualiteForm');
        const submitButton = document.querySelector('button[type="submit"]');
        const titreInput = document.getElementById('titre');
        
        console.log('Éléments trouvés:', {
            form: !!form,
            submitButton: !!submitButton,
            titreInput: !!titreInput,
            formAction: form?.action,
            formMethod: form?.method
        });
        
        // SOLUTION: UN SEUL HANDLER PROPRE sur le bouton
        if (submitButton) {
            console.log('Ajout du handler unique sur le bouton...');
            
            // SUPPRIMER tous les handlers existants d'abord
            submitButton.onclick = null;
            
            // UN SEUL handler clair et simple
            submitButton.addEventListener('click', function(e) {
                console.log('=== CLICK SUR BOUTON ENREGISTRER ===');
                console.log('Form action:', form?.action);
                console.log('Event:', e);
                
                // Validation du titre
                const titre = document.getElementById('titre').value.trim();
                if (!titre) {
                    alert('Le titre est requis');
                    e.preventDefault();
                    return false;
                }
                
                // Validation du contenu CKEditor
                let contenuValide = false;
                if (window.texteEditor) {
                    const contenu = window.texteEditor.getData().trim();
                    contenuValide = contenu && contenu !== '<p>&nbsp;</p>' && contenu !== '<p></p>';
                    console.log('Contenu CKEditor:', contenu.substring(0, 100) + '...');
                    
                    // Synchroniser avec le textarea caché
                    document.getElementById('texte').value = contenu;
                } else {
                    // Fallback sur le textarea standard
                    const texteArea = document.getElementById('texte');
                    contenuValide = texteArea.value.trim().length > 0;
                    console.log('Contenu textarea:', texteArea.value.substring(0, 100) + '...');
                }
                
                if (!contenuValide) {
                    alert('Le contenu de l\'actualité est requis');
                    e.preventDefault();
                    return false;
                }
                
                console.log('Validation OK, soumission autorisée');
                // Laisser l'événement continuer normalement vers le form
                return true;
            });
        } else {
            console.error('BOUTON SUBMIT NON TROUVÉ !');
        }
        
        // Handler simple du formulaire
        if (form) {
            // UN SEUL handler de formulaire
            form.addEventListener('submit', function(e) {
                console.log('=== FORM SUBMIT EVENT ===');
                console.log('Action:', this.action);
                console.log('Method:', this.method);
                
                // Synchroniser CKEditor avec le textarea avant soumission
                if (window.texteEditor) {
                    const contenu = window.texteEditor.getData();
                    document.getElementById('texte').value = contenu;
                    console.log('Contenu synchronisé avec textarea');
                }
                
                // Validation finale minimale
                const titre = document.getElementById('titre').value.trim();
                const texte = document.getElementById('texte').value.trim();
                
                if (!titre) {
                    e.preventDefault();
                    alert('Le titre est requis');
                    return false;
                }
                
                if (!texte || texte === '<p>&nbsp;</p>' || texte === '<p></p>') {
                    e.preventDefault();
                    alert('Le contenu est requis');
                    return false;
                }
                
                console.log('Form validation OK - soumission autorisée');
                // Laisser la soumission continuer normalement
                return true;
            });
        } else {
            console.error('FORMULAIRE NON TROUVÉ !');
        }
        
        // Désactiver temporairement la validation complexe
        console.log('Initialisation des autres fonctions...');
        setupImagePreview();
        setupDragDrop();
        // TEMPORAIREMENT DÉSACTIVÉ: setupFormValidation();
        
        console.log('=== INITIALISATION TERMINÉE ===');
        
    } catch (error) {
        console.error('ERREUR LORS DE L\'INITIALISATION:', error);
    }
});

</script>
