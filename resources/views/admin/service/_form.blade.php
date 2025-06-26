@props(['service' => null, 'formAction'])

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($service) @method('PUT') @endif

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Nom</label>
        <input type="text" name="nom" value="{{ old('nom', $service->nom ?? '') }}" class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description', $service->description ?? '') }}</textarea>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Icône (optionnelle)</label>
        <input type="file" name="icone" class="w-full border rounded px-3 py-2">
        @if($service && $service->icone)
            <img src="{{ asset('storage/' . $service->icone) }}" class="h-16 mt-2" alt="Aperçu">
        @endif
    </div>

    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        {{ $service ? 'Mettre à jour' : 'Enregistrer' }}
    </button>
</form>
