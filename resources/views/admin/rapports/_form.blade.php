<div id="rapport-form" class="space-y-6">

    <div>
        <label class="block text-sm font-medium text-gray-700">Titre</label>
        <input type="text" name="titre" id="titre"
               value="{{ old('titre', $rapport->titre ?? '') }}"
               class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring">
        <p id="titre-error" class="text-sm text-red-500"></p>
        @error('titre')
            <p class="text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Fichier (PDF ou DOCX)</label>
        <input type="file" name="fichier" id="fichier" accept=".pdf,.doc,.docx"
               class="w-full border rounded p-2">
        @error('fichier')
            <p class="text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Résumé</label>
        <textarea name="resume" id="resume" rows="3"
                  class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring">{{ old('resume', $rapport->resume ?? '') }}</textarea>
        @error('resume')
            <p class="text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Date de publication</label>
        <input type="date" name="date_publication" id="date_publication"
               value="{{ old('date_publication', $rapport->date_publication ?? '') }}"
               class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring">
        @error('date_publication')
            <p class="text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Catégorie</label>
        <select name="categorie_id" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring">
            <option value="">-- Choisir une catégorie --</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" {{ old('categorie_id', $rapport->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>
        @error('categorie_id')
            <p class="text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ isset($rapport) ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('rapport-form').closest('form');
        const titre = document.getElementById('titre');
        const titreError = document.getElementById('titre-error');

        form.addEventListener('submit', function (e) {
            let valid = true;

            if (!titre.value.trim()) {
                e.preventDefault();
                titreError.textContent = "Le titre est requis.";
                titre.classList.add('border-red-500');
                valid = false;
            } else {
                titreError.textContent = "";
                titre.classList.remove('border-red-500');
            }

            return valid;
        });
    });
</script>
