@extends('layouts.admin')

@section('title', 'IRI UCBC | Nouvelle Actualité')

@section('breadcrumbs')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <a href="{{ route('admin.actualite.index') }}" class="text-iri-gray hover:text-iri-primary transition-colors duration-200">Actualités</a>
        </div>
    </li>
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">Nouveau</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Créer une actualité</h1>
        <p class="text-gray-600 mt-1">Ajoutez une nouvelle actualité à votre site</p>
    </div>

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

    @php
        $formAction = route('admin.actualite.store');
    @endphp
    
    @include('admin.actualite._form')
</div>
@endsection



