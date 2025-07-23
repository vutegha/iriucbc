
<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($publication)) @method('PUT') @endif

    {{-- Titre --}}
    <div class="mb-4">
        <label for="titre" class="block text-gray-700">Titre</label>
        <input type="text" name="titre" id="titre" class="w-full border rounded px-3 py-2 @error('titre') border-red-500 @enderror"
            value="{{ old('titre', $publication->titre ?? '') }}" required maxlength="255">
        @error('titre')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Résumé --}}
    <div class="mb-4">
        <label for="resume" class="form-label">Résumé</label>
        <textarea name="resume" id="resume" class="form-input @error('resume') border-red-500 @enderror" rows="3" placeholder="Résumé de la publication...">{{ old('resume', $publication->resume ?? '') }}</textarea>
        @error('resume')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    

    {{-- Citation --}}
    <div class="mb-4">
        <label for="citation" class="form-label">Citation</label>
        <textarea name="citation" id="citation" class="form-input @error('citation') border-red-500 @enderror" rows="2" placeholder="Citation de la publication...">{{ old('citation', $publication->citation ?? '') }}</textarea>
        @error('citation')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- À la une --}}
    <div class="mb-4 flex items-center">
        <input type="hidden" name="a_la_une" value="0">
        <input type="checkbox" name="a_la_une" id="a_la_une" class="mr-2" value="1"
            {{ old('a_la_une', $publication->a_la_une ?? false) ? 'checked' : '' }}>
        <label for="a_la_une" class="text-gray-700">À la une</label>
    </div>

    {{-- En vedette --}}
    <div class="mb-4 flex items-center">
        <input type="hidden" name="en_vedette" value="0">
        <input type="checkbox" name="en_vedette" id="en_vedette" class="mr-2" value="1"
            {{ old('en_vedette', $publication->en_vedette ?? false) ? 'checked' : '' }}>
        <label for="en_vedette" class="text-gray-700">En vedette</label>
    </div>

    {{-- Auteurs (sélection multiple) --}}
    <div class="mb-4">
        <label for="auteurs" class="block text-gray-700 text-sm font-medium mb-2">Auteurs</label>
        <select name="auteurs[]" id="auteurs" multiple 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('auteurs') border-red-500 @enderror" 
                required>
            @foreach($auteurs as $auteur)
                <option value="{{ $auteur->id }}" 
                    {{ in_array($auteur->id, old('auteurs', isset($publication) ? $publication->auteurs->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                    {{ $auteur->nom }}
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs auteurs</p>
        @error('auteurs')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        @error('auteurs.*')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Catégorie --}}
    <div class="mb-4">
        <label for="categorie_id" class="block text-gray-700">Catégorie</label>
        <select name="categorie_id" id="categorie_id" class="w-full border rounded px-3 py-2 @error('categorie_id') border-red-500 @enderror" required>
            <option value="" disabled {{ old('categorie_id', $publication->categorie_id ?? '') == '' ? 'selected' : '' }}>Sélectionner une catégorie</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" {{ old('categorie_id', $publication->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>
        @error('categorie_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Fichier (pdf/doc/docx/ppt/pptx/xlsx) --}}
    <div class="mb-4">
        <label for="fichier_pdf" class="block text-gray-700">Fichier (PDF, Word, PowerPoint, Excel)</label>
        <input type="file" name="fichier_pdf" id="fichier_pdf"
            class="w-full border rounded px-3 py-2 @error('fichier_pdf') border-red-500 @enderror"
            accept=".pdf,.doc,.docx,.ppt,.pptx,.xlsx">
        @error('fichier_pdf')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        @if(isset($publication) && $publication->fichier_pdf)
            <p class="mt-2 text-sm text-gray-600">
                Fichier actuel : <a href="{{ asset('storage/' . $publication->fichier_pdf) }}"
                                   class="text-blue-500 underline"
                                   target="_blank">Télécharger</a>
            </p>
        @endif
    </div>

    {{-- Boutons --}}
    <div class="mt-6">
        <button type="submit" class="bg-iri-primary text-white px-4 py-2 rounded hover:bg-iri-secondary">
            {{ isset($publication) ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
    </div>
</form>

{{-- Script pour améliorer le select multiple --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('auteurs');
    
    // Améliorer le style du select multiple
    selectElement.style.minHeight = '120px';
    
    // Ajouter des boutons de sélection rapide
    const selectContainer = selectElement.parentNode;
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'mt-2 space-x-2';
    
    const selectAllBtn = document.createElement('button');
    selectAllBtn.type = 'button';
    selectAllBtn.textContent = 'Tout sélectionner';
    selectAllBtn.className = 'text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600';
    selectAllBtn.addEventListener('click', function() {
        Array.from(selectElement.options).forEach(option => option.selected = true);
    });
    
    const clearAllBtn = document.createElement('button');
    clearAllBtn.type = 'button';
    clearAllBtn.textContent = 'Tout désélectionner';
    clearAllBtn.className = 'text-xs bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600';
    clearAllBtn.addEventListener('click', function() {
        Array.from(selectElement.options).forEach(option => option.selected = false);
    });
    
    buttonContainer.appendChild(selectAllBtn);
    buttonContainer.appendChild(clearAllBtn);
    selectContainer.appendChild(buttonContainer);
});
</script>
