@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Ajouter un abonné')
@if(session('alert'))
    <div class="mb-4">{!! session('alert') !!}</div>
@endif
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Ajouter un abonné</h1>
    @include('admin.newsletter._form', ['formAction' => route('admin.newsletter.store')])
</div>
@endsection
