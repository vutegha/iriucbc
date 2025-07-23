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
                <a href="{{ route('admin.rapports.index') }}" class="text-white/70 hover:text-white">rapports</a>
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

@section('title', 'CrÃ©er un Rapport')
@section('subtitle', 'Ajouter un nouveau rapport institutionnel')

@section('content')

<x-admin-form 
    title="CrÃ©er un Rapport"
    subtitle="Ajouter un nouveau rapport institutionnel"
    :action="route('admin.rapports.store')"
    method="POST"
    back-route="{{ route('admin.rapports.index') }}"
    back-label="Liste des rapports"
    submit-label="CrÃ©er le rapport"
    :multipart="true"
>

    <!-- Informations gÃ©nÃ©rales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="md:col-span-2">
            <x-form-field
                name="titre"
                type="text"
                label="Titre du rapport"
                placeholder="Entrez le titre du rapport"
                :value="old('titre')"
                required="true"
                help="Le titre principal du rapport"
            />
        </div>

        <div class="md:col-span-2">
            <x-form-field
                name="description"
                type="textarea"
                label="Description"
                placeholder="DÃ©crivez le contenu du rapport"
                :value="old('description')"
                rows="4"
                help="RÃ©sumÃ© du contenu du rapport"
            />
        </div>
    </div>

    <!-- CatÃ©gorie et dates -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-tags mr-2 text-coral"></i>
            Classification et dates
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-field
                name="categorie_id"
                type="select"
                label="CatÃ©gorie"
                :value="old('categorie_id')"
                :options="$categories->pluck('nom', 'id')->prepend('SÃ©lectionner une catÃ©gorie', '')"
                help="CatÃ©gorie du rapport"
            />

            <x-form-field
                name="date_publication"
                type="date"
                label="Date de publication"
                :value="old('date_publication', now()->format('Y-m-d'))"
                required="true"
            />
        </div>
    </div>

    <!-- Fichier PDF -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-file-pdf mr-2 text-coral"></i>
            Document PDF
        </h3>
        
        <x-form-field
            name="fichier"
            type="file"
            label="Fichier PDF"
            :value="old('fichier')"
            accept=".pdf"
            help="SÃ©lectionnez le fichier PDF du rapport (max 50MB)"
        />
    </div>

    <!-- MÃ©tadonnÃ©es -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="auteur"
            type="text"
            label="Auteur(s)"
            placeholder="Nom de l'auteur ou des auteurs"
            :value="old('auteur')"
            help="Auteur(s) du rapport"
        />

        <x-form-field
            name="langue"
            type="select"
            label="Langue"
            :value="old('langue', 'fr')"
            :options="[
                'fr' => 'FranÃ§ais',
                'en' => 'Anglais',
                'es' => 'Espagnol',
                'de' => 'Allemand'
            ]"
        />

        <x-form-field
            name="pages"
            type="number"
            label="Nombre de pages"
            placeholder="0"
            :value="old('pages')"
            min="1"
        />

        <x-form-field
            name="taille_fichier"
            type="text"
            label="Taille du fichier"
            placeholder="Ex: 2.5 MB"
            :value="old('taille_fichier')"
            help="Taille approximative du fichier"
        />
    </div>

    <!-- Mots-clÃ©s et rÃ©sumÃ© -->
    <div class="grid grid-cols-1 gap-6 mb-6">
        <x-form-field
            name="mots_cles"
            type="text"
            label="Mots-clÃ©s"
            placeholder="SÃ©parez les mots-clÃ©s par des virgules"
            :value="old('mots_cles')"
            help="Mots-clÃ©s pour faciliter la recherche"
        />

        <x-form-field
            name="resume_executif"
            type="textarea"
            label="RÃ©sumÃ© exÃ©cutif"
            placeholder="RÃ©sumÃ© exÃ©cutif du rapport"
            :value="old('resume_executif')"
            rows="3"
            help="RÃ©sumÃ© concis des principales conclusions"
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
                name="is_public"
                type="checkbox"
                label="Rapport public"
                :value="old('is_public', true)"
                help="Le rapport peut Ãªtre tÃ©lÃ©chargÃ© librement"
            />

            @if(auth()->check() && auth()->user()->canModerate())
                <x-form-field
                    name="is_published"
                    type="checkbox"
                    label="Publier immÃ©diatement"
                    :value="old('is_published')"
                    help="Le rapport sera visible sur le site public"
                />
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="bi bi-info-circle text-yellow-600 mr-2"></i>
                        <div class="text-sm text-yellow-700">
                            <p class="font-medium">Information</p>
                            <p>Votre rapport sera soumis Ã  modÃ©ration avant publication.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-admin-form>

@endsection

