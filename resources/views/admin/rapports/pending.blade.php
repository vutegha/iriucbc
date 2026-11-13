@extends('layouts.admin')

@section('title', 'Rapports en attente')
@section('subtitle', 'Modération des rapports')

@section('content')
    @include('admin.partials.pending-moderation', [
        'items' => $rapports,
        'heading' => 'Rapports en attente de modération',
        'itemLabel' => 'rapports',
        'routePrefix' => 'admin.rapports',
        'titleField' => 'titre',
        'publishAbility' => 'publish',
        'meta' => fn ($rapport) => optional($rapport->categorie)->nom,
    ])
@endsection
