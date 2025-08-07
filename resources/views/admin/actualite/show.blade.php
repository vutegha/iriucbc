@extends('layouts.admin')

@section('breadcrumbs')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <a href="{{ route('admin.actualite.index') }}" class="text-iri-gray hover:text-iri-primary transition-colors duration-200">Actualités</a>
        </div>
    </li>
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">{{ Str::limit($actualite->titre, 30) }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Messages Flash -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.style.display='none'" 
                                class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100">
                            <i class="fas fa-times h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.style.display='none'" 
                                class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100">
                            <i class="fas fa-times h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-iri-dark flex items-center">
                <i class="fas fa-newspaper text-iri-primary mr-3"></i>
                {{ $actualite->titre }}
            </h1>
            <p class="text-iri-gray mt-2">
                <i class="fas fa-info-circle mr-2"></i>
                Détails complets de l'actualité
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-3">
            @can('update_actualites')
            <a href="{{ route('admin.actualite.edit', $actualite) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            @endcan
            <a href="{{ route('admin.actualite.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-iri-gray/10 text-iri-gray rounded-lg hover:bg-iri-gray/20 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contenu de l'actualité -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Contenu de l'actualité
                    </h2>
                </div>
                <div class="p-6">
                    @if($actualite->image)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $actualite->image) }}" 
                             alt="{{ $actualite->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <div>
                            <div class="text-sm text-iri-gray flex items-center mb-4">
                                <i class="fas fa-calendar mr-2"></i>
                                Publié le {{ $actualite->created_at ? $actualite->created_at->format('d/m/Y à H:i') : 'Date non disponible' }}
                            </div>
                            
                            @if($actualite->resume)
                            <div class="p-3 bg-gradient-to-r from-iri-accent/10 to-iri-gold/10 rounded-lg">
                                <h3 class="text-sm font-medium text-iri-gray mb-2">
                                    <i class="fas fa-clipboard-list mr-2"></i>Résumé
                                </h3>
                                <p class="text-iri-dark italic">{{ $actualite->resume }}</p>
                            </div>
                            @endif
                            
                            @if($actualite->texte)
                            <div class="text-iri-dark leading-relaxed prose prose-lg max-w-none prose-headings:text-iri-primary prose-links:text-iri-secondary prose-strong:text-iri-dark prose-ul:text-iri-dark prose-ol:text-iri-dark">
                                {!! $actualite->texte !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails de l'actualité -->
            @if($actualite->auteur)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations supplémentaires
                    </h2>
                </div>
                <div class="p-6">
                    <div>
                        <label class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-user-edit mr-2"></i>Auteur
                        </label>
                        <div class="p-3 bg-gray-50 rounded-lg border">
                            {{ $actualite->auteur }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Statut et métadonnées -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        Statut & Métadonnées
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Statut de publication -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-globe mr-3 text-iri-gray"></i>
                            <span class="font-medium">Statut de publication</span>
                        </div>
                        @if($actualite->is_published)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i>Publiée
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                <i class="fas fa-clock mr-1"></i>En attente
                            </span>
                        @endif
                    </div>

                    <!-- À la une -->
                    @if(isset($actualite->a_la_une))
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-star mr-3 text-iri-gray"></i>
                            <span class="font-medium">À la une</span>
                        </div>
                        @if($actualite->a_la_une)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-check mr-1"></i>Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fas fa-times mr-1"></i>Non
                            </span>
                        @endif
                    </div>
                    @endif

                    <!-- Date de création -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus mr-3 text-iri-gray"></i>
                            <span class="font-medium">Créé le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $actualite->created_at ? $actualite->created_at->format('d/m/Y à H:i') : 'Date non disponible' }}</span>
                    </div>

                    <!-- Dernière modification -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-edit mr-3 text-iri-gray"></i>
                            <span class="font-medium">Modifié le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $actualite->updated_at ? $actualite->updated_at->format('d/m/Y à H:i') : 'Date non disponible' }}</span>
                    </div>
                </div>
            </div>

            <!-- Informations de modération -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-3"></i>
                        Modération
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Statut de modération -->
                    <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-medium text-gray-700">
                                <i class="fas fa-check-circle mr-2"></i>Statut
                            </span>
                            @if($actualite->is_published)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    <i class="fas fa-check mr-1"></i>Publié
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <i class="fas fa-clock mr-1"></i>En attente
                                </span>
                            @endif
                        </div>
                        
                        @if($actualite->published_at)
                        <div class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar mr-2"></i>
                            <strong>Date de publication :</strong> {{ $actualite->published_at->format('d/m/Y à H:i') }}
                        </div>
                        @endif
                        
                        @if($actualite->published_by)
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            <strong>Publié par :</strong> 
                            @if($actualite->publisher)
                                {{ $actualite->publisher->name }}
                            @else
                                Utilisateur #{{ $actualite->published_by }}
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Commentaire de modération -->
                    @if($actualite->moderation_comment)
                    <div class="p-4 rounded-lg border border-blue-200 bg-blue-50">
                        <div class="flex items-start">
                            <i class="fas fa-comment-alt text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-blue-800 mb-2">Commentaire de modération</h4>
                                <p class="text-blue-700 text-sm leading-relaxed">{{ $actualite->moderation_comment }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions de modération -->
                    @can('moderate', $actualite)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                            <h2 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-gavel mr-3"></i>
                                Actions
                            </h2>
                        </div>
                        <div class="p-6 space-y-3">
                            @can('publish_actualites')
                            @if(!$actualite->is_published)
                                <button onclick="moderateActualite('publish')" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye mr-2"></i>
                                    Publier
                                </button>
                            @endif
                            @endcan
                            
                            @can('unpublish_actualites')
                            @if($actualite->is_published)
                                <button onclick="moderateActualite('unpublish')" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye-slash mr-2"></i>
                                    Dépublier
                                </button>
                            @endif
                            @endcan
                            
                            @can('delete_actualites')
                                <button onclick="moderateActualite('delete')" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-trash mr-2"></i>
                                    Supprimer
                                </button>
                            @endcan
                        </div>
                    </div>
                    @endcan
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('site.actualite.show', $actualite->slug) }}" target="_blank" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir sur le site
                    </a>
                    
                    @can('update_actualites')
                    <a href="{{ route('admin.actualite.edit', $actualite) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier cette actualité
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modération unifié -->
@can('moderate', $actualite)
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
                          placeholder="Ajoutez un commentaire de modération...">{{ $actualite->moderation_comment ?? '' }}</textarea>
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
let currentActualiteId = {{ $actualite->id }};

function moderateActualite(action) {
    currentAction = action;
    const modal = document.getElementById('moderationModal');
    const form = document.getElementById('moderationForm');
    const title = document.getElementById('modalTitle');
    const button = document.getElementById('confirmButton');
    const commentSection = document.getElementById('commentSection');
    
    // Configuration selon l'action
    switch(action) {
        case 'publish':
            form.action = '{{ route("admin.actualite.publish", $actualite) }}';
            title.textContent = 'Publier l\'actualité';
            button.innerHTML = '<i class="fas fa-eye mr-2"></i>Publier';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'block';
            break;
            
        case 'unpublish':
            form.action = '{{ route("admin.actualite.unpublish", $actualite) }}';
            title.textContent = 'Dépublier l\'actualité';
            button.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Dépublier';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'block';
            break;
            
        case 'delete':
            form.action = '{{ route("admin.actualite.destroy", $actualite) }}';
            form.querySelector('input[name="_method"]').value = 'DELETE';
            title.textContent = 'Supprimer l\'actualité';
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
