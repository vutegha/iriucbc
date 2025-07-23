# Script PowerShell pour moderniser toutes les vues admin avec le branding IRI
# Ce script applique le design moderne sur les vues index, create et edit

# Définir les sections à moderniser
$sections = @(
    "publication", "actualite", "projets", "rapports", 
    "auteur", "media", "newsletter", "job-offers", "job-applications", "categories"
)

# Fonctions utilitaires pour les templates modernes
function Get-BreadcrumbsTemplate {
    param($sectionName, $itemTitle = $null)
    
    $breadcrumb = @"
@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
"@

    if ($itemTitle) {
        $breadcrumb += @"

        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.$sectionName.index') }}" class="text-white/70 hover:text-white">$sectionName</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">$itemTitle</span>
            </div>
        </li>
"@
    } else {
        $breadcrumb += @"

        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">$sectionName</span>
            </div>
        </li>
"@
    }

    $breadcrumb += @"

    </ol>
</nav>
@endsection
"@
    
    return $breadcrumb
}

function Get-HeaderTemplate {
    param($title, $subtitle, $createRoute = $null, $createLabel = $null)
    
    $header = @"
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec statistiques -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">$title</h1>
                    <p class="text-white/80 mt-1">$subtitle</p>
                </div>
"@

    if ($createRoute -and $createLabel) {
        $header += @"

                <a href="{{ $createRoute }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-iri-primary rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    $createLabel
                </a>
"@
    }

    $header += @"

            </div>
        </div>
    </div>
"@
    
    return $header
}

function Update-IndexView {
    param($filePath, $sectionName)
    
    if (Test-Path $filePath) {
        Write-Host "Modernisation de $filePath..." -ForegroundColor Green
        
        # Lire le contenu actuel
        $content = Get-Content $filePath -Raw
        
        # Remplacer les parties principales
        $newContent = $content -replace '@extends\(''layouts\.admin''\)', '@extends(''layouts.admin'')'
        
        # Ajouter breadcrumbs après @extends
        $breadcrumbs = Get-BreadcrumbsTemplate $sectionName
        $newContent = $newContent -replace '(@extends\(''layouts\.admin''\))', "`$1`n`n$breadcrumbs"
        
        # Remplacer @section('title'...) et @section('subtitle'...) par @section('content')
        $newContent = $newContent -replace '@section\(''title''[^@]+@section\(''subtitle''[^@]+@section\(''content''\)', '@section(''content'')'
        
        # Sauvegarder
        Set-Content $filePath $newContent -Encoding UTF8
        Write-Host "✅ $filePath modernisé" -ForegroundColor Green
    }
}

function Update-CreateEditView {
    param($filePath, $sectionName, $isEdit = $false)
    
    if (Test-Path $filePath) {
        Write-Host "Modernisation de $filePath..." -ForegroundColor Yellow
        
        # Lire le contenu actuel
        $content = Get-Content $filePath -Raw
        
        # Remplacer les parties principales selon le type
        if ($isEdit) {
            $itemTitle = "Modifier"
            $breadcrumbs = Get-BreadcrumbsTemplate $sectionName $itemTitle
        } else {
            $itemTitle = "Nouveau"
            $breadcrumbs = Get-BreadcrumbsTemplate $sectionName $itemTitle
        }
        
        # Remplacer @extends et ajouter breadcrumbs
        $newContent = $content -replace '(@extends\(''layouts\.admin''\))', "`$1`n`n$breadcrumbs"
        
        # Remplacer les anciens titles/subtitles
        $newContent = $newContent -replace '@section\(''title''[^@]+@section\(''subtitle''[^@]+@section\(''content''\)', '@section(''content'')'
        
        # Sauvegarder
        Set-Content $filePath $newContent -Encoding UTF8
        Write-Host "✅ $filePath modernisé" -ForegroundColor Green
    }
}

# Exécution du script
Write-Host "🚀 Début de la modernisation des vues admin avec le branding IRI..." -ForegroundColor Cyan

foreach ($section in $sections) {
    Write-Host "`n📁 Traitement de la section: $section" -ForegroundColor Magenta
    
    $basePath = "resources\views\admin\$section"
    
    # Vérifier si le dossier existe
    if (Test-Path $basePath) {
        # Moderniser index.blade.php
        $indexPath = "$basePath\index.blade.php"
        Update-IndexView $indexPath $section
        
        # Moderniser create.blade.php
        $createPath = "$basePath\create.blade.php"
        Update-CreateEditView $createPath $section $false
        
        # Moderniser edit.blade.php
        $editPath = "$basePath\edit.blade.php"
        Update-CreateEditView $editPath $section $true
    } else {
        Write-Host "⚠️  Le dossier $basePath n'existe pas" -ForegroundColor Red
    }
}

Write-Host "`n✨ Modernisation terminée! Toutes les vues admin ont été mises à jour avec le branding IRI." -ForegroundColor Green
Write-Host "🎨 Design appliqué:" -ForegroundColor Cyan
Write-Host "  - Breadcrumbs centralisés" -ForegroundColor White
Write-Host "  - En-têtes avec gradients IRI" -ForegroundColor White  
Write-Host "  - Statistiques modernes" -ForegroundColor White
Write-Host "  - Boutons et actions harmonisés" -ForegroundColor White
Write-Host "  - Palette de couleurs IRI unifiée" -ForegroundColor White
