@extends('layouts.admin')

@section('title', 'Publications en attente')
@section('subtitle', 'Modération des publications')

@section('content')
    @include('admin.partials.pending-moderation', [
        'items' => $publications,
        'heading' => 'Publications en attente de modération',
        'itemLabel' => 'publications',
        'routePrefix' => 'admin.publication',
        'titleField' => 'titre',
        'publishAbility' => 'publish',
        'meta' => fn ($publication) => optional($publication->categorie)->nom,
    ])
@endsection
