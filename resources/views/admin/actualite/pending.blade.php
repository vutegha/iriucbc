@extends('layouts.admin')

@section('title', 'Actualités en attente')
@section('subtitle', 'Modération des actualités')

@section('content')
    @include('admin.partials.pending-moderation', [
        'items' => $actualites,
        'heading' => 'Actualités en attente de modération',
        'itemLabel' => 'actualités',
        'routePrefix' => 'admin.actualite',
        'titleField' => 'titre',
        'publishAbility' => 'publish',
        'meta' => fn ($actualite) => \Illuminate\Support\Str::limit(strip_tags((string) $actualite->resume), 100),
    ])
@endsection
