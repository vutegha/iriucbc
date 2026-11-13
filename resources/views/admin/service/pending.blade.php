@extends('layouts.admin')

@section('title', 'Services en attente')
@section('subtitle', 'Modération des services')

@section('content')
    @include('admin.partials.pending-moderation', [
        'items' => $services,
        'heading' => 'Services en attente de modération',
        'itemLabel' => 'services',
        'routePrefix' => 'admin.service',
        'titleField' => 'nom',
        'publishAbility' => 'publish',
        'meta' => fn ($service) => \Illuminate\Support\Str::limit(strip_tags((string) $service->resume), 100),
    ])
@endsection
