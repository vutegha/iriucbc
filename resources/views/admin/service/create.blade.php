@extends('layouts.admin')

@section('content')


{{-- create.blade.php --}}

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Ajouter un service</h2>
    @include('admin.service._form', [
        'formAction' => route('admin.service.store')
    ])
</div>
@endsection






@endsection
