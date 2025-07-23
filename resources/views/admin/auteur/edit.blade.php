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
                <span class="text-white">Modifier</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
@section('title', 'IRI UCBC | ?odifier Profil Auteur')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-xl font-semibold mb-4">Modifier lâ€™auteur</h1>
        @php
        $formAction=route('admin.auteur.update', $auteur);
        @endphp

       
        @include('admin.auteur._form')
    </form>
</div>
@endsection

