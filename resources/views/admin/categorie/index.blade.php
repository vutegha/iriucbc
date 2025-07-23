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
                <span class="text-white">categorie</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
@section('title', 'IRI UCBC | CatÃ©gories')

{{-- Main content --}}
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Liste des entitÃ©s</h1>
        <a href="{{ route('admin.categorie.create') }}"
           class="bg-iri-primary text-white px-4 py-2 rounded hover:bg-iri-secondary">
            + Nouvelle entitÃ©
        </a>
    </div>

    @if(session('alert'))
        <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded">
            {!! session('alert') !!}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded">
            <thead>
                <tr class="bg-gray-100 text-left text-sm text-gray-600 uppercase">
                    <th class="px-6 py-3 border-b">Nom</th>
                    <th class="px-6 py-3 border-b">Description</th>
                    <th class="px-6 py-3 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $categorie)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 border-b">{{ $categorie->nom }}</td>
                        <td class="px-6 py-4 border-b">{{ Str::limit($categorie->description, 80) }}</td>
                        <td class="px-6 py-4 border-b text-center">
                            <a href="{{ route('admin.categorie.edit', $categorie) }}"
                               class="text-blue-600 hover:underline mr-3">Modifier</a>

                            <form action="{{ route('admin.categorie.destroy', $categorie) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-500 py-4">Aucune entitÃ© trouvÃ©e.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection

