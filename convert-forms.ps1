# Script PowerShell pour convertir tous les formulaires Trix vers CKEditor
# Exécuter depuis le répertoire racine du projet

Write-Host "Conversion des formulaires de Trix vers CKEditor..." -ForegroundColor Green

# Liste des fichiers à convertir
$files = @(
    "resources\views\admin\publication\_form.blade.php",
    "resources\views\admin\projets\_form.blade.php", 
    "resources\views\admin\auteur\_form.blade.php",
    "resources\views\admin\rapports\_form.blade.php",
    "resources\views\admin\categorie\_form.blade.php"
)

foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "Conversion de $file..." -ForegroundColor Yellow
        
        $content = Get-Content $file -Raw
        
        # Remplacer les pattern Trix par des textareas CKEditor
        $content = $content -replace '<input id="([^"]+)" type="hidden" name="([^"]+)" value="([^"]*)">\s*<trix-editor input="([^"]+)"[^>]*></trix-editor>', '<textarea name="$2" id="$1" class="wysiwyg form-input" rows="4">$3</textarea>'
        
        # Remplacer les labels pour utiliser la classe form-label
        $content = $content -replace 'class="block text-sm font-semibold text-olive"', 'class="form-label"'
        $content = $content -replace 'class="block text-sm font-medium text-gray-700"', 'class="form-label"'
        
        # Sauvegarder le fichier modifié
        Set-Content $file -Value $content -Encoding UTF8
        
        Write-Host "✓ $file converti" -ForegroundColor Green
    } else {
        Write-Host "✗ $file non trouvé" -ForegroundColor Red
    }
}

Write-Host "Conversion terminée !" -ForegroundColor Green
