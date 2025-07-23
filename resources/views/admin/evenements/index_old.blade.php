@extends('layouts.admin')

@section('title', 'Gestion des Événements')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-alt me-2"></i>Gestion des Événements
        </h1>
        <a href="{{ route('admin.evenements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvel Événement
        </a>
    </div>

    @if(session('alert'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {!! session('alert') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>Filtres
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.evenements.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="etat" class="form-label">État</label>
                    <select name="etat" id="etat" class="form-select">
                        <option value="">Tous</option>
                        <option value="a_venir" {{ request('etat') == 'a_venir' ? 'selected' : '' }}>À venir</option>
                        <option value="en_cours" {{ request('etat') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="passe" {{ request('etat') == 'passe' ? 'selected' : '' }}>Passés</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="annee" class="form-label">Année</label>
                    <select name="annee" id="annee" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($anneesDisponibles as $annee)
                            <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>
                                {{ $annee }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Titre ou lieu..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.evenements.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des événements -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                Liste des Événements ({{ $evenements->total() }})
            </h5>
        </div>
        <div class="card-body">
            @if($evenements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Titre</th>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th>Lieu</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evenements as $evenement)
                                <tr>
                                    <td>
                                        @if($evenement->image)
                                            <img src="{{ asset('storage/' . $evenement->image) }}" 
                                                 alt="{{ $evenement->titre }}" 
                                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-calendar-alt text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($evenement->titre, 40) }}</strong>
                                        @if($evenement->resume)
                                            <br><small class="text-muted">{{ Str::limit($evenement->resume, 60) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d/m/Y') }}
                                        </span>
                                        <br><small class="text-muted">
                                            {{ \Carbon\Carbon::parse($evenement->date_debut)->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($evenement->date_fin)
                                            <span class="badge bg-secondary">
                                                {{ \Carbon\Carbon::parse($evenement->date_fin)->format('d/m/Y') }}
                                            </span>
                                            <br><small class="text-muted">
                                                {{ \Carbon\Carbon::parse($evenement->date_fin)->format('H:i') }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $evenement->lieu ?: 'Non spécifié' }}</td>
                                    <td>
                                        @if($evenement->est_a_venir)
                                            <span class="badge bg-success">À venir</span>
                                        @elseif($evenement->est_passe)
                                            <span class="badge bg-secondary">Passé</span>
                                        @else
                                            <span class="badge bg-warning">En cours</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.evenements.show', $evenement) }}" 
                                               class="btn btn-sm btn-outline-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.evenements.edit', $evenement) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.evenements.destroy', $evenement) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $evenements->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun événement trouvé</h5>
                    <p class="text-muted">Créez votre premier événement pour commencer.</p>
                    <a href="{{ route('admin.evenements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Créer un événement
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}
.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 2px;
}
.badge {
    font-size: 0.75em;
}
</style>
@endpush
