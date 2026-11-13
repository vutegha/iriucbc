@extends('layouts.admin')

@section('title', 'Projets en attente')
@section('subtitle', 'Modération des projets')

@section('content')
    @include('admin.partials.pending-moderation', [
        'items' => $projets,
        'heading' => 'Projets en attente de modération',
        'itemLabel' => 'projets',
        'routePrefix' => 'admin.projets',
        'titleField' => 'nom',
        'publishAbility' => 'moderate',
        'meta' => fn ($projet) => optional($projet->service)->nom,
    ])
@endsection
