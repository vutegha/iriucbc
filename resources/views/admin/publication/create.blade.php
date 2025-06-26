@extends('layouts.admin')

@section('content')
@section('title', 'IRI UCBC | Creer une Publication')
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

<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-4">Create - Publications</h2>
    @php
    $formAction = route('admin.publication.store');
    @endphp
    @include('admin.publication._form');
</div>
@endsection
