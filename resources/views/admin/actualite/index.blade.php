@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Actualités')
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">📰 Liste des actualités</h2>
        <a href="{{ route('admin.actualite.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">+ Nouvelle actualité</a>
    </div>

    

    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-4 py-2">Titre</th>
                <th class="px-4 py-2">Résumé</th>
                <th class="px-4 py-2">Texte</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($actualites as $actualite)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            @if ($actualite->image)
                                <img src="{{ asset('storage/' . $actualite->image) }}" alt="Image"
                                     class="h-10 w-10 rounded object-cover border">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm">
                                    {{ strtoupper(substr($actualite->titre, 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-800">{{ $actualite->titre }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2">{{ Str::limit($actualite->resume, 60) }}</td>
                    <td class="px-4 py-2 flex items-center gap-2">
                        @if($actualite->actualite?->image)
                            <img src="{{ asset('storage/' . $actualite->image->image) }}" class="h-8 w-8 rounded-full object-cover">
                        @endif
                        
                    </td>
                    <td class="px-4 py-2">{{ optional($actualite->created_at)->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('admin.actualite.edit', $actualite) }}" class="text-blue-600 hover:underline">✏️ Modifier</a>
                        <form action="{{ route('admin.actualite.destroy', $actualite) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette actualité ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">🗑️ Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-gray-500 italic">Aucune actualité trouvée.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $actualites->appends(['categorie_id' => request('categorie_id')])->links() }}
    </div>
</div>


@endsection
