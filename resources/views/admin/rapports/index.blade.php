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
                <span class="text-white">rapports</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Gestion des Rapports')
@section('subtitle', 'BibliothÃ¨que des rapports institutionnels')

@section('content')

<x-admin-layout 
    title="Gestion des Rapports"
    subtitle="BibliothÃ¨que des rapports institutionnels"
    create-route="{{ route('admin.rapports.create') }}"
    create-label="Nouveau Rapport"
    :show-search="true"
    search-placeholder="Rechercher par titre, description..."
    :filters="[
        [
            'name' => 'annee',
            'label' => 'AnnÃ©e',
            'type' => 'select',
            'options' => array_merge(['' => 'Toutes les annÃ©es'], array_combine($annees, $annees))
        ],
        [
            'name' => 'categorie',
            'label' => 'CatÃ©gorie',
            'type' => 'select',
            'options' => array_merge(['' => 'Toutes les catÃ©gories'], $categories->pluck('nom', 'id')->toArray())
        ],
        [
            'name' => 'date_from',
            'label' => 'Date dÃ©but',
            'type' => 'date'
        ],
        [
            'name' => 'date_to',
            'label' => 'Date fin',
            'type' => 'date'
        ]
    ]"
    :stats="[
        [
            'label' => 'Total Rapports',
            'value' => $rapports->total(),
            'icon' => 'bi-file-earmark-text',
            'bg_color' => 'bg-coral',
            'icon_color' => 'text-coral'
        ],
        [
            'label' => 'Cette AnnÃ©e',
            'value' => $rapports->filter(function($r) { return $r->date_publication && \Carbon\Carbon::parse($r->date_publication)->year == now()->year; })->count(),
            'icon' => 'bi-calendar-check',
            'bg_color' => 'bg-blue-100',
            'icon_color' => 'text-blue-600'
        ],
        [
            'label' => 'CatÃ©gories',
            'value' => $categories->count(),
            'icon' => 'bi-tags',
            'bg_color' => 'bg-green-100',
            'icon_color' => 'text-green-600'
        ],
        [
            'label' => 'RÃ©cents (30j)',
            'value' => $rapports->filter(function($r) { return $r->created_at && $r->created_at >= now()->subDays(30); })->count(),
            'icon' => 'bi-clock-history',
            'bg_color' => 'bg-purple-100',
            'icon_color' => 'text-purple-600'
        ]
    ]"
>

    <!-- Table des rapports -->
    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rapport</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CatÃ©gorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fichier</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rapports as $rapport)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-coral bg-opacity-10 rounded-full flex items-center justify-center">
                                        <i class="bi bi-file-earmark-text text-coral"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($rapport->titre, 40) }}</div>
                                        @if($rapport->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($rapport->description, 60) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($rapport->categorie)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="bi bi-tag mr-1"></i>
                                        {{ $rapport->categorie->nom }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">Non catÃ©gorisÃ©</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($rapport->date_publication)
                                        {{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}
                                    @else
                                        <span class="text-gray-400">Date non dÃ©finie</span>
                                    @endif
                                </div>
                                @if($rapport->created_at)
                                    <div class="text-xs text-gray-500">
                                        CrÃ©Ã© le {{ $rapport->created_at->format('d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($rapport->fichier)
                                    <div class="flex items-center">
                                        <i class="bi bi-file-pdf text-red-500 mr-2"></i>
                                        <div>
                                            <div class="text-sm text-gray-900">{{ basename($rapport->fichier) }}</div>
                                            @if(file_exists(public_path($rapport->fichier)))
                                                @php
                                                    $fileSize = filesize(public_path($rapport->fichier));
                                                    $fileSizeFormatted = $fileSize > 1024 * 1024 
                                                        ? round($fileSize / (1024 * 1024), 1) . ' MB'
                                                        : round($fileSize / 1024, 1) . ' KB';
                                                @endphp
                                                <div class="text-xs text-gray-500">{{ $fileSizeFormatted }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Aucun fichier</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    @can('view rapports')
                                    @if($rapport->fichier && file_exists(public_path($rapport->fichier)))
                                        <a href="{{ asset($rapport->fichier) }}" target="_blank"
                                           class="text-indigo-600 hover:text-indigo-800 p-1 rounded-md hover:bg-indigo-50 transition-colors duration-200"
                                           title="TÃ©lÃ©charger">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.rapports.show', $rapport) }}" 
                                       class="text-coral hover:text-coral/80 p-1 rounded-md hover:bg-coral/5 transition-colors duration-200"
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endcan
                                    @can('update rapports')
                                    <a href="{{ route('admin.rapports.edit', $rapport) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcan
                                    @can('delete rapports')
                                    <form action="{{ route('admin.rapports.destroy', $rapport) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('ÃŠtes-vous sÃ»r de vouloir supprimer ce rapport ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                                title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-file-earmark-text text-gray-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun rapport</h3>
                                    <p class="text-gray-500">Commencez par ajouter votre premier rapport.</p>
                                    @can('create rapports')
                                    <a href="{{ route('admin.rapports.create') }}" 
                                       class="mt-4 btn-primary">
                                        <i class="bi bi-plus mr-2"></i>
                                        CrÃ©er un rapport
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($rapports->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $rapports->links() }}
        </div>
    @endif

</x-admin-layout>

@endsection

