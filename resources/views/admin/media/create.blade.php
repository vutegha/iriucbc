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
                <a href="{{ route('admin.media.index') }}" class="text-white/70 hover:text-white">media</a>
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

<div class="max-w-2xl mx-auto p-6 bg-white shadow mt-10 rounded">
    <h2 class="text-xl font-semibold mb-4">Ajouter un media</h2>

@section('title', 'IRI UCBC | Media')

    @include('admin.media._form', [
        'formAction' => route('admin.media.store'),
    ])
</div>
@endsection


