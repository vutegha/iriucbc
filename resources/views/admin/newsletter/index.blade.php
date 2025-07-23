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
                <span class="text-white">newsletter</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Gestion Newsletter')
@section('subtitle', 'GÃ©rez les abonnÃ©s et leurs prÃ©fÃ©rences')

@section('content')
<div class="space-y-6">
    
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">AbonnÃ©s Newsletter</h2>
            <p class="text-sm text-gray-600">GÃ©rez les abonnÃ©s Ã  votre newsletter et leurs prÃ©fÃ©rences de contenu</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('newsletter.subscribe') }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                <i class="bi bi-box-arrow-up-right mr-2"></i>
                Page d'inscription
            </a>
            <a href="{{ route('admin.newsletter.export', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-olive hover:bg-olive hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive transition-colors duration-200">
                <i class="bi bi-download mr-2"></i>
                Exporter CSV
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="bi bi-people text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total AbonnÃ©s</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="bi bi-check-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Actifs</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['actifs'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <i class="bi bi-x-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Inactifs</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['inactifs'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="bi bi-envelope-check text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">ConfirmÃ©s</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['confirmes'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="bi bi-funnel mr-2"></i>
                Filtres et Recherche
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.newsletter.index') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-12 sm:gap-4">
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher par nom ou email..."
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-coral focus:border-coral">
                </div>
                
                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-coral focus:border-coral">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actifs</option>
                        <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                
                <div class="sm:col-span-3 flex items-end space-x-3">
                    <button type="submit" 
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-coral hover:bg-coral hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                        <i class="bi bi-search mr-2"></i>
                        Filtrer
                    </button>
                    @if(request()->hasAny(['search', 'statut']))
                        <a href="{{ route('admin.newsletter.index') }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table des abonnÃ©s -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="bi bi-table mr-2"></i>
                Liste des AbonnÃ©s ({{ $newsletters->total() }})
            </h3>
        </div>
        
        @if(optional($newsletters)->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                AbonnÃ©
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                PrÃ©fÃ©rences
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Inscription
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($newsletters as $newsletter)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-coral bg-opacity-10 flex items-center justify-center">
                                                <span class="text-coral font-medium">
                                                    {{ strtoupper(substr($newsletter->email, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $newsletter->nom ?: 'Nom non fourni' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $newsletter->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-1">
                                        @php
                                            $preferenceTypes = $newsletter->preferences->pluck('type')->toArray();
                                        @endphp
                                        @if(in_array('publications', $preferenceTypes))
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                ðŸ“š Pub
                                            </span>
                                        @endif
                                        @if(in_array('actualites', $preferenceTypes))
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ðŸ“° Act
                                            </span>
                                        @endif
                                        @if(in_array('projets', $preferenceTypes))
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                ðŸš€ Proj
                                            </span>
                                        @endif
                                        @if(empty($preferenceTypes))
                                            <span class="text-xs text-gray-400">Aucune prÃ©fÃ©rence</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        @if($newsletter->actif)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Actif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="bi bi-x-circle mr-1"></i>
                                                Inactif
                                            </span>
                                        @endif
                                        @if($newsletter->confirme_a)
                                            <span class="text-xs text-gray-500">
                                                <i class="bi bi-envelope-check mr-1"></i>
                                                ConfirmÃ©
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $newsletter->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $newsletter->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.newsletter.show', $newsletter) }}" 
                                           class="text-coral hover:text-coral hover:text-opacity-80">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.newsletter.toggle', $newsletter) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-blue-600 hover:text-blue-800"
                                                    title="{{ $newsletter->actif ? 'DÃ©sactiver' : 'Activer' }}">
                                                <i class="bi bi-{{ $newsletter->actif ? 'pause' : 'play' }}-circle"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.newsletter.destroy', $newsletter) }}" 
                                              class="inline"
                                              onsubmit="return confirm('ÃŠtes-vous sÃ»r de vouloir supprimer cet abonnÃ© ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-inbox text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun abonnÃ© trouvÃ©</h3>
                <p class="text-gray-500 mb-4">
                    @if(request()->hasAny(['search', 'statut']))
                        Aucun abonnÃ© ne correspond Ã  vos critÃ¨res de recherche.
                    @else
                        Vous n'avez encore aucun abonnÃ© Ã  votre newsletter.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'statut']))
                    <a href="{{ route('admin.newsletter.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-coral bg-coral bg-opacity-10 hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                        Voir tous les abonnÃ©s
                    </a>
                @endif
            </div>
        @endif

        <!-- Pagination -->
        @if($newsletters->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $newsletters->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


