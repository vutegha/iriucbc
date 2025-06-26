
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
        <label for="resume" class="block text-gray-700">Résumé</label>
        <textarea name="resume" id="resume" rows="3" class="w-full border rounded px-3 py-2 @error('resume') border-red-500 @enderror"
            required>{{ old('resume', $publication->resume ?? '') }}</textarea>
        @error('resume')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Contenu --}}
    <div class="mb-4">
        <label for="contenu" class="block text-gray-700">Contenu</label>
        <textarea name="contenu" id="contenu" rows="6" class="w-full border rounded px-3 py-2 @error('contenu') border-red-500 @enderror"
            required>{{ old('contenu', $publication->contenu ?? '') }}</textarea>
        @error('contenu')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Citation --}}
    <div class="mb-4">
        <label for="citation" class="block text-gray-700">Citation</label>
        <input type="text" name="citation" id="citation" class="w-full border rounded px-3 py-2 @error('citation') border-red-500 @enderror"
            value="{{ old('citation', $publication->citation ?? '') }}" maxlength="255">
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

    {{-- Auteur --}}
    <div class="mb-4">
        <label for="auteur_id" class="block text-gray-700">Auteur</label>
        <select name="auteur_id" id="auteur_id" class="w-full border rounded px-3 py-2 @error('auteur_id') border-red-500 @enderror" required>
            <option value="" disabled {{ old('auteur_id', $publication->auteur_id ?? '') == '' ? 'selected' : '' }}>Sélectionner un auteur</option>
            @foreach($auteurs as $auteur)
                <option value="{{ $auteur->id }}" {{ old('auteur_id', $publication->auteur_id ?? '') == $auteur->id ? 'selected' : '' }}>
                    {{ $auteur->nom }}
                </option>
            @endforeach
        </select>
        @error('auteur_id')
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
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            {{ isset($publication) ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
    </div>
</form>
