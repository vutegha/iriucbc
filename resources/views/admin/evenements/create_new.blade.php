@extends('layouts.admin')

@section('title', 'Créer un Événement')
@section('subtitle', 'Ajouter un nouvel événement')

@section('content')

<x-admin-form 
    title="Créer un Événement"
    subtitle="Ajouter un nouvel événement"
    :action="route('admin.evenements.store')"
    method="POST"
    back-route="{{ route('admin.evenements.index') }}"
    back-label="Liste des événements"
    submit-label="Créer l'événement"
    :multipart="false"
>

    <!-- Informations générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="md:col-span-2">
            <x-form-field
                name="titre"
                type="text"
                label="Titre de l'événement"
                placeholder="Entrez le titre de l'événement"
                :value="old('titre')"
                required="true"
                help="Le titre principal qui apparaîtra sur le site"
            />
        </div>

        <div class="md:col-span-2">
            <x-form-field
                name="description"
                type="textarea"
                label="Description"
                placeholder="Décrivez l'événement en détail"
                :value="old('description')"
                rows="4"
                help="Description complète de l'événement"
            />
        </div>
    </div>

    <!-- Dates et horaires -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-calendar-event mr-2 text-coral"></i>
            Dates et horaires
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-field
                name="date_debut"
                type="date"
                label="Date de début"
                :value="old('date_debut')"
                required="true"
            />

            <x-form-field
                name="date_fin"
                type="date"
                label="Date de fin"
                :value="old('date_fin')"
                help="Laissez vide si l'événement ne dure qu'une journée"
            />

            <x-form-field
                name="heure_debut"
                type="time"
                label="Heure de début"
                :value="old('heure_debut')"
            />

            <x-form-field
                name="heure_fin"
                type="time"
                label="Heure de fin"
                :value="old('heure_fin')"
            />
        </div>
    </div>

    <!-- Lieu -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-geo-alt mr-2 text-coral"></i>
            Localisation
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-field
                name="lieu"
                type="text"
                label="Lieu"
                placeholder="Nom du lieu de l'événement"
                :value="old('lieu')"
            />

            <x-form-field
                name="adresse"
                type="text"
                label="Adresse"
                placeholder="Adresse complète"
                :value="old('adresse')"
            />
        </div>
    </div>

    <!-- Informations supplémentaires -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="organisateur"
            type="text"
            label="Organisateur"
            placeholder="Nom de l'organisateur"
            :value="old('organisateur')"
        />

        <x-form-field
            name="contact"
            type="email"
            label="Contact"
            placeholder="Email de contact"
            :value="old('contact')"
            help="Email pour les informations sur l'événement"
        />

        <x-form-field
            name="capacite"
            type="number"
            label="Capacité d'accueil"
            placeholder="Nombre maximum de participants"
            :value="old('capacite')"
            min="1"
        />

        <x-form-field
            name="prix"
            type="number"
            label="Prix (€)"
            placeholder="0.00"
            :value="old('prix')"
            step="0.01"
            min="0"
            help="Laissez vide ou 0 pour un événement gratuit"
        />
    </div>

    <!-- Options de publication -->
    <div class="bg-blue-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-eye mr-2 text-coral"></i>
            Options de publication
        </h3>
        
        <div class="space-y-4">
            <x-form-field
                name="is_featured"
                type="checkbox"
                label="Événement mis en avant"
                :value="old('is_featured')"
                help="L'événement apparaîtra en tête de liste"
            />

            @if(auth()->check() && auth()->user()->canModerate())
                <x-form-field
                    name="is_published"
                    type="checkbox"
                    label="Publier immédiatement"
                    :value="old('is_published')"
                    help="L'événement sera visible sur le site public"
                />
            @endif
        </div>
    </div>

</x-admin-form>

@endsection
