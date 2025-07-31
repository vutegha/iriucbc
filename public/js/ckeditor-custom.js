// Configuration CKEditor 5 personnalisée avec plus d'outils
// Cette version utilise un build étendu via CDN

function createAdvancedEditor(elementId, config = {}) {
    return ClassicEditor
        .create(document.getElementById(elementId), {
            language: 'fr',
            toolbar: {
                items: [
                    'heading', 'fontSize', 'fontFamily', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'fontColor', 'fontBackgroundColor', '|',
                    'alignment', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', '|',
                    'imageUpload', '|',
                    'highlight', 'removeFormat', '|',
                    'undo', 'redo'
                ],
                shouldNotGroupWhenFull: true
            },
            // Configuration des tailles de police
            fontSize: {
                options: [
                    'tiny',
                    'small',
                    'default',
                    'big',
                    'huge'
                ]
            },
            // Configuration des polices
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ]
            },
            // Configuration des couleurs de texte
            fontColor: {
                colors: [
                    {
                        color: 'hsl(0, 0%, 0%)',
                        label: 'Noir'
                    },
                    {
                        color: 'hsl(0, 0%, 30%)',
                        label: 'Gris foncé'
                    },
                    {
                        color: 'hsl(0, 0%, 60%)',
                        label: 'Gris'
                    },
                    {
                        color: 'hsl(0, 0%, 90%)',
                        label: 'Gris clair'
                    },
                    {
                        color: 'hsl(0, 0%, 100%)',
                        label: 'Blanc',
                        hasBorder: true
                    },
                    // Couleurs vives
                    {
                        color: 'hsl(0, 75%, 60%)',
                        label: 'Rouge'
                    },
                    {
                        color: 'hsl(30, 75%, 60%)',
                        label: 'Orange'
                    },
                    {
                        color: 'hsl(60, 75%, 60%)',
                        label: 'Jaune'
                    },
                    {
                        color: 'hsl(90, 75%, 60%)',
                        label: 'Vert clair'
                    },
                    {
                        color: 'hsl(120, 75%, 60%)',
                        label: 'Vert'
                    },
                    {
                        color: 'hsl(180, 75%, 60%)',
                        label: 'Turquoise'
                    },
                    {
                        color: 'hsl(210, 75%, 60%)',
                        label: 'Bleu clair'
                    },
                    {
                        color: 'hsl(240, 75%, 60%)',
                        label: 'Bleu'
                    },
                    {
                        color: 'hsl(270, 75%, 60%)',
                        label: 'Violet'
                    }
                ]
            },
            // Configuration du surlignage
            fontBackgroundColor: {
                colors: [
                    {
                        color: 'hsl(0, 0%, 100%)',
                        label: 'Blanc',
                        hasBorder: true
                    },
                    {
                        color: 'hsl(60, 75%, 90%)',
                        label: 'Jaune clair'
                    },
                    {
                        color: 'hsl(120, 75%, 90%)',
                        label: 'Vert clair'
                    },
                    {
                        color: 'hsl(180, 75%, 90%)',
                        label: 'Cyan clair'
                    },
                    {
                        color: 'hsl(240, 75%, 90%)',
                        label: 'Bleu clair'
                    },
                    {
                        color: 'hsl(300, 75%, 90%)',
                        label: 'Violet clair'
                    },
                    {
                        color: 'hsl(0, 75%, 90%)',
                        label: 'Rouge clair'
                    }
                ]
            },
            // Configuration de l'alignement
            alignment: {
                options: ['left', 'center', 'right', 'justify']
            },
            // Configuration des images
            image: {
                toolbar: [
                    'imageTextAlternative', '|',
                    'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                    'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight'
                ],
                styles: [
                    'full',
                    'side',
                    'alignLeft',
                    'alignCenter',
                    'alignRight',
                    'inline',
                    'block'
                ]
            },
            // Configuration des tableaux
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells',
                    'tableProperties', 'tableCellProperties'
                ]
            },
            // Configuration du surlignage
            highlight: {
                options: [
                    {
                        model: 'yellowMarker',
                        class: 'marker-yellow',
                        title: 'Surligneur jaune',
                        color: '#ffff00',
                        type: 'marker'
                    },
                    {
                        model: 'greenMarker',
                        class: 'marker-green',
                        title: 'Surligneur vert',
                        color: '#00ff00',
                        type: 'marker'
                    },
                    {
                        model: 'pinkMarker',
                        class: 'marker-pink',
                        title: 'Surligneur rose',
                        color: '#ff69b4',
                        type: 'marker'
                    },
                    {
                        model: 'blueMarker',
                        class: 'marker-blue',
                        title: 'Surligneur bleu',
                        color: '#87ceeb',
                        type: 'marker'
                    }
                ]
            },
            // Fusionner avec la configuration passée en paramètre
            ...config
        });
}
