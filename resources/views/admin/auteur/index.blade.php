@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">auteur</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
@section('title', 'IRI UCBC | Liste des Auteurs')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Liste des Auteurs</h1>
    <a href="{{ route('admin.auteur.create') }}" class="bg-iri-primary text-white px-4 py-2 rounded hover:bg-iri-secondary">CrÃ©er un nouvel auteur</a>

    <table class="min-w-full mt-6 bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Nom</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Bio</th>
                <th class="border px-4 py-2">Image</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($auteurs as $auteur)
                <tr>
                    <td class="border px-4 py-2">{{ $auteur->nom }}</td>
                    <td class="border px-4 py-2">{{ $auteur->email }}</td>
                    <td class="border px-4 py-2">{{ Str::limit($auteur->biographie, 50) }}</td>
                    <td class="border px-4 py-2">
                        @if($auteur->photo)
                            <img src="{{ asset('storage/' . $auteur->photo) }}" alt="Photo de {{ $auteur->nom }}" class="h-32 rounded">
                        @endif
                    </td>
                    <td class="border px-4 py-2 space-x-2">
    <a href="{{ route('admin.auteur.show', $auteur) }}"
       class="inline-block bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
        ðŸ‘ï¸ Voir
    </a>

    <a href="{{ route('admin.auteur.edit', $auteur) }}"
       class="inline-block bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
        âœï¸ Modifier
    </a>

    <form action="{{ route('admin.auteur.destroy', $auteur) }}" method="POST" class="inline-block"
          onsubmit="return confirm('Confirmer la suppression ?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
            ðŸ—‘ï¸ Supprimer
        </button>
    </form>
</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

