<form id="auteurForm" action    {{-- Biographie --}}
    <div class="mb-4">
        <label for="biographie" class="form-label">Biographie</label>
        <textarea name="biographie" id="biographie" class="wysiwyg form-input" rows="4">{{ old('biographie', $auteur->biographie ?? '') }}</textarea>
        @error('biographie')
            <p class="text-sm text-coral mt-1">{{ $message }}</p>
        @enderror
    </div>mAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($auteur))
        @method('PUT')
    @endif

    {{-- Nom --}}
    <div class="mb-4">
        <label for="nom" class="block text-gray-700">Nom <span class="text-red-500">*</span></label>
        <input type="text" id="nom" name="nom"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300 @error('nom') border-red-500 @enderror"
               value="{{ old('nom', $auteur->nom ?? '') }}" required>
        @error('nom')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Prénom --}}
    <div class="mb-4">
        <label for="prenom" class="block text-gray-700">Prénom</label>
        <input type="text" id="prenom" name="prenom"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300 @error('prenom') border-red-500 @enderror"
               value="{{ old('prenom', $auteur->prenom ?? '') }}">
        @error('prenom')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div class="mb-4">
        <label for="email" class="block text-gray-700">Email</label>
        <input type="email" id="email" name="email"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300 @error('email') border-red-500 @enderror"
               value="{{ old('email', $auteur->email ?? '') }}">
        @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Institution --}}
    <div class="mb-4">
        <label for="institution" class="block text-gray-700">Institution</label>
        <input type="text" id="institution" name="institution"
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

