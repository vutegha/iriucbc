<!-- Modern Author Form with Protection -->
<form id="auteurForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if(isset($auteur))
        @method('PUT')
    @endif
    
    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">{{ isset($auteur) ? 'Modifier l\'auteur' : 'Nouvel auteur' }}</h2>
            <div class="text-sm text-gray-500">
                Étape 1 sur 1 - Informations personnelles
            </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-iri-primary to-iri-secondary h-2 rounded-full" style="width: 100%"></div>
        </div>
    </div>

    <!-- Basic Information Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Informations personnelles</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom (Required) -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nom" 
                       name="nom"
                       value="{{ old('nom', $auteur->nom ?? '') }}" 
                       required
                       maxlength="100"
                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('nom') border-red-500 ring-red-500 @enderror"
                       placeholder="Entrez le nom de famille">
                @error('nom')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Prénom -->
            <div>
                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                <input type="text" 
                       id="prenom" 
                       name="prenom"
                       value="{{ old('prenom', $auteur->prenom ?? '') }}" 
                       maxlength="100"
                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('prenom') border-red-500 ring-red-500 @enderror"
                       placeholder="Entrez le prénom">
                @error('prenom')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                <input type="email" 
                       id="email" 
                       name="email"
                       value="{{ old('email', $auteur->email ?? '') }}" 
                       maxlength="255"
                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('email') border-red-500 ring-red-500 @enderror"
                       placeholder="exemple@email.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Institution -->
            <div>
                <label for="institution" class="block text-sm font-medium text-gray-700 mb-2">Institution</label>
                <input type="text" 
                       id="institution" 
                       name="institution"
                       value="{{ old('institution', $auteur->institution ?? '') }}" 
                       maxlength="255"
                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('institution') border-red-500 ring-red-500 @enderror"
                       placeholder="Nom de l'institution ou organisation">
                @error('institution')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Biography Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Biographie</h3>
            <span class="ml-2 text-sm text-gray-500">(Optionnel)</span>
        </div>

        <div>
            <label for="biographie" class="block text-sm font-medium text-gray-700 mb-2">
                Biographie de l'auteur
            </label>
            <textarea id="biographie" 
                      name="biographie" 
                      rows="6"
                      maxlength="2000"
                      class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('biographie') border-red-500 ring-red-500 @enderror"
                      placeholder="Décrivez brièvement l'auteur, son parcours, ses domaines d'expertise...">{{ old('biographie', $auteur->biographie ?? '') }}</textarea>
            <div class="mt-2 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Partagez les informations importantes sur cet auteur
                </div>
                <div class="text-sm text-gray-400">
                    <span id="biographieCount">0</span>/2000 caractères
                </div>
            </div>
            @error('biographie')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <!-- Photo Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6" id="photo">
        <div class="flex items-center mb-6">
            <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Photo de profil</h3>
            <span class="ml-2 text-sm text-gray-500">(Optionnel)</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
            <!-- Current Photo Preview -->
            <div class="flex-shrink-0 mb-4 md:mb-0">
                <div class="w-32 h-32 bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-gray-300" id="photoPreview">
                    @if(isset($auteur) && $auteur->photo)
                        <img src="{{ asset('storage/' . $auteur->photo) }}" 
                             alt="Photo actuelle" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2-2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Photo Upload -->
            <div class="flex-1">
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                    Choisir une nouvelle photo
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-iri-primary transition-colors duration-200" id="dropZone">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-iri-primary hover:text-iri-secondary">
                                <span>Télécharger un fichier</span>
                                <input id="photo" name="photo" type="file" accept="image/*" class="sr-only" onchange="previewPhoto(this)">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG jusqu'à 2MB</p>
                    </div>
                </div>
                @error('photo')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 pt-6">
        <button type="submit" 
                id="submitBtn"
                class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span id="submitText">{{ isset($auteur) ? 'Modifier l\'auteur' : 'Créer l\'auteur' }}</span>
        </button>
        
        <a href="{{ route('admin.auteur.index') }}" 
           class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Annuler
        </a>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for biography
    const biographieTextarea = document.getElementById('biographie');
    const biographieCount = document.getElementById('biographieCount');
    
    function updateBiographieCount() {
        const count = biographieTextarea.value.length;
        biographieCount.textContent = count;
        
        if (count > 1800) {
            biographieCount.classList.add('text-red-500');
            biographieCount.classList.remove('text-gray-400');
        } else if (count > 1500) {
            biographieCount.classList.add('text-orange-500');
            biographieCount.classList.remove('text-gray-400', 'text-red-500');
        } else {
            biographieCount.classList.add('text-gray-400');
            biographieCount.classList.remove('text-red-500', 'text-orange-500');
        }
    }
    
    biographieTextarea.addEventListener('input', updateBiographieCount);
    updateBiographieCount(); // Initial count
    
    // Form submission with loading state
    const form = document.getElementById('auteurForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const originalText = submitText.textContent;
    
    form.addEventListener('submit', function(e) {
        // Validate form
        const nom = document.getElementById('nom').value.trim();
        if (!nom) {
            e.preventDefault();
            alert('Le nom est obligatoire.');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Enregistrement...';
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        
        // Add spinner
        submitBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>Enregistrement...</span>
        `;
    });
    
    // Drag and drop functionality
    const dropZone = document.getElementById('dropZone');
    const photoInput = document.getElementById('photo');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        dropZone.classList.add('border-iri-primary', 'bg-iri-primary/5');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-iri-primary', 'bg-iri-primary/5');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            photoInput.files = files;
            previewPhoto(photoInput);
        }
    }
});

function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Veuillez sélectionner un fichier image.');
            input.value = '';
            return;
        }
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('La taille du fichier ne doit pas dépasser 2MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Aperçu" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    }
}

// Input validation and styling
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '' && this.hasAttribute('required')) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
        
        input.addEventListener('focus', function() {
            this.classList.remove('border-red-500');
            this.classList.add('border-iri-primary');
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500') && this.value.trim() !== '') {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    });
});
</script>
@endpush
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300 @error('institution') border-red-500 @enderror"
               value="{{ old('institution', $auteur->institution ?? '') }}"
               placeholder="Ex: Université de Kinshasa, CNRS, etc.">
        @error('institution')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Biographie --}}
    <div class="mb-4">
        <label for="biographie" class="block text-sm font-semibold text-olive">Biographie</label>
        <input id="biographie" type="hidden" name="biographie" value="{{ old('biographie', $auteur->biographie ?? '') }}">
        <trix-editor input="biographie" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-coral focus:border-coral"></trix-editor>
        @error('biographie')
            <p class="text-sm text-coral mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Photo --}}
    <div class="mb-4">
        <label for="photo" class="block text-gray-700">Photo (jpg, png, webp – max 5 Mo)</label>
        <input type="file" id="photo" name="photo"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300 @error('photo') border-red-500 @enderror"
               accept=".jpg,.jpeg,.png,.webp">
        @error('photo')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        @if(isset($auteur) && $auteur->photo)
            <div class="mt-2">
                <p class="text-sm text-gray-600 mb-1">Photo actuelle :</p>
                <img src="{{ asset('storage/' . $auteur->photo) }}" alt="Photo de {{ $auteur->nom }}" class="h-32 rounded">
            </div>
        @endif
    </div>

    {{-- Bouton --}}
    <div class="mt-6">
        <button type="submit"
                class="bg-iri-primary text-white px-6 py-2 rounded hover:bg-iri-secondary transition">
            {{ isset($auteur) ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('auteurForm');

    form.addEventListener('submit', function (e) {
        let valid = true;
        const nom = form.querySelector('#nom');
        const email = form.querySelector('#email');
        const photo = form.querySelector('#photo');

        // Nettoyage ancien message
        form.querySelectorAll('.text-red-500').forEach(el => el.remove());

        // Nom requis
        if (!nom.value.trim()) {
            showError(nom, "Le nom est requis.");
            valid = false;
        }

        // Email requis et format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim()) {
            showError(email, "L'email est requis.");
            valid = false;
        } else if (!emailRegex.test(email.value.trim())) {
            showError(email, "Email invalide.");
            valid = false;
        }

        // Photo : type et taille
        if (photo.files.length > 0) {
            const file = photo.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            const maxSize = 5048 * 1024;

            if (!allowedTypes.includes(file.type)) {
                showError(photo, "Seuls les formats jpg, png, webp sont autorisés.");
                valid = false;
            }

            if (file.size > maxSize) {
                showError(photo, "La taille maximale est de 5 Mo.");
                valid = false;
            }
        }

        if (!valid) e.preventDefault();
    });

    function showError(input, message) {
        const error = document.createElement('p');
        error.className = 'text-red-500 text-sm mt-1';
        error.innerText = message;
        input.parentNode.appendChild(error);
    }
});
</script>

