@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Nouvelle Actualité')


<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-4">Create - actualites</h2>
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
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Créer une actualité</h1>
    <form action="{{ route('admin.actualite.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.actualite._form')
    </form>
</div>
</div>
@endsection


