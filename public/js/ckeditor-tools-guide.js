// Guide pour ajouter plus d'outils à CKEditor

// 1. ALIGNEMENT DU TEXTE
function addAlignmentButtons(editor) {
    const alignments = [
        { name: 'left', icon: '⬅️', title: 'Aligner à gauche' },
        { name: 'center', icon: '➡️⬅️', title: 'Centrer' },
        { name: 'right', icon: '➡️', title: 'Aligner à droite' },
        { name: 'justify', icon: '⬅️➡️', title: 'Justifier' }
    ];
    
    alignments.forEach(align => {
        const button = createToolbarButton(align.icon, align.title, () => {
            editor.execute('alignment', { value: align.name });
        });
        addToToolbar(button);
    });
}

// 2. TAILLE DE POLICE
function addFontSizeButtons(editor) {
    const sizes = ['12px', '14px', '16px', '18px', '20px', '24px', '28px'];
    
    const dropdown = createDropdown('Taille', sizes.map(size => ({
        label: size,
        action: () => editor.execute('fontSize', { value: size })
    })));
    
    addToToolbar(dropdown);
}

// 3. POLICE DE CARACTÈRES
function addFontFamilyButtons(editor) {
    const fonts = [
        'Arial', 'Times New Roman', 'Helvetica', 
        'Georgia', 'Verdana', 'Courier New'
    ];
    
    const dropdown = createDropdown('Police', fonts.map(font => ({
        label: font,
        action: () => editor.execute('fontFamily', { value: font })
    })));
    
    addToToolbar(dropdown);
}

// 4. STYLES DE TEXTE AVANCÉS
function addTextStyleButtons(editor) {
    const styles = [
        { name: 'strikethrough', icon: 'S̶', title: 'Barré' },
        { name: 'subscript', icon: 'X₂', title: 'Indice' },
        { name: 'superscript', icon: 'X²', title: 'Exposant' }
    ];
    
    styles.forEach(style => {
        const button = createToolbarButton(style.icon, style.title, () => {
            editor.execute(style.name);
        });
        addToToolbar(button);
    });
}

// 5. LISTES AVANCÉES
function addAdvancedListButtons(editor) {
    const listTypes = [
        { name: 'todoList', icon: '☑️', title: 'Liste de tâches' },
        { name: 'bulletedList', icon: '•', title: 'Liste à puces' },
        { name: 'numberedList', icon: '1.', title: 'Liste numérotée' }
    ];
    
    listTypes.forEach(list => {
        const button = createToolbarButton(list.icon, list.title, () => {
            editor.execute(list.name);
        });
        addToToolbar(button);
    });
}

// 6. INSERTION D'ÉLÉMENTS
function addInsertButtons(editor) {
    const elements = [
        { name: 'horizontalLine', icon: '─', title: 'Ligne horizontale' },
        { name: 'pageBreak', icon: '📄', title: 'Saut de page' },
        { name: 'specialCharacters', icon: '©', title: 'Caractères spéciaux' }
    ];
    
    elements.forEach(element => {
        const button = createToolbarButton(element.icon, element.title, () => {
            editor.execute(element.name);
        });
        addToToolbar(button);
    });
}

// FONCTIONS UTILITAIRES
function createToolbarButton(icon, title, callback) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'ck ck-button ck-off';
    button.innerHTML = `
        <span class="ck ck-button__label">${icon} ${title}</span>
    `;
    button.title = title;
    button.addEventListener('click', callback);
    return button;
}

function createDropdown(title, options) {
    const dropdown = document.createElement('div');
    dropdown.className = 'ck ck-dropdown';
    dropdown.innerHTML = `
        <button class="ck ck-button ck-dropdown__button" type="button">
            <span class="ck ck-button__label">${title}</span>
            <svg class="ck ck-dropdown__arrow" width="10" height="10">
                <path d="M.941 4.523a.75.75 0 1 1 1.06-1.06l3.006 3.005 3.005-3.005a.75.75 0 1 1 1.06 1.06l-3.549 3.55a.75.75 0 0 1-1.06 0L.941 4.523z"/>
            </svg>
        </button>
        <div class="ck ck-dropdown__panel" style="display: none;">
            ${options.map(opt => `
                <button class="dropdown-option" data-action="${opt.label}">
                    ${opt.label}
                </button>
            `).join('')}
        </div>
    `;
    
    // Gestion des événements
    const button = dropdown.querySelector('.ck-dropdown__button');
    const panel = dropdown.querySelector('.ck-dropdown__panel');
    
    button.addEventListener('click', () => {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });
    
    options.forEach((opt, index) => {
        const optionBtn = dropdown.querySelectorAll('.dropdown-option')[index];
        optionBtn.addEventListener('click', () => {
            opt.action();
            panel.style.display = 'none';
        });
    });
    
    return dropdown;
}

function addToToolbar(element) {
    setTimeout(() => {
        const toolbar = document.querySelector('.ck-toolbar__items');
        if (toolbar) {
            toolbar.appendChild(element);
        }
    }, 100);
}

// EXEMPLE D'UTILISATION COMPLÈTE
function initializeFullFeaturedEditor(elementId) {
    ClassicEditor.create(document.getElementById(elementId), {
        // Configuration de base...
    }).then(editor => {
        // Ajouter tous les outils personnalisés
        addAlignmentButtons(editor);
        addFontSizeButtons(editor);
        addFontFamilyButtons(editor);
        addTextStyleButtons(editor);
        addAdvancedListButtons(editor);
        addInsertButtons(editor);
        addCustomToolbarButtons(editor); // Nos boutons couleur/surlignage
        addMediaLibraryButton(editor);
    });
}
