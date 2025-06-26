@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Liste des services</h2>
        <a href="{{ route('admin.service.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Ajouter</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($services as $service)
            <div class="bg-white rounded shadow p-4 text-center">
                @if($service->icone)
                    <img src="{{ asset('storage/' . $service->icone) }}" class="w-20 h-20 mx-auto mb-2" alt="Icône">
                @endif
                <h3 class="text-lg font-bold mb-1">{{ $service->nom }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $service->description }}</p>
                <div class="flex justify-center gap-2">
                    <a href="{{ route('admin.service.edit', $service) }}" class="text-blue-600 hover:underline">Modifier</a>
                    <form action="{{ route('admin.service.destroy', $service) }}" method="POST" onsubmit="return confirm('Supprimer ce service ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
