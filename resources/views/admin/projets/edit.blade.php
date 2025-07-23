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
                <a href="{{ route('admin.projets.index') }}" class="text-white/70 hover:text-white">projets</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">Modifier</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Modifier le Projet')
@section('subtitle', 'Modification de ' . $projet->titre)

@section('content')

<x-admin-form 
    title="Modifier le Projet"
    subtitle="Modification de {{ $projet->titre }}"
    :action="route('admin.projets.update', $projet)"
    method="PUT"
    back-route="{{ route('admin.projets.index') }}"
    back-label="Liste des projets"
    submit-label="Mettre Ã  jour"
    :multipart="false"
>

    <!-- Informations gÃ©nÃ©rales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="md:col-span-2">
            <x-form-field
                name="titre"
                type="text"
                label="Titre du projet"
                placeholder="Entrez le titre du projet"
                :value="old('titre', $projet->titre)"
                required="true"
                help="Le titre principal du projet de recherche"
            />
        </div>

        <div class="md:col-span-2">
            <x-form-field
                name="description"
                type="textarea"
                label="Description"
                placeholder="DÃ©crivez le projet en dÃ©tail"
                :value="old('description', $projet->description)"
                rows="4"
                help="Description complÃ¨te des objectifs et mÃ©thodologies"
            />
        </div>
    </div>

    <!-- Dates et Ã©tat -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-calendar-range mr-2 text-coral"></i>
            Planification et Ã©tat
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-form-field
                name="date_debut"
                type="date"
                label="Date de dÃ©but"
                :value="old('date_debut', $projet->date_debut ? $projet->date_debut->format('Y-m-d') : '')"
                required="true"
            />

            <x-form-field
                name="date_fin"
                type="date"
                label="Date de fin prÃ©vue"
                :value="old('date_fin', $projet->date_fin ? $projet->date_fin->format('Y-m-d') : '')"
                help="Date prÃ©vue de fin du projet"
            />

            <x-form-field
                name="etat"
                type="select"
                label="Ã‰tat du projet"
                :value="old('etat', $projet->etat)"
                :options="[
                    'en cours' => 'En cours',
                    'terminÃ©' => 'TerminÃ©',
                    'suspendu' => 'Suspendu'
                ]"
                required="true"
            />
        </div>
    </div>

    <!-- Budget et bÃ©nÃ©ficiaires -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-cash-stack mr-2 text-coral"></i>
            Budget et impact
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-field
                name="budget"
                type="number"
                label="Budget (â‚¬)"
                placeholder="0.00"
                :value="old('budget', $projet->budget)"
                step="0.01"
                min="0"
                help="Budget total allouÃ© au projet"
            />

            <x-form-field
                name="beneficiaires"
                type="text"
                label="BÃ©nÃ©ficiaires"
                placeholder="Population ciblÃ©e"
                :value="old('beneficiaires', $projet->beneficiaires)"
                help="Description des bÃ©nÃ©ficiaires du projet"
            />

            <x-form-field
                name="beneficiaires_attendu"
                type="number"
                label="Nombre de bÃ©nÃ©ficiaires attendus"
                placeholder="0"
                :value="old('beneficiaires_attendu', $projet->beneficiaires_attendu)"
                min="0"
            />
        </div>
    </div>

    <!-- Partenaires et Ã©quipe -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="partenaires"
            type="textarea"
            label="Partenaires"
            placeholder="Liste des partenaires du projet"
            :value="old('partenaires', $projet->partenaires)"
            rows="3"
            help="Organismes et institutions partenaires"
        />

        <x-form-field
            name="responsable"
            type="text"
            label="Responsable du projet"
            placeholder="Nom du responsable principal"
            :value="old('responsable', $projet->responsable)"
        />
    </div>

    <!-- Localisation -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="localisation"
            type="text"
            label="Localisation"
            placeholder="Lieu de mise en Å“uvre"
            :value="old('localisation', $projet->localisation)"
        />

        <x-form-field
            name="secteur"
            type="text"
            label="Secteur d'activitÃ©"
            placeholder="Domaine de recherche"
            :value="old('secteur', $projet->secteur)"
        />
    </div>

    <!-- Ã‰tat et modÃ©ration -->
    <div class="bg-blue-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-gear mr-2 text-coral"></i>
            Ã‰tat et modÃ©ration
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                @if(auth()->check() && auth()->user()->canModerate())
                    <x-form-field
                        name="is_published"
                        type="checkbox"
                        label="PubliÃ©"
                        :value="old('is_published', $projet->is_published ?? false)"
                        help="Le projet est visible sur le site public"
                    />
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="bi bi-info-circle text-gray-600 mr-2"></i>
                            <div class="text-sm text-gray-700">
                                <p class="font-medium">Ã‰tat actuel:</p>
                                <p>{{ $projet->is_published ? 'PubliÃ©' : 'En attente de modÃ©ration' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if(auth()->check() && auth()->user()->canModerate())
                <div>
                    <x-form-field
                        name="moderation_comment"
                        type="textarea"
                        label="Commentaire de modÃ©ration"
                        placeholder="Commentaire interne (non visible publiquement)"
                        :value="old('moderation_comment', $projet->moderation_comment ?? '')"
                        rows="3"
                        help="Notes internes pour l'Ã©quipe de modÃ©ration"
                    />
                </div>
            @endif
        </div>
    </div>

    <!-- Informations de publication -->
    @if($projet->is_published && $projet->published_at)
        <div class="bg-green-50 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="bi bi-check-circle text-green-600 mr-2"></i>
                <div>
                    <p class="text-sm font-medium text-green-800">
                        PubliÃ© le {{ $projet->published_at->format('d/m/Y Ã  H:i') }}
                    </p>
                    @if($projet->publishedBy)
                        <p class="text-sm text-green-600">
                            par {{ $projet->publishedBy->prenom }} {{ $projet->publishedBy->nom }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

</x-admin-form>

@endsection

