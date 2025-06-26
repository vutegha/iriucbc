<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($projet))
        @method('PUT')
    @endif

    <div id="projet-form" class="space-y-6">

        {{-- Nom --}}
        <div>
            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
            <input type="text" name="nom" id="nom"
                value="{{ old('nom', $projet->nom ?? '') }}"
                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <p id="nom-error" class="text-sm text-red-500 mt-1"></p>
            @error('nom')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4"
                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $projet->description ?? '') }}</textarea>
            <p id="description-error" class="text-sm text-red-500 mt-1"></p>
            @error('description')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image --}}
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Image (optionnelle)</label>
            <input type="file" name="image" id="image" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4 file:rounded file:border-0
                    file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('image')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                <input type="date" name="date_debut" id="date_debut"
                    value="{{ old('date_debut', $projet->date_debut ?? '') }}"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('date_debut')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin"
                    value="{{ old('date_fin', $projet->date_fin ?? '') }}"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('date_fin')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- État --}}
        <div>
            <label for="etat" class="block text-sm font-medium text-gray-700">État</label>
            <select name="etat" id="etat"
                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @php $etatOptions = ['en cours', 'terminé', 'suspendu']; @endphp
                @foreach($etatOptions as $option)
                    <option value="{{ $option }}" {{ old('etat', $projet->etat ?? '') === $option ? 'selected' : '' }}>
                        {{ ucfirst($option) }}
                    </option>
                @endforeach
            </select>
            @error('etat')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Bouton --}}
        <div>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ isset($projet) ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
        </div>
    </div>
</form>

{{-- JS validation --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('projet-form').closest('form');
        const nom = document.getElementById('nom');
        const description = document.getElementById('description');

        form.addEventListener('submit', function (e) {
            let valid = true;

            if (!nom.value.trim()) {
                e.preventDefault();
                document.getElementById('nom-error').textContent = "Le nom est requis.";
                nom.classList.add('border-red-500');
                valid = false;
            } else {
                document.getElementById('nom-error').textContent = "";
                nom.classList.remove('border-red-500');
            }

            if (!description.value.trim()) {
                e.preventDefault();
                document.getElementById('description-error').textContent = "La description est requise.";
                description.classList.add('border-red-500');
                valid = false;
            } else {
                document.getElementById('description-error').textContent = "";
                description.classList.remove('border-red-500');
            }

            return valid;
        });
    });
</script>
