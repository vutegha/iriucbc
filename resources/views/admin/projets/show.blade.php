@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ $projet->nom }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <p><span class="font-semibold text-gray-700">Slug :</span> {{ $projet->slug }}</p>
            <p><span class="font-semibold text-gray-700">État :</span> {{ ucfirst($projet->etat) }}</p>
            <p><span class="font-semibold text-gray-700">Date de début :</span> {{ $projet->date_debut ?? 'N/A' }}</p>
            <p><span class="font-semibold text-gray-700">Date de fin :</span> {{ $projet->date_fin ?? 'N/A' }}</p>
        </div>
        <div>
            @if($projet->image)
                <img src="{{ asset('storage/' . $projet->image) }}" alt="Image du projet" class="w-full h-auto rounded shadow">
            @else
                <p class="text-gray-500 italic">Aucune image disponible.</p>
            @endif
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Description</h2>
        <div class="prose max-w-none">{!! nl2br(e($projet->description)) !!}</div>
    </div>

    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Médias associés</h2>
        @if($projet->media->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($projet->media as $media)
                    <div class="bg-gray-100 p-4 rounded shadow">
                        <p class="text-sm text-gray-700 font-medium mb-2">{{ $media->titre }}</p>
                        @if(Str::startsWith($media->fichier, 'http'))
                            <a href="{{ $media->fichier }}" target="_blank" class="text-blue-600 underline">Voir le média</a>
                        @else
                            <a href="{{ asset('storage/' . $media->fichier) }}" target="_blank" class="text-blue-600 underline">Voir le média</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">Aucun média associé pour ce projet.</p>
        @endif
    </div>
</div>
@endsection
