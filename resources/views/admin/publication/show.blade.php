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
                <a href="{{ route('admin.publication.index') }}" class="text-white/70 hover:text-white">Publications</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ Str::limit($publication->titre, 30) }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- En-tête de la publication -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h1 class="text-2xl font-bold text-white">{{ $publication->titre }}</h1>
                </div>
                <div class="p-6">
                    @if($publication->image)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $publication->image) }}" 
                             alt="{{ $publication->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <div class="space-y-4">
                            <div class="text-sm text-iri-gray flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Publié le {{ $publication->created_at->format('d/m/Y à H:i') }}
                            </div>
                            
                            @if($publication->description)
                            <div class="text-iri-dark leading-relaxed">
                                {!! nl2br(e($publication->description)) !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails de la publication -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Détails de la publication
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($publication->auteur)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-user-edit mr-2"></i>Auteur principal
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $publication->auteur }}
                            </div>
                        </div>
                        @endif

                        @if($publication->année_publication)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-alt mr-2"></i>Année de publication
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $publication->année_publication }}
                            </div>
                        </div>
                        @endif

                        @if($publication->journal)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-newspaper mr-2"></i>Journal/Revue
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $publication->journal }}
                            </div>
                        </div>
                        @endif

                        @if($publication->volume)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-book mr-2"></i>Volume
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $publication->volume }}
                            </div>
                        </div>
                        @endif

                        @if($publication->numero)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-hashtag mr-2"></i>Numéro
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $publication->numero }}
                            </div>
                        </div>
                        @endif

                        @if($publication->pages)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-file-alt mr-2"></i>Pages
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $publication->pages }}
                            </div>
                        </div>
                        @endif

                        @if($publication->resume)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-clipboard-list mr-2"></i>Résumé
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $publication->resume }}
                            </div>
                        </div>
                        @endif
                        
                        @if($publication->citation)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-quote-left mr-2"></i>Citation
                            </label>
                            <div class="p-3 bg-gradient-to-r from-iri-accent/10 to-iri-gold/10 rounded-lg border border-iri-accent/20">
                                <em class="text-iri-dark">{{ $publication->citation }}</em>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Auteurs -->
            @if(optional($publication->auteurs)->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-users mr-3"></i>
                        Auteurs ({{ $publication->auteurs->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($publication->auteurs as $auteur)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border">
                                <div class="w-10 h-10 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-iri-dark">{{ $auteur->nom }}</p>
                                    @if($auteur->email)
                                        <p class="text-sm text-iri-gray">{{ $auteur->email }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Lecteur PDF -->
            @if($publication->fichier_pdf)
            @php
                $fileUrl = asset('storage/' . $publication->fichier_pdf);
                $extension = pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION);
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-pdf mr-3"></i>
                        Document PDF
                    </h2>
                </div>
                
                <!-- Contrôles PDF -->
                @if ($extension === 'pdf')
                <div class="sticky top-0 z-50 bg-gradient-to-r from-iri-light to-white py-4 flex flex-wrap items-center gap-4 border-b border-iri-primary/20 shadow-sm px-6">
                    <div class="flex items-center space-x-3 flex-1">
                        <i class="fas fa-search text-iri-primary"></i>
                        <input type="text" id="searchText" placeholder="Rechercher dans le document..." 
                               class="flex-1 max-w-md px-3 py-2 border border-iri-primary/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                        <button id="searchBtn" 
                                class="px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                            <i class="fas fa-search mr-1"></i>Chercher
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button id="prevMatch" 
                                class="hidden px-3 py-2 bg-iri-accent text-white rounded-lg hover:bg-iri-gold transition-colors duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="nextMatch" 
                                class="hidden px-3 py-2 bg-iri-accent text-white rounded-lg hover:bg-iri-gold transition-colors duration-200">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <span id="matchCount" class="hidden text-sm text-iri-gray px-2"></span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <span id="pageCount" class="text-sm text-iri-gray"></span>
                        <a href="{{ $fileUrl }}" target="_blank" 
                           class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200">
                            <i class="fas fa-download mr-1"></i>Télécharger
                        </a>
                    </div>
                </div>
                
                <!-- Conteneur PDF -->
                <div id="pdfViewer" class="p-6 max-w-full mx-auto space-y-8 bg-gray-50"></div>
                @else
                <div class="p-6">
                    <div class="text-center">
                        <i class="fas fa-file text-iri-gray text-4xl mb-4"></i>
                        <p class="text-iri-gray mb-4">Document non-PDF disponible</p>
                        <a href="{{ $fileUrl }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                            <i class="fas fa-download mr-2"></i>Télécharger le fichier
                        </a>
                    </div>
                </div>
                @endif
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
                    <!-- Catégorie -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-folder mr-3 text-iri-gray"></i>
                            <span class="font-medium">Catégorie</span>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-primary/20 text-iri-primary border border-iri-primary/30">
                            {{ $publication->categorie->nom ?? 'Non définie' }}
                        </span>
                    </div>

                    <!-- À la une -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-star mr-3 text-iri-gray"></i>
                            <span class="font-medium">À la une</span>
                        </div>
                        @if($publication->a_la_une)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-check mr-1"></i>Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fas fa-times mr-1"></i>Non
                            </span>
                        @endif
                    </div>

                    <!-- En vedette -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-medal mr-3 text-iri-gray"></i>
                            <span class="font-medium">En vedette</span>
                        </div>
                        @if($publication->en_vedette)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                <i class="fas fa-check mr-1"></i>Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fas fa-times mr-1"></i>Non
                            </span>
                        @endif
                    </div>

                    <!-- Date de création -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus mr-3 text-iri-gray"></i>
                            <span class="font-medium">Créé le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $publication->created_at->format('d/m/Y') }}</span>
                    </div>
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
                    <a href="{{ route('admin.publication.edit', $publication) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier cette publication
                    </a>
                    
                    @if($publication->fichier_pdf)
                    <a href="{{ asset('storage/' . $publication->fichier_pdf) }}" target="_blank" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i>
                        Télécharger le PDF
                    </a>
                    @endif
                    
                    <form action="{{ route('admin.publication.destroy', $publication) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer cette publication
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles pour le lecteur PDF -->
<style>
    #pdfViewer canvas {
        display: block;
        margin: 0 auto 2rem auto;
        width: 100% !important;
        height: auto !important;
        max-width: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    .highlighted {
        background-color: rgba(255, 255, 0, 0.4) !important;
        color: inherit !important;
        padding: 1px 2px;
        border-radius: 2px;
    }
    #pdfViewer {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
</style>

<!-- Scripts PDF.js -->
@if($publication->fichier_pdf && pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION) === 'pdf')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    const url = "{{ asset('storage/' . $publication->fichier_pdf) }}";
    const container = document.getElementById('pdfViewer');
    const pageCountDisplay = document.getElementById('pageCount');
    const matchCountDisplay = document.getElementById('matchCount');
    const prevMatchBtn = document.getElementById('prevMatch');
    const nextMatchBtn = document.getElementById('nextMatch');
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchText');

    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.2;
    let searchMatches = [];
    let currentMatch = -1;

    // Configuration PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    // Charger le PDF
    pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
        pdfDoc = pdfDoc_;
        pageCountDisplay.textContent = `${pdfDoc.numPages} pages`;
        
        // Rendre toutes les pages
        for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
            renderPage(pageNum);
        }
    });

    function renderPage(num) {
        pdfDoc.getPage(num).then(function(page) {
            const viewport = page.getViewport({scale: scale});
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            canvas.id = `page-${num}`;

            // Conteneur pour la page
            const pageContainer = document.createElement('div');
            pageContainer.className = 'relative mb-8';
            pageContainer.appendChild(canvas);
            
            // Numéro de page
            const pageNumber = document.createElement('div');
            pageNumber.className = 'text-center text-sm text-iri-gray mt-2 font-medium';
            pageNumber.textContent = `Page ${num}`;
            pageContainer.appendChild(pageNumber);
            
            container.appendChild(pageContainer);

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            
            page.render(renderContext).promise.then(function() {
                // Page rendue avec succès
            });
        });
    }

    // Fonctionnalité de recherche
    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    function performSearch() {
        const searchTerm = searchInput.value.trim();
        if (!searchTerm) return;

        // Réinitialiser les résultats précédents
        clearHighlights();
        searchMatches = [];
        currentMatch = -1;

        // Rechercher dans chaque page
        for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
            searchInPage(pageNum, searchTerm);
        }
    }

    function searchInPage(pageNum, searchTerm) {
        pdfDoc.getPage(pageNum).then(function(page) {
            page.getTextContent().then(function(textContent) {
                const text = textContent.items.map(item => item.str).join(' ');
                const regex = new RegExp(searchTerm, 'gi');
                let match;
                
                while ((match = regex.exec(text)) !== null) {
                    searchMatches.push({
                        page: pageNum,
                        text: match[0],
                        index: match.index
                    });
                }
                
                updateMatchDisplay();
            });
        });
    }

    function updateMatchDisplay() {
        if (searchMatches.length > 0) {
            matchCountDisplay.textContent = `${searchMatches.length} résultat(s)`;
            matchCountDisplay.classList.remove('hidden');
            prevMatchBtn.classList.remove('hidden');
            nextMatchBtn.classList.remove('hidden');
            currentMatch = 0;
            highlightCurrentMatch();
        } else {
            matchCountDisplay.textContent = 'Aucun résultat';
            matchCountDisplay.classList.remove('hidden');
            prevMatchBtn.classList.add('hidden');
            nextMatchBtn.classList.add('hidden');
        }
    }

    function clearHighlights() {
        const highlights = document.querySelectorAll('.highlighted');
        highlights.forEach(highlight => highlight.classList.remove('highlighted'));
    }

    function highlightCurrentMatch() {
        // Logique de mise en surbrillance simplifiée
        // Dans une implémentation complète, il faudrait coordonner avec le rendu du texte
    }

    // Navigation dans les résultats
    prevMatchBtn.addEventListener('click', function() {
        if (currentMatch > 0) {
            currentMatch--;
            highlightCurrentMatch();
        }
    });

    nextMatchBtn.addEventListener('click', function() {
        if (currentMatch < searchMatches.length - 1) {
            currentMatch++;
            highlightCurrentMatch();
        }
    });
</script>
@endif
@endsection