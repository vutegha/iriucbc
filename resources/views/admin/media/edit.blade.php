@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow mt-10 rounded">
    <h2 class="text-xl font-semibold mb-4">Modifier le media</h2>

@section('title', 'IRI UCBC | Media')
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
    @include('admin.media._form', [
        'formAction' => route('admin.media.update', $media),
        'media' => $media,
    ])
</div>
@endsection
