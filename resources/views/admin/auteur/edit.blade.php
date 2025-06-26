@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | ?odifier Profil Auteur')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-xl font-semibold mb-4">Modifier l’auteur</h1>
        @php
        $formAction=route('admin.auteur.update', $auteur);
        @endphp

       
        @include('admin.auteur._form')
    </form>
</div>
@endsection
