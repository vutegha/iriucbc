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
                <span class="text-white">job-applications</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Gestion des Candidatures')

@section('content')
<div class="container-fluid">
    <!-- En-tÃªte avec actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestion des Candidatures</h1>
            <p class="text-muted">GÃ©rez toutes les candidatures reÃ§ues</p>
        </div>
        <div>
            <a href="{{ route('admin.job-applications.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Exporter CSV
            </a>
            <a href="{{ route('admin.job-applications.statistics') }}" class="btn btn-info">
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
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>RÃ©visÃ©es</option>
                        <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>PrÃ©sÃ©lectionnÃ©es</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>AcceptÃ©es</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>RejetÃ©es</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Offre d'emploi</label>
                    <select name="job_offer_id" class="form-select">
                        <option value="">Toutes les offres</option>
                        @foreach($jobOffers as $id => $title)
                            <option value="{{ $id }}" {{ request('job_offer_id') == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Nom, prÃ©nom, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">Filtrer</button>
                    <a href="{{ route('admin.job-applications.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions en lot -->
    <div class="card mb-4" id="bulk-actions" style="display: none;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.job-applications.bulk-review') }}" id="bulk-form">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Action en lot</label>
                        <select name="status" class="form-select" required>
                            <option value="">Choisir une action</option>
                            <option value="reviewed">Marquer comme rÃ©visÃ©es</option>
                            <option value="shortlisted">PrÃ©sÃ©lectionner</option>
                            <option value="rejected">Rejeter</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <span id="selected-count">0</span> candidature(s) sÃ©lectionnÃ©e(s)
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Appliquer</button>
                        <button type="button" class="btn btn-secondary" onclick="clearSelection()">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des candidatures -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $applications->total() }} Candidature(s)</h5>
            <div>
                <input type="checkbox" id="select-all" class="form-check-input me-2">
                <label for="select-all" class="form-check-label">Tout sÃ©lectionner</label>
            </div>
        </div>
        <div class="card-body p-0">
            @if(optional($applications)->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="30"></th>
                                <th>Candidat</th>
                                <th>Offre d'emploi</th>
                                <th>Statut</th>
                                <th>Note</th>
                                <th>Date de candidature</th>
                                <th>Fichiers</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="application_ids[]" value="{{ $application->id }}" 
                                               class="form-check-input application-checkbox">
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('admin.job-applications.show', $application) }}" class="text-decoration-none">
                                                    {{ $application->first_name }} {{ $application->last_name }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">{{ $application->email }}</small>
                                            @if($application->phone)
                                                <br><small class="text-muted">{{ $application->phone }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.job-offers.show', $application->jobOffer) }}" class="text-decoration-none">
                                            {{ $application->jobOffer->title }}
                                        </a>
                                        <br><small class="text-muted">{{ $application->jobOffer->location }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'reviewed' => 'info',
                                                'shortlisted' => 'primary',
                                                'accepted' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'reviewed' => 'RÃ©visÃ©e',
                                                'shortlisted' => 'PrÃ©sÃ©lectionnÃ©e',
                                                'accepted' => 'AcceptÃ©e',
                                                'rejected' => 'RejetÃ©e'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$application->status] ?? $application->status }}
                                        </span>
                                        @if($application->reviewed_at)
                                            <br><small class="text-muted">{{ $application->reviewed_at->format('d/m/Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->score)
                                            <span class="badge bg-{{ $application->score >= 80 ? 'success' : ($application->score >= 60 ? 'warning' : 'danger') }}">
                                                {{ $application->score }}/100
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $application->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($application->cv_path)
                                                <a href="{{ route('admin.job-applications.download-cv', $application) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="TÃ©lÃ©charger CV">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                            @if($application->portfolio_path)
                                                <a href="{{ route('admin.job-applications.download-portfolio', $application) }}" 
                                                   class="btn btn-sm btn-outline-info" title="TÃ©lÃ©charger Portfolio">
                                                    <i class="fas fa-briefcase"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.job-applications.show', $application) }}" 
                                               class="btn btn-outline-primary" title="Voir dÃ©tails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-info dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="Changer statut">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="reviewed">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-eye text-info"></i> Marquer rÃ©visÃ©e
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="shortlisted">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-star text-primary"></i> PrÃ©sÃ©lectionner
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="accepted">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-check text-success"></i> Accepter
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-times text-danger"></i> Rejeter
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
                    {{ $applications->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune candidature trouvÃ©e</h5>
                    <p class="text-muted">Les candidatures apparaÃ®tront ici une fois soumises.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = count;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            // Ajouter les IDs au formulaire
            const existingInputs = bulkForm.querySelectorAll('input[name="application_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            checkedBoxes.forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'application_ids[]';
                hiddenInput.value = checkbox.value;
                bulkForm.appendChild(hiddenInput);
            });
        } else {
            bulkActions.style.display = 'none';
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    applicationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(applicationCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(applicationCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            
            updateBulkActions();
        });
    });

    window.clearSelection = function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        updateBulkActions();
    };
});
</script>
@endpush

