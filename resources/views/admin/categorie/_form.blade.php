<form id="categorieForm" action="{{ $formAction }}" method="POST" novalidate>
    @csrf
    @if(isset($method) && $method === 'PUT')
        @method('PUT')
    @endif

    {{-- Champ: Nom --}}
    <div class="mb-4">
        <label for="nom" class="block text-gray-700 font-medium mb-1">Nom <span class="text-red-500">*</span></label>
        <input type="text" name="nom" id="nom"
            value="{{ old('nom', $categorie->nom ?? '') }}"
            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <p class="text-red-500 text-sm mt-1 hidden" id="nomError">Le nom est requis.</p>
    </div>

    {{-- Champ: Description --}}
    <div class="mb-4">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="wysiwyg form-input" rows="4">{{ old('description', $categorie->description ?? '') }}</textarea>
        @error('description')
            <p class="text-sm text-coral mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Bouton --}}
    <div class="mt-6">
        <button type="submit"
            class="bg-iri-primary text-white px-4 py-2 rounded hover:bg-iri-secondary transition">
            {{ $submitLabel ?? 'Enregistrer' }}
        </button>
    </div>
</form>

{{-- Validation JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#categorieForm').on('submit', function (e) {
        let valid = true;

        const nomInput = $('#nom');
        const nomError = $('#nomError');

        // Reset error display
        nomError.addClass('hidden');
        nomInput.removeClass('border-red-500');

        if (nomInput.val().trim() === '') {
            nomError.removeClass('hidden');
            nomInput.addClass('border-red-500');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
</script>
