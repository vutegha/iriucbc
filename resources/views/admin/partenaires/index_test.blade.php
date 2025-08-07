@extends('layouts.admin')

@section('title', 'Partenaires')
@section('subtitle', 'Gestion des partenaires GRN-UCBC')

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold mb-4">Test de la vue Partenaires</h2>
        
        <p>Total partenaires: {{ $stats['total'] ?? 'Non défini' }}</p>
        <p>Partenaires actifs: {{ $stats['actifs'] ?? 'Non défini' }}</p>
        <p>Partenaires publiés: {{ $stats['publies'] ?? 'Non défini' }}</p>
        
        <h3 class="text-lg font-semibold mt-6 mb-3">Liste des partenaires :</h3>
        @if(isset($partenaires) && $partenaires->count() > 0)
            <ul class="list-disc list-inside">
            @foreach($partenaires as $partenaire)
                <li>{{ $partenaire->nom }} - {{ $partenaire->statut }}</li>
            @endforeach
            </ul>
        @else
            <p>Aucun partenaire trouvé.</p>
        @endif
        
        <div class="mt-6">
            <a href="{{ route('admin.partenaires.create') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded">
               Ajouter un partenaire
            </a>
        </div>
    </div>
</div>
@endsection
