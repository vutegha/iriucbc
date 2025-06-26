@extends('layouts.admin')


@section('content')

<div class="max-w-2xl mx-auto p-6 bg-white shadow mt-10 rounded">
    <h2 class="text-xl font-semibold mb-4">Ajouter un media</h2>

@section('title', 'IRI UCBC | Media')

    @include('admin.media._form', [
        'formAction' => route('admin.media.store'),
    ])
</div>
@endsection

