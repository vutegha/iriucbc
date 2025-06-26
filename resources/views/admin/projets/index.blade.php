@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Liste des projets')
<div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Liste des projets</h1>
        <a href="{{ route('admin.projets.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Nouveau projet
        </a>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('admin.projets.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label for="etat" class="block text-sm text-gray-600">Filtrer par état</label>
            <select name="etat" id="etat" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm">
                <option value="">-- Tous les états --</option>
                @foreach(['en cours', 'terminé', 'suspendu'] as $option)
                    <option value="{{ $option }}" {{ request('etat') == $option ? 'selected' : '' }}>
                        {{ ucfirst($option) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="annee" class="block text-sm text-gray-600">Filtrer par année</label>
            <select name="annee" id="annee" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm">
                <option value="">-- Toutes les années --</option>
                @foreach ($anneesDisponibles as $annee)
                    <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>
                        {{ $annee }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Filtrer</button>
        <a href="{{ route('admin.projets.index') }}" class="text-blue-600 underline text-sm ml-2">Réinitialiser</a>
    </form>

    {{-- Tableau --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Projet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">État</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($projets as $projet)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            @if ($projet->image)
                                <img src="{{ asset('storage/' . $projet->image) }}" alt="Image"
                                     class="h-10 w-10 rounded object-cover border">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm">
                                    {{ strtoupper(substr($projet->nom, 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-800">{{ $projet->nom }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $projet->date_debut ?? '---' }} <br> {{ $projet->date_fin ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2 py-1 rounded-full text-xs
                            {{ $projet->etat === 'en cours' ? 'bg-yellow-100 text-yellow-800' :
                               ($projet->etat === 'terminé' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($projet->etat) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        <a href="{{ route('admin.projets.edit', $projet) }}" class="text-indigo-600 hover:underline">✏️ Modifier</a>
                        <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" class="inline"
                              onsubmit="return confirm('Supprimer ce projet ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">🗑️ Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Aucun projet trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $projets->appends(request()->query())->links() }}
    </div>
</div>

@endsection
