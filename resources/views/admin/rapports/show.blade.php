@extends('layouts.admin')

@section('title', 'Détail du Rapport')
@section('subtitle', 'Visualisation du rapport : ' . $rapport->titre)

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.rapports.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-file-alt mr-2 group-hover:rotate-12 transition-transform duration-200"></i>
            Rapports
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium">{{ Str::limit($rapport->titre, 30) }}</span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-iri-dark flex items-center">
                <i class="fas fa-file-alt text-iri-primary mr-3"></i>
                {{ $rapport->titre }}
            </h1>
            <p class="text-iri-gray mt-2">
                <i class="fas fa-info-circle mr-2"></i>
                Détails complets du rapport
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-3">
            <a href="{{ route('admin.rapports.edit', $rapport) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('admin.rapports.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-iri-gray/10 text-iri-gray rounded-lg hover:bg-iri-gray/20 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
            <!-- Informations principales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations Principales
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-heading mr-2"></i>Titre du rapport
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $rapport->titre }}
                            </div>
                        </div>
                        
                        @if($rapport->auteur)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-user mr-2"></i>Auteur
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $rapport->auteur }}
                            </div>
                        </div>
                        @endif
                        
                        @if($rapport->type)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-tag mr-2"></i>Type
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                    <i class="fas fa-circle mr-1 text-xs"></i>{{ ucfirst($rapport->type) }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-plus mr-2"></i>Date de création
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $rapport->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        
                        @if($rapport->date_publication)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-check mr-2"></i>Date de publication
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-edit mr-2"></i>Dernière modification
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $rapport->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé -->
            @if($rapport->resume)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Résumé
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($rapport->resume)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Contenu -->
            @if($rapport->contenu)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Contenu du Rapport
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {!! $rapport->contenu !!}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Statut et visibilité -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        Statut & Visibilité
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Statut de publication -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-globe mr-3 text-iri-gray"></i>
                            <span class="font-medium">Publication</span>
                        </div>
                        @if(isset($rapport->is_published) && $rapport->is_published)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                <i class="fas fa-check-circle mr-1"></i>Publié
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-clock mr-1"></i>Brouillon
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fichier PDF -->
            @if($rapport->fichier_pdf)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-gold to-iri-accent">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-file-pdf mr-3"></i>
                        Fichier PDF
                    </h2>
                </div>
                <div class="p-6">
                    <a href="{{ asset('storage/' . $rapport->fichier_pdf) }}" target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i>
                        Télécharger le PDF
                    </a>
                </div>
            </div>
            @endif

            <!-- Actions de modération -->
            @can('moderate', $rapport)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-gavel mr-3"></i>
                        Actions
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @can('publish rapports')
                    @if(!$rapport->is_published)
                        <button onclick="moderateRapport('publish')" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-eye mr-2"></i>
                            Publier
                        </button>
                    @endif
                    @endcan
                    
                    @can('unpublish rapports')
                    @if($rapport->is_published)
                        <button onclick="moderateRapport('unpublish')" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-eye-slash mr-2"></i>
                            Dépublier
                        </button>
                    @endif
                    @endcan
                    
                    @can('delete rapports')
                        <button onclick="moderateRapport('delete')" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer
                        </button>
                    @endcan
                </div>
            </div>
            @endcan

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.rapports.edit', $rapport) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier ce rapport
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modération unifié -->
@can('moderate', $rapport)
<div id="moderationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-gavel mr-3"></i>
                    <span id="modalTitle">Action de modération</span>
                </h3>
                <button onclick="closeModerationModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="moderationForm" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4" id="commentSection">
                <label for="moderation_comment" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment mr-2"></i>Commentaire
                </label>
                <textarea name="moderation_comment" 
                          id="moderation_comment" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                          placeholder="Ajoutez un commentaire de modération...">{{ $rapport->moderation_comment ?? '' }}</textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        id="confirmButton"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-check mr-2"></i>
                    Confirmer
                </button>
                <button type="button" 
                        onclick="closeModerationModal()" 
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
@endcan

<script>
let currentAction = null;
let currentRapportId = {{ $rapport->id }};

function moderateRapport(action) {
    currentAction = action;
    const modal = document.getElementById('moderationModal');
    const form = document.getElementById('moderationForm');
    const title = document.getElementById('modalTitle');
    const button = document.getElementById('confirmButton');
    const commentSection = document.getElementById('commentSection');
    
    // Configuration selon l'action
    switch(action) {
        case 'publish':
            form.action = '{{ route("admin.rapports.publish", $rapport) }}';
            title.textContent = 'Publier le rapport';
            button.innerHTML = '<i class="fas fa-eye mr-2"></i>Publier';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'block';
            break;
            
        case 'unpublish':
            form.action = '{{ route("admin.rapports.unpublish", $rapport) }}';
            title.textContent = 'Dépublier le rapport';
            button.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Dépublier';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'block';
            break;
            
        case 'delete':
            form.action = '{{ route("admin.rapports.destroy", $rapport) }}';
            form.querySelector('input[name="_method"]').value = 'DELETE';
            title.textContent = 'Supprimer le rapport';
            button.innerHTML = '<i class="fas fa-trash mr-2"></i>Supprimer';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'none';
            break;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function confirmModerationAction() {
    const form = document.getElementById('moderationForm');
    
    // Envoi AJAX pour une UX fluide
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Notification de succès
            showNotification(data.message || 'Action effectuée avec succès', 'success');
            
            // Fermer le modal
            closeModerationModal();
            
            // Recharger la page pour refléter les changements
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Une erreur est survenue', 'error');
    });
}

function closeModerationModal() {
    document.getElementById('moderationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentAction = null;
}

function showNotification(message, type = 'info') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-circle' : 
                'fa-info-circle'
            } mr-3"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animer l'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Gestion des événements
document.addEventListener('DOMContentLoaded', function() {
    // Soumettre le formulaire avec AJAX
    document.getElementById('moderationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        confirmModerationAction();
    });
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModerationModal();
        }
    });
    
    // Fermer en cliquant à l'extérieur
    document.getElementById('moderationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModerationModal();
        }
    });
});
</script>
@endsection
