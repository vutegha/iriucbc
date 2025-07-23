@extends('layouts.admin-new')

@section('title', 'Test CKEditor')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Test CKEditor WYSIWYG</h2>
        
        <form action="#" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="test-content" class="form-label">Contenu de test</label>
                <textarea name="content" id="test-content" class="wysiwyg form-input" rows="5">
                    <p>Bonjour ! Voici un <strong>test</strong> de l'éditeur CKEditor.</p>
                    <ul>
                        <li>Élément de liste 1</li>
                        <li>Élément de liste 2</li>
                    </ul>
                </textarea>
            </div>
            
            <div>
                <label for="test-description" class="form-label">Description de test</label>
                <textarea name="description" id="test-description" class="wysiwyg form-input" rows="4">
                    <p>Une autre zone de texte pour tester l'éditeur.</p>
                </textarea>
            </div>
            
            <div>
                <label for="simple-text" class="form-label">Texte simple (sans éditeur)</label>
                <textarea name="simple" id="simple-text" class="form-input" rows="3" placeholder="Ceci est un textarea normal..."></textarea>
            </div>
            
            <div class="flex space-x-4">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer le test
                </button>
                <button type="button" class="btn-secondary" onclick="showContent()">
                    <i class="fas fa-eye mr-2"></i>
                    Afficher le contenu
                </button>
            </div>
        </form>
        
        <div id="content-display" class="mt-8 p-4 bg-gray-50 rounded-lg hidden">
            <h3 class="font-bold text-gray-900 mb-4">Contenu récupéré :</h3>
            <div id="content-output" class="space-y-4"></div>
        </div>
    </div>
</div>

<script>
function showContent() {
    const content = document.getElementById('test-content').value;
    const description = document.getElementById('test-description').value;
    const simple = document.getElementById('simple-text').value;
    
    const output = document.getElementById('content-output');
    output.innerHTML = `
        <div>
            <strong>Contenu :</strong>
            <div class="border p-2 bg-white rounded mt-1">${content || 'Vide'}</div>
        </div>
        <div>
            <strong>Description :</strong>
            <div class="border p-2 bg-white rounded mt-1">${description || 'Vide'}</div>
        </div>
        <div>
            <strong>Texte simple :</strong>
            <div class="border p-2 bg-white rounded mt-1">${simple || 'Vide'}</div>
        </div>
    `;
    
    document.getElementById('content-display').classList.remove('hidden');
}
</script>
@endsection
