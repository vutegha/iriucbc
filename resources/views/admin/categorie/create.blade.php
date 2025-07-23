@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.categorie.index') }}" class="text-white/70 hover:text-white">categorie</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">Nouveau</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
@section('title', 'IRI UCBC | Ajouter CatÃ©gorie')

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
    <h2 class="text-2xl font-semibold mb-6">CrÃ©er une nouvelle categorie</h2>
    

    @php
        $formAction = route('admin.categorie.store');
        $submitLabel = 'CrÃ©er';
    @endphp

    @include('admin.categorie._form', ['formAction' => $formAction, 'submitLabel' => $submitLabel])
</div>
@endsection

