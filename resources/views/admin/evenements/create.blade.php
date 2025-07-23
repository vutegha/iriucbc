@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.evenements.index') }}" class="text-white/70 hover:text-white">Événements</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">Nouveau</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form action="{{ route('admin.evenements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Créer un Événement</h1>
                        <p class="text-white/80 mt-1">Ajouter un nouvel événement</p>
                    </div>
                    <a href="{{ route('admin.evenements.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-iri-primary rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations générales -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Informations générales
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Titre -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-iri-gray mb-2">
                        <i class="fas fa-heading mr-2"></i>Titre de l'événement *
                    </label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('titre') border-red-500 @enderror"
                           placeholder="Entrez le titre de l'événement">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Résumé -->
                <div>
                    <label for="resume" class="block text-sm font-medium text-iri-gray mb-2">
                        <i class="fas fa-clipboard-list mr-2"></i>Résumé
                    </label>
                    <textarea name="resume" id="resume" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('resume') border-red-500 @enderror"
                              placeholder="Résumé court de l'événement">{{ old('resume') }}</textarea>
                    @error('resume')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-iri-gray mb-2">
                        <i class="fas fa-file-alt mr-2"></i>Description détaillée
                    </label>
                    <textarea name="description" id="description" rows="6" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('description') border-red-500 @enderror"
                              placeholder="Décrivez l'événement en détail">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-iri-gray mb-2">
                        <i class="fas fa-image mr-2"></i>Image de l'événement
                    </label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-iri-gray">Formats acceptés : JPG, PNG, GIF. Taille max : 2MB</p>
                </div>
            </div>
        </div>

        <!-- Dates et horaires -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Dates et horaires
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date de début -->
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-calendar-plus mr-2"></i>Date de début *
                        </label>
                        <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('date_debut') border-red-500 @enderror">
                        @error('date_debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date de fin -->
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-calendar-minus mr-2"></i>Date de fin
                        </label>
                        <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('date_fin') border-red-500 @enderror">
                        @error('date_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-iri-gray">Laissez vide si l'événement ne dure qu'une journée</p>
                    </div>

                    <!-- Heure de début -->
                    <div>
                        <label for="heure_debut" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-clock mr-2"></i>Heure de début
                        </label>
                        <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('heure_debut') border-red-500 @enderror">
                        @error('heure_debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Heure de fin -->
                    <div>
                        <label for="heure_fin" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-clock mr-2"></i>Heure de fin
                        </label>
                        <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('heure_fin') border-red-500 @enderror">
                        @error('heure_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Localisation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-map-marker-alt mr-3"></i>
                    Localisation
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Lieu -->
                    <div>
                        <label for="lieu" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-building mr-2"></i>Lieu
                        </label>
                        <input type="text" name="lieu" id="lieu" value="{{ old('lieu') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('lieu') border-red-500 @enderror"
                               placeholder="Nom du lieu de l'événement">
                        @error('lieu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Adresse
                        </label>
                        <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('adresse') border-red-500 @enderror"
                               placeholder="Adresse complète">
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-info mr-3"></i>
                    Informations complémentaires
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Organisateur -->
                    <div>
                        <label for="organisateur" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-user-tie mr-2"></i>Organisateur
                        </label>
                        <input type="text" name="organisateur" id="organisateur" value="{{ old('organisateur') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('organisateur') border-red-500 @enderror"
                               placeholder="Nom de l'organisateur">
                        @error('organisateur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact -->
                    <div>
                        <label for="contact" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-envelope mr-2"></i>Contact
                        </label>
                        <input type="email" name="contact" id="contact" value="{{ old('contact') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('contact') border-red-500 @enderror"
                               placeholder="Email de contact">
                        @error('contact')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-iri-gray">Email pour les informations sur l'événement</p>
                    </div>

                    <!-- Capacité -->
                    <div>
                        <label for="capacite" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-users mr-2"></i>Capacité d'accueil
                        </label>
                        <input type="number" name="capacite" id="capacite" value="{{ old('capacite') }}" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('capacite') border-red-500 @enderror"
                               placeholder="Nombre maximum de participants">
                        @error('capacite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="prix" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-euro-sign mr-2"></i>Prix (€)
                        </label>
                        <input type="number" name="prix" id="prix" value="{{ old('prix') }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('prix') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('prix')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-iri-gray">Laissez vide ou 0 pour un événement gratuit</p>
                    </div>

                    <!-- URL du rapport -->
                    <div class="md:col-span-2">
                        <label for="rapport_url" class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-file-pdf mr-2"></i>URL du rapport (optionnel)
                        </label>
                        <input type="url" name="rapport_url" id="rapport_url" value="{{ old('rapport_url') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('rapport_url') border-red-500 @enderror"
                               placeholder="https://exemple.com/rapport.pdf">
                        @error('rapport_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-iri-gray">Lien vers un rapport ou document associé à l'événement</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Options de publication -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-gold to-iri-accent">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-eye mr-3"></i>
                    Options de publication
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Événement en vedette -->
                    <div class="flex items-center p-3 border rounded-lg">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                        <label for="is_featured" class="ml-3 block text-sm text-iri-gray">
                            <span class="font-medium">Événement mis en avant</span>
                            <span class="block text-xs text-iri-gray/70">L'événement apparaîtra en tête de liste</span>
                        </label>
                    </div>

                    @if(auth()->check() && auth()->user()->canModerate())
                        <!-- Publication immédiate -->
                        <div class="flex items-center p-3 border rounded-lg">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                                   class="h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                            <label for="is_published" class="ml-3 block text-sm text-iri-gray">
                                <span class="font-medium">Publier immédiatement</span>
                                <span class="block text-xs text-iri-gray/70">L'événement sera visible sur le site public</span>
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <a href="{{ route('admin.evenements.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
            
            <div class="flex space-x-4">
                <button type="submit" name="action" value="draft"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer en brouillon
                </button>
                
                <button type="submit" name="action" value="publish"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Créer l'événement
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
