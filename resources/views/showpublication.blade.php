@extends('layouts.iri')

@section('title', 'Publication - ' . $publication->titre)

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <!-- Breadcrumb Overlay -->
        @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
            <nav class="absolute top-4 left-0 right-0 z-10" aria-label="Breadcrumb">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ url('/') }}" class="text-white/70 hover:text-white transition-colors duration-200">
                                <i class="fas fa-home mr-1"></i>
                                Accueil
                            </a>
                        </li>
                        
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-white/50 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                @if($loop->last)
                                    <span class="text-white font-medium">{{ $breadcrumb['title'] }}</span>
                                @else
                                    <a href="{{ $breadcrumb['url'] }}" class="text-white/70 hover:text-white transition-colors duration-200">
                                        {{ $breadcrumb['title'] }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            </nav>
        @endif
        
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <!-- Content -->
                <div class="lg:col-span-2">
                    <!-- Category Badge -->
                    @php
                        $categoryName = $publication->categorie->nom ?? 'Non catégorisé';
                        $badgeClass = match ($categoryName) {
                            'Rapport' => 'bg-blue-500/20 text-blue-100 border-blue-300/30',
                            'Article' => 'bg-yellow-500/20 text-yellow-100 border-yellow-300/30',
                            'Document' => 'bg-purple-500/20 text-purple-100 border-purple-300/30',
                            'Publication scientifique' => 'bg-emerald-500/20 text-emerald-100 border-emerald-300/30',
                            'Actualité' => 'bg-red-500/20 text-red-100 border-red-300/30',
                            default => 'bg-white/20 text-white border-white/30',
                        };
                    @endphp
                    
                    <div class="inline-flex items-center {{ $badgeClass }} border backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-tag mr-2"></i>
                        {{ $categoryName }}
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                        {{ $publication->titre }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-white/90 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>{{ $publication->created_at->format('d M Y') }}</span>
                        </div>
                        @if($publication->auteur)
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                <span>{{ $publication->auteur->nom }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <button onclick="showToastAgain()"
                                class="bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-3 px-6 rounded-lg hover:bg-white/30 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-book-open mr-2"></i>
                            Lire le résumé
                        </button>
                        
                        @if($publication->fichier_pdf)
                            <a href="{{ asset('storage/'.$publication->fichier_pdf) }}" 
                               target="_blank"
                               class="bg-iri-gold/80 backdrop-blur-sm border border-iri-gold/50 text-white font-semibold py-3 px-6 rounded-lg hover:bg-iri-gold transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger PDF
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Publication Preview -->
                <div class="lg:col-span-1">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-2xl">
                        @if($publication->fichier_pdf)
                            <canvas id="pdf-preview" 
                                    class="w-full rounded-lg shadow-lg" 
                                    data-pdf-url="{{ asset('storage/'.$publication->fichier_pdf) }}">
                            </canvas>
                        @else
                            <div class="aspect-[3/4] bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-white/50 text-6xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Resume Toast/Modal -->
    <div id="resumeToast"
         class="fixed left-6 top-[20vh] bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-md w-full overflow-hidden z-50 hidden"
         style="max-height: 70vh;">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary text-white p-4 flex justify-between items-center">
            <h4 class="text-lg font-bold flex items-center">
                <i class="fas fa-book-open mr-2"></i>
                Résumé de la publication
            </h4>
            <button onclick="closeToast()" class="text-white/80 hover:text-white text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto" style="max-height: calc(70vh - 80px);">
            <p class="text-gray-700 leading-relaxed">
                {{ $publication->resume ?? 'Aucun résumé disponible pour cette publication.' }}
            </p>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button onclick="showToastAgain()"
            class="fixed bottom-6 left-6 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-full shadow-lg w-14 h-14 flex items-center justify-center hover:shadow-xl transform hover:scale-110 transition-all duration-200 z-[60]">
        <i class="fas fa-book-open text-lg"></i>
    </button>

    <!-- Main Content Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                        <!-- Back Button -->
                        <div class="mb-6">
                            <a href="{{ route('site.publications') }}" 
                               class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour aux publications
                            </a>
                        </div>

                        <!-- Publication Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Informations</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar-alt mr-3 text-iri-primary w-4"></i>
                                    <span>{{ $publication->created_at->format('d M Y') }}</span>
                                </div>
                                @if($publication->auteur)
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user mr-3 text-iri-primary w-4"></i>
                                        <span>{{ $publication->auteur->nom }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-tag mr-3 text-iri-primary w-4"></i>
                                    <span>{{ $publication->categorie->nom ?? 'Non catégorisé' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Citation -->
                        @if($publication->citation)
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-iri-primary">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment citer :</h4>
                                <p class="text-sm text-gray-700 italic">{{ $publication->citation }}</p>
                            </div>
                        @endif

                        <!-- Related Publications -->
                        @if(optional($autresPublications)->count() > 0)
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 mb-4">Publications similaires</h4>
                                <div class="space-y-3">
                                    @foreach($autresPublications->take(3) as $otherPub)
                                        <a href="{{ route('publication.show', $otherPub->slug) }}" 
                                           class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                            <h5 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2">
                                                {{ $otherPub->titre }}
                                            </h5>
                                            <p class="text-xs text-gray-500">
                                                {{ $otherPub->created_at->format('d M Y') }}
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-white p-6 border-b border-gray-200">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $publication->titre }}</h2>
                            
                            @if($publication->description)
                                <div class="prose prose-gray max-w-none">
                                    {!! $publication->description !!}
                                </div>
                            @endif
                        </div>

                        <!-- PDF Viewer Optimized -->
                        @if ($extension === 'pdf')
                            @php
                                $file = Storage::url($publication->fichier_pdf ?? '');
                            @endphp
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                <!-- PDF Header with Search -->
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 border-b">
                                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                                        <div class="flex items-center gap-4">
                                            <input type="text" id="searchText" 
                                                   placeholder="Rechercher dans le document..." 
                                                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                            <button id="searchBtn" 
                                                    class="bg-iri-primary text-white px-4 py-2 rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            <button id="resetBtn" 
                                                    class="hidden bg-gray-500 text-white px-3 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                                <i class="fas fa-times mr-1"></i>Reset
                                            </button>
                                            <button id="prevMatch" 
                                                    class="hidden bg-iri-accent text-white px-3 py-2 rounded-lg hover:bg-iri-gold transition-colors duration-200">
                                                ←
                                            </button>
                                            <button id="nextMatch" 
                                                    class="hidden bg-iri-accent text-white px-3 py-2 rounded-lg hover:bg-iri-gold transition-colors duration-200">
                                                →
                                            </button>
                                            <span id="matchCount" class="hidden text-sm text-gray-600"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center justify-between gap-4">
                                        <div id="pdfControls" class="flex items-center space-x-2">
                                            <button id="prevPage" 
                                                    class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors duration-200"
                                                    title="Page précédente">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-600">Page</span>
                                                <input type="number" 
                                                       id="pageInput" 
                                                       min="1" 
                                                       value="1"
                                                       class="w-16 px-2 py-1 text-center border border-gray-300 rounded text-sm focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                                <span class="text-sm text-gray-600">sur <span id="totalPages">-</span></span>
                                            </div>
                                            
                                            <button id="nextPage" 
                                                    class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors duration-200"
                                                    title="Page suivante">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ $file }}" 
                                               target="_blank"
                                               class="bg-iri-accent text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors duration-200 flex items-center">
                                                <i class="fas fa-download mr-2"></i>
                                                Télécharger
                                            </a>
                                            <button id="fullscreenBtn" 
                                                    class="bg-iri-secondary text-white px-3 py-2 rounded-lg hover:bg-iri-primary transition-colors duration-200">
                                                <i class="fas fa-expand"></i>
                                            </button>
                                            <span id="pageCount" class="text-sm text-gray-600"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Navigation Bar -->
                                <div id="floatingNav" class="fixed top-0 left-0 right-0 bg-white shadow-lg border-b border-gray-200 z-50 hidden transform -translate-y-full transition-transform duration-300">
                                    <div class="max-w-7xl mx-auto px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex items-center space-x-2">
                                                    <button id="floatingPrevPage" 
                                                            class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors duration-200"
                                                            title="Page précédente">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </button>
                                                    
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-sm text-gray-600">Page</span>
                                                        <input type="number" 
                                                               id="floatingPageInput" 
                                                               min="1" 
                                                               value="1"
                                                               class="w-16 px-2 py-1 text-center border border-gray-300 rounded text-sm focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                                        <span class="text-sm text-gray-600">sur <span id="floatingTotalPages">-</span></span>
                                                    </div>
                                                    
                                                    <button id="floatingNextPage" 
                                                            class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors duration-200"
                                                            title="Page suivante">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2">
                                                <!-- Search indicators for floating nav -->
                                                <div id="floatingSearchInfo" class="hidden flex items-center space-x-2">
                                                    <button id="floatingPrevMatch" 
                                                            class="bg-iri-accent text-white px-2 py-2 rounded hover:bg-iri-gold transition-colors duration-200">
                                                        ←
                                                    </button>
                                                    <span id="floatingMatchCount" class="text-sm text-gray-600 px-2"></span>
                                                    <button id="floatingNextMatch" 
                                                            class="bg-iri-accent text-white px-2 py-2 rounded hover:bg-iri-gold transition-colors duration-200">
                                                        →
                                                    </button>
                                                </div>
                                                
                                                <button id="floatingFullscreen" 
                                                        class="bg-iri-secondary text-white px-3 py-2 rounded-lg hover:bg-iri-primary transition-colors duration-200">
                                                    <i class="fas fa-expand"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PDF Content Container -->
                                <div id="pdfContainer" class="relative min-h-96" data-pdf-url="{{ $file }}">
                                    <!-- Loading State (Initial) -->
                                    <div id="pdfLoader" class="flex flex-col items-center justify-center p-12 bg-gray-50">
                                        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary w-16 h-16 rounded-full flex items-center justify-center mb-4 animate-pulse">
                                            <i class="fas fa-file-pdf text-white text-2xl"></i>
                                        </div>
                                        <p class="text-gray-700 font-medium text-lg mb-4">Chargement du document PDF...</p>
                                        <div class="w-full max-w-md">
                                            <div id="pdfProgressContainer" class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                                <div id="pdfProgressBar" class="bg-gradient-to-r from-iri-primary to-iri-secondary h-3 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                            </div>
                                            <div id="pdfProgressText" class="text-sm text-gray-600 text-center">Initialisation...</div>
                                        </div>
                                    </div>

                                    <!-- PDF Viewer Container -->
                                    <div id="pdfViewer" class="hidden p-6 space-y-6 bg-gray-50"></div>
                                </div>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="bg-red-100 text-red-800 p-6 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-3xl mb-4"></i>
                                    <p class="text-lg">Ce type de fichier n'est pas pris en charge pour l'aperçu direct.</p>
                                    @if($publication->fichier_pdf)
                                        <a href="{{ asset('storage/'.$publication->fichier_pdf) }}" 
                                           target="_blank"
                                           class="inline-flex items-center mt-4 bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-200">
                                            <i class="fas fa-download mr-2"></i>
                                            Télécharger le fichier
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Scripts -->
<script>
    function closeToast() {
        const toast = document.getElementById('resumeToast');
        toast.classList.add('hidden');
    }

    function showToastAgain() {
        const toast = document.getElementById('resumeToast');
        toast.classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Auto-show resume toast after 3 seconds
        setTimeout(function () {
            showToastAgain();
        }, 3000);
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // PDF.js Worker configuration
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        // Render PDF preview in hero section (optimized - just first page)
        const previewCanvas = document.getElementById('pdf-preview');
        if (previewCanvas) {
            const url = previewCanvas.getAttribute('data-pdf-url');
            renderPdfPreview(url, previewCanvas);
        }

        // Auto-load main PDF viewer
        const pdfContainer = document.getElementById('pdfContainer');
        if (pdfContainer) {
            const pdfUrl = pdfContainer.getAttribute('data-pdf-url');
            if (pdfUrl) {
                autoLoadPdfViewer(pdfUrl);
            }
        }
    });

    function renderPdfPreview(url, canvas) {
        const ctx = canvas.getContext('2d');
        const containerWidth = canvas.parentElement.offsetWidth;

        // Only load first page for preview
        pdfjsLib.getDocument({
            url: url,
            disableWorker: false,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true
        }).promise.then(pdf => {
            return pdf.getPage(1);
        }).then(page => {
            const viewport = page.getViewport({ scale: 1 });
            const scale = Math.min(containerWidth / viewport.width, 0.8);
            const scaledViewport = page.getViewport({ scale });

            canvas.width = scaledViewport.width;
            canvas.height = scaledViewport.height;

            return page.render({ 
                canvasContext: ctx, 
                viewport: scaledViewport,
                intent: 'display'
            }).promise;
        }).catch(err => {
            console.error("Erreur lors du rendu du PDF preview :", err);
            canvas.style.display = 'none';
        });
    }

    function autoLoadPdfViewer(url) {
        const loaderDiv = document.getElementById('pdfLoader');
        const viewerDiv = document.getElementById('pdfViewer');
        
        let pdfDocument = null;
        let currentPage = 1;
        let scale = 1.0;
        let searchMatches = [];
        let currentMatch = 0;

        // Show loader initially
        showLoader();
        updateProgress(0, 'Initialisation...');

        // Load PDF document
        const loadingOptions = {
            url: url,
            disableWorker: false,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true,
            disableAutoFetch: false,
            disableStream: false
        };

        const loadingTask = pdfjsLib.getDocument(loadingOptions);
        
        // Progress tracking
        loadingTask.onProgress = function(progress) {
            if (progress.loaded && progress.total) {
                const percent = Math.round((progress.loaded / progress.total) * 100);
                updateProgress(percent, `Chargement... ${formatBytes(progress.loaded)} / ${formatBytes(progress.total)}`);
            }
        };

        loadingTask.promise.then(function(pdf) {
            pdfDocument = pdf;
            updateProgress(100, 'Rendu en cours...');
            
            setTimeout(() => {
                setupPdfViewer(pdf);
                showViewer();
            }, 500);
        }).catch(function(error) {
            console.error('Erreur lors du chargement du PDF:', error);
            loaderDiv.innerHTML = `
                <div class="text-center">
                    <div class="bg-red-100 text-red-800 p-6 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-3xl mb-4"></i>
                        <p class="text-lg mb-4">Erreur lors du chargement du PDF</p>
                        <a href="${url}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            Ouvrir dans un nouvel onglet
                        </a>
                    </div>
                </div>
            `;
        });

        function setupPdfViewer(pdf) {
            document.getElementById('totalPages').textContent = pdf.numPages;
            document.getElementById('floatingTotalPages').textContent = pdf.numPages;
            document.getElementById('pageInput').value = currentPage;
            document.getElementById('floatingPageInput').value = currentPage;
            document.getElementById('pageInput').max = pdf.numPages;
            document.getElementById('floatingPageInput').max = pdf.numPages;
            document.getElementById('pageCount').textContent = `${pdf.numPages} pages`;
            
            // Setup controls
            setupPdfControls(pdf);
            setupSearchControls(pdf);
            setupFloatingNavigation(pdf);
            
            // Render all pages
            renderAllPages(pdf);
        }

        function setupPdfControls(pdf) {
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageInput = document.getElementById('pageInput');
            const fullscreenBtn = document.getElementById('fullscreenBtn');

            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };

            nextBtn.onclick = () => {
                if (currentPage < pdf.numPages) {
                    currentPage++;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };

            // Handle direct page input
            pageInput.addEventListener('change', () => {
                const pageNum = parseInt(pageInput.value);
                if (pageNum >= 1 && pageNum <= pdf.numPages) {
                    currentPage = pageNum;
                    scrollToPage(currentPage);
                    updatePageInfo();
                } else {
                    pageInput.value = currentPage; // Reset to current page if invalid
                }
            });

            pageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const pageNum = parseInt(pageInput.value);
                    if (pageNum >= 1 && pageNum <= pdf.numPages) {
                        currentPage = pageNum;
                        scrollToPage(currentPage);
                        updatePageInfo();
                    } else {
                        pageInput.value = currentPage; // Reset to current page if invalid
                    }
                }
            });

            fullscreenBtn.onclick = () => {
                const container = document.getElementById('pdfContainer');
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
            };
        }

        function scrollToPage(pageNumber) {
            const pageElement = document.getElementById(`page-${pageNumber}`);
            if (pageElement) {
                console.log(`Défilement vers la page ${pageNumber}`);
                
                // For page 1, scroll to the very top
                if (pageNumber === 1) {
                    viewerDiv.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    // Also use window scroll as fallback
                    window.scrollTo({
                        top: viewerDiv.offsetTop,
                        behavior: 'smooth'
                    });
                } else {
                    // For other pages, scroll into view
                    pageElement.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start',
                        inline: 'nearest'
                    });
                }
            } else {
                console.warn(`Élément page-${pageNumber} introuvable`);
            }
        }

        function setupSearchControls(pdf) {
            const searchText = document.getElementById('searchText');
            const searchBtn = document.getElementById('searchBtn');
            const resetBtn = document.getElementById('resetBtn');
            const prevMatch = document.getElementById('prevMatch');
            const nextMatch = document.getElementById('nextMatch');
            const matchCount = document.getElementById('matchCount');

            console.log('Initialisation des contrôles de recherche');

            // Vérifier que tous les éléments existent
            if (!searchText || !searchBtn) {
                console.error('Éléments de recherche introuvables');
                return;
            }

            searchBtn.onclick = () => {
                console.log('Bouton de recherche cliqué');
                performSearch(pdf);
            };
            
            resetBtn.onclick = () => {
                console.log('Reset de recherche');
                resetSearch();
            };
            
            searchText.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    console.log('Recherche par Enter');
                    performSearch(pdf);
                }
            });

            prevMatch.onclick = () => {
                console.log('Résultat précédent');
                if (currentMatch > 0) {
                    currentMatch--;
                    goToMatch(pdf);
                }
            };

            nextMatch.onclick = () => {
                console.log('Résultat suivant');
                if (currentMatch < searchMatches.length - 1) {
                    currentMatch++;
                    goToMatch(pdf);
                }
            };

            async function performSearch(pdf) {
                const query = searchText.value.trim();
                if (!query) {
                    alert('Veuillez saisir un terme à rechercher');
                    return;
                }

                // Show loading indicator
                searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                searchBtn.disabled = true;

                // Clear previous highlights
                clearHighlights();

                searchMatches = [];
                currentMatch = 0;

                try {
                    console.log(`Début de la recherche pour "${query}"`);
                    
                    // Search all pages with optimized processing
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        const page = await pdf.getPage(pageNum);
                        const textContent = await page.getTextContent();
                        
                        // Quick text extraction without storing positions initially
                        let pageText = '';
                        textContent.items.forEach(item => {
                            if (item.str && item.str.trim()) {
                                pageText += item.str + ' ';
                            }
                        });

                        // Search with case-insensitive regex
                        const regex = new RegExp(query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
                        let match;
                        
                        // Only process positions for pages that have matches
                        const pageMatches = [];
                        while ((match = regex.exec(pageText)) !== null) {
                            pageMatches.push({
                                index: match.index,
                                text: match[0],
                                context: pageText.substring(Math.max(0, match.index - 50), match.index + match[0].length + 50)
                            });
                        }
                        
                        // Only extract detailed positioning if there are matches on this page
                        if (pageMatches.length > 0) {
                            const textItems = textContent.items.filter(item => item.str && item.str.trim());
                            let textMap = [];
                            let currentIndex = 0;
                            
                            textItems.forEach((item, itemIndex) => {
                                const text = item.str;
                                textMap.push({
                                    itemIndex: itemIndex,
                                    startIndex: currentIndex,
                                    endIndex: currentIndex + text.length,
                                    text: text,
                                    transform: item.transform,
                                    bbox: {
                                        x: item.transform[4],
                                        y: item.transform[5],
                                        width: item.width,
                                        height: item.height
                                    }
                                });
                                pageText += text + ' ';
                                currentIndex += text.length + 1;
                            });
                            
                            // Now map matches to text items
                            pageMatches.forEach(pageMatch => {
                                const matchingItems = textMap.filter(item => 
                                    pageMatch.index < item.endIndex && (pageMatch.index + pageMatch.text.length) > item.startIndex
                                );

                                searchMatches.push({
                                    page: pageNum,
                                    text: pageMatch.text,
                                    index: pageMatch.index,
                                    context: pageMatch.context,
                                    textItems: matchingItems,
                                    matchStart: pageMatch.index,
                                    matchEnd: pageMatch.index + pageMatch.text.length
                                });
                            });
                        }
                    }

                    console.log(`Recherche "${query}" terminée - ${searchMatches.length} résultats trouvés`);

                    if (searchMatches.length > 0) {
                        // Highlight all matches
                        await highlightAllMatches(pdf);
                        showSearchResults();
                        goToMatch(pdf);
                    } else {
                        alert(`Aucun résultat trouvé pour "${query}"`);
                    }
                } catch (error) {
                    console.error('Erreur lors de la recherche:', error);
                    alert('Erreur lors de la recherche. Veuillez réessayer.');
                } finally {
                    // Restore search button
                    searchBtn.innerHTML = '<i class="fas fa-search"></i>';
                    searchBtn.disabled = false;
                }
            }

            function goToMatch(pdf) {
                if (searchMatches.length === 0) return;
                
                const match = searchMatches[currentMatch];
                currentPage = match.page;
                
                console.log(`Navigation vers le résultat ${currentMatch + 1}/${searchMatches.length} sur la page ${match.page}`);
                
                // Scroll to the page
                scrollToPage(currentPage);
                
                // Update UI
                updatePageInfo();
                updateMatchInfo();
                
                // Highlight current match with special styling
                highlightCurrentMatch(match);
            }

            async function highlightAllMatches(pdf) {
                console.log('Début du surlignage de tous les résultats');
                
                // Group matches by page for efficiency
                const matchesByPage = {};
                searchMatches.forEach(match => {
                    if (!matchesByPage[match.page]) {
                        matchesByPage[match.page] = [];
                    }
                    matchesByPage[match.page].push(match);
                });
                
                // Process pages with matches only
                for (const pageNum of Object.keys(matchesByPage)) {
                    const pageMatches = matchesByPage[pageNum];
                    const pageContainer = document.getElementById(`page-${pageNum}`);
                    if (!pageContainer) continue;

                    // Remove existing highlight overlay for this page
                    const existingOverlay = pageContainer.querySelector('.search-highlight-overlay');
                    if (existingOverlay) {
                        existingOverlay.remove();
                    }

                    try {
                        const page = await pdf.getPage(parseInt(pageNum));
                        const viewport = page.getViewport({ scale: scale });
                        const canvas = pageContainer.querySelector('canvas');
                        
                        if (!canvas) continue;

                        // Create highlight overlay
                        const highlightOverlay = document.createElement('div');
                        highlightOverlay.className = 'search-highlight-overlay';
                        highlightOverlay.style.cssText = `
                            position: absolute;
                            top: ${canvas.offsetTop}px;
                            left: ${canvas.offsetLeft}px;
                            width: ${canvas.offsetWidth}px;
                            height: ${canvas.offsetHeight}px;
                            pointer-events: none;
                            z-index: 10;
                        `;

                        // Create highlights for all matches on this page
                        pageMatches.forEach((match, matchIndex) => {
                            if (match.textItems && match.textItems.length > 0) {
                                match.textItems.forEach((textItem, index) => {
                                    const highlightRect = document.createElement('div');
                                    highlightRect.className = 'search-highlight-rect';
                                    highlightRect.dataset.matchIndex = matchIndex;
                                    
                                    // Calculate position relative to canvas
                                    const x = textItem.bbox.x * scale;
                                    const y = viewport.height - (textItem.bbox.y * scale) - (textItem.bbox.height * scale);
                                    const width = textItem.bbox.width * scale;
                                    const height = textItem.bbox.height * scale;

                                    highlightRect.style.cssText = `
                                        position: absolute;
                                        left: ${x}px;
                                        top: ${y}px;
                                        width: ${width}px;
                                        height: ${height}px;
                                        background-color: rgba(255, 255, 0, 0.3);
                                        border: 1px solid rgba(255, 193, 7, 0.8);
                                        border-radius: 2px;
                                        transition: all 0.3s ease;
                                    `;

                                    highlightOverlay.appendChild(highlightRect);
                                });
                            }
                        });

                        // Make container relative for proper overlay positioning
                        pageContainer.style.position = 'relative';
                        pageContainer.appendChild(highlightOverlay);

                    } catch (error) {
                        console.error(`Erreur lors du surlignage de la page ${pageNum}:`, error);
                    }
                }
                
                console.log('Surlignage de tous les résultats terminé');
            }

            function highlightCurrentMatch(match) {
                // Remove previous current match highlighting
                document.querySelectorAll('.current-match-highlight').forEach(el => el.remove());
                
                const pageContainer = document.getElementById(`page-${match.page}`);
                if (!pageContainer) return;

                // Add special highlighting for current match
                const currentHighlights = pageContainer.querySelectorAll('.search-highlight-rect');
                if (currentHighlights.length > 0) {
                    // Find the highlight that corresponds to current match
                    // For simplicity, highlight the first one with a different color
                    const currentRect = currentHighlights[0];
                    if (currentRect) {
                        const currentMarker = currentRect.cloneNode(true);
                        currentMarker.className = 'current-match-highlight';
                        currentMarker.style.cssText += `
                            background-color: rgba(255, 0, 0, 0.4) !important;
                            border: 2px solid rgba(220, 38, 127, 0.9) !important;
                            box-shadow: 0 0 10px rgba(220, 38, 127, 0.5);
                            z-index: 15;
                        `;
                        
                        const overlay = pageContainer.querySelector('.search-highlight-overlay');
                        if (overlay) {
                            overlay.appendChild(currentMarker);
                        }
                    }
                }

                // Add page border highlight for current match
                pageContainer.style.border = '3px solid #dc2626';
                pageContainer.style.borderRadius = '8px';
                pageContainer.style.transition = 'all 0.3s ease';
                
                // Remove page highlight after navigation
                setTimeout(() => {
                    pageContainer.style.border = '';
                    pageContainer.style.borderRadius = '';
                }, 3000);
            }

            function clearHighlights() {
                console.log('Suppression de tous les surlignages');
                
                // Remove all search highlight overlays efficiently
                const overlays = document.querySelectorAll('.search-highlight-overlay');
                overlays.forEach(overlay => overlay.remove());
                
                // Remove current match highlights
                const currentHighlights = document.querySelectorAll('.current-match-highlight');
                currentHighlights.forEach(highlight => highlight.remove());
                
                // Remove page border highlights
                const pages = document.querySelectorAll('.pdf-page-container');
                pages.forEach(page => {
                    page.style.border = '';
                    page.style.borderRadius = '';
                });
            }

            function highlightSearchTerm(searchTerm) {
                // This function is now replaced by the more advanced highlighting system
                const pageElement = document.getElementById(`page-${currentPage}`);
                if (pageElement) {
                    pageElement.style.border = '3px solid #f59e0b';
                    pageElement.style.borderRadius = '8px';
                    
                    // Remove highlight after 2 seconds
                    setTimeout(() => {
                        pageElement.style.border = '';
                        pageElement.style.borderRadius = '';
                    }, 2000);
                }
            }

            function showSearchResults() {
                resetBtn.classList.remove('hidden');
                prevMatch.classList.remove('hidden');
                nextMatch.classList.remove('hidden');
                matchCount.classList.remove('hidden');
                updateMatchInfo();
                
                // Update floating navigation search info
                if (window.updateFloatingSearchInfo) {
                    window.updateFloatingSearchInfo();
                }
            }

            function resetSearch() {
                console.log('Reset de la recherche');
                
                // Clear all highlights first
                clearHighlights();
                
                searchMatches = [];
                currentMatch = 0;
                searchText.value = '';
                resetBtn.classList.add('hidden');
                prevMatch.classList.add('hidden');
                nextMatch.classList.add('hidden');
                matchCount.classList.add('hidden');
                
                // Update floating navigation search info
                if (window.updateFloatingSearchInfo) {
                    window.updateFloatingSearchInfo();
                }
            }

            function updateMatchInfo() {
                if (searchMatches.length > 0) {
                    const matchText = `${currentMatch + 1} / ${searchMatches.length}`;
                    matchCount.textContent = matchText;
                    console.log(`Affichage du résultat: ${matchText}`);
                    
                    // Update floating navigation search info
                    if (window.updateFloatingSearchInfo) {
                        window.updateFloatingSearchInfo();
                    }
                }
            }
        }

        function renderPage(pdf, pageNumber) {
            // Cette fonction n'est plus utilisée car nous rendons toutes les pages
            renderAllPages(pdf);
        }

        function renderAllPages(pdf) {
            // Clear any existing highlights only if they exist
            if (searchMatches.length > 0) {
                clearHighlights();
            }
            
            viewerDiv.innerHTML = '';
            
            console.log(`Rendu de ${pdf.numPages} pages en ordre séquentiel`);
            
            // Reset scroll position to top
            viewerDiv.scrollTop = 0;
            
            // Créer tous les conteneurs de pages d'abord pour maintenir l'ordre
            const pageContainers = [];
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                const pageContainer = document.createElement('div');
                pageContainer.className = 'pdf-page-container mb-4';
                pageContainer.id = `page-${pageNum}`;
                
                // Add page number indicator
                const pageLabel = document.createElement('div');
                pageLabel.className = 'text-center text-gray-500 text-sm mb-2';
                pageLabel.textContent = `Page ${pageNum}`;
                pageContainer.appendChild(pageLabel);
                
                // Add loading placeholder
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'bg-gray-100 p-8 rounded-lg text-center';
                loadingDiv.innerHTML = `
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Chargement de la page ${pageNum}...</p>
                `;
                pageContainer.appendChild(loadingDiv);
                
                pageContainers.push(pageContainer);
                viewerDiv.appendChild(pageContainer);
            }
            
            // Maintenant rendre chaque page dans son conteneur
            renderPagesSequentially(pdf, pageContainers, 1);
        }

        async function renderPagesSequentially(pdf, pageContainers, pageNum) {
            if (pageNum > pdf.numPages) {
                console.log('Rendu de toutes les pages terminé');
                // Scroll to the first page when all pages are rendered
                setTimeout(() => {
                    scrollToPage(1);
                    currentPage = 1;
                    updatePageInfo();
                }, 500);
                return;
            }
            
            const pageContainer = pageContainers[pageNum - 1];
            const loadingDiv = pageContainer.querySelector('.bg-gray-100');
            
            try {
                const page = await pdf.getPage(pageNum);
                const viewport = page.getViewport({ scale: scale });
                
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport,
                    intent: 'display'
                };
                
                await page.render(renderContext).promise;
                
                // Remplacer le placeholder par le canvas
                if (loadingDiv) {
                    pageContainer.removeChild(loadingDiv);
                }
                pageContainer.appendChild(canvas);
                
                console.log(`Page ${pageNum} rendue avec succès`);
                
            // Rendre la page suivante
            renderPagesSequentially(pdf, pageContainers, pageNum + 1);
            
        } catch (error) {
            console.error(`Erreur lors du rendu de la page ${pageNum}:`, error);
            
            // Remplacer le placeholder par un message d'erreur
            if (loadingDiv) {
                loadingDiv.className = 'bg-red-100 text-red-800 p-4 rounded-lg text-center';
                loadingDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Erreur lors du chargement de la page ${pageNum}</p>
                `;
            }
            
            // Continuer avec la page suivante même en cas d'erreur
            renderPagesSequentially(pdf, pageContainers, pageNum + 1);
        }
    }

        function updatePageInfo() {
            document.getElementById('pageInput').value = currentPage;
            document.getElementById('floatingPageInput').value = currentPage;
        }

        function setupFloatingNavigation(pdf) {
            const floatingNav = document.getElementById('floatingNav');
            const pdfContainer = document.getElementById('pdfContainer');
            const pdfHeader = pdfContainer.previousElementSibling;
            
            // Floating navigation controls
            const floatingPrevPage = document.getElementById('floatingPrevPage');
            const floatingNextPage = document.getElementById('floatingNextPage');
            const floatingPageInput = document.getElementById('floatingPageInput');
            const floatingFullscreen = document.getElementById('floatingFullscreen');
            const floatingPrevMatch = document.getElementById('floatingPrevMatch');
            const floatingNextMatch = document.getElementById('floatingNextMatch');
            
            // Sync floating controls with main controls
            floatingPrevPage.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };

            floatingNextPage.onclick = () => {
                if (currentPage < pdf.numPages) {
                    currentPage++;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };

            floatingPageInput.addEventListener('change', () => {
                const pageNum = parseInt(floatingPageInput.value);
                if (pageNum >= 1 && pageNum <= pdf.numPages) {
                    currentPage = pageNum;
                    scrollToPage(currentPage);
                    updatePageInfo();
                } else {
                    floatingPageInput.value = currentPage;
                }
            });

            floatingPageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const pageNum = parseInt(floatingPageInput.value);
                    if (pageNum >= 1 && pageNum <= pdf.numPages) {
                        currentPage = pageNum;
                        scrollToPage(currentPage);
                        updatePageInfo();
                    } else {
                        floatingPageInput.value = currentPage;
                    }
                }
            });

            floatingFullscreen.onclick = () => {
                const container = document.getElementById('pdfContainer');
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
            };

            // Search navigation in floating bar
            floatingPrevMatch.onclick = () => {
                if (currentMatch > 0) {
                    currentMatch--;
                    goToMatch(pdf);
                }
            };

            floatingNextMatch.onclick = () => {
                if (currentMatch < searchMatches.length - 1) {
                    currentMatch++;
                    goToMatch(pdf);
                }
            };

            // Scroll detection for showing/hiding floating nav
            let lastScrollY = 0;
            let ticking = false;
            let isFloatingVisible = false;
            let scrollTimeout = null;
            let userIsScrolling = false;
            let scrollStopTimeout = null;
            
            function updateFloatingNav() {
                const scrollY = window.pageYOffset;
                const headerRect = pdfHeader.getBoundingClientRect();
                const isHeaderVisible = headerRect.bottom > 80; // Add buffer zone
                const scrollDirection = scrollY > lastScrollY ? 'down' : 'up';
                const scrollDelta = Math.abs(scrollY - lastScrollY);
                
                // Mark that user is scrolling
                userIsScrolling = true;
                
                // Clear any existing timeouts
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                if (scrollStopTimeout) {
                    clearTimeout(scrollStopTimeout);
                }
                
                // Set timeout to detect when scrolling stops
                scrollStopTimeout = setTimeout(() => {
                    userIsScrolling = false;
                }, 150);
                
                // Only show floating nav if:
                // 1. Header is completely out of view (with buffer)
                // 2. User has scrolled down significantly (at least 100px past header)
                // 3. We're in the PDF viewer area
                // 4. Scroll delta is significant (avoid micro-scrolls)
                const pdfContainerRect = pdfContainer.getBoundingClientRect();
                const isInPdfArea = pdfContainerRect.top < window.innerHeight && pdfContainerRect.bottom > 0;
                
                if (!isHeaderVisible && scrollY > 100 && isInPdfArea && scrollDirection === 'down' && scrollDelta > 5) {
                    // Show floating nav with a delay to avoid flickering
                    if (!isFloatingVisible) {
                        scrollTimeout = setTimeout(() => {
                            if (!userIsScrolling || scrollDirection === 'down') {
                                showFloatingNav();
                                isFloatingVisible = true;
                            }
                        }, 200);
                    }
                } else if (isHeaderVisible || (scrollDirection === 'up' && scrollDelta > 10) || !isInPdfArea) {
                    // Hide floating nav when:
                    // - Header becomes visible
                    // - Scrolling up with significant movement
                    // - Outside PDF area
                    if (isFloatingVisible) {
                        hideFloatingNav();
                        isFloatingVisible = false;
                    }
                }
                
                lastScrollY = scrollY;
                ticking = false;
            }
            
            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateFloatingNav);
                    ticking = true;
                }
            }
            
            // Use throttled scroll event for better performance
            let scrollTimer = null;
            window.addEventListener('scroll', function() {
                if (scrollTimer) {
                    clearTimeout(scrollTimer);
                }
                scrollTimer = setTimeout(requestTick, 16); // ~60fps
            }, { passive: true });
            
            function showFloatingNav() {
                if (!isFloatingVisible) {
                    floatingNav.classList.remove('hidden');
                    floatingNav.classList.add('show');
                    floatingNav.classList.remove('hide');
                    document.body.classList.add('floating-nav-visible');
                    console.log('Floating nav shown');
                }
            }
            
            function hideFloatingNav() {
                if (isFloatingVisible) {
                    floatingNav.classList.add('hide');
                    floatingNav.classList.remove('show');
                    document.body.classList.remove('floating-nav-visible');
                    console.log('Floating nav hidden');
                    
                    setTimeout(() => {
                        if (floatingNav.classList.contains('hide')) {
                            floatingNav.classList.add('hidden');
                        }
                    }, 400); // Match CSS transition duration
                }
            }
            
            // Update floating search info when search state changes
            window.updateFloatingSearchInfo = function() {
                const floatingSearchInfo = document.getElementById('floatingSearchInfo');
                const floatingMatchCount = document.getElementById('floatingMatchCount');
                
                if (searchMatches.length > 0) {
                    floatingSearchInfo.classList.remove('hidden');
                    floatingMatchCount.textContent = `${currentMatch + 1} / ${searchMatches.length}`;
                } else {
                    floatingSearchInfo.classList.add('hidden');
                }
            };
        }

        function updateProgress(percent, message) {
            const progressBar = document.getElementById('pdfProgressBar');
            const progressText = document.getElementById('pdfProgressText');
            
            if (progressBar) progressBar.style.width = percent + '%';
            if (progressText) progressText.textContent = message;
        }

        function showLoader() {
            loaderDiv.classList.remove('hidden');
            loaderDiv.classList.add('flex');
            viewerDiv.classList.add('hidden');
        }

        function showViewer() {
            loaderDiv.classList.add('hidden');
            viewerDiv.classList.remove('hidden');
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }
</script>
@endsection
