@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">job-offers</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Gestion des Offres d\'Emploi')

@section('content')
<div class="container-fluid">
    <!-- En-tÃªte avec actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestion des Offres d'Emploi</h1>
            <p class="text-muted">GÃ©rez toutes les offres d'emploi internes et partenaires</p>
        </div>
        <div>
            <a href="{{ route('admin.job-offers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle Offre
            </a>
            <a href="{{ route('admin.job-offers.statistics') }}" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillons</option>
                        <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>En pause</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>FermÃ©es</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Source</label>
                    <select name="source" class="form-select">
                        <option value="">Toutes les sources</option>
                        <option value="internal" {{ request('source') == 'internal' ? 'selected' : '' }}>Internes</option>
                        <option value="partner" {{ request('source') == 'partner' ? 'selected' : '' }}>Partenaires</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Titre, description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">Filtrer</button>
                    <a href="{{ route('admin.job-offers.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des offres -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ $jobOffers->total() }} Offre(s) d'Emploi</h5>
        </div>
        <div class="card-body p-0">
            @if(optional($jobOffers)->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th>Statut</th>
                                <th>Candidatures</th>
                                <th>Ã‰chÃ©ance</th>
                                <th>CrÃ©Ã©</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobOffers as $offer)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('admin.job-offers.show', $offer) }}" class="text-decoration-none">
                                                    {{ $offer->title }}
                                                </a>
                                                @if($offer->is_featured)
                                                    <span class="badge bg-warning text-dark ms-1">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $offer->location }}</small>
                                            @if($offer->partner_name)
                                                <br><small class="text-info">{{ $offer->partner_name }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst(str_replace('-', ' ', $offer->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $offer->source == 'internal' ? 'bg-primary' : 'bg-info' }}">
                                            {{ $offer->source == 'internal' ? 'Interne' : 'Partenaire' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'draft' => 'secondary',
                                                'paused' => 'warning',
                                                'closed' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'active' => 'Active',
                                                'draft' => 'Brouillon',
                                                'paused' => 'En pause',
                                                'closed' => 'FermÃ©e'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$offer->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$offer->status] ?? $offer->status }}
                                        </span>
                                        @if($offer->is_expired)
                                            <br><small class="text-danger">ExpirÃ©e</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $offer->applications_count }}</span>
                                        @if($offer->applications_count > 0)
                                            <br><small class="text-muted">{{ $offer->views_count }} vues</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="{{ $offer->is_expired ? 'text-danger' : 'text-muted' }}">
                                            {{ $offer->deadline ? $offer->deadline->format('d/m/Y') : 'Non dÃ©finie' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $offer->created_at ? $offer->created_at->format('d/m/Y') : 'Non dÃ©finie' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.job-offers.show', $offer) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.job-offers.edit', $offer) }}" 
                                               class="btn btn-outline-secondary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-info dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="Plus d'actions">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-offers.duplicate', $offer) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-copy"></i> Dupliquer
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-offers.toggle-featured', $offer) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-star"></i> 
                                                                {{ $offer->is_featured ? 'Retirer vedette' : 'Marquer vedette' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-offers.destroy', $offer) }}" 
                                                              class="d-inline" onsubmit="return confirm('ÃŠtes-vous sÃ»r de vouloir supprimer cette offre ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{ $jobOffers->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune offre d'emploi trouvÃ©e</h5>
                    <p class="text-muted">Commencez par crÃ©er votre premiÃ¨re offre d'emploi.</p>
                    <a href="{{ route('admin.job-offers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> CrÃ©er une offre
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script pour confirmer les suppressions
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('form[onsubmit*="confirm"]');
        deleteButtons.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('ÃŠtes-vous sÃ»r de vouloir supprimer cette offre d\'emploi ? Cette action est irrÃ©versible.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush

