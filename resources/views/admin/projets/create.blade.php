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
                <span class="text-white">Nouveau</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'CrÃ©er un Projet')
@section('subtitle', 'Ajouter un nouveau projet de recherche')

@section('content')

<x-admin-form 
    title="CrÃ©er un Projet"
    subtitle="Ajouter un nouveau projet de recherche"
    :action="route('admin.projets.store')"
    method="POST"
    back-route="{{ route('admin.projets.index') }}"
    back-label="Liste des projets"
    submit-label="CrÃ©er le projet"
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
                :value="old('titre')"
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
                :value="old('description')"
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
                :value="old('date_debut')"
                required="true"
            />

            <x-form-field
                name="date_fin"
                type="date"
                label="Date de fin prÃ©vue"
                :value="old('date_fin')"
                help="Date prÃ©vue de fin du projet"
            />

            <x-form-field
                name="etat"
                type="select"
                label="Ã‰tat du projet"
                :value="old('etat', 'en cours')"
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
                :value="old('budget')"
                step="0.01"
                min="0"
                help="Budget total allouÃ© au projet"
            />

            <x-form-field
                name="beneficiaires"
                type="text"
                label="BÃ©nÃ©ficiaires"
                placeholder="Population ciblÃ©e"
                :value="old('beneficiaires')"
                help="Description des bÃ©nÃ©ficiaires du projet"
            />

            <x-form-field
                name="beneficiaires_attendu"
                type="number"
                label="Nombre de bÃ©nÃ©ficiaires attendus"
                placeholder="0"
                :value="old('beneficiaires_attendu')"
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
            :value="old('partenaires')"
            rows="3"
            help="Organismes et institutions partenaires"
        />

        <x-form-field
            name="responsable"
            type="text"
            label="Responsable du projet"
            placeholder="Nom du responsable principal"
            :value="old('responsable')"
        />
    </div>

    <!-- Localisation -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="localisation"
            type="text"
            label="Localisation"
            placeholder="Lieu de mise en Å“uvre"
            :value="old('localisation')"
        />

        <x-form-field
            name="secteur"
            type="text"
            label="Secteur d'activitÃ©"
            placeholder="Domaine de recherche"
            :value="old('secteur')"
        />
    </div>

    <!-- Options de publication -->
    <div class="bg-blue-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-eye mr-2 text-coral"></i>
            Options de publication
        </h3>
        
        <div class="space-y-4">
            @if(auth()->check() && auth()->user()->canModerate())
                <x-form-field
                    name="is_published"
                    type="checkbox"
                    label="Publier immÃ©diatement"
                    :value="old('is_published')"
                    help="Le projet sera visible sur le site public"
                />
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="bi bi-info-circle text-yellow-600 mr-2 mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium">ModÃ©ration requise</p>
                            <p>Ce projet sera soumis Ã  modÃ©ration avant publication.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-admin-form>

@endsection

