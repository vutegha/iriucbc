/**
 * Publication PDF Viewer
 * Système sécurisé de visualisation PDF avec recherche
 */

(function() {
    'use strict';
    
    // Configuration sécurisée
    const CONFIG = {
        workerUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js',
        cmapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
        maxFileSize: 50 * 1024 * 1024, // 50MB
        allowedOrigins: [window.location.origin],
        defaultScale: 1.0,
        maxScale: 3.0,
        minScale: 0.5
    };
    
    // État global
    let pdfDocument = null;
    let currentPage = 1;
    let scale = CONFIG.defaultScale;
    let searchMatches = [];
    let currentMatch = 0;
    let isInitialized = false;
    
    // Références DOM
    let elements = {};
    
    /**
     * Initialise le viewer PDF
     */
    function initPdfViewer() {
        if (isInitialized) return;
        
        // Configuration du worker PDF.js
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = CONFIG.workerUrl;
        } else {
            console.error('PDF.js library not loaded');
            return;
        }
        
        // Récupération des éléments DOM
        collectDOMElements();
        
        // Initialisation du preview hero
        initHeroPreview();
        
        // Initialisation du viewer principal
        initMainViewer();
        
        isInitialized = true;
    }
    
    /**
     * Collecte les références DOM
     */
    function collectDOMElements() {
        elements = {
            previewCanvas: document.getElementById('pdf-preview'),
            pdfContainer: document.getElementById('pdfContainer'),
            pdfLoader: document.getElementById('pdfLoader'),
            pdfViewer: document.getElementById('pdfViewer'),
            progressBar: document.getElementById('pdfProgressBar'),
            progressText: document.getElementById('pdfProgressText'),
            floatingNav: document.getElementById('floatingNav'),
            
            // Contrôles de navigation
            prevPage: document.getElementById('prevPage'),
            nextPage: document.getElementById('nextPage'),
            pageInput: document.getElementById('pageInput'),
            totalPages: document.getElementById('totalPages'),
            fullscreenBtn: document.getElementById('fullscreenBtn'),
            pageCount: document.getElementById('pageCount'),
            
            // Contrôles de recherche
            searchText: document.getElementById('searchText'),
            searchBtn: document.getElementById('searchBtn'),
            resetBtn: document.getElementById('resetBtn'),
            prevMatch: document.getElementById('prevMatch'),
            nextMatch: document.getElementById('nextMatch'),
            matchCount: document.getElementById('matchCount'),
            
            // Navigation flottante
            floatingPrevPage: document.getElementById('floatingPrevPage'),
            floatingNextPage: document.getElementById('floatingNextPage'),
            floatingPageInput: document.getElementById('floatingPageInput'),
            floatingTotalPages: document.getElementById('floatingTotalPages'),
            floatingFullscreen: document.getElementById('floatingFullscreen'),
            floatingPrevMatch: document.getElementById('floatingPrevMatch'),
            floatingNextMatch: document.getElementById('floatingNextMatch'),
            floatingMatchCount: document.getElementById('floatingMatchCount'),
            floatingSearchInfo: document.getElementById('floatingSearchInfo')
        };
    }
    
    /**
     * Initialise le preview dans la section hero
     */
    function initHeroPreview() {
        if (!elements.previewCanvas) return;
        
        const url = elements.previewCanvas.getAttribute('data-pdf-url');
        if (url && isValidPdfUrl(url)) {
            renderPdfPreview(url, elements.previewCanvas);
        }
    }
    
    /**
     * Initialise le viewer principal
     */
    function initMainViewer() {
        if (!elements.pdfContainer) return;
        
        const pdfUrl = elements.pdfContainer.getAttribute('data-pdf-url');
        if (pdfUrl && isValidPdfUrl(pdfUrl)) {
            loadMainPdfViewer(pdfUrl);
        }
    }
    
    /**
     * Valide l'URL du PDF
     */
    function isValidPdfUrl(url) {
        try {
            const urlObj = new URL(url, window.location.origin);
            return CONFIG.allowedOrigins.includes(urlObj.origin) && 
                   urlObj.pathname.toLowerCase().endsWith('.pdf');
        } catch {
            return false;
        }
    }
    
    /**
     * Rend le preview PDF (première page seulement)
     */
    function renderPdfPreview(url, canvas) {
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const containerWidth = canvas.parentElement.offsetWidth;

        const loadingOptions = {
            url: sanitizeUrl(url),
            disableWorker: false,
            cMapUrl: CONFIG.cmapUrl,
            cMapPacked: true
        };

        pdfjsLib.getDocument(loadingOptions).promise
            .then(pdf => pdf.getPage(1))
            .then(page => {
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
            })
            .catch(err => {
                console.error("Erreur lors du rendu du PDF preview :", err);
                canvas.style.display = 'none';
            });
    }
    
    /**
     * Charge le viewer PDF principal
     */
    function loadMainPdfViewer(url) {
        if (!elements.pdfLoader || !elements.pdfViewer) return;
        
        showLoader();
        updateProgress(0, 'Initialisation...');

        const loadingOptions = {
            url: sanitizeUrl(url),
            disableWorker: false,
            cMapUrl: CONFIG.cmapUrl,
            cMapPacked: true,
            disableAutoFetch: false,
            disableStream: false
        };

        const loadingTask = pdfjsLib.getDocument(loadingOptions);
        
        // Suivi de progression
        loadingTask.onProgress = function(progress) {
            if (progress.loaded && progress.total) {
                const percent = Math.round((progress.loaded / progress.total) * 100);
                updateProgress(percent, `Chargement... ${formatBytes(progress.loaded)} / ${formatBytes(progress.total)}`);
            }
        };

        loadingTask.promise
            .then(function(pdf) {
                pdfDocument = pdf;
                updateProgress(100, 'Rendu en cours...');
                
                setTimeout(() => {
                    setupPdfViewer(pdf);
                    showViewer();
                }, 500);
            })
            .catch(function(error) {
                console.error('Erreur lors du chargement du PDF:', error);
                showError(url);
            });
    }
    
    /**
     * Configure le viewer PDF
     */
    function setupPdfViewer(pdf) {
        // Mise à jour des informations de page
        updatePdfInfo(pdf);
        
        // Configuration des contrôles
        setupPdfControls(pdf);
        setupSearchControls(pdf);
        setupFloatingNavigation(pdf);
        
        // Rendu de toutes les pages
        renderAllPages(pdf);
    }
    
    /**
     * Met à jour les informations du PDF
     */
    function updatePdfInfo(pdf) {
        const totalPages = pdf.numPages;
        
        // Mise à jour des éléments d'information
        if (elements.totalPages) elements.totalPages.textContent = totalPages;
        if (elements.floatingTotalPages) elements.floatingTotalPages.textContent = totalPages;
        if (elements.pageInput) {
            elements.pageInput.value = currentPage;
            elements.pageInput.max = totalPages;
        }
        if (elements.floatingPageInput) {
            elements.floatingPageInput.value = currentPage;
            elements.floatingPageInput.max = totalPages;
        }
        if (elements.pageCount) elements.pageCount.textContent = `${totalPages} pages`;
    }
    
    /**
     * Configuration des contrôles PDF
     */
    function setupPdfControls(pdf) {
        if (elements.prevPage) {
            elements.prevPage.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };
        }

        if (elements.nextPage) {
            elements.nextPage.onclick = () => {
                if (currentPage < pdf.numPages) {
                    currentPage++;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };
        }

        if (elements.pageInput) {
            elements.pageInput.addEventListener('change', () => {
                const pageNum = parseInt(elements.pageInput.value);
                if (pageNum >= 1 && pageNum <= pdf.numPages) {
                    currentPage = pageNum;
                    scrollToPage(currentPage);
                    updatePageInfo();
                } else {
                    elements.pageInput.value = currentPage;
                }
            });

            elements.pageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const pageNum = parseInt(elements.pageInput.value);
                    if (pageNum >= 1 && pageNum <= pdf.numPages) {
                        currentPage = pageNum;
                        scrollToPage(currentPage);
                        updatePageInfo();
                    } else {
                        elements.pageInput.value = currentPage;
                    }
                }
            });
        }

        if (elements.fullscreenBtn) {
            elements.fullscreenBtn.onclick = () => {
                if (elements.pdfContainer.requestFullscreen) {
                    elements.pdfContainer.requestFullscreen();
                } else if (elements.pdfContainer.webkitRequestFullscreen) {
                    elements.pdfContainer.webkitRequestFullscreen();
                } else if (elements.pdfContainer.msRequestFullscreen) {
                    elements.pdfContainer.msRequestFullscreen();
                }
            };
        }
    }
    
    /**
     * Configuration des contrôles de recherche
     */
    function setupSearchControls(pdf) {
        if (!elements.searchText || !elements.searchBtn) return;

        elements.searchBtn.onclick = () => performSearch(pdf);
        
        if (elements.resetBtn) {
            elements.resetBtn.onclick = () => resetSearch();
        }
        
        elements.searchText.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                performSearch(pdf);
            }
        });

        if (elements.prevMatch) {
            elements.prevMatch.onclick = () => {
                if (currentMatch > 0) {
                    currentMatch--;
                    goToMatch(pdf);
                }
            };
        }

        if (elements.nextMatch) {
            elements.nextMatch.onclick = () => {
                if (currentMatch < searchMatches.length - 1) {
                    currentMatch++;
                    goToMatch(pdf);
                }
            };
        }
    }
    
    /**
     * Effectue une recherche dans le PDF
     */
    async function performSearch(pdf) {
        const query = elements.searchText.value.trim();
        if (!query) {
            alert('Veuillez saisir un terme à rechercher');
            return;
        }

        // Show loading indicator
        elements.searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        elements.searchBtn.disabled = true;

        searchMatches = [];
        currentMatch = 0;

        try {
            // Recherche simple page par page
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                const page = await pdf.getPage(pageNum);
                const textContent = await page.getTextContent();
                
                let pageText = '';
                textContent.items.forEach(item => {
                    if (item.str && item.str.trim()) {
                        pageText += item.str + ' ';
                    }
                });

                // Recherche insensible à la casse
                const regex = new RegExp(query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
                let match;
                
                while ((match = regex.exec(pageText)) !== null) {
                    searchMatches.push({
                        page: pageNum,
                        text: match[0],
                        index: match.index
                    });
                }
            }

            if (searchMatches.length > 0) {
                showSearchResults();
                goToMatch(pdf);
            } else {
                alert(`Aucun résultat trouvé pour "${query}"`);
            }
        } catch (error) {
            console.error('Erreur lors de la recherche:', error);
            alert('Erreur lors de la recherche. Veuillez réessayer.');
        } finally {
            elements.searchBtn.innerHTML = '<i class="fas fa-search"></i>';
            elements.searchBtn.disabled = false;
        }
    }
    
    /**
     * Va au résultat de recherche
     */
    function goToMatch(pdf) {
        if (searchMatches.length === 0) return;
        
        const match = searchMatches[currentMatch];
        currentPage = match.page;
        
        scrollToPage(currentPage);
        updatePageInfo();
        updateMatchInfo();
    }
    
    /**
     * Affiche les résultats de recherche
     */
    function showSearchResults() {
        if (elements.resetBtn) elements.resetBtn.classList.remove('hidden');
        if (elements.prevMatch) elements.prevMatch.classList.remove('hidden');
        if (elements.nextMatch) elements.nextMatch.classList.remove('hidden');
        if (elements.matchCount) elements.matchCount.classList.remove('hidden');
        updateMatchInfo();
    }
    
    /**
     * Remet à zéro la recherche
     */
    function resetSearch() {
        searchMatches = [];
        currentMatch = 0;
        elements.searchText.value = '';
        if (elements.resetBtn) elements.resetBtn.classList.add('hidden');
        if (elements.prevMatch) elements.prevMatch.classList.add('hidden');
        if (elements.nextMatch) elements.nextMatch.classList.add('hidden');
        if (elements.matchCount) elements.matchCount.classList.add('hidden');
    }
    
    /**
     * Met à jour les informations de résultat
     */
    function updateMatchInfo() {
        if (searchMatches.length > 0 && elements.matchCount) {
            const matchText = `${currentMatch + 1} / ${searchMatches.length}`;
            elements.matchCount.textContent = matchText;
            
            if (elements.floatingMatchCount) {
                elements.floatingMatchCount.textContent = matchText;
            }
        }
    }
    
    /**
     * Fait défiler vers une page
     */
    function scrollToPage(pageNumber) {
        const pageElement = document.getElementById(`page-${pageNumber}`);
        if (pageElement) {
            if (pageNumber === 1) {
                elements.pdfViewer.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            } else {
                pageElement.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start',
                    inline: 'nearest'
                });
            }
        }
    }
    
    /**
     * Rend toutes les pages du PDF
     */
    function renderAllPages(pdf) {
        elements.pdfViewer.innerHTML = '';
        elements.pdfViewer.scrollTop = 0;
        
        // Créer tous les conteneurs de pages
        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            const pageContainer = document.createElement('div');
            pageContainer.className = 'pdf-page-container mb-4';
            pageContainer.id = `page-${pageNum}`;
            
            const pageLabel = document.createElement('div');
            pageLabel.className = 'text-center text-gray-500 text-sm mb-2';
            pageLabel.textContent = `Page ${pageNum}`;
            pageContainer.appendChild(pageLabel);
            
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'bg-gray-100 p-8 rounded-lg text-center';
            loadingDiv.innerHTML = `
                <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Chargement de la page ${pageNum}...</p>
            `;
            pageContainer.appendChild(loadingDiv);
            
            elements.pdfViewer.appendChild(pageContainer);
        }
        
        // Rendre chaque page
        renderPagesSequentially(pdf, 1);
    }
    
    /**
     * Rend les pages séquentiellement
     */
    async function renderPagesSequentially(pdf, pageNum) {
        if (pageNum > pdf.numPages) {
            setTimeout(() => {
                scrollToPage(1);
                currentPage = 1;
                updatePageInfo();
            }, 500);
            return;
        }
        
        const pageContainer = document.getElementById(`page-${pageNum}`);
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
            
            if (loadingDiv) {
                pageContainer.removeChild(loadingDiv);
            }
            pageContainer.appendChild(canvas);
            
            // Rendre la page suivante
            renderPagesSequentially(pdf, pageNum + 1);
            
        } catch (error) {
            console.error(`Erreur lors du rendu de la page ${pageNum}:`, error);
            
            if (loadingDiv) {
                loadingDiv.className = 'bg-red-100 text-red-800 p-4 rounded-lg text-center';
                loadingDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Erreur lors du chargement de la page ${pageNum}</p>
                `;
            }
            
            renderPagesSequentially(pdf, pageNum + 1);
        }
    }
    
    /**
     * Configuration de la navigation flottante
     */
    function setupFloatingNavigation(pdf) {
        // Sync floating controls with main controls
        if (elements.floatingPrevPage) {
            elements.floatingPrevPage.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };
        }

        if (elements.floatingNextPage) {
            elements.floatingNextPage.onclick = () => {
                if (currentPage < pdf.numPages) {
                    currentPage++;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };
        }

        if (elements.floatingPageInput) {
            elements.floatingPageInput.addEventListener('change', () => {
                const pageNum = parseInt(elements.floatingPageInput.value);
                if (pageNum >= 1 && pageNum <= pdf.numPages) {
                    currentPage = pageNum;
                    scrollToPage(currentPage);
                    updatePageInfo();
                } else {
                    elements.floatingPageInput.value = currentPage;
                }
            });
        }

        if (elements.floatingFullscreen) {
            elements.floatingFullscreen.onclick = () => {
                if (elements.pdfContainer.requestFullscreen) {
                    elements.pdfContainer.requestFullscreen();
                }
            };
        }

        // Gestion du scroll pour afficher/masquer la navigation flottante
        setupFloatingNavScroll();
    }
    
    /**
     * Gestion du scroll pour la navigation flottante
     */
    function setupFloatingNavScroll() {
        let lastScrollY = 0;
        let isFloatingVisible = false;
        
        function updateFloatingNav() {
            const scrollY = window.pageYOffset;
            const pdfContainerRect = elements.pdfContainer.getBoundingClientRect();
            const isInPdfArea = pdfContainerRect.top < window.innerHeight && pdfContainerRect.bottom > 0;
            
            if (scrollY > 200 && isInPdfArea && !isFloatingVisible) {
                showFloatingNav();
                isFloatingVisible = true;
            } else if ((scrollY <= 200 || !isInPdfArea) && isFloatingVisible) {
                hideFloatingNav();
                isFloatingVisible = false;
            }
            
            lastScrollY = scrollY;
        }
        
        function showFloatingNav() {
            if (elements.floatingNav) {
                elements.floatingNav.classList.remove('hidden');
                elements.floatingNav.classList.add('show');
            }
        }
        
        function hideFloatingNav() {
            if (elements.floatingNav) {
                elements.floatingNav.classList.remove('show');
                elements.floatingNav.classList.add('hide');
                
                setTimeout(() => {
                    if (elements.floatingNav.classList.contains('hide')) {
                        elements.floatingNav.classList.add('hidden');
                    }
                }, 400);
            }
        }
        
        window.addEventListener('scroll', updateFloatingNav, { passive: true });
    }
    
    /**
     * Met à jour les informations de page
     */
    function updatePageInfo() {
        if (elements.pageInput) elements.pageInput.value = currentPage;
        if (elements.floatingPageInput) elements.floatingPageInput.value = currentPage;
    }
    
    /**
     * Nettoie et valide une URL
     */
    function sanitizeUrl(url) {
        try {
            const urlObj = new URL(url, window.location.origin);
            if (!CONFIG.allowedOrigins.includes(urlObj.origin)) {
                throw new Error('Origin not allowed');
            }
            return urlObj.href;
        } catch {
            throw new Error('Invalid URL');
        }
    }
    
    /**
     * Affiche le loader
     */
    function showLoader() {
        if (elements.pdfLoader) {
            elements.pdfLoader.classList.remove('hidden');
            elements.pdfLoader.classList.add('flex');
        }
        if (elements.pdfViewer) {
            elements.pdfViewer.classList.add('hidden');
        }
    }
    
    /**
     * Affiche le viewer
     */
    function showViewer() {
        if (elements.pdfLoader) {
            elements.pdfLoader.classList.add('hidden');
        }
        if (elements.pdfViewer) {
            elements.pdfViewer.classList.remove('hidden');
        }
    }
    
    /**
     * Affiche une erreur
     */
    function showError(url) {
        if (!elements.pdfLoader) return;
        
        elements.pdfLoader.innerHTML = `
            <div class="text-center">
                <div class="bg-red-100 text-red-800 p-6 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-3xl mb-4" aria-hidden="true"></i>
                    <p class="text-lg mb-4">Erreur lors du chargement du PDF</p>
                    <a href="${escapeHtml(url)}" target="_blank" rel="noopener noreferrer" 
                       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Ouvrir dans un nouvel onglet
                    </a>
                </div>
            </div>
        `;
    }
    
    /**
     * Met à jour la barre de progression
     */
    function updateProgress(percent, message) {
        if (elements.progressBar) {
            elements.progressBar.style.width = percent + '%';
        }
        if (elements.progressText) {
            elements.progressText.textContent = message;
        }
    }
    
    /**
     * Formate les bytes en format lisible
     */
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    /**
     * Échappe le HTML pour éviter les injections
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Initialisation automatique
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPdfViewer);
    } else {
        initPdfViewer();
    }
    
    // Export pour usage externe si nécessaire
    window.PublicationPdfViewer = {
        init: initPdfViewer,
        loadPdf: loadMainPdfViewer,
        getCurrentPage: () => currentPage,
        getTotalPages: () => pdfDocument ? pdfDocument.numPages : 0
    };
    
})();
