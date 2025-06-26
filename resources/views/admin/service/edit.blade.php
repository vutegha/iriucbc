{{-- edit.blade.php --}}
@extends('layouts.admin')
@section('content')
<div class="max-w-3xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Modifier le service</h2>
    @include('admin.service._form', [
        'service' => $service,
        'formAction' => route('admin.service.update', $service)
    ])
</div>
@endsection
