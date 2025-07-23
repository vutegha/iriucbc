@extends('layouts.admin')

@section('title', 'Gestion des Événements')
@section('subtitle', 'Organisation et gestion des événements IRI-UCBC')

@section('content')

<x-admin-layout 
    title="Gestion des Événements"
    subtitle="Organisation et gestion des événements IRI-UCBC"
    create-route="{{ route('admin.evenements.create') }}"
    create-label="Nouvel Événement"
    :show-search="true"
    search-placeholder="Rechercher par titre..."
    :filters="[
        [
            'name' => 'etat',
            'label' => 'État',
            'type' => 'select',
            'options' => [
                '' => 'Tous',
                'a_venir' => 'À venir',
                'en_cours' => 'En cours', 
                'passe' => 'Passés'
            ]
        ],
        [
            'name' => 'annee',
            'label' => 'Année',
            'type' => 'select',
            'options' => array_merge(['' => 'Toutes'], array_combine($anneesDisponibles, $anneesDisponibles))
        ],
        [
            'name' => 'date_debut',
            'label' => 'Date début',
            'type' => 'date'
        ],
        [
            'name' => 'date_fin',
            'label' => 'Date fin',
            'type' => 'date'
        ]
    ]"
    :stats="[
        [
            'label' => 'Total Événements',
            'value' => $evenements->total(),
            'icon' => 'bi-calendar-event',
            'bg_color' => 'bg-coral',
            'icon_color' => 'text-coral'
        ],
        [
            'label' => 'À venir',
            'value' => $evenements->where('date_debut', '>', now())->count(),
            'icon' => 'bi-clock',
            'bg_color' => 'bg-blue-100',
            'icon_color' => 'text-blue-600'
        ],
        [
            'label' => 'En cours',
            'value' => $evenements->where('date_debut', '<=', now())->where('date_fin', '>=', now())->count(),
            'icon' => 'bi-play-circle',
            'bg_color' => 'bg-green-100',
            'icon_color' => 'text-green-600'
        ],
        [
            'label' => 'Passés',
            'value' => $evenements->where('date_fin', '<', now())->count(),
            'icon' => 'bi-check-circle',
            'bg_color' => 'bg-gray-100',
            'icon_color' => 'text-gray-600'
        ]
    ]"
>

    <!-- Table des événements -->
    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Événement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lieu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($evenements as $evenement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-coral bg-opacity-10 rounded-full flex items-center justify-center">
                                        <i class="bi bi-calendar-event text-coral"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($evenement->titre, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($evenement->description, 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $evenement->date_debut->format('d/m/Y') }}
                                    @if($evenement->date_fin && $evenement->date_fin != $evenement->date_debut)
                                        <br><span class="text-xs text-gray-500">au {{ $evenement->date_fin->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $evenement->heure_debut ?? '00:00' }}
                                    @if($evenement->heure_fin)
                                        - {{ $evenement->heure_fin }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $evenement->lieu ?? 'Non spécifié' }}</div>
                                @if($evenement->adresse)
                                    <div class="text-xs text-gray-500">{{ Str::limit($evenement->adresse, 30) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $now = now();
                                    if ($evenement->date_debut > $now) {
                                        $etat = ['class' => 'bg-blue-100 text-blue-800', 'text' => 'À venir'];
                                    } elseif ($evenement->date_fin >= $now) {
                                        $etat = ['class' => 'bg-green-100 text-green-800', 'text' => 'En cours'];
                                    } else {
                                        $etat = ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Passé'];
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $etat['class'] }}">
                                    {{ $etat['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.evenements.show', $evenement) }}" 
                                       class="text-coral hover:text-coral/80 p-1 rounded-md hover:bg-coral/5 transition-colors duration-200"
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.evenements.edit', $evenement) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.evenements.destroy', $evenement) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                                title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-calendar-event text-gray-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun événement</h3>
                                    <p class="text-gray-500">Commencez par créer votre premier événement.</p>
                                    <a href="{{ route('admin.evenements.create') }}" 
                                       class="mt-4 btn-primary">
                                        <i class="bi bi-plus mr-2"></i>
                                        Créer un événement
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($evenements->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $evenements->links() }}
        </div>
    @endif

</x-admin-layout>

@endsection
