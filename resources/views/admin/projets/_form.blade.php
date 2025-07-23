<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($projet))
        @method('PUT')
    @endif

    <div id="projet-form" class="space-y-6 bg-block">

        {{-- Nom --}}
        <div>
            <label for="nom" class="block text-sm font-semibold text-olive">Nom</label>
            <input type="text" name="nom" id="nom"
                   value="{{ old('nom', $projet->nom ?? '') }}"
                   class="mt-1 block w-full rounded border border-gray-300 focus:ring-coral focus:border-coral shadow-sm transition duration-300">
            <p id="nom-error" class="text-sm text-coral mt-1"></p>
            @error('nom')
                <p class="text-sm text-coral mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-input" rows="4" placeholder="Description détaillée du projet...">{{ old('description', $projet->description ?? '') }}</textarea>
            <p id="description-error" class="text-sm text-coral mt-1"></p>
            @error('description')
                <p class="text-sm text-coral mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Résumé --}}
        <div>
            <label for="resume" class="form-label">Résumé</label>
            <textarea name="resume" id="resume" class="form-input" rows="3" placeholder="Résumé court du projet...">{{ old('resume', $projet->resume ?? '') }}</textarea>
            @error('resume')
                <p class="text-sm text-coral mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image --}}
        <div>
            <label for="image" class="block text-sm font-semibold text-olive">Image (optionnelle)</label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="mt-1 block w-full text-sm text-olive
                          file:mr-4 file:py-2 file:px-4 file:rounded file:border-0
                          file:bg-light-green file:text-olive hover:file:bg-light-gray">
            @error('image')
                <p class="text-sm text-coral mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Service --}}
        <div>
            <label for="service_id" class="block text-sm font-semibold text-olive">Service associé</label>
            <select name="service_id" id="service_id"
                    class="mt-1 block w-full rounded border border-gray-300 focus:ring-coral focus:border-coral shadow-sm transition duration-300">
                <option value="">-- Sélectionner un service --</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id', $projet->service_id ?? '') == $service->id ? 'selected' : '' }}>
                        {{ $service->nom }}
                    </option>
                @endforeach
            </select>
            @error('service_id')
                <p class="text-sm text-coral mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="date_debut" class="block text-sm font-semibold text-olive">Date de début</label>
                <input type="date" name="date_debut" id="date_debut"
                       value="{{ old('date_debut', $projet->date_debut ?? '') }}"
                       class="mt-1 block w-full rounded border border-gray-300 focus:ring-coral focus:border-coral shadow-sm transition duration-300">
                @error('date_debut')
                    <p class="text-sm text-coral mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="date_fin" class="block text-sm font-semibold text-olive">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin"
                       value="{{ old('date_fin', $projet->date_fin ?? '') }}"
                       class="mt-1 block w-full rounded border border-gray-300 focus:ring-coral focus:border-coral shadow-sm transition duration-300">
                @error('date_fin')
                    <p class="text-sm text-coral mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- État --}}
        <div>
            <label for="etat" class="block text-sm font-semibold text-olive">État</label>
            <select name="etat" id="etat"
                    class="mt-1 block w-full rounded border border-gray-300 focus:ring-coral focus:border-coral shadow-sm transition duration-300">
                @php $etatOptions = ['en cours', 'terminé', 'suspendu']; @endphp
                @foreach($etatOptions as $option)
                    <option value="{{ $option }}" {{ old('etat', $projet->etat ?? '') === $option ? 'selected' : '' }}>
                        {{ ucfirst($option) }}
                    </option>
                @endforeach
            </select>
            @error('etat')
                <p class="text-sm text-coral mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Statistiques des bénéficiaires --}}
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-olive mb-4">Statistiques des bénéficiaires</h3>
            <p class="text-sm text-gray-600 mb-4">Ces informations peuvent être mises à jour après la création du projet.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="beneficiaires_hommes" class="block text-sm font-semibold text-olive">Bénéficiaires hommes</label>
                    <input type="number" name="beneficiaires_hommes" id="beneficiaires_hommes" min="0"
                           value="{{ old('beneficiaires_hommes', $projet->beneficiaires_hommes ?? '') }}"
                           class="mt-1 block w-full rounded border border-gray-300 focus:ring-coral focus:border-coral shadow-sm transition duration-300">
                    @error('beneficiaires_hommes')
                        <p class="text-sm text-coral mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="beneficiaires_femmes" class="block text-sm font-semibold text-olive">Bénéficiaires femmes</label>
                    <input type="number" name="beneficiaires_femmes" id="beneficiaires_femmes" min="0"
                           value="{{ old('beneficiaires_femmes', $projet->beneficiaires_femmes ?? '') }}"
                           class="mt-1 block w-full rounded border border-gray-300 focus:ring-coral focus:border-coral shadow-sm transition duration-300">
                    @error('beneficiaires_femmes')
                        <p class="text-sm text-coral mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="beneficiaires_total" class="block text-sm font-semibold text-olive">Total bénéficiaires</label>
                    <input type="number" name="beneficiaires_total" id="beneficiaires_total" min="0"
                           value="{{ old('beneficiaires_total', $projet->beneficiaires_total ?? '') }}"
                           class="mt-1 block w-full rounded border border-gray-300 focus:ring-coral focus:border-coral shadow-sm transition duration-300">
                    @error('beneficiaires_total')
                        <p class="text-sm text-coral mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Bouton --}}
        <div>
            <button type="submit" class="btn-ci">
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
