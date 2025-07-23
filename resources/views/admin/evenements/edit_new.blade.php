@extends('layouts.admin')

@section('title', 'Modifier l\'Événement')
@section('subtitle', 'Modification de ' . $evenement->titre)

@section('content')

<x-admin-form 
    title="Modifier l'Événement"
    subtitle="Modification de {{ $evenement->titre }}"
    :action="route('admin.evenements.update', $evenement)"
    method="PUT"
    back-route="{{ route('admin.evenements.index') }}"
    back-label="Liste des événements"
    submit-label="Mettre à jour"
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
                :value="old('titre', $evenement->titre)"
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
                :value="old('description', $evenement->description)"
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
                :value="old('date_debut', $evenement->date_debut ? $evenement->date_debut->format('Y-m-d') : '')"
                required="true"
            />

            <x-form-field
                name="date_fin"
                type="date"
                label="Date de fin"
                :value="old('date_fin', $evenement->date_fin ? $evenement->date_fin->format('Y-m-d') : '')"
                help="Laissez vide si l'événement ne dure qu'une journée"
            />

            <x-form-field
                name="heure_debut"
                type="time"
                label="Heure de début"
                :value="old('heure_debut', $evenement->heure_debut)"
            />

            <x-form-field
                name="heure_fin"
                type="time"
                label="Heure de fin"
                :value="old('heure_fin', $evenement->heure_fin)"
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
                :value="old('lieu', $evenement->lieu)"
            />

            <x-form-field
                name="adresse"
                type="text"
                label="Adresse"
                placeholder="Adresse complète"
                :value="old('adresse', $evenement->adresse)"
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
            :value="old('organisateur', $evenement->organisateur)"
        />

        <x-form-field
            name="contact"
            type="email"
            label="Contact"
            placeholder="Email de contact"
            :value="old('contact', $evenement->contact)"
            help="Email pour les informations sur l'événement"
        />

        <x-form-field
            name="capacite"
            type="number"
            label="Capacité d'accueil"
            placeholder="Nombre maximum de participants"
            :value="old('capacite', $evenement->capacite)"
            min="1"
        />

        <x-form-field
            name="prix"
            type="number"
            label="Prix (€)"
            placeholder="0.00"
            :value="old('prix', $evenement->prix)"
            step="0.01"
            min="0"
            help="Laissez vide ou 0 pour un événement gratuit"
        />
    </div>

    <!-- État et modération -->
    <div class="bg-blue-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-gear mr-2 text-coral"></i>
            État et modération
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <x-form-field
                    name="is_featured"
                    type="checkbox"
                    label="Événement mis en avant"
                    :value="old('is_featured', $evenement->is_featured ?? false)"
                    help="L'événement apparaîtra en tête de liste"
                />

                @if(auth()->check() && auth()->user()->canModerate())
                    <x-form-field
                        name="is_published"
                        type="checkbox"
                        label="Publié"
                        :value="old('is_published', $evenement->is_published ?? false)"
                        help="L'événement est visible sur le site public"
                    />
                @endif
            </div>

            @if(auth()->check() && auth()->user()->canModerate())
                <div>
                    <x-form-field
                        name="moderation_comment"
                        type="textarea"
                        label="Commentaire de modération"
                        placeholder="Commentaire interne (non visible publiquement)"
                        :value="old('moderation_comment', $evenement->moderation_comment ?? '')"
                        rows="3"
                        help="Notes internes pour l'équipe de modération"
                    />
                </div>
            @endif
        </div>
    </div>

    <!-- Informations de publication -->
    @if($evenement->is_published && $evenement->published_at)
        <div class="bg-green-50 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="bi bi-check-circle text-green-600 mr-2"></i>
                <div>
                    <p class="text-sm font-medium text-green-800">
                        Publié le {{ $evenement->published_at->format('d/m/Y à H:i') }}
                    </p>
                    @if($evenement->publishedBy)
                        <p class="text-sm text-green-600">
                            par {{ $evenement->publishedBy->prenom }} {{ $evenement->publishedBy->nom }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

</x-admin-form>

@endsection
