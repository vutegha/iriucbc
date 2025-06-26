@extends('layouts.admin')

@section('title', 'Profil de l\'auteur')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow space-y-8">
    <!-- Section auteur -->
    <div class="flex items-center space-x-6">
        @if ($auteur->photo)
            <img src="{{ asset('storage/' . $auteur->photo) }}" alt="Photo de {{ $auteur->nom }}" class="w-32 h-32 rounded-full object-cover">
        @else
            <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center text-white text-2xl font-semibold">
                {{ strtoupper(substr($auteur->nom, 0, 1)) }}
            </div>
        @endif

        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $auteur->nom }}</h2>
            <p class="text-gray-600">{{ $auteur->email }}</p>

            <div class="mt-4 space-x-2">
                <a href="{{ route('admin.auteur.edit', $auteur) }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    ✏️ Modifier
                </a>

                <a href="{{ route('admin.auteur.edit', $auteur) . '#photo' }}"
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    📷 Changer la photo
                </a>
            </div>
        </div>
    </div>

    <!-- Biographie -->
    <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Biographie</h3>
        <p class="text-gray-700 leading-relaxed">
            {{ $auteur->biographie ?? 'Aucune biographie renseignée.' }}
        </p>
    </div>

    <!-- Publications -->
    <div>
        <div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-700">📚 Mes Publications</h3>
        <a href="{{ route('admin.publication.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            ➕ Ajouer Une Nouvelle publication
        </a>
    </div>

    @if($auteur->publications && $auteur->publications->count())
        <ul class="space-y-3 list-disc list-inside text-gray-800">
            @foreach ($auteur->publications as $publication)
                <li>
                    <a href="{{ route('admin.publication.show', $publication) }}" class="text-blue-600 hover:underline">
                        {{ $publication->titre }}
                    </a>
                    <span class="text-sm text-gray-500">({{ $publication->created_at->format('d/m/Y') }})</span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-600 italic">Aucune publication enregistrée.</p>
    @endif
</div>

    </div>
</div>
@endsection
