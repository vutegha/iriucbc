@extends('layouts.admin')

@section('title', 'Gestion des Auteurs')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="list-none p-0 inline-flex">
        <li class="flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-iri-primary hover:text-iri-secondary">Dashboard</a>
            <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.476 239.03c9.373 9.372 9.373 24.568 0 33.941z"/>
            </svg>
        </li>
        <li class="text-gray-500">Auteurs</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-users mr-3 text-iri-primary"></i>
                    Gestion des Auteurs
                </h1>
                <p class="text-gray-600 mt-1">Gérez les auteurs des publications et documents</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Actions rapides -->
                <div class="flex gap-2">
                    @can('create_author')
                    <a href="{{ route('admin.auteur.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i>
                        Nouvel Auteur
                    </a>
                    @endcan
                    
                    @can('export_authors')
                    <button onclick="exportAuthors()" 
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-accent to-iri-gold text-white rounded-lg hover:from-iri-gold hover:to-iri-accent transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i>
                        Exporter
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.auteur.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Recherche -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>Rechercher
                </label>
                <input type="text" name="search" id="search" 
                       value="{{ request('search') }}"
                       placeholder="Nom, prénom, email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary">
            </div>

            <!-- Tri -->
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sort mr-1"></i>Trier par
                </label>
                <select name="sort" id="sort" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date création</option>
                    <option value="publications_count" {{ request('sort') == 'publications_count' ? 'selected' : '' }}>Nb publications</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 bg-iri-primary text-white px-4 py-2 rounded-lg hover:bg-iri-secondary transition-colors">
                    <i class="fas fa-filter mr-1"></i>Filtrer
                </button>
                
                <a href="{{ route('admin.auteur.index') }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Auteurs</p>
                    <p class="text-2xl font-bold">{{ $auteurs->total() ?? 0 }}</p>
                </div>
                <i class="fas fa-users text-3xl text-blue-200"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Actifs ce mois</p>
                    <p class="text-2xl font-bold">{{ $activeThisMonth ?? 0 }}</p>
                </div>
                <i class="fas fa-chart-line text-3xl text-green-200"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Publications</p>
                    <p class="text-2xl font-bold">{{ $totalPublications ?? 0 }}</p>
                </div>
                <i class="fas fa-file-alt text-3xl text-purple-200"></i>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Nouveaux (7j)</p>
                    <p class="text-2xl font-bold">{{ $newThisWeek ?? 0 }}</p>
                </div>
                <i class="fas fa-user-plus text-3xl text-orange-200"></i>
            </div>
        </div>
    </div>

    <!-- Table des auteurs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Liste des Auteurs</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Auteur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Publications
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dernière activité
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($auteurs as $auteur)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($auteur->photo)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ asset('storage/' . $auteur->photo) }}" 
                                             alt="{{ $auteur->nom }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-iri-primary to-iri-secondary flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                {{ strtoupper(substr($auteur->nom, 0, 1)) }}{{ strtoupper(substr($auteur->prenom ?? '', 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $auteur->nom }} {{ $auteur->prenom }}
                                    </div>
                                    @if($auteur->titre)
                                        <div class="text-sm text-gray-500">{{ $auteur->titre }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($auteur->email)
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                        {{ $auteur->email }}
                                    </div>
                                @endif
                                @if($auteur->institution)
                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-building mr-2 text-gray-400"></i>
                                        {{ $auteur->institution }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    {{ $auteur->publications_count ?? 0 }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $auteur->updated_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                @can('view_author')
                                <a href="{{ route('admin.auteur.show', $auteur) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors"
                                   title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan

                                @can('edit_author')
                                <a href="{{ route('admin.auteur.edit', $auteur) }}" 
                                   class="text-iri-primary hover:text-iri-secondary transition-colors"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan

                                @can('delete_author')
                                <button onclick="confirmDelete({{ $auteur->id }}, '{{ $auteur->nom }}')" 
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun auteur trouvé</h3>
                                <p class="text-gray-500 mb-4">Commencez par ajouter un premier auteur.</p>
                                @can('create_author')
                                <a href="{{ route('admin.auteur.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Ajouter un auteur
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($auteurs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $auteurs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-900">Confirmer la suppression</h3>
        </div>
        
        <p class="text-gray-600 mb-6">
            Êtes-vous sûr de vouloir supprimer l'auteur <strong id="deleteAuteurName"></strong> ? 
            Cette action est irréversible.
        </p>
        
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Annuler
            </button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(auteurId, auteurName) {
    document.getElementById('deleteAuteurName').textContent = auteurName;
    document.getElementById('deleteForm').action = `/admin/auteur/${auteurId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function exportAuthors() {
    // Implémenter l'export
    window.location.href = '{{ route("admin.auteur.index") }}?export=true';
}

// Fermer la modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
