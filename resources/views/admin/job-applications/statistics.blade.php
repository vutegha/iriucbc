@extends('layouts.admin')

@section('title', 'Statistiques des candidatures')
@section('subtitle', 'Vue d\'ensemble des candidatures reçues')

@section('content')
@php
    $cards = [
        ['label' => 'Total', 'value' => $stats['total_applications'] ?? 0, 'icon' => 'fa-users', 'color' => 'from-blue-500 to-blue-600'],
        ['label' => 'En attente', 'value' => $stats['pending_applications'] ?? 0, 'icon' => 'fa-hourglass-half', 'color' => 'from-yellow-500 to-yellow-600'],
        ['label' => 'Examinées', 'value' => $stats['reviewed_applications'] ?? 0, 'icon' => 'fa-clipboard-check', 'color' => 'from-indigo-500 to-indigo-600'],
        ['label' => 'Acceptées', 'value' => $stats['accepted_applications'] ?? 0, 'icon' => 'fa-check-circle', 'color' => 'from-green-500 to-green-600'],
        ['label' => 'Refusées', 'value' => $stats['rejected_applications'] ?? 0, 'icon' => 'fa-times-circle', 'color' => 'from-red-500 to-red-600'],
        ['label' => '7 derniers jours', 'value' => $stats['recent_applications'] ?? 0, 'icon' => 'fa-calendar-week', 'color' => 'from-teal-500 to-teal-600'],
    ];

    $monthNames = ['Janv.', 'Févr.', 'Mars', 'Avr.', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'];
    $monthMax = max(1, ...array_values($applicationsByMonth ?: [0]));
    $jobMax = max(1, (int) $applicationsByJob->max('count'));
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Statistiques des candidatures</h1>
            <p class="text-gray-600">Répartition par statut, par offre et par mois ({{ now()->year }}).</p>
        </div>
        <a href="{{ route('admin.job-applications.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux candidatures
        </a>
    </div>

    <!-- Chiffres clés -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        @foreach($cards as $card)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="w-10 h-10 bg-gradient-to-br {{ $card['color'] }} rounded-lg flex items-center justify-center mb-3">
                    <i class="fas {{ $card['icon'] }} text-white"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                <p class="text-sm text-gray-600">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Par offre -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">Candidatures par offre</h2>
            <p class="text-sm text-gray-500 mb-5">Les 10 offres les plus demandées.</p>

            @forelse($applicationsByJob as $row)
                <div class="mb-4 last:mb-0">
                    <div class="flex items-baseline justify-between gap-3 mb-1">
                        <span class="text-sm text-gray-800 truncate">{{ optional($row->jobOffer)->title ?? 'Offre supprimée' }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $row->count }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-2 bg-blue-500 rounded-full" style="width: {{ round($row->count / $jobMax * 100) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Aucune candidature pour le moment.</p>
            @endforelse
        </div>

        <!-- Par mois -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">Candidatures par mois</h2>
            <p class="text-sm text-gray-500 mb-5">Année {{ now()->year }}.</p>

            <table class="w-full text-sm">
                <tbody>
                    @foreach($monthNames as $index => $name)
                        @php $count = (int) ($applicationsByMonth[$index + 1] ?? 0); @endphp
                        <tr>
                            <td class="py-1.5 pr-3 text-gray-600 w-16">{{ $name }}</td>
                            <td class="py-1.5">
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-2 bg-teal-500 rounded-full" style="width: {{ round($count / $monthMax * 100) }}%"></div>
                                </div>
                            </td>
                            <td class="py-1.5 pl-3 text-right font-semibold text-gray-900 w-10">{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
