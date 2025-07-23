<form id="actualiteForm" action="{{ $formAction ?? '#' }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
   
    @if(isset($actualite)) @method('PUT') @endif
    

    {{-- Titre --}}
    <div>
        <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
        <input type="text" id="titre" name="titre" value="{{ old('titre', $actualite->titre ?? '') }}"
               class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
               required>
        <p class="text-red-500 text-sm mt-1 hidden" id="error-titre">Ce champ est requis.</p>
    </div>

    {{-- Résumé --}}
    <div>
        <label for="resume" class="form-label">Résumé</label>
        <textarea name="resume" id="resume" class="form-input" rows="3" placeholder="Résumé de l'actualité...">{{ old('resume', $actualite->resume ?? '') }}</textarea>
        @error('resume')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Texte --}}
    <div>
        <label for="texte" class="form-label">Texte</label>
        <textarea name="texte" id="texte" class="form-input" rows="6" placeholder="Contenu principal de l'actualité...">{{ old('texte', $actualite->texte ?? '') }}</textarea>
        @error('texte')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
        <p class="text-red-500 text-sm mt-1 hidden" id="error-texte">Ce champ est requis.</p>
    </div>

    {{-- Image --}}
    <div>
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" id="image" name="image" accept="image/*"
               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    </div>

    {{-- Options --}}
    {{-- À la une --}}
    <div class="mb-4 flex items-center">
        <input type="hidden" name="a_la_une" value="0">
        <input type="checkbox" name="a_la_une" id="a_la_une" class="mr-2" value="1"
            {{ old('a_la_une', $actualite->a_la_une ?? false) ? 'checked' : '' }}>
        <label for="a_la_une" class="text-gray-700">À la une</label>
    </div>

    {{-- En vedette --}}
    <div class="mb-4 flex items-center">
        <input type="hidden" name="en_vedette" value="0">
        <input type="checkbox" name="en_vedette" id="en_vedette" class="mr-2" value="1"
            {{ old('en_vedette', $actualite->en_vedette ?? false) ? 'checked' : '' }}>
        <label for="en_vedette" class="text-gray-700">En vedette</label>
    </div>

    {{-- Bouton de soumission --}}
    <div>
        <button type="submit" class="bg-iri-primary text-white px-4 py-2 rounded hover:bg-iri-secondary">
            {{ isset($actualite) ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
    </div>
</form>

<script>
    document.getElementById('actualiteForm').addEventListener('submit', function (e) {
        let isValid = true;

     

        // Validation du titre
        const titre = document.getElementById('titre');
        const errorTitre = document.getElementById('error-titre');
        if (!titre.value.trim()) {
            errorTitre.classList.remove('hidden');
            isValid = false;
        } else {
            errorTitre.classList.add('hidden');
        }

        // Validation du texte
        const texte = document.getElementById('texte');
        const errorTexte = document.getElementById('error-texte');
        if (!texte.value.trim()) {
            errorTexte.classList.remove('hidden');
            isValid = false;
        } else {
            errorTexte.classList.add('hidden');
        }

        if (!isValid) {
            e.preventDefault();
        }
    })
</script>
