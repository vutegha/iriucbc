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
                <span class="text-white">Modifier</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Modifier le Rapport')
@section('subtitle', 'Modification de ' . $rapport->titre)

@section('content')

<x-admin-form 
    title="Modifier le Rapport"
    subtitle="Modification de {{ $rapport->titre }}"
    :action="route('admin.rapports.update', $rapport)"
    method="PUT"
    back-route="{{ route('admin.rapports.index') }}"
    back-label="Liste des rapports"
    submit-label="Mettre Ã  jour"
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
                :value="old('titre', $rapport->titre)"
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
                :value="old('description', $rapport->description)"
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
                :value="old('categorie_id', $rapport->categorie_id)"
                :options="$categories->pluck('nom', 'id')->prepend('SÃ©lectionner une catÃ©gorie', '')"
                help="CatÃ©gorie du rapport"
            />

            <x-form-field
                name="date_publication"
                type="date"
                label="Date de publication"
                :value="old('date_publication', $rapport->date_publication ? \Carbon\Carbon::parse($rapport->date_publication)->format('Y-m-d') : '')"
                required="true"
            />
        </div>
    </div>

    <!-- Fichier PDF existant et nouveau -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-file-pdf mr-2 text-coral"></i>
            Document PDF
        </h3>
        
        @if($rapport->fichier)
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <i class="bi bi-file-pdf text-red-500 text-2xl mr-3"></i>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">Fichier actuel:</h4>
                        <p class="text-sm text-gray-600">{{ basename($rapport->fichier) }}</p>
                        @if(file_exists(public_path($rapport->fichier)))
                            @php
                                $fileSize = filesize(public_path($rapport->fichier));
                                $fileSizeFormatted = $fileSize > 1024 * 1024 
                                    ? round($fileSize / (1024 * 1024), 1) . ' MB'
                                    : round($fileSize / 1024, 1) . ' KB';
                            @endphp
                            <p class="text-xs text-gray-500">Taille: {{ $fileSizeFormatted }}</p>
                        @endif
                    </div>
                    <a href="{{ asset($rapport->fichier) }}" target="_blank" 
                       class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                        <i class="bi bi-download mr-1"></i>
                        TÃ©lÃ©charger
                    </a>
                </div>
            </div>
        @endif
        
        <x-form-field
            name="fichier"
            type="file"
            label="{{ $rapport->fichier ? 'Remplacer le fichier PDF' : 'Fichier PDF' }}"
            :value="old('fichier')"
            accept=".pdf"
            help="SÃ©lectionnez un nouveau fichier PDF pour remplacer l'existant (max 50MB)"
        />
    </div>

    <!-- MÃ©tadonnÃ©es -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="auteur"
            type="text"
            label="Auteur(s)"
            placeholder="Nom de l'auteur ou des auteurs"
            :value="old('auteur', $rapport->auteur)"
            help="Auteur(s) du rapport"
        />

        <x-form-field
            name="langue"
            type="select"
            label="Langue"
            :value="old('langue', $rapport->langue ?? 'fr')"
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
            :value="old('pages', $rapport->pages)"
            min="1"
        />

        <x-form-field
            name="taille_fichier"
            type="text"
            label="Taille du fichier"
            placeholder="Ex: 2.5 MB"
            :value="old('taille_fichier', $rapport->taille_fichier)"
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
            :value="old('mots_cles', $rapport->mots_cles)"
            help="Mots-clÃ©s pour faciliter la recherche"
        />

        <x-form-field
            name="resume_executif"
            type="textarea"
            label="RÃ©sumÃ© exÃ©cutif"
            placeholder="RÃ©sumÃ© exÃ©cutif du rapport"
            :value="old('resume_executif', $rapport->resume_executif)"
            rows="3"
            help="RÃ©sumÃ© concis des principales conclusions"
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
                <x-form-field
                    name="is_public"
                    type="checkbox"
                    label="Rapport public"
                    :value="old('is_public', $rapport->is_public ?? true)"
                    help="Le rapport peut Ãªtre tÃ©lÃ©chargÃ© librement"
                />

                @if(auth()->check() && auth()->user()->canModerate())
                    <x-form-field
                        name="is_published"
                        type="checkbox"
                        label="PubliÃ©"
                        :value="old('is_published', $rapport->is_published ?? false)"
                        help="Le rapport est visible sur le site public"
                    />
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="bi bi-info-circle text-gray-600 mr-2"></i>
                            <div class="text-sm text-gray-700">
                                <p class="font-medium">Ã‰tat actuel:</p>
                                <p>{{ $rapport->is_published ? 'PubliÃ©' : 'En attente de modÃ©ration' }}</p>
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
                        :value="old('moderation_comment', $rapport->moderation_comment ?? '')"
                        rows="3"
                        help="Notes internes pour l'Ã©quipe de modÃ©ration"
                    />
                </div>
            @endif
        </div>
    </div>

    <!-- Informations de publication -->
    @if($rapport->is_published && $rapport->published_at)
        <div class="bg-green-50 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="bi bi-check-circle text-green-600 mr-2"></i>
                <div>
                    <p class="text-sm font-medium text-green-800">
                        PubliÃ© le {{ \Carbon\Carbon::parse($rapport->published_at)->format('d/m/Y Ã  H:i') }}
                    </p>
                    @if($rapport->publishedBy)
                        <p class="text-sm text-green-600">
                            par {{ $rapport->publishedBy->prenom }} {{ $rapport->publishedBy->nom }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

</x-admin-form>

@endsection

