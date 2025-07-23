@extends('layouts.iri')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-iri-primary mb-12 text-center">
        Test des Composants UI - Design IRI
    </h1>
    
    <!-- Section Boutons -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-iri-primary mb-6 border-b-2 border-iri-accent pb-2">
            Boutons
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Boutons primaires -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-iri-secondary">Boutons Primaires</h3>
                <button class="btn-iri-primary w-full">Bouton Principal</button>
                <button class="btn-iri-primary w-full" disabled>Bouton Désactivé</button>
                <a href="#" class="btn-iri-primary block text-center">Lien Bouton</a>
            </div>
            
            <!-- Boutons secondaires -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-iri-secondary">Boutons Secondaires</h3>
                <button class="btn-iri-secondary w-full">Bouton Secondaire</button>
                <button class="btn-iri-secondary w-full" disabled>Bouton Désactivé</button>
                <a href="#" class="btn-iri-secondary block text-center">Lien Bouton</a>
            </div>
            
            <!-- Boutons outline -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-iri-secondary">Boutons Outline</h3>
                <button class="btn-iri-outline w-full">Bouton Outline</button>
                <button class="btn-iri-outline w-full" disabled>Bouton Désactivé</button>
                <a href="#" class="btn-iri-outline block text-center">Lien Bouton</a>
            </div>
        </div>
        
        <!-- Boutons avec icônes -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-iri-secondary mb-4">Boutons avec Icônes</h3>
            <div class="flex flex-wrap gap-4">
                <button class="btn-iri-primary">
                    <i class="fas fa-home mr-2"></i>
                    Accueil
                </button>
                <button class="btn-iri-secondary">
                    <i class="fas fa-search mr-2"></i>
                    Rechercher
                </button>
                <button class="btn-iri-outline">
                    <i class="fas fa-download mr-2"></i>
                    Télécharger
                </button>
            </div>
        </div>
    </section>
    
    <!-- Section Cartes -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-iri-primary mb-6 border-b-2 border-iri-accent pb-2">
            Cartes
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Carte basique -->
            <div class="card-iri">
                <div class="card-body">
                    <h3 class="card-title">Carte Basique</h3>
                    <p class="card-text">
                        Ceci est une carte basique avec le nouveau design IRI. Elle utilise les couleurs et styles institutionnels.
                    </p>
                    <a href="#" class="btn-iri-primary mt-4">En savoir plus</a>
                </div>
            </div>
            
            <!-- Carte avec image -->
            <div class="card-iri">
                <img src="{{ asset('assets/img/iri.jpg') }}" alt="Image test" class="w-full h-48 object-cover rounded-t-lg">
                <div class="card-body">
                    <h3 class="card-title">Carte avec Image</h3>
                    <p class="card-text">
                        Cette carte inclut une image et démontre l'adaptation du design aux contenus visuels.
                    </p>
                    <a href="#" class="btn-iri-secondary mt-4">Voir plus</a>
                </div>
            </div>
            
            <!-- Carte avec badge -->
            <div class="card-iri">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="card-title">Carte avec Badge</h3>
                        <span class="badge-iri">Nouveau</span>
                    </div>
                    <p class="card-text">
                        Cette carte inclut un badge pour mettre en évidence des informations importantes.
                    </p>
                    <a href="#" class="btn-iri-outline mt-4">Découvrir</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section Liens -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-iri-primary mb-6 border-b-2 border-iri-accent pb-2">
            Liens
        </h2>
        
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium text-iri-secondary mb-2">Liens Standards</h3>
                <p class="text-gray-700">
                    Voici un <a href="#" class="link-iri">lien standard</a> dans un paragraphe.
                    Et voici un <a href="#" class="link-iri-secondary">lien secondaire</a> avec un style différent.
                </p>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-iri-secondary mb-2">Liens avec Icônes</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="link-iri"><i class="fas fa-file-pdf mr-2"></i>Télécharger PDF</a></li>
                    <li><a href="#" class="link-iri"><i class="fas fa-external-link-alt mr-2"></i>Lien externe</a></li>
                    <li><a href="#" class="link-iri"><i class="fas fa-envelope mr-2"></i>Contact</a></li>
                </ul>
            </div>
        </div>
    </section>
    
    <!-- Section Tableau -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-iri-primary mb-6 border-b-2 border-iri-accent pb-2">
            Tableaux
        </h2>
        
        <div class="table-iri-container">
            <table class="table-iri">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Publication 1</td>
                        <td>Rapport</td>
                        <td>15 Jan 2024</td>
                        <td><span class="badge-iri">Publié</span></td>
                        <td>
                            <button class="btn-iri-secondary text-xs">Voir</button>
                            <button class="btn-iri-outline text-xs ml-2">Modifier</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Publication 2</td>
                        <td>Article</td>
                        <td>12 Jan 2024</td>
                        <td><span class="badge-iri bg-yellow-500">Brouillon</span></td>
                        <td>
                            <button class="btn-iri-secondary text-xs">Voir</button>
                            <button class="btn-iri-outline text-xs ml-2">Modifier</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Publication 3</td>
                        <td>Document</td>
                        <td>10 Jan 2024</td>
                        <td><span class="badge-iri">Publié</span></td>
                        <td>
                            <button class="btn-iri-secondary text-xs">Voir</button>
                            <button class="btn-iri-outline text-xs ml-2">Modifier</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    
    <!-- Section Formulaires -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-iri-primary mb-6 border-b-2 border-iri-accent pb-2">
            Formulaires
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Formulaire de contact -->
            <div class="card-iri">
                <div class="card-body">
                    <h3 class="card-title">Formulaire de Contact</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="form-label">Nom complet</label>
                            <input type="text" class="form-input" placeholder="Votre nom">
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" placeholder="votre@email.com">
                        </div>
                        <div>
                            <label class="form-label">Message</label>
                            <textarea class="form-textarea" rows="4" placeholder="Votre message"></textarea>
                        </div>
                        <button type="submit" class="btn-iri-primary w-full">Envoyer</button>
                    </form>
                </div>
            </div>
            
            <!-- Formulaire de recherche -->
            <div class="card-iri">
                <div class="card-body">
                    <h3 class="card-title">Recherche Avancée</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="form-label">Recherche</label>
                            <input type="search" class="form-input" placeholder="Rechercher...">
                        </div>
                        <div>
                            <label class="form-label">Catégorie</label>
                            <select class="form-select">
                                <option>Toutes les catégories</option>
                                <option>Rapports</option>
                                <option>Articles</option>
                                <option>Documents</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Date</label>
                            <input type="date" class="form-input">
                        </div>
                        <div class="flex space-x-4">
                            <button type="submit" class="btn-iri-primary flex-1">Rechercher</button>
                            <button type="reset" class="btn-iri-outline flex-1">Réinitialiser</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section Alertes -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-iri-primary mb-6 border-b-2 border-iri-accent pb-2">
            Alertes et Notifications
        </h2>
        
        <div class="space-y-4">
            <div class="alert-iri-success">
                <i class="fas fa-check-circle mr-2"></i>
                <strong>Succès!</strong> Votre message a été envoyé avec succès.
            </div>
            
            <div class="alert-iri-info">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Information:</strong> Nouvelle mise à jour disponible.
            </div>
            
            <div class="alert-iri-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Attention:</strong> Veuillez vérifier vos informations.
            </div>
            
            <div class="alert-iri-error">
                <i class="fas fa-times-circle mr-2"></i>
                <strong>Erreur:</strong> Une erreur s'est produite lors du traitement.
            </div>
        </div>
    </section>
    
    <!-- Section Navigation -->
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-iri-primary mb-6 border-b-2 border-iri-accent pb-2">
            Navigation
        </h2>
        
        <div class="space-y-6">
            <!-- Breadcrumb -->
            <div>
                <h3 class="text-lg font-medium text-iri-secondary mb-2">Fil d'Ariane</h3>
                <nav class="breadcrumb-iri">
                    <a href="#" class="breadcrumb-item">Accueil</a>
                    <span class="breadcrumb-separator">/</span>
                    <a href="#" class="breadcrumb-item">Publications</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">Rapports</span>
                </nav>
            </div>
            
            <!-- Pagination -->
            <div>
                <h3 class="text-lg font-medium text-iri-secondary mb-2">Pagination</h3>
                <nav class="pagination-iri">
                    <a href="#" class="pagination-item">← Précédent</a>
                    <a href="#" class="pagination-item active">1</a>
                    <a href="#" class="pagination-item">2</a>
                    <a href="#" class="pagination-item">3</a>
                    <span class="pagination-dots">...</span>
                    <a href="#" class="pagination-item">10</a>
                    <a href="#" class="pagination-item">Suivant →</a>
                </nav>
            </div>
        </div>
    </section>
</div>
@endsection
