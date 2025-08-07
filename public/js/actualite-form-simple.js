/**
 * Gestionnaire spécialisé pour le formulaire d'actualité
 * Version simplifiée qui cohabite avec l'initialisation CKEditor du layout
 */

class ActualiteFormHandler {
    constructor() {
        this.form = null;
        this.checkboxes = [];
        this.init();
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.form = document.getElementById('actualiteForm');
        if (!this.form) {
            console.log('Formulaire actualité non trouvé');
            return;
        }

        console.log('Initialisation ActualiteFormHandler...');
        this.setupCheckboxes();
        this.setupAccessibility();
        this.setupEventListeners();
    }

    setupCheckboxes() {
        const checkboxes = this.form.querySelectorAll('input[type="checkbox"]');
        console.log(`Configuration de ${checkboxes.length} checkboxes...`);

        checkboxes.forEach((checkbox, index) => {
            const label = checkbox.closest('label');
            if (!label) return;

            console.log(`Configuration checkbox ${index + 1}: ${checkbox.id || 'sans-id'}`);

            // Assigner un ID si nécessaire
            if (!checkbox.id) {
                checkbox.id = `checkbox-${index + 1}`;
            }

            // Configurer les événements
            this.setupCheckboxEvents(checkbox, label);
            
            // Initialiser l'état visuel
            this.updateCheckboxVisual(checkbox);

            this.checkboxes.push({ checkbox, label });
        });
    }

    setupCheckboxEvents(checkbox, label) {
        // Gérer le clic sur le label entier
        label.addEventListener('click', (e) => {
            // Empêcher la propagation multiple
            if (e.target === checkbox) return;
            
            e.preventDefault();
            checkbox.checked = !checkbox.checked;
            
            // Déclencher l'événement change
            const event = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(event);
        });

        // Gérer les changements d'état
        checkbox.addEventListener('change', () => {
            this.updateCheckboxVisual(checkbox);
            console.log(`Checkbox ${checkbox.id} changed to:`, checkbox.checked);
        });

        // Support clavier
        label.addEventListener('keydown', (e) => {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                checkbox.click();
            }
        });
    }

    updateCheckboxVisual(checkbox) {
        const label = checkbox.closest('label');
        if (!label) return;

        const container = label.querySelector('.flex-shrink-0 > div');
        const checkmark = label.querySelector('svg');
        
        if (checkbox.checked) {
            // État coché
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
            // État non coché
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

        // Mettre à jour ARIA
        label.setAttribute('aria-checked', checkbox.checked);
    }

    setupAccessibility() {
        // Améliorer l'accessibilité des checkboxes
        this.checkboxes.forEach(({ checkbox, label }) => {
            label.setAttribute('role', 'checkbox');
            label.setAttribute('tabindex', '0');
            label.setAttribute('aria-checked', checkbox.checked);
            
            if (!label.getAttribute('aria-label') && !label.getAttribute('aria-labelledby')) {
                const text = label.querySelector('.text-sm')?.textContent || 'Option';
                label.setAttribute('aria-label', text);
            }
        });

        // Ajouter aria-hidden aux SVGs décoratifs
        const decorativeSvgs = this.form.querySelectorAll('svg:not([aria-label]):not([aria-labelledby])');
        decorativeSvgs.forEach(svg => {
            svg.setAttribute('aria-hidden', 'true');
        });
    }

    setupEventListeners() {
        // Validation en temps réel
        const titre = this.form.querySelector('#titre');
        const resume = this.form.querySelector('#resume');
        const texte = this.form.querySelector('#texte');
        const image = this.form.querySelector('#image');

        if (titre) {
            titre.addEventListener('input', () => this.validateField(titre));
            titre.addEventListener('blur', () => this.validateField(titre));
        }

        if (resume) {
            resume.addEventListener('input', () => this.validateResumeField(resume));
            resume.addEventListener('blur', () => this.validateResumeField(resume));
        }

        if (texte) {
            // Pour CKEditor, on écoute les changements via l'événement personnalisé
            texte.addEventListener('input', () => this.validateField(texte));
            
            // Écouter aussi les changements CKEditor
            document.addEventListener('ckeditor:change', () => {
                this.validateField(texte);
            });
        }

        // Gestion de l'aperçu d'image
        if (image) {
            image.addEventListener('change', (e) => this.handleImageSelect(e));
            this.setupDragAndDrop(image);
        }

        // Gestion de la soumission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        // Amélioration UX : afficher les conseils
        this.setupFieldHelp();
    }

    handleImageSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        console.log('Image sélectionnée:', file.name);

        // Validation du fichier
        if (!this.validateImageFile(file)) {
            return;
        }

        // Afficher la prévisualisation
        this.displayImagePreview(file);

        // Optionnel: Compresser l'image si c'est trop lourd
        if (file.size > 2 * 1024 * 1024) { // Plus de 2MB
            this.showImageCompressionInfo(file);
        }
    }

    validateImageFile(file) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            this.showNotification(this.createNotification(
                'Format non supporté',
                'Veuillez sélectionner une image au format JPG, PNG, GIF ou WebP.',
                'error'
            ));
            return false;
        }

        if (file.size > maxSize) {
            this.showNotification(this.createNotification(
                'Fichier trop volumineux',
                'L\'image ne peut pas dépasser 10 MB.',
                'error'
            ));
            return false;
        }

        return true;
    }

    displayImagePreview(file) {
        const reader = new FileReader();
        
        reader.onload = (e) => {
            const previewImg = document.getElementById('image-preview-actualite');
            const placeholder = document.getElementById('image-placeholder-actualite');
            
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                
                // Masquer le placeholder si il existe
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }

                // Ajouter une animation d'apparition
                previewImg.style.opacity = '0';
                previewImg.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    previewImg.style.transition = 'all 0.3s ease-in-out';
                    previewImg.style.opacity = '1';
                    previewImg.style.transform = 'scale(1)';
                }, 10);

                console.log('Aperçu d\'image affiché avec succès');

                // Afficher les informations du fichier
                this.showImageInfo(file);
            } else {
                console.error('Élément image-preview-actualite non trouvé');
                this.createImagePreviewElement(e.target.result, file);
            }
        };

        reader.onerror = () => {
            console.error('Erreur lors de la lecture du fichier image');
            this.showNotification(this.createNotification(
                'Erreur de lecture',
                'Impossible de lire le fichier image sélectionné.',
                'error'
            ));
        };

        reader.readAsDataURL(file);
    }

    createImagePreviewElement(imageSrc, file) {
        // Si l'élément de prévisualisation n'existe pas, le créer
        const imageContainer = document.querySelector('.flex-shrink-0');
        if (imageContainer) {
            imageContainer.innerHTML = `
                <div class="relative group">
                    <img id="image-preview-actualite" 
                         src="${imageSrc}" 
                         class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm" 
                         alt="Aperçu de l'image sélectionnée"
                         style="opacity: 0; transform: scale(0.8); transition: all 0.3s ease-in-out;">
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <button type="button" onclick="actualiteFormHandler.removeImagePreview()" 
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors duration-200"
                            title="Supprimer l'image">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            // Animation d'apparition
            setTimeout(() => {
                const img = document.getElementById('image-preview-actualite');
                if (img) {
                    img.style.opacity = '1';
                    img.style.transform = 'scale(1)';
                }
            }, 10);

            this.showImageInfo(file);
        }
    }

    showImageInfo(file) {
        const fileSize = this.formatFileSize(file.size);
        const notification = this.createNotification(
            'Image sélectionnée',
            `${file.name} (${fileSize}) - Aperçu affiché`,
            'success'
        );
        this.showNotification(notification);
    }

    showImageCompressionInfo(file) {
        const fileSize = this.formatFileSize(file.size);
        const notification = this.createNotification(
            'Image volumineuse détectée',
            `Fichier de ${fileSize}. L'image sera automatiquement optimisée lors de l'upload.`,
            'info'
        );
        this.showNotification(notification);
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    removeImagePreview() {
        const imageInput = document.getElementById('image');
        const previewImg = document.getElementById('image-preview-actualite');
        const placeholder = document.getElementById('image-placeholder-actualite');

        // Réinitialiser l'input
        if (imageInput) {
            imageInput.value = '';
        }

        // Masquer la prévisualisation
        if (previewImg) {
            previewImg.style.transition = 'all 0.3s ease-in-out';
            previewImg.style.opacity = '0';
            previewImg.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                previewImg.classList.add('hidden');
                previewImg.src = '';
                
                // Afficher le placeholder si il existe
                if (placeholder) {
                    placeholder.classList.remove('hidden');
                }
            }, 300);
        }

        console.log('Aperçu d\'image supprimé');
    }

    setupDragAndDrop(imageInput) {
        const dropZone = document.getElementById('image-drop-zone');
        if (!dropZone) return;

        // Prévenir les comportements par défaut
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
            document.body.addEventListener(eventName, this.preventDefaults, false);
        });

        // Effet visuel lors du survol
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.highlight(dropZone), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => this.unhighlight(dropZone), false);
        });

        // Gestion du drop
        dropZone.addEventListener('drop', (e) => this.handleDrop(e, imageInput), false);
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    highlight(element) {
        element.classList.add('border-iri-primary', 'bg-iri-light');
        element.classList.remove('border-gray-300', 'bg-gray-50');
    }

    unhighlight(element) {
        element.classList.remove('border-iri-primary', 'bg-iri-light');
        element.classList.add('border-gray-300', 'bg-gray-50');
    }

    handleDrop(e, imageInput) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            const file = files[0];
            
            // Assigner le fichier à l'input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            imageInput.files = dataTransfer.files;

            // Déclencher l'événement change
            const event = new Event('change', { bubbles: true });
            imageInput.dispatchEvent(event);

            console.log('Fichier déposé:', file.name);
        }
    }

    setupFieldHelp() {
        // Ajouter des conseils d'aide pour le résumé
        const resume = this.form.querySelector('#resume');
        if (resume) {
            const helpText = document.createElement('div');
            helpText.className = 'text-xs text-blue-600 mt-1 hidden';
            helpText.id = 'resume-help';
            helpText.innerHTML = `
                <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                <strong>Astuce :</strong> Un bon résumé fait environ 100-200 mots répartis en 2 paragraphes courts.
            `;
            
            const description = document.getElementById('resume-description');
            description.parentNode.insertBefore(helpText, description.nextSibling);

            resume.addEventListener('focus', () => {
                helpText.classList.remove('hidden');
            });

            resume.addEventListener('blur', () => {
                setTimeout(() => helpText.classList.add('hidden'), 2000);
            });
        }
    }

    validateResumeField(field) {
        const errorElement = document.getElementById('error-resume');
        const errorMessage = document.getElementById('error-resume-message');
        
        if (!errorElement || !errorMessage) return;

        const value = field.value.trim();
        const words = value ? value.split(/\s+/).filter(word => word.length > 0) : [];
        const paragraphs = value ? value.split(/\n\s*\n/).filter(p => p.trim().length > 0) : [];

        let isValid = true;
        let message = '';

        if (value.length > 0) {
            // Validation optionnelle si le champ est rempli
            if (words.length < 10) {
                isValid = false;
                message = 'Le résumé semble trop court. Essayez d\'écrire au moins quelques phrases.';
            } else if (words.length > 300) {
                isValid = false;
                message = 'Le résumé est très long. Essayez de le condenser en environ 2 paragraphes.';
            } else if (paragraphs.length === 1 && words.length > 50) {
                isValid = false;
                message = 'Pour un résumé de cette longueur, pensez à le diviser en 2 paragraphes.';
            }
        }

        if (!isValid) {
            errorElement.classList.remove('hidden');
            errorMessage.textContent = message;
            field.classList.add('border-orange-300', 'focus:border-orange-500', 'focus:ring-orange-200');
            field.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-200');
        } else {
            errorElement.classList.add('hidden');
            field.classList.remove('border-orange-300', 'focus:border-orange-500', 'focus:ring-orange-200', 'border-red-300', 'focus:border-red-500', 'focus:ring-red-200');
        }

        return isValid;
    }

    validateField(field) {
        const errorElement = document.getElementById(`error-${field.name}`);
        if (!errorElement) return true;

        let isValid = true;
        let message = '';

        // Validation pour les champs requis
        if (field.required && !field.value.trim()) {
            isValid = false;
            message = 'Ce champ est obligatoire.';
        } else if (field.name === 'titre' && field.value.trim().length < 5) {
            isValid = false;
            message = 'Le titre doit contenir au moins 5 caractères.';
        } else if (field.name === 'texte') {
            // Pour CKEditor, vérifier le contenu
            const content = field.value.replace(/<[^>]*>/g, '').trim();
            if (content.length < 20) {
                isValid = false;
                message = 'Le contenu doit être plus détaillé (au moins 20 caractères de texte).';
            }
        }

        const errorMessage = errorElement.querySelector('span') || errorElement;
        
        if (!isValid) {
            errorElement.classList.remove('hidden');
            if (errorMessage.textContent !== undefined) {
                errorMessage.textContent = message;
            }
            field.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-200');
            field.classList.remove('border-gray-300');
        } else {
            errorElement.classList.add('hidden');
            field.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-200');
            field.classList.add('border-gray-300');
        }

        return isValid;
    }

    handleSubmit(e) {
        console.log('Validation du formulaire avant soumission...');
        
        // Validation finale de tous les champs
        let isValid = true;
        const requiredFields = this.form.querySelectorAll('[required]');
        
        // Valider les champs requis
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Valider le résumé si rempli
        const resume = this.form.querySelector('#resume');
        if (resume && resume.value.trim()) {
            if (!this.validateResumeField(resume)) {
                // Le résumé invalide ne bloque pas la soumission, juste un avertissement
                console.warn('Le résumé pourrait être amélioré');
            }
        }

        // Validation spéciale pour CKEditor
        const texte = this.form.querySelector('#texte');
        if (texte) {
            let content = '';
            
            // Si CKEditor est initialisé, utiliser son contenu
            if (window.texteEditor) {
                content = window.texteEditor.getData().replace(/<[^>]*>/g, '').trim();
            } else {
                // Fallback vers le contenu du textarea
                content = texte.value.replace(/<[^>]*>/g, '').trim();
            }
            
            if (content.length < 20) {
                this.validateField(texte);
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            console.log('Formulaire invalide, soumission annulée');
            
            // Afficher une notification d'erreur
            this.showFormErrorNotification();
            
            // Scroll vers le premier champ avec erreur
            const firstError = this.form.querySelector('.border-red-300');
            if (firstError) {
                firstError.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                firstError.focus();
            }
        } else {
            console.log('Formulaire valide, soumission en cours...');
            this.showFormSubmissionNotification();
        }

        return isValid;
    }

    showFormErrorNotification() {
        const notification = this.createNotification(
            'Erreurs dans le formulaire',
            'Veuillez corriger les erreurs signalées avant de soumettre.',
            'error'
        );
        this.showNotification(notification);
    }

    showFormSubmissionNotification() {
        const notification = this.createNotification(
            'Soumission en cours',
            'Votre actualité est en cours d\'enregistrement...',
            'info'
        );
        this.showNotification(notification);
    }

    createNotification(title, message, type = 'info') {
        const colors = {
            error: 'bg-red-100 border-red-400 text-red-700',
            success: 'bg-green-100 border-green-400 text-green-700',
            info: 'bg-blue-100 border-blue-400 text-blue-700',
            warning: 'bg-yellow-100 border-yellow-400 text-yellow-700'
        };

        const icons = {
            error: 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z',
            success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z'
        };

        return {
            title,
            message,
            type,
            className: colors[type] || colors.info,
            icon: icons[type] || icons.info
        };
    }

    showNotification(notification) {
        // Créer l'élément de notification
        const notificationEl = document.createElement('div');
        notificationEl.className = `fixed top-4 right-4 max-w-sm p-4 border rounded-lg shadow-lg z-50 ${notification.className}`;
        notificationEl.innerHTML = `
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="${notification.icon}" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <h4 class="font-medium">${notification.title}</h4>
                    <p class="text-sm mt-1">${notification.message}</p>
                </div>
                <button class="ml-3 flex-shrink-0" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notificationEl);

        // Auto-suppression après 5 secondes
        setTimeout(() => {
            if (notificationEl.parentNode) {
                notificationEl.remove();
            }
        }, 5000);
    }

    // Méthodes utilitaires
    getCheckboxValues() {
        const values = {};
        this.checkboxes.forEach(({ checkbox }) => {
            values[checkbox.name] = checkbox.checked;
        });
        return values;
    }

    setCheckboxValue(name, checked) {
        const checkbox = this.form.querySelector(`input[name="${name}"]`);
        if (checkbox) {
            checkbox.checked = checked;
            this.updateCheckboxVisual(checkbox);
        }
    }

    // Méthode pour la prévisualisation d'image (compatibilité)
    previewImageActualite(input) {
        if (input.files && input.files[0]) {
            this.handleImageSelect({ target: input });
        }
    }

    // Méthode publique pour supprimer l'aperçu
    removeImagePreview() {
        const imageInput = document.getElementById('image');
        const previewImg = document.getElementById('image-preview-actualite');
        const placeholder = document.getElementById('image-placeholder-actualite');

        // Réinitialiser l'input
        if (imageInput) {
            imageInput.value = '';
        }

        // Masquer la prévisualisation avec animation
        if (previewImg) {
            previewImg.style.transition = 'all 0.3s ease-in-out';
            previewImg.style.opacity = '0';
            previewImg.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                previewImg.classList.add('hidden');
                previewImg.src = '';
                
                // Afficher le placeholder si il existe
                if (placeholder) {
                    placeholder.classList.remove('hidden');
                }
            }, 300);
        }

        // Notification
        this.showNotification(this.createNotification(
            'Image supprimée',
            'L\'aperçu de l\'image a été supprimé.',
            'info'
        ));

        console.log('Aperçu d\'image supprimé');
    }
}

// Initialisation
let actualiteFormHandler;

// Fonction d'initialisation
function initializeActualiteForm() {
    if (!actualiteFormHandler) {
        actualiteFormHandler = new ActualiteFormHandler();
    }
}

// Fonction pour attendre CKEditor
function waitForCKEditor() {
    return new Promise((resolve) => {
        if (window.ckeditorReady) {
            resolve();
            return;
        }
        
        // Écouter l'événement de préparation de CKEditor
        document.addEventListener('ckeditor:ready', () => {
            console.log('CKEditor prêt, initialisation du formulaire...');
            resolve();
        }, { once: true });
        
        // Timeout de sécurité
        setTimeout(() => {
            console.log('Timeout CKEditor, initialisation du formulaire quand même...');
            resolve();
        }, 3000);
    });
}

// Auto-initialisation avec attente CKEditor
document.addEventListener('DOMContentLoaded', async () => {
    console.log('DOM chargé, attente de CKEditor...');
    await waitForCKEditor();
    initializeActualiteForm();
});

// Exposition globale pour compatibilité et méthodes utiles
window.actualiteFormHandler = actualiteFormHandler;
window.initializeActualiteForm = initializeActualiteForm;

// Exposer les méthodes principales globalement
window.previewImageActualite = function(input) {
    if (actualiteFormHandler) {
        actualiteFormHandler.previewImageActualite(input);
    }
};

window.removeImagePreview = function() {
    if (actualiteFormHandler) {
        actualiteFormHandler.removeImagePreview();
    }
};

// Export pour modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ActualiteFormHandler;
}
