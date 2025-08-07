/**
 * Gestionnaire de formulaire d'actualité
 * Avec compression d'images, gestion d'erreurs robuste, accessibilité et fonctionnalités avancées
 */

class ActualiteFormManager {
    constructor() {
        this.form = null;
        this.editorInstance = null;
        this.mediaInsertCallback = null;
        this.uploadProgressBar = null;
        this.autosaveInterval = null;
        this.currentImageFile = null;
        
        // Configuration
        this.config = {
            autosaveDelay: 30000, // 30 secondes
            maxImageSize: 2 * 1024 * 1024, // 2MB après compression
            imageQuality: 0.8,
            maxImageDimensions: { width: 1920, height: 1080 },
            allowedMimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            maxFileSize: 10 * 1024 * 1024, // 10MB avant compression
        };
        
        this.init();
    }

    init() {
        // Vérifier si on est sur une page avec le formulaire d'actualité
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initializeForm());
        } else {
            this.initializeForm();
        }
    }

    initializeForm() {
        this.form = document.getElementById('actualiteForm');
        if (!this.form) return;

        console.log('Initialisation ActualiteFormManager...');

        this.setupElements();
        this.setupEventListeners();
        this.setupAccessibility();
        this.setupKeyboardShortcuts();
        this.setupCheckboxes();
        
        // Attendre un peu avant d'initialiser CKEditor pour éviter les conflits
        setTimeout(() => {
            this.initializeCKEditor();
        }, 500);
        
        this.setupAutosave();
        this.setupTooltips();
        this.animateFormEntry();
    }

    setupElements() {
        this.elements = {
            form: this.form,
            titre: document.getElementById('titre'),
            resume: document.getElementById('resume'),
            texte: document.getElementById('texte'),
            image: document.getElementById('image'),
            modal: document.getElementById('mediaModal'),
            submitBtn: this.form.querySelector('button[type="submit"]'),
            cancelBtn: this.form.querySelector('a[href*="index"]'),
        };

        // Créer la barre de progression pour les uploads
        this.createProgressBar();
    }

    createProgressBar() {
        const progressContainer = document.createElement('div');
        progressContainer.id = 'upload-progress-container';
        progressContainer.className = 'hidden fixed top-4 right-4 bg-white rounded-lg shadow-lg border p-4 z-50';
        progressContainer.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-medium text-gray-900" id="upload-status">Upload en cours...</div>
                    <div class="w-48 bg-gray-200 rounded-full h-2 mt-2">
                        <div id="upload-progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1" id="upload-details"></div>
                </div>
                <button type="button" onclick="actualiteForm.hideProgress()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        document.body.appendChild(progressContainer);
        this.uploadProgressBar = progressContainer;
    }

    setupEventListeners() {
        // Validation en temps réel
        this.elements.titre?.addEventListener('input', () => this.validateField('titre'));
        this.elements.texte?.addEventListener('input', () => this.validateField('texte'));
        
        // Gestion des images avec compression
        this.elements.image?.addEventListener('change', (e) => this.handleImageSelect(e));
        
        // Soumission du formulaire
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Gestion du modal
        this.setupModalEvents();
        
        // Drag & Drop avec validation
        this.setupDragAndDrop();
    }

    setupAccessibility() {
        // Ajouter les attributs ARIA manquants
        const checkboxes = this.form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            const label = checkbox.closest('label');
            if (label) {
                const id = checkbox.id || `checkbox-${Math.random().toString(36).substr(2, 9)}`;
                checkbox.id = id;
                label.setAttribute('for', id);
                label.setAttribute('role', 'checkbox');
                label.setAttribute('aria-checked', checkbox.checked);
                
                checkbox.addEventListener('change', () => {
                    label.setAttribute('aria-checked', checkbox.checked);
                    this.updateCheckboxVisual(checkbox);
                });
            }
        });

        // Ajouter des descriptions aux SVG
        const svgs = this.form.querySelectorAll('svg');
        svgs.forEach((svg, index) => {
            if (!svg.getAttribute('aria-label') && !svg.getAttribute('aria-hidden')) {
                svg.setAttribute('aria-hidden', 'true');
            }
        });

        // Améliorer la navigation au clavier
        const interactiveElements = this.form.querySelectorAll('button, input, textarea, select');
        interactiveElements.forEach(element => {
            element.addEventListener('keydown', this.handleKeyNavigation.bind(this));
        });
    }

    setupCheckboxes() {
        // Configuration spéciale pour les checkboxes personnalisées
        const checkboxes = this.form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            const label = checkbox.closest('label');
            if (label) {
                // Gérer le clic sur le label
                label.addEventListener('click', (e) => {
                    if (e.target === label || e.target.closest('.flex-shrink-0')) {
                        e.preventDefault();
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                        this.updateCheckboxVisual(checkbox);
                    }
                });

                // Gérer les changements d'état
                checkbox.addEventListener('change', () => {
                    this.updateCheckboxVisual(checkbox);
                });

                // Initialiser l'état visuel
                this.updateCheckboxVisual(checkbox);
            }
        });
    }

    updateCheckboxVisual(checkbox) {
        const label = checkbox.closest('label');
        if (!label) return;

        const checkmark = label.querySelector('svg');
        const container = label.querySelector('.flex-shrink-0 > div');
        
        if (checkbox.checked) {
            label.classList.add('bg-iri-light', 'border-iri-primary');
            label.classList.remove('bg-white');
            if (container) {
                container.classList.add('bg-iri-primary', 'border-iri-primary');
                container.classList.remove('border-gray-300');
            }
            if (checkmark) {
                checkmark.classList.remove('hidden');
                checkmark.classList.add('block');
            }
        } else {
            label.classList.remove('bg-iri-light', 'border-iri-primary');
            label.classList.add('bg-white');
            if (container) {
                container.classList.remove('bg-iri-primary', 'border-iri-primary');
                container.classList.add('border-gray-300');
            }
            if (checkmark) {
                checkmark.classList.add('hidden');
                checkmark.classList.remove('block');
            }
        }
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl+S pour sauvegarder
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                this.saveAsDraft();
                this.showNotification('Brouillon sauvegardé', 'success');
            }
            
            // Ctrl+Enter pour soumettre
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                this.form.requestSubmit();
            }
            
            // Escape pour fermer le modal
            if (e.key === 'Escape' && this.elements.modal && !this.elements.modal.classList.contains('hidden')) {
                this.closeMediaModal();
            }
        });
    }

    async handleImageSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        try {
            // Validation du fichier
            this.validateImageFile(file);
            
            // Compression de l'image
            this.showProgress('Compression de l\'image...', 0);
            const compressedFile = await this.compressImage(file);
            
            this.currentImageFile = compressedFile;
            this.updateImagePreview(compressedFile);
            this.hideProgress();
            
        } catch (error) {
            this.hideProgress();
            this.showNotification(error.message, 'error');
            event.target.value = ''; // Reset input
        }
    }

    validateImageFile(file) {
        // Vérification du type MIME
        if (!this.config.allowedMimeTypes.includes(file.type)) {
            throw new Error('Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP.');
        }
        
        // Vérification de la taille
        if (file.size > this.config.maxFileSize) {
            throw new Error(`Le fichier est trop volumineux (max: ${this.config.maxFileSize / 1024 / 1024}MB).`);
        }
    }

    async compressImage(file) {
        return new Promise((resolve, reject) => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            
            img.onload = () => {
                try {
                    // Calculer les nouvelles dimensions
                    let { width, height } = this.calculateImageDimensions(img.width, img.height);
                    
                    canvas.width = width;
                    canvas.height = height;
                    
                    // Dessiner l'image redimensionnée
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    // Convertir en blob avec compression
                    canvas.toBlob((blob) => {
                        if (blob && blob.size <= this.config.maxImageSize) {
                            // Créer un nouveau fichier avec le blob compressé
                            const compressedFile = new File([blob], file.name, {
                                type: file.type,
                                lastModified: Date.now()
                            });
                            resolve(compressedFile);
                        } else {
                            reject(new Error('Impossible de compresser l\'image suffisamment.'));
                        }
                    }, file.type, this.config.imageQuality);
                } catch (error) {
                    reject(error);
                }
            };
            
            img.onerror = () => reject(new Error('Impossible de charger l\'image.'));
            img.src = URL.createObjectURL(file);
        });
    }

    calculateImageDimensions(originalWidth, originalHeight) {
        const { width: maxWidth, height: maxHeight } = this.config.maxImageDimensions;
        
        if (originalWidth <= maxWidth && originalHeight <= maxHeight) {
            return { width: originalWidth, height: originalHeight };
        }
        
        const aspectRatio = originalWidth / originalHeight;
        
        if (originalWidth > originalHeight) {
            return {
                width: Math.min(maxWidth, originalWidth),
                height: Math.min(maxWidth / aspectRatio, originalHeight)
            };
        } else {
            return {
                width: Math.min(maxHeight * aspectRatio, originalWidth),
                height: Math.min(maxHeight, originalHeight)
            };
        }
    }

    updateImagePreview(file) {
        const preview = document.getElementById('image-preview-actualite');
        const placeholder = document.getElementById('image-placeholder-actualite');
        
        if (preview && file) {
            const url = URL.createObjectURL(file);
            preview.src = url;
            preview.classList.remove('hidden');
            preview.setAttribute('alt', `Aperçu de ${file.name}`);
            
            if (placeholder) placeholder.classList.add('hidden');
            
            // Nettoyer l'URL après usage
            preview.onload = () => URL.revokeObjectURL(url);
        }
    }

    setupDragAndDrop() {
        const dropZone = this.elements.image?.closest('.group');
        if (!dropZone) return;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.highlightDropZone(dropZone), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.unhighlightDropZone(dropZone), false);
        });

        dropZone.addEventListener('drop', (e) => this.handleDrop(e), false);
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    highlightDropZone(element) {
        element.classList.add('border-iri-primary', 'bg-iri-light');
        element.classList.remove('border-gray-300');
    }

    unhighlightDropZone(element) {
        element.classList.remove('border-iri-primary', 'bg-iri-light');
        element.classList.add('border-gray-300');
    }

    async handleDrop(e) {
        const files = Array.from(e.dataTransfer.files);
        const imageFile = files.find(file => file.type.startsWith('image/'));
        
        if (imageFile) {
            // Simuler la sélection de fichier
            const fileList = new DataTransfer();
            fileList.items.add(imageFile);
            this.elements.image.files = fileList.files;
            
            // Déclencher l'événement change
            this.elements.image.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        if (!this.validateForm()) {
            this.showNotification('Veuillez corriger les erreurs avant de continuer.', 'error');
            return;
        }

        this.showSubmitLoading();
        
        try {
            // Préparer les données du formulaire
            const formData = new FormData(this.form);
            
            // Remplacer le fichier image par la version compressée si disponible
            if (this.currentImageFile) {
                formData.delete('image');
                formData.append('image', this.currentImageFile);
            }
            
            // Ajouter le contenu de l'éditeur
            if (this.editorInstance) {
                formData.set('texte', this.editorInstance.getData());
            }

            // Soumettre avec indication de progression
            await this.submitFormWithProgress(formData);
            
        } catch (error) {
            this.hideSubmitLoading();
            this.showNotification('Erreur lors de l\'enregistrement: ' + error.message, 'error');
        }
    }

    async submitFormWithProgress(formData) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            
            // Progression de l'upload
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    this.showProgress('Envoi en cours...', percentComplete);
                }
            });
            
            xhr.addEventListener('load', () => {
                this.hideProgress();
                this.hideSubmitLoading();
                
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success !== false) {
                            // Redirection ou message de succès
                            window.location.href = response.redirect || this.elements.cancelBtn.href;
                            resolve(response);
                        } else {
                            reject(new Error(response.message || 'Erreur inconnue'));
                        }
                    } catch (e) {
                        // Si ce n'est pas du JSON, c'est probablement une redirection HTML
                        window.location.href = this.elements.cancelBtn.href;
                        resolve();
                    }
                } else {
                    reject(new Error(`Erreur HTTP: ${xhr.status}`));
                }
            });
            
            xhr.addEventListener('error', () => {
                this.hideProgress();
                this.hideSubmitLoading();
                reject(new Error('Erreur de connexion'));
            });
            
            xhr.addEventListener('timeout', () => {
                this.hideProgress();
                this.hideSubmitLoading();
                reject(new Error('Délai d\'attente dépassé'));
            });
            
            xhr.timeout = 60000; // 60 secondes
            xhr.open(this.form.method, this.form.action);
            
            // Ajouter les headers nécessaires
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            this.form.querySelector('input[name="_token"]')?.value;
            if (csrfToken) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            }
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            this.showProgress('Préparation de l\'envoi...', 0);
            xhr.send(formData);
        });
    }

    validateForm() {
        let isValid = true;
        
        // Validation des champs requis
        const requiredFields = ['titre', 'texte'];
        requiredFields.forEach(fieldName => {
            if (!this.validateField(fieldName)) {
                isValid = false;
            }
        });
        
        return isValid;
    }

    validateField(fieldName) {
        const field = this.elements[fieldName];
        if (!field) return true;
        
        const errorElement = document.getElementById(`error-${fieldName}`);
        let isValid = true;
        let errorMessage = '';
        
        // Validation spécifique par champ
        switch (fieldName) {
            case 'titre':
                if (!field.value.trim()) {
                    isValid = false;
                    errorMessage = 'Le titre est requis.';
                }
                break;
            case 'texte':
                const content = this.editorInstance ? this.editorInstance.getData() : field.value;
                if (!content.trim()) {
                    isValid = false;
                    errorMessage = 'Le contenu est requis.';
                }
                break;
        }
        
        // Appliquer les styles de validation
        if (isValid) {
            field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            field.classList.add('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
            if (errorElement) errorElement.classList.add('hidden');
        } else {
            field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            field.classList.remove('border-gray-300', 'focus:border-iri-primary', 'focus:ring-iri-primary');
            if (errorElement) {
                errorElement.textContent = errorMessage;
                errorElement.classList.remove('hidden');
            }
        }
        
        return isValid;
    }

    // Gestion du modal de médiathèque
    setupModalEvents() {
        const modal = this.elements.modal;
        if (!modal) return;

        // Responsive modal
        this.makeModalResponsive();
        
        // Fermeture par clic extérieur
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeMediaModal();
            }
        });
        
        // Empêcher la propagation à l'intérieur
        const modalContent = modal.querySelector('.bg-white');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => e.stopPropagation());
        }

        // Bouton de fermeture du modal
        const closeBtn = document.getElementById('modal-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeMediaModal());
        }

        // Bouton d'upload dans le modal
        const uploadBtn = document.getElementById('media-upload-btn');
        const uploadInput = document.getElementById('mediaUploadInput');
        if (uploadBtn && uploadInput) {
            uploadBtn.addEventListener('click', () => uploadInput.click());
        }

        // Bouton refresh dans le modal
        const refreshBtn = document.getElementById('media-refresh-btn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.loadMediaList());
        }

        // Gestion de l'upload dans le modal
        if (uploadInput) {
            uploadInput.addEventListener('change', (e) => this.handleMediaUpload(e));
        }
    }

    makeModalResponsive() {
        const modal = this.elements.modal;
        if (!modal) return;

        // Styles responsive pour le modal
        const style = document.createElement('style');
        style.textContent = `
            #mediaModal .bg-white {
                max-width: min(90vw, 40rem) !important;
                max-height: min(90vh, 600px) !important;
                margin: 1rem !important;
            }
            
            @media (max-width: 640px) {
                #mediaModal .bg-white {
                    max-width: 95vw !important;
                    max-height: 85vh !important;
                    margin: 0.5rem !important;
                }
                
                #mediaModal .grid-cols-4 {
                    grid-template-columns: repeat(2, 1fr) !important;
                }
            }
            
            @media (max-width: 480px) {
                #mediaModal .grid-cols-2 {
                    grid-template-columns: 1fr !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    openMediaModal(callback) {
        this.mediaInsertCallback = callback;
        const modal = this.elements.modal;
        if (!modal) return;

        // Empêcher le scroll du body
        document.body.style.overflow = 'hidden';
        modal.classList.remove('hidden');
        
        // Focus sur le modal pour l'accessibilité
        modal.setAttribute('tabindex', '-1');
        modal.focus();
        
        this.loadMediaList();
    }

    closeMediaModal() {
        const modal = this.elements.modal;
        if (!modal) return;

        modal.classList.add('hidden');
        document.body.style.overflow = '';
        this.mediaInsertCallback = null;
    }

    async loadMediaList(page = 1) {
        const mediaList = document.getElementById('mediaList');
        if (!mediaList) return;

        try {
            // Afficher le loader
            mediaList.innerHTML = `
                <div class="col-span-full text-center text-gray-500 py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-iri-primary mx-auto mb-2"></div>
                    <p>Chargement des médias...</p>
                </div>
            `;

            const response = await fetch(`/admin/media/list?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Erreur ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            this.renderMediaList(data);
            
        } catch (error) {
            mediaList.innerHTML = `
                <div class="col-span-full text-center text-red-500 py-8">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Erreur: ${error.message}</p>
                    <button onclick="actualiteForm.loadMediaList()" class="mt-2 text-iri-primary hover:underline">Réessayer</button>
                </div>
            `;
        }
    }

    renderMediaList(data) {
        const mediaList = document.getElementById('mediaList');
        if (!mediaList) return;

        if (!data.data || data.data.length === 0) {
            mediaList.innerHTML = '<p class="text-gray-500 text-center col-span-full">Aucune image disponible</p>';
            return;
        }

        // Afficher les images
        mediaList.innerHTML = data.data.map(media => `
            <div class="border rounded-lg overflow-hidden cursor-pointer hover:shadow-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-iri-primary" 
                 tabindex="0" 
                 role="button"
                 aria-label="Sélectionner l'image ${media.name}"
                 onclick="actualiteForm.selectMedia('${media.url}')"
                 onkeydown="if(event.key==='Enter' || event.key===' ') actualiteForm.selectMedia('${media.url}')">
                <img src="${media.url}" 
                     alt="${media.name}" 
                     class="w-full h-24 object-cover"
                     loading="lazy"
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBzdHJva2U9IiNjY2MiIGZpbGw9IiNmZmYiLz4KPHN2Zz4K';">
                <div class="p-2">
                    <p class="text-xs text-gray-600 truncate">${media.name}</p>
                </div>
            </div>
        `).join('');

        // Ajouter la pagination si nécessaire
        if (data.last_page > 1) {
            this.renderPagination(data);
        }
    }

    renderPagination(data) {
        const mediaList = document.getElementById('mediaList');
        const paginationHtml = `
            <div class="col-span-full mt-4 flex justify-center">
                <nav class="flex items-center space-x-2">
                    ${data.current_page > 1 ? 
                        `<button onclick="actualiteForm.loadMediaList(${data.current_page - 1})" 
                                class="px-3 py-2 text-sm text-iri-primary hover:bg-iri-light rounded">
                            Précédent
                        </button>` : ''}
                    
                    ${Array.from({length: Math.min(5, data.last_page)}, (_, i) => {
                        const page = i + Math.max(1, data.current_page - 2);
                        if (page > data.last_page) return '';
                        return `
                            <button onclick="actualiteForm.loadMediaList(${page})" 
                                    class="px-3 py-2 text-sm rounded ${page === data.current_page ? 
                                        'bg-iri-primary text-white' : 
                                        'text-iri-primary hover:bg-iri-light'}">
                                ${page}
                            </button>
                        `;
                    }).join('')}
                    
                    ${data.current_page < data.last_page ? 
                        `<button onclick="actualiteForm.loadMediaList(${data.current_page + 1})" 
                                class="px-3 py-2 text-sm text-iri-primary hover:bg-iri-light rounded">
                            Suivant
                        </button>` : ''}
                </nav>
            </div>
        `;
        mediaList.insertAdjacentHTML('beforeend', paginationHtml);
    }

    selectMedia(url) {
        if (this.mediaInsertCallback) {
            this.mediaInsertCallback(url);
        }
        this.closeMediaModal();
    }

    // Gestion de l'upload dans le modal de médiathèque
    async handleMediaUpload(event) {
        const files = Array.from(event.target.files);
        if (files.length === 0) return;

        try {
            for (const file of files) {
                await this.uploadMediaFile(file);
            }
            
            // Recharger la liste après upload
            this.loadMediaList();
            
            // Reset input
            event.target.value = '';
            
        } catch (error) {
            this.showNotification('Erreur lors de l\'upload: ' + error.message, 'error');
        }
    }

    async uploadMediaFile(file) {
        // Validation du fichier
        this.validateImageFile(file);
        
        // Créer FormData
        const formData = new FormData();
        formData.append('media', file);
        
        // Ajouter token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        document.querySelector('input[name="_token"]')?.value;
        if (csrfToken) {
            formData.append('_token', csrfToken);
        }

        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentage = (e.loaded / e.total) * 100;
                    this.updateUploadProgress(file.name, percentage);
                }
            });
            
            xhr.addEventListener('load', () => {
                this.hideUploadProgress();
                
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            this.showNotification(`${file.name} uploadé avec succès`, 'success');
                            resolve(response);
                        } else {
                            reject(new Error(response.message || 'Erreur lors de l\'upload'));
                        }
                    } catch (e) {
                        reject(new Error('Réponse invalide du serveur'));
                    }
                } else {
                    reject(new Error(`Erreur HTTP: ${xhr.status}`));
                }
            });
            
            xhr.addEventListener('error', () => {
                this.hideUploadProgress();
                reject(new Error('Erreur de connexion'));
            });
            
            this.showUploadProgress(file.name, 0);
            xhr.open('POST', '/admin/media/upload');
            xhr.send(formData);
        });
    }

    showUploadProgress(filename, percentage) {
        const progressElement = document.getElementById('upload-progress');
        const statusElement = document.getElementById('upload-status');
        const percentageElement = document.getElementById('upload-percentage');
        const barElement = document.getElementById('upload-bar');
        
        if (progressElement) {
            progressElement.classList.remove('hidden');
            if (statusElement) statusElement.textContent = `Upload: ${filename}`;
            if (percentageElement) percentageElement.textContent = `${Math.round(percentage)}%`;
            if (barElement) barElement.style.width = `${percentage}%`;
        }
    }

    hideUploadProgress() {
        const progressElement = document.getElementById('upload-progress');
        if (progressElement) {
            progressElement.classList.add('hidden');
        }
    }

    updateUploadProgress(filename, percentage) {
        this.showUploadProgress(filename, percentage);
    }

    // Système d'autosave
    setupAutosave() {
        // Vérifier s'il y a des données sauvegardées
        this.loadDraft();
        
        // Démarrer l'autosave
        this.autosaveInterval = setInterval(() => {
            this.saveAsDraft();
        }, this.config.autosaveDelay);
    }

    saveAsDraft() {
        const formData = new FormData(this.form);
        const draftData = {};
        
        // Récupérer les données du formulaire
        for (let [key, value] of formData.entries()) {
            if (key !== '_token' && key !== '_method') {
                draftData[key] = value;
            }
        }
        
        // Ajouter le contenu de l'éditeur
        if (this.editorInstance) {
            draftData.texte = this.editorInstance.getData();
        }
        
        // Sauvegarder dans localStorage
        localStorage.setItem('actualite_draft', JSON.stringify({
            data: draftData,
            timestamp: Date.now()
        }));
    }

    loadDraft() {
        const draft = localStorage.getItem('actualite_draft');
        if (!draft) return;
        
        try {
            const { data, timestamp } = JSON.parse(draft);
            
            // Vérifier si le brouillon n'est pas trop ancien (24h)
            if (Date.now() - timestamp > 24 * 60 * 60 * 1000) {
                localStorage.removeItem('actualite_draft');
                return;
            }
            
            // Demander à l'utilisateur s'il veut restaurer
            if (confirm('Un brouillon a été trouvé. Voulez-vous le restaurer ?')) {
                this.restoreDraft(data);
            }
            
        } catch (error) {
            console.error('Erreur lors du chargement du brouillon:', error);
            localStorage.removeItem('actualite_draft');
        }
    }

    restoreDraft(data) {
        Object.keys(data).forEach(key => {
            const element = this.form.querySelector(`[name="${key}"]`);
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = data[key] === '1';
                } else if (key === 'texte' && this.editorInstance) {
                    this.editorInstance.setData(data[key]);
                } else {
                    element.value = data[key];
                }
            }
        });
        
        this.showNotification('Brouillon restauré avec succès', 'success');
    }

    // Système de tooltips
    setupTooltips() {
        const elementsWithTooltips = [
            { selector: 'button[type="submit"]', text: 'Ctrl+Entrée pour envoyer' },
            { selector: 'a[href*="index"]', text: 'Échap pour annuler' },
            { selector: '#image', text: 'Glissez-déposez une image ici' },
        ];

        elementsWithTooltips.forEach(({ selector, text }) => {
            const element = this.form.querySelector(selector);
            if (element) {
                this.addTooltip(element, text);
            }
        });
    }

    addTooltip(element, text) {
        element.setAttribute('title', text);
        element.setAttribute('data-tooltip', text);
        
        // Créer un tooltip personnalisé
        let tooltip = null;
        
        element.addEventListener('mouseenter', () => {
            tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg pointer-events-none';
            tooltip.textContent = text;
            
            document.body.appendChild(tooltip);
            
            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.bottom + 5 + 'px';
        });
        
        element.addEventListener('mouseleave', () => {
            if (tooltip) {
                tooltip.remove();
                tooltip = null;
            }
        });
    }

    // CKEditor avec protection XSS - S'adapte à l'instance existante
    async initializeCKEditor() {
        console.log('Initialisation CKEditor...');
        
        if (!this.elements.texte) {
            console.warn('Élément textarea non trouvé');
            return;
        }

        // Vérifier si CKEditor est déjà initialisé sur cet élément
        const existingEditor = this.findExistingCKEditor();
        if (existingEditor) {
            console.log('CKEditor déjà initialisé, utilisation de l\'instance existante');
            this.editorInstance = existingEditor;
            this.enhanceExistingEditor(existingEditor);
            return;
        }

        // Attendre que ClassicEditor soit disponible
        let attempts = 0;
        const maxAttempts = 20;
        
        while (!window.ClassicEditor && attempts < maxAttempts) {
            await new Promise(resolve => setTimeout(resolve, 200));
            attempts++;
        }

        if (!window.ClassicEditor) {
            console.error('ClassicEditor non disponible après', maxAttempts, 'tentatives');
            return;
        }

        try {
            await this.createNewCKEditorInstance();
        } catch (error) {
            console.error('Erreur lors de la création de CKEditor:', error);
        }
    }

    findExistingCKEditor() {
        // Chercher dans les instances globales de CKEditor
        const textareaElement = this.elements.texte;
        
        // Vérifier si le textarea est masqué (signe qu'un éditeur est actif)
        if (textareaElement.style.display === 'none' || textareaElement.offsetParent === null) {
            // Chercher l'éditeur CKEditor suivant
            const nextSibling = textareaElement.nextElementSibling;
            if (nextSibling && nextSibling.classList.contains('ck-editor')) {
                // Essayer de trouver l'instance dans les données du DOM
                const editorView = nextSibling.querySelector('.ck-editor__editable');
                if (editorView && editorView.ckeditorInstance) {
                    return editorView.ckeditorInstance;
                }
            }
        }
        
        return null;
    }

    enhanceExistingEditor(editor) {
        this.editorInstance = editor;
        
        // Ajouter nos fonctionnalités personnalisées
        try {
            this.addMediaLibraryButton(editor);
            
            // Synchronisation
            editor.model.document.on('change:data', () => {
                this.elements.texte.value = editor.getData();
            });
            
            console.log('Éditeur existant amélioré avec succès');
        } catch (error) {
            console.warn('Erreur lors de l\'amélioration de l\'éditeur existant:', error);
        }
    }

    async createNewCKEditorInstance() {
        if (!window.ClassicEditor) {
            console.error('ClassicEditor non disponible');
            return;
        }

        try {
            const editor = await ClassicEditor.create(this.elements.texte, {
                language: 'fr',
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'blockQuote', 'insertTable', '|',
                    'imageUpload', '|',
                    'undo', 'redo'
                ],
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: false
                        }
                    ],
                    disallow: [
                        {
                            name: 'script'
                        },
                        {
                            attributes: [
                                { key: /^on.*/, value: true },
                                { key: 'javascript:', value: true }
                            ]
                        }
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', '|',
                        'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                    ]
                }
            });

            this.editorInstance = editor;
            
            // Style personnalisé
            editor.editing.view.change(writer => {
                writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
            });
            
            // Ajouter bouton médiathèque
            this.addMediaLibraryButton(editor);
            
            // Synchronisation
            editor.model.document.on('change:data', () => {
                this.elements.texte.value = editor.getData();
            });

        } catch (error) {
            console.error('Erreur création CKEditor:', error);
        }
    }

    addMediaLibraryButton(editor) {
        // Implementation du bouton médiathèque dans CKEditor
        const toolbar = editor.ui.view.toolbar;
        const mediaButton = document.createElement('button');
        mediaButton.type = 'button';
        mediaButton.className = 'ck ck-button ck-off';
        mediaButton.innerHTML = `
            <svg class="ck ck-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="ck ck-button__label">Médiathèque</span>
        `;
        mediaButton.title = 'Ouvrir la médiathèque';
        
        mediaButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.openMediaModal((url) => {
                editor.model.change(writer => {
                    const imageElement = writer.createElement('imageBlock', { src: url });
                    editor.model.insertContent(imageElement, editor.model.document.selection);
                });
            });
        });
        
        setTimeout(() => {
            const toolbarElement = toolbar.element.querySelector('.ck-toolbar__items');
            if (toolbarElement) {
                toolbarElement.appendChild(mediaButton);
            }
        }, 100);
    }

    // Utilitaires d'interface
    showProgress(message, percentage) {
        if (!this.uploadProgressBar) return;
        
        this.uploadProgressBar.classList.remove('hidden');
        document.getElementById('upload-status').textContent = message;
        document.getElementById('upload-progress-bar').style.width = percentage + '%';
        document.getElementById('upload-details').textContent = `${Math.round(percentage)}%`;
    }

    hideProgress() {
        if (this.uploadProgressBar) {
            this.uploadProgressBar.classList.add('hidden');
        }
    }

    showSubmitLoading() {
        const btn = this.elements.submitBtn;
        if (!btn) return;
        
        btn.disabled = true;
        btn.dataset.originalText = btn.innerHTML;
        btn.innerHTML = `
            <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Enregistrement...
        `;
    }

    hideSubmitLoading() {
        const btn = this.elements.submitBtn;
        if (!btn) return;
        
        btn.disabled = false;
        if (btn.dataset.originalText) {
            btn.innerHTML = btn.dataset.originalText;
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg max-w-sm ${
            type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
            type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
            'bg-blue-100 border border-blue-400 text-blue-700'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    handleKeyNavigation(e) {
        // Navigation améliorée au clavier
        if (e.key === 'Tab') {
            // Gestion personnalisée du focus si nécessaire
        }
    }

    animateFormEntry() {
        const formContainer = this.form.closest('.max-w-4xl');
        if (!formContainer) return;
        
        formContainer.style.opacity = '0';
        formContainer.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            formContainer.style.transition = 'all 0.5s ease-out';
            formContainer.style.opacity = '1';
            formContainer.style.transform = 'translateY(0)';
        }, 100);
    }

    // Nettoyage
    destroy() {
        if (this.autosaveInterval) {
            clearInterval(this.autosaveInterval);
        }
        
        if (this.editorInstance) {
            this.editorInstance.destroy();
        }
        
        // Nettoyer les event listeners si nécessaire
    }

    /**
     * Fonction d'aperçu d'image pour compatibilité
     */
    previewImageActualite(input) {
        if (input.files && input.files[0]) {
            this.handleImageSelect({ target: input });
        }
    }
}

// Créer une instance globale
let actualiteFormManager;

// Initialiser l'instance globale
document.addEventListener('DOMContentLoaded', () => {
    actualiteFormManager = new ActualiteFormManager();
});

// Exposer quelques méthodes utiles globalement
window.actualiteForm = {
    hideProgress: function() {
        if (actualiteFormManager && actualiteFormManager.uploadProgressBar) {
            actualiteFormManager.uploadProgressBar.classList.add('hidden');
        }
    },
    showProgress: function() {
        if (actualiteFormManager && actualiteFormManager.uploadProgressBar) {
            actualiteFormManager.uploadProgressBar.classList.remove('hidden');
        }
    },
    initializeCKEditor: function() {
        if (actualiteFormManager) {
            actualiteFormManager.initializeCKEditor();
        }
    },
    previewImageActualite: function(input) {
        if (actualiteFormManager) {
            actualiteFormManager.previewImageActualite(input);
        }
    }
};

// Exposer la fonction previewImageActualite globalement pour compatibilité
window.previewImageActualite = function(input) {
    if (window.actualiteForm) {
        window.actualiteForm.previewImageActualite(input);
    }
};

// Export pour utilisation en module si nécessaire
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ActualiteFormManager;
}
