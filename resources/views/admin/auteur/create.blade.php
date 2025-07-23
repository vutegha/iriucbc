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
                <a href="{{ route('admin.auteur.index') }}" class="text-white/70 hover:text-white">auteur</a>
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
@section('title', 'IRI UCBC | Enregistrement Nouvelle Auteur')
@if(session('alert'))
    <div class="mb-4">{!! session('alert') !!}</div>
@endif

@if($errors->any())
    <div class="mb-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-xl font-semibold mb-4">CrÃ©er un auteur</h1>
        

        @php
        $formAction = route('admin.auteur.store');
@endphp
        @include('admin.auteur._form')
</div>
@endsection

