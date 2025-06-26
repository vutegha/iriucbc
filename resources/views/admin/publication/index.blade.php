@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Nos Publications')
<div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">📚 Publications</h2>
        <a href="{{ route('admin.publication.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">+ Nouvelle publication</a>
    </div>

    {{-- Filtres --}}
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Auteur</label>
            <select name="auteur" class="mt-1 w-full border border-gray-300 rounded px-2 py-2">
                <option value="">-- Tous --</option>
                @foreach ($auteurs as $auteur)
                    <option value="{{ $auteur->id }}" {{ request('auteur') == $auteur->id ? 'selected' : '' }}>
                        {{ $auteur->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catégorie</label>
            <select name="categorie" class="mt-1 w-full border border-gray-300 rounded px-2 py-2">
                <option value="">-- Toutes --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 w-full">Filtrer</button>
        </div>
    </form>

    {{-- Table des publications --}}
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
            <tr>
                <th class="px-4 py-2">Image</th>
                <th class="px-4 py-2">Titre</th>
                <th class="px-4 py-2">Auteur</th>
                <th class="px-4 py-2">Catégorie</th>
                <th class="px-4 py-2">Citation</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($publications as $pub)
                <tr>
                    <td class="px-4 py-2">
                        @if($pub->image)
                            <img src="{{ asset('storage/' . $pub->image) }}" class="h-14 w-14 object-cover rounded" alt="Image publication">
                        @else
                            <span class="text-sm text-gray-400 italic">Pas d'image</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $pub->titre }}</td>
                    <td class="px-4 py-2">
    @if ($pub->auteur)
        <div class="flex items-center space-x-2">
            @if ($pub->auteur->photo)
                <img src="{{ asset('storage/' . $pub->auteur->photo) }}" alt="Photo de {{ $pub->auteur->nom }}"
                     class="h-10 w-10 rounded-full object-cover border">
            @else
                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm">
                    {{ strtoupper(substr($pub->auteur->nom, 0, 1)) }}
                </div>
            @endif
            <span class="text-sm text-gray-800">{{ $pub->auteur->nom }}</span>
        </div>
    @else
        <span class="text-sm text-gray-400 italic">Aucun auteur</span>
    @endif
</td>
                    <td class="px-4 py-2">{{ $pub->categorie->nom ?? '—' }}</td>
                    <td class="px-4 py-2 text-sm">{{ Str::limit($pub->citation, 60) }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('admin.publication.show', $pub) }}" class="text-green-600 hover:underline">👁️</a>
                        <a href="{{ route('admin.publication.edit', $pub) }}" class="text-blue-600 hover:underline">✏️</a>
                        <form action="{{ route('admin.publication.destroy', $pub) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">🗑️</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500 italic">Aucune publication trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $publications->links() }}
    </div>
</div>



@endsection
