@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Modifier un abonné')
@if(session('alert'))
    <div class="mb-4">{!! session('alert') !!}</div>        
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Modifier un abonné</h1>

    @include('admin.newsletter._form', [
        'formAction' => route('admin.newsletter.update', $newsletter),
        'newsletter' => $newsletter
    ])
</div>
@endsection
