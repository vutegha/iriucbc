<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- En-tête du formulaire -->
    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-8 py-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-white">
                    {{ isset($actualite) ? 'Modifier l\'actualité' : 'Nouvelle actualité' }}
                </h2>
                <p class="text-iri-light/80 text-sm mt-1">
                    {{ isset($actualite) ? 'Modifiez les informations de cette actualité' : 'Créez une nouvelle actualité pour votre site' }}
                </p>
            </div>
        </div>
    </div>

    <form id="actualiteForm" action="{{ $formAction ?? '#' }}" method="POST" enctype="multipart/form-data" class="p-8">
        @csrf
        @if(isset($actualite)) @method('PUT') @endif

        <!-- Section Contenu Principal -->
        <div class="space-y-8">
            <!-- Titre -->
            <div class="group">
                <label for="titre" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                           required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-iri-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-red-500 text-sm mt-2 hidden flex items-center" id="error-titre">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
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
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Résumé
                </label>
                <div class="relative">
                    <textarea name="resume" 
                              id="resume" 
                              rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white placeholder-gray-400 resize-none"
                              placeholder="Rédigez un bref résumé de l'actualité...">{{ old('resume', $actualite->resume ?? '') }}</textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                        <span id="resume-count">0</span>/200 caractères
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

            <!-- Contenu Principal -->
            <div class="group">
                <label for="texte" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                    <svg class="w-4 h-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    Contenu de l'actualité *
                    <button type="button" id="testMediaButton" class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                        Test Médiathèque
                    </button>
                </label>
                <div class="relative">
                    <textarea name="texte" 
                              id="texte" 
                              class="wysiwyg w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white placeholder-gray-400 lg:min-h-[400px]"
                              rows="12"
                              placeholder="Rédigez le contenu principal de votre actualité...">{{ old('texte', $actualite->texte ?? '') }}</textarea>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Utilisez l'éditeur de texte enrichi pour formatter votre contenu avec des titres, listes, liens, etc.
                </p>
                <p class="text-red-500 text-sm mt-2 hidden flex items-center" id="error-texte">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Ce champ est requis.
                </p>
                @error('texte')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Image -->
            <div class="group">
                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image text-olive mr-2"></i>Image d'illustration
                    <span class="text-xs font-normal text-gray-500">(optionnelle)</span>
                </label>
                
                <div class="flex items-start space-x-6">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="file" 
                                   id="image"
                                   name="image" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-olive/10 file:text-olive hover:file:bg-olive/20"
                                   accept="image/*"
                                   onchange="previewImageActualite(this)">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Formats acceptés: JPG, PNG, GIF (max 10MB)
                        </p>
                    </div>
                    
                    <!-- Aperçu de l'image -->
                    <div class="flex-shrink-0">
                        @if(isset($actualite) && $actualite->image && Storage::disk('public')->exists($actualite->image))
                            <div class="relative group">
                                <img id="image-preview-actualite" 
                                     src="{{ asset('storage/' . $actualite->image) }}" 
                                     class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm" 
                                     alt="Aperçu"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-eye text-white text-lg"></i>
                                </div>
                            </div>
                        @else
                            <div id="image-placeholder-actualite" class="w-24 h-24 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            <img id="image-preview-actualite" 
                                 class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm hidden" 
                                 alt="Aperçu">
                        @endif
                    </div>
                </div>
                @error('image')
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('actualiteForm');
    const titreField = document.getElementById('titre');
    const resumeField = document.getElementById('resume');
    const texteField = document.getElementById('texte');
    const imageField = document.getElementById('image');
    
    // Compteurs de caractères
    function updateCharacterCount(field, countElement, maxLength = null) {
        const count = field.value.length;
        const countSpan = document.getElementById(countElement);
        if (countSpan) {
            countSpan.textContent = count;
            if (maxLength && count > maxLength) {
                countSpan.parentElement.classList.add('text-red-500');
                countSpan.parentElement.classList.remove('text-gray-400');
            } else {
                countSpan.parentElement.classList.remove('text-red-500');
                countSpan.parentElement.classList.add('text-gray-400');
            }
        }
    }
    
    // Initialiser les compteurs
    if (resumeField) {
        updateCharacterCount(resumeField, 'resume-count', 200);
        resumeField.addEventListener('input', () => updateCharacterCount(resumeField, 'resume-count', 200));
    }
    
    // Note: Le champ texte utilise maintenant CKEditor, pas de compteur de caractères nécessaire
    
    // Gestion des checkboxes customisées
    function handleCustomCheckbox(checkboxId) {
        const checkbox = document.getElementById(checkboxId);
        if (!checkbox) {
            console.error('Checkbox not found:', checkboxId);
            return;
        }
        
        const label = checkbox.closest('label');
        const indicator = label.querySelector('.flex-shrink-0 > div');
        const checkIcon = indicator.querySelector('svg');
        
        console.log('Initializing checkbox:', checkboxId, 'checked:', checkbox.checked);
        
        function updateCheckboxAppearance() {
            console.log('Updating appearance for:', checkboxId, 'checked:', checkbox.checked);
            
            if (checkbox.checked) {
                label.classList.add('bg-iri-light', 'border-iri-primary');
                label.classList.remove('bg-white', 'border-gray-200');
                indicator.classList.add('bg-iri-primary', 'border-iri-primary');
                indicator.classList.remove('border-gray-300');
                checkIcon.classList.remove('hidden');
                checkIcon.classList.add('block');
            } else {
                label.classList.remove('bg-iri-light', 'border-iri-primary');
                label.classList.add('bg-white', 'border-gray-200');
                indicator.classList.remove('bg-iri-primary', 'border-iri-primary');
                indicator.classList.add('border-gray-300');
                checkIcon.classList.add('hidden');
                checkIcon.classList.remove('block');
            }
        }
        
        // Initialiser l'apparence basée sur l'état actuel
        updateCheckboxAppearance();
        
        // Écouter les changements du checkbox
        checkbox.addEventListener('change', updateCheckboxAppearance);
        
        // Gérer les clics sur le label
        label.addEventListener('click', function(e) {
            // Empêcher la double activation si on clique directement sur le checkbox
            if (e.target === checkbox) return;
            
            e.preventDefault();
            checkbox.checked = !checkbox.checked;
            
            // Déclencher l'événement change manuellement
            const changeEvent = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(changeEvent);
        });
    }
    
    // Initialiser les checkboxes personnalisées
    handleCustomCheckbox('a_la_une');
    handleCustomCheckbox('en_vedette');
    
    // Gestion du drag & drop pour l'image
    const imageDropZone = imageField.closest('.border-dashed');
    
    if (imageDropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            imageDropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            imageDropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            imageDropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            imageDropZone.classList.add('border-iri-primary', 'bg-iri-light');
            imageDropZone.classList.remove('border-gray-300');
        }
        
        function unhighlight() {
            imageDropZone.classList.remove('border-iri-primary', 'bg-iri-light');
            imageDropZone.classList.add('border-gray-300');
        }
        
        imageDropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                imageField.files = files;
                updateImagePreview(files[0]);
            }
        }
    }
    
    // Prévisualisation de l'image
    imageField.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            updateImagePreview(e.target.files[0]);
        }
    });
    
    function updateImagePreview(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Créer un aperçu simple
                const preview = document.createElement('div');
                preview.innerHTML = `
                    <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-green-700">
                                    Nouvelle image sélectionnée
                                </p>
                                <p class="text-sm text-green-600 truncate">
                                    ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                
                // Trouver le conteneur parent de l'image
                const imageContainer = imageField.closest('.group').querySelector('.space-y-4');
                
                // Supprimer l'ancien aperçu s'il existe
                const existingPreview = imageContainer.querySelector('.image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                preview.classList.add('image-preview');
                imageContainer.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validation du titre
        const errorTitre = document.getElementById('error-titre');
        if (!titreField.value.trim()) {
            errorTitre.classList.remove('hidden');
            titreField.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            titreField.classList.remove('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
            isValid = false;
        } else {
            errorTitre.classList.add('hidden');
            titreField.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            titreField.classList.add('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
        }
        
        // Validation du texte
        const errorTexte = document.getElementById('error-texte');
        if (!texteField.value.trim()) {
            errorTexte.classList.remove('hidden');
            texteField.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            texteField.classList.remove('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
            isValid = false;
        } else {
            errorTexte.classList.add('hidden');
            texteField.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            texteField.classList.add('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
        }
        
        // Validation de la longueur du résumé
        if (resumeField.value.length > 200) {
            isValid = false;
            resumeField.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            resumeField.classList.remove('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
        } else {
            resumeField.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            resumeField.classList.add('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
        }
        
        if (!isValid) {
            e.preventDefault();
            
            // Scroll vers la première erreur
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
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
});
</script>

<style>
/* Styles pour assurer le centrage parfait du modal par rapport au viewport */
#mediaModal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 9999 !important;
    margin: 0 !important;
    padding: 1rem !important;
    box-sizing: border-box !important;
    background-color: rgba(0, 0, 0, 0.5) !important;
}

#mediaModal.hidden {
    display: none !important;
}

#mediaModal .bg-white {
    position: relative !important;
    margin: 0 !important;
    max-width: 40rem !important; /* max-w-2xl equivalent */
    width: 100% !important;
    max-height: 90vh !important;
    overflow-y: auto !important;
    transform: none !important;
    top: auto !important;
    left: auto !important;
    right: auto !important;
    bottom: auto !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
}

/* Empêcher le scroll du body quand le modal est ouvert */
body.modal-open {
    overflow: hidden !important;
    position: fixed !important;
    width: 100% !important;
}
</style>

<!-- Modale Médiathèque -->
<div id="mediaModal" class="hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b flex items-center justify-between bg-gradient-to-r from-iri-primary to-iri-secondary">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-photo-video mr-3"></i>
                Médiathèque
            </h3>
            <button type="button" onclick="closeMediaModal()" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <!-- Upload d'image -->
            <form id="mediaUploadForm" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6 flex items-center space-x-4">
                @csrf
                <input type="file" name="image" id="mediaUploadInput" accept="image/*" class="border rounded p-2">
                <button type="submit" class="px-4 py-2 bg-iri-primary text-white rounded hover:bg-iri-secondary transition">Uploader</button>
            </form>
            <!-- Liste des images existantes -->
            <div id="mediaList" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="col-span-full text-center text-gray-500 py-8">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Chargement des médias...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
// Variables globales
let editorInstance = null;

// Ouvre la modale médiathèque
function openMediaModal(callback) {
    console.log('=== DÉBUT openMediaModal ===');
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
    loadMediaList();
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
function loadMediaList() {
    console.log('Chargement de la liste des médias...');
    fetch("{{ route('admin.media.list') }}")
        .then(res => {
            console.log('Réponse reçue:', res.status);
            if (!res.ok) {
                throw new Error('Erreur réseau: ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            const mediaList = document.getElementById('mediaList');
            mediaList.innerHTML = '';
            
            if (data.length === 0) {
                mediaList.innerHTML = '<p class="text-gray-500 text-center col-span-full">Aucune image disponible</p>';
                return;
            }
            
            data.forEach((media, index) => {
                console.log('Traitement du média:', media);
                const div = document.createElement('div');
                div.className = 'border rounded-lg overflow-hidden cursor-pointer hover:shadow-lg transition duration-200';
                div.innerHTML = `
                    <img src="${media.url}" 
                         alt="${media.name}" 
                         class="w-full h-24 object-cover"
                         onerror="console.error('Erreur de chargement image:', this.src); this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBzdHJva2U9IiNjY2MiIGZpbGw9IiNmZmYiLz4KPHN2Zz4K';">
                    <div class="p-2">
                        <p class="text-xs text-gray-600 truncate">${media.name}</p>
                    </div>
                `;
                div.onclick = function(e) {
                    e.stopPropagation();
                    console.log('Image sélectionnée:', media.url);
                    if(window._mediaInsertCallback) {
                        window._mediaInsertCallback(media.url);
                    }
                    closeMediaModal();
                };
                mediaList.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des médias:', error);
            const mediaList = document.getElementById('mediaList');
            mediaList.innerHTML = `<p class="text-red-500 text-center col-span-full">Erreur lors du chargement: ${error.message}</p>`;
        });
}

// Upload d'image dans la médiathèque
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('mediaUploadForm');
    if(uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Upload en cours...');
            const formData = new FormData(uploadForm);
            const submitButton = uploadForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Désactiver le bouton pendant l'upload
            submitButton.disabled = true;
            submitButton.textContent = 'Upload...';
            
            fetch(uploadForm.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                console.log('Réponse upload:', res.status);
                if (!res.ok) {
                    throw new Error('Erreur upload: ' + res.status);
                }
                return res.json();
            })
            .then(data => {
                console.log('Upload réussi:', data);
                if(data.success) {
                    loadMediaList(); // Recharger la liste
                    uploadForm.reset(); // Vider le formulaire
                    
                    // Afficher un message de succès
                    const successMsg = document.createElement('div');
                    successMsg.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
                    successMsg.textContent = data.message || 'Image uploadée avec succès';
                    uploadForm.parentNode.insertBefore(successMsg, uploadForm);
                    
                    // Supprimer le message après 3 secondes
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'upload');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'upload:', error);
                // Afficher un message d'erreur
                const errorMsg = document.createElement('div');
                errorMsg.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
                errorMsg.textContent = 'Erreur: ' + error.message;
                uploadForm.parentNode.insertBefore(errorMsg, uploadForm);
                
                // Supprimer le message après 5 secondes
                setTimeout(() => errorMsg.remove(), 5000);
            })
            .finally(() => {
                // Réactiver le bouton
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    }

    // Initialiser CKEditor
    initializeCKEditor();
    
    // Gestionnaire pour empêcher la fermeture du modal par clic extérieur
    const modal = document.getElementById('mediaModal');
    if (modal) {
        // Gestionnaire pour le clic sur l'overlay (fermeture)
        modal.addEventListener('click', function(e) {
            console.log('Clic sur modal, target:', e.target);
            if (e.target === modal) {
                console.log('Clic sur overlay - fermeture du modal');
                closeMediaModal();
            }
        });
        
        // Empêcher la propagation des clics à l'intérieur du modal
        const modalContent = modal.querySelector('.bg-white');
        if (modalContent) {
            modalContent.addEventListener('click', function(e) {
                console.log('Clic à l\'intérieur du modal - propagation stoppée');
                e.stopPropagation();
            });
        }
        
        // Gestionnaire pour la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                console.log('Escape pressé - fermeture du modal');
                closeMediaModal();
            }
        });
    }
    
    // Bouton de test pour la médiathèque
    const testMediaButton = document.getElementById('testMediaButton');
    if (testMediaButton) {
        testMediaButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('=== TEST BOUTON MÉDIA DIRECT ===');
            openMediaModal(function(url) {
                console.log('URL sélectionnée dans le test:', url);
                alert('Image sélectionnée: ' + url);
            });
        });
    }
});

function initializeCKEditor() {
    const textElement = document.getElementById('texte');
    if (!textElement) return;

    ClassicEditor
        .create(textElement, {
            language: 'fr',
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'link', 'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', '|',
                'imageUpload', '|',
                'undo', 'redo'
            ],
            image: {
                toolbar: [
                    'imageTextAlternative', '|',
                    'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                    'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight'
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .then(editor => {
            editorInstance = editor;
            
            // Style personnalisé pour CKEditor - hauteur minimum de 400px
            editor.editing.view.change(writer => {
                writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
            });
            
            // Ajouter le bouton Médiathèque manuellement
            addMediaLibraryButton(editor);
            
            // Synchronisation avec le textarea
            editor.model.document.on('change:data', () => {
                textElement.value = editor.getData();
            });

            // Charger le contenu existant en mode édition
            const existingContent = textElement.value;
            if (existingContent) {
                editor.setData(existingContent);
            }
        })
        .catch(error => {
            console.error('Erreur CKEditor:', error);
        });
}

function addMediaLibraryButton(editor) {
    // Ajouter un bouton personnalisé dans la toolbar
    const toolbar = editor.ui.view.toolbar;
    const mediaButton = document.createElement('button');
    mediaButton.type = 'button'; // Important: empêcher la soumission de formulaire
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
        console.log('=== CLIC SUR BOUTON MÉDIA ===');
        console.log('Event type:', e.type);
        console.log('Event target:', e.target);
        
        // Attendre un peu pour éviter les conflits
        setTimeout(() => {
            console.log('Ouverture différée du modal média...');
            openMediaModal(function(url) {
                console.log('Insertion de l\'image:', url);
                try {
                    editor.model.change(writer => {
                        const imageElement = writer.createElement('imageBlock', { src: url });
                        editor.model.insertContent(imageElement, editor.model.document.selection);
                    });
                } catch (error) {
                    console.error('Erreur lors de l\'insertion:', error);
                }
            });
        }, 100);
        
        return false;
    });
    
    // Insérer le bouton dans la toolbar
    setTimeout(() => {
        const toolbarElement = toolbar.element.querySelector('.ck-toolbar__items');
        if (toolbarElement) {
            toolbarElement.appendChild(mediaButton);
        }
    }, 100);
}

// Initialiser CKEditor
initializeCKEditor();

// Fonction d'aperçu d'image pour l'actualité
window.previewImageActualite = function(input) {
    const preview = document.getElementById('image-preview-actualite');
    const placeholder = document.getElementById('image-placeholder-actualite');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
};
</script>
