@props(['media' => null, 'formAction'])

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($media) @method('PUT') @endif

    {{-- Titre --}}
    <div class="mb-4">
        <label for="titre" class="block text-gray-700">Titre</label>
        <input type="text" name="titre" id="titre"
               value="{{ old('titre', $media->titre ?? '') }}"
               class="w-full border rounded px-3 py-2 @error('titre') border-red-500 @enderror"
               maxlength="255">
        @error('titre')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Type --}}
    <div class="mb-4">
        <label for="type" class="block text-gray-700">Type de média</label>
        <select name="type" id="type"
                class="w-full border rounded px-3 py-2 @error('type') border-red-500 @enderror">
            <option value="" disabled {{ old('type', $media->type ?? '') === '' ? 'selected' : '' }}>Choisir un type</option>
            <option value="image" {{ old('type', $media->type ?? '') === 'image' ? 'selected' : '' }}>Image</option>
            <option value="video" {{ old('type', $media->type ?? '') === 'video' ? 'selected' : '' }}>Vidéo</option>
        </select>
        @error('type')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Média (image ou vidéo) --}}
    <div class="mb-4">
        <label for="medias" class="block text-gray-700">Fichier média</label>
        <input type="file" name="medias" id="medias"
               class="w-full border rounded px-3 py-2 @error('medias') border-red-500 @enderror"
               accept="image/*,video/mp4,video/quicktime,video/webm">
        @error('medias')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        {{-- Aperçu du média existant --}}
        @if($media && $media->medias)
            <div class="mt-2 text-sm text-gray-600">
                Média actuel :
                @if($media->type === 'image')
                    <img src="{{ asset('storage/' . $media->medias) }}" alt="Image" class="h-32 mt-1 rounded shadow">
                @elseif($media->type === 'video')
                    <video controls class="h-40 mt-1 rounded shadow">
                        <source src="{{ asset('storage/' . $media->medias) }}" type="video/mp4">
                        Votre navigateur ne prend pas en charge la lecture vidéo.
                    </video>
                @endif
            </div>
        @endif
    </div>

    {{-- Bouton --}}
    <div class="mt-6">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            {{ $media ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
    </div>
</form>
