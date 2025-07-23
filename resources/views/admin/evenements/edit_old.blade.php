@extends('layouts.admin')

@section('title', 'Modifier l\'Événement')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Modifier l'Événement
        </h1>
        <div>
            <a href="{{ route('admin.evenements.show', $evenement) }}" class="btn btn-info me-2">
                <i class="fas fa-eye"></i> Voir
            </a>
            <a href="{{ route('admin.evenements.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    @if(session('alert'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {!! session('alert') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.evenements.update', $evenement) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        @include('admin.evenements._form')

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.evenements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
