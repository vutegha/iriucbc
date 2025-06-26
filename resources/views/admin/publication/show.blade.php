@extends('layouts.admin')

@section('content')
@section('title', 'Lire une Publication')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-4">Show - Publications</h2>
    <!-- TODO: Implémenter le contenu spécifique pour show.blade.php -->
    

<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Détails de la publication</h1>

    <div class="bg-white shadow rounded p-6">
        <p><strong>Titre :</strong> {{ $publication->titre }}</p>
        <p><strong>Résumé :</strong> {{ $publication->resume }}</p>
        <p><strong>Citation :</strong> {{ $publication->citation }}</p>
        <p><strong>Auteur :</strong> {{ $publication->auteur->nom ?? 'Non défini' }}</p>
        <p><strong>Catégorie :</strong> {{ $publication->categorie->nom ?? 'Non définie' }}</p>
        <p><strong>À la une :</strong> {{ $publication->a_la_une ? 'Oui' : 'Non' }}</p>
        <p><strong>En vedette :</strong> {{ $publication->en_vedette ? 'Oui' : 'Non' }}</p>
            

               
        </p>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.publication.index') }}" class="text-blue-500 hover:underline">← Retour à la liste</a>
    </div>
</div>

   
</div>



@php
    $fileUrl = asset('storage/' . $publication->fichier_pdf);
@endphp

<div class="mb-6">
    <h3 class="font-bold text-lg mb-2">Fichier associé :</h3>

    <a href="{{ $fileUrl }}" class="text-blue-600 underline mb-4 inline-block" target="_blank">
        Télécharger le fichier
    </a>

    @if ($extension === 'pdf')
        <!-- Contrôles PDF -->
        <div class="sticky top-0 z-50 bg-white py-2 flex flex-wrap items-center gap-4 border-b border-gray-200 shadow-sm px-4">
            <input type="text" id="searchText" placeholder="Rechercher..." class="border px-3 py-1 rounded w-64">
            <button id="searchBtn" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Chercher</button>

            <button id="prevMatch" class="hidden bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">←</button>
            <button id="nextMatch" class="hidden bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">→</button>

            <span id="matchCount" class="hidden text-sm text-gray-700"></span>
            <span id="pageCount" class="text-sm text-gray-700 ml-auto"></span>
        </div>

        <!-- Conteneur PDF -->
        <div id="pdfViewer" class="px-4 max-w-7xl mx-auto space-y-8"></div>

        <style>
            #pdfViewer canvas {
                display: block;
                margin: 0 auto;
                width: 100% !important;
                height: auto !important;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            .highlighted {
                background-color: rgba(255, 255, 0, 0.4) !important;
                color: inherit !important;
                padding: 1px 2px;
                border-radius: 2px;
            }
        </style>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script>
            const url = "{{ $fileUrl }}";
            const container = document.getElementById('pdfViewer');
            const pageCountDisplay = document.getElementById('pageCount');
            const matchCountDisplay = document.getElementById('matchCount');
            const prevBtn = document.getElementById('prevMatch');
            const nextBtn = document.getElementById('nextMatch');
            let scale = 1.2;
            let pdfDoc = null;
            let searchText = '';
            let matches = [];
            let currentMatch = 0;

            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

            function renderPDF() {
                container.innerHTML = '';
                matches = [];
                currentMatch = 0;
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
                matchCountDisplay.classList.add('hidden');
                matchCountDisplay.textContent = '';

                pdfjsLib.getDocument(url).promise.then(pdf => {
                    pdfDoc = pdf;
                    pageCountDisplay.textContent = `Nombre de pages : ${pdf.numPages}`;

                    for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                        pdf.getPage(pageNumber).then(page => {
                            const viewport = page.getViewport({ scale });
                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            const renderContext = { canvasContext: context, viewport };
                            page.render(renderContext).promise.then(() => {
                                page.getTextContent().then(textContent => {
                                    const wrapper = document.createElement('div');
                                    wrapper.style.position = 'relative';
                                    wrapper.appendChild(canvas);

                                    const textLayer = document.createElement('div');
                                    textLayer.style.position = 'absolute';
                                    textLayer.style.top = 0;
                                    textLayer.style.left = 0;
                                    textLayer.style.width = canvas.width + 'px';
                                    textLayer.style.height = canvas.height + 'px';
                                    textLayer.style.pointerEvents = 'none';

                                    const textItems = textContent.items;

                                    textItems.forEach(item => {
                                        const div = document.createElement('div');
                                        div.textContent = item.str;
                                        div.style.position = 'absolute';
                                        div.style.left = item.transform[4] + 'px';
                                        div.style.top = (canvas.height - item.transform[5]) + 'px';
                                        div.style.fontSize = item.height + 'px';
                                        div.style.whiteSpace = 'pre';
                                        div.style.backgroundColor = 'transparent';
                                        div.style.color = 'transparent';
                                        div.style.pointerEvents = 'none';

                                        if (searchText && item.str.toLowerCase().includes(searchText)) {
                                            div.classList.add('highlighted');
                                            div.style.color = 'inherit';
                                            matches.push(div);
                                        }

                                        textLayer.appendChild(div);
                                    });

                                    wrapper.appendChild(textLayer);
                                    container.appendChild(wrapper);

                                    if (matches.length > 0) {
                                        prevBtn.classList.remove('hidden');
                                        nextBtn.classList.remove('hidden');
                                        matchCountDisplay.classList.remove('hidden');
                                        matchCountDisplay.textContent = `Occurrences : ${matches.length}`;
                                    }
                                });
                            });
                        });
                    }
                });
            }

            document.getElementById('searchBtn').addEventListener('click', function () {
                searchText = document.getElementById('searchText').value.trim().toLowerCase();
                if (searchText.length > 0) {
                    renderPDF();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (matches.length > 0 && currentMatch > 0) {
                    currentMatch--;
                    highlightMatch();
                }
            });

            nextBtn.addEventListener('click', () => {
                if (matches.length > 0 && currentMatch < matches.length - 1) {
                    currentMatch++;
                    highlightMatch();
                }
            });

            function highlightMatch() {
                matches.forEach((el, idx) => el.style.outline = idx === currentMatch ? '2px solid #2563eb' : 'none');
                matches[currentMatch].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            renderPDF();
        </script>
    @else
        <p class="text-red-500">Ce type de fichier n’est pas pris en charge pour l’aperçu direct.</p>
    @endif
</div>










@endsection 