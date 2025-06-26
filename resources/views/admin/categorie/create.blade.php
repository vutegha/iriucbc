@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Ajouter Catégorie')

@if ($errors->any())
    <div class="mb-6 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
        <strong class="font-semibold">Une erreur est survenue :</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('alert'))
    <div class="mb-6">
        {!! session('alert') !!}
    </div>
@endif

<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-6">Créer une nouvelle categorie</h2>
    

    @php
        $formAction = route('admin.categorie.store');
        $submitLabel = 'Créer';
    @endphp

    @include('admin.categorie._form', ['formAction' => $formAction, 'submitLabel' => $submitLabel])
</div>
@endsection
