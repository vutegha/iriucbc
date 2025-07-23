# Script PowerShell pour appliquer le design IRI moderne sur toutes les vues admin
# Ce script transforme automatiquement index.blade.php, create.blade.php et edit.blade.php

Write-Host "🎨 Début de la transformation massive des vues admin..." -ForegroundColor Cyan

# Définir les sections à transformer
$sections = @(
    @{name="publication"; title="Publications"; subtitle="Gestion des publications scientifiques IRI-UCBC"; icon="fas fa-file-alt"},
    @{name="actualite"; title="Actualités"; subtitle="Gestion des actualités et nouvelles IRI-UCBC"; icon="fas fa-newspaper"},
    @{name="projets"; title="Projets"; subtitle="Gestion des projets de recherche IRI-UCBC"; icon="fas fa-project-diagram"},
    @{name="rapports"; title="Rapports"; subtitle="Gestion des rapports et documents IRI-UCBC"; icon="fas fa-file-pdf"},
    @{name="auteur"; title="Auteurs"; subtitle="Gestion des auteurs et chercheurs IRI-UCBC"; icon="fas fa-user-edit"},
    @{name="media"; title="Médias"; subtitle="Gestion des médias et fichiers IRI-UCBC"; icon="fas fa-photo-video"},
    @{name="newsletter"; title="Newsletter"; subtitle="Gestion des abonnés newsletter IRI-UCBC"; icon="fas fa-envelope"},
    @{name="job-offers"; title="Offres d'emploi"; subtitle="Gestion des offres d'emploi IRI-UCBC"; icon="fas fa-briefcase"},
    @{name="job-applications"; title="Candidatures"; subtitle="Gestion des candidatures IRI-UCBC"; icon="fas fa-users"},
    @{name="categorie"; title="Catégories"; subtitle="Gestion des catégories IRI-UCBC"; icon="fas fa-tags"}
)

# Template pour les breadcrumbs
function Get-Breadcrumbs {
    param($sectionName, $itemName = $null)
    
    if ($itemName) {
        return @"
@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.$sectionName.index') }}" class="text-white/70 hover:text-white">$sectionName</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">$itemName</span>
            </div>
        </li>
    </ol>
</nav>
@endsection
"@
    } else {
        return @"
@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">$sectionName</span>
            </div>
        </li>
    </ol>
</nav>
@endsection
"@
    }
}

# Template pour l'en-tête moderne
function Get-ModernHeader {
    param($title, $subtitle, $sectionName, $icon, $hasCreate = $true)
    
    $createButton = ""
    if ($hasCreate) {
        $createButton = @"
                <a href="{{ route('admin.$sectionName.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-iri-primary rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau
                </a>
"@
    }
    
    return @"
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec design IRI -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="$icon mr-3"></i>
                        $title
                    </h1>
                    <p class="text-white/80 mt-1">$subtitle</p>
                </div>$createButton
            </div>
        </div>
    </div>
"@
}

# Fonction pour transformer index.blade.php
function Transform-IndexView {
    param($filePath, $section)
    
    if (Test-Path $filePath) {
        Write-Host "  📄 Transformation de $($section.name)/index.blade.php..." -ForegroundColor Yellow
        
        $content = Get-Content $filePath -Raw
        
        # Remplacer le début du fichier
        $newContent = "@extends('layouts.admin')`n`n"
        $newContent += Get-Breadcrumbs $section.name
        $newContent += "`n`n@section('content')`n"
        $newContent += Get-ModernHeader $section.title $section.subtitle $section.name $section.icon
        
        # Ajouter le contenu restant en gardant la logique métier
        $contentLines = $content -split "`n"
        $inContentSection = $false
        $skipNext = 0
        
        foreach ($line in $contentLines) {
            if ($skipNext -gt 0) {
                $skipNext--
                continue
            }
            
            if ($line -match "@section\('content'\)") {
                $inContentSection = $true
                continue
            }
            
            if ($inContentSection) {
                # Skip les anciennes statistiques et headers
                if ($line -match "<!-- Statistiques -->" -or 
                    $line -match "<div class=.*grid.*gap-.*mb-8" -or
                    $line -match "bg-coral" -or
                    $line -match "bi bi-") {
                    continue
                }
                
                # Garder le reste du contenu
                $newContent += $line + "`n"
            }
        }
        
        # Ajouter la fermeture
        $newContent += "</div>`n@endsection"
        
        Set-Content $filePath $newContent -Encoding UTF8
        Write-Host "    ✅ $($section.name)/index.blade.php transformé" -ForegroundColor Green
    }
}

# Fonction pour transformer create.blade.php
function Transform-CreateView {
    param($filePath, $section)
    
    if (Test-Path $filePath) {
        Write-Host "  📄 Transformation de $($section.name)/create.blade.php..." -ForegroundColor Yellow
        
        $content = Get-Content $filePath -Raw
        
        # Remplacer le début du fichier
        $newContent = "@extends('layouts.admin')`n`n"
        $newContent += Get-Breadcrumbs $section.name "Nouveau"
        $newContent += "`n`n@section('content')`n"
        
        # En-tête de création
        $newContent += @"
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="$($section.icon) mr-3"></i>
                            Créer - $($section.title)
                        </h1>
                        <p class="text-white/80 mt-1">Ajouter un nouveau contenu</p>
                    </div>
                    <a href="{{ route('admin.$($section.name).index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-iri-primary rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>

"@
        
        # Ajouter le contenu du formulaire existant (en gardant la logique)
        $contentLines = $content -split "`n"
        $inFormSection = $false
        
        foreach ($line in $contentLines) {
            if ($line -match "<x-admin-form" -or $line -match "<form") {
                $inFormSection = $true
                continue
            }
            
            if ($inFormSection -and $line -match "@endsection") {
                break
            }
            
            if ($inFormSection) {
                $newContent += $line + "`n"
            }
        }
        
        # Ajouter les actions finales
        $newContent += @"
        
        <!-- Actions -->
        <div class="flex items-center justify-between p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <a href="{{ route('admin.$($section.name).index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
            
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
"@
        
        Set-Content $filePath $newContent -Encoding UTF8
        Write-Host "    ✅ $($section.name)/create.blade.php transformé" -ForegroundColor Green
    }
}

# Fonction pour transformer edit.blade.php
function Transform-EditView {
    param($filePath, $section)
    
    if (Test-Path $filePath) {
        Write-Host "  📄 Transformation de $($section.name)/edit.blade.php..." -ForegroundColor Yellow
        
        $content = Get-Content $filePath -Raw
        
        # Remplacer le début du fichier
        $newContent = "@extends('layouts.admin')`n`n"
        $newContent += Get-Breadcrumbs $section.name "Modifier"
        $newContent += "`n`n@section('content')`n"
        
        # En-tête de modification
        $newContent += @"
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="$($section.icon) mr-3"></i>
                            Modifier - $($section.title)
                        </h1>
                        <p class="text-white/80 mt-1">Modification du contenu</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.$($section.name).index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-iri-primary rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

"@
        
        # Ajouter le contenu du formulaire existant (en gardant la logique)
        $contentLines = $content -split "`n"
        $inFormSection = $false
        
        foreach ($line in $contentLines) {
            if ($line -match "<x-admin-form" -or $line -match "<form") {
                $inFormSection = $true
                continue
            }
            
            if ($inFormSection -and $line -match "@endsection") {
                break
            }
            
            if ($inFormSection) {
                $newContent += $line + "`n"
            }
        }
        
        # Ajouter les actions finales
        $newContent += @"
        
        <!-- Actions -->
        <div class="flex items-center justify-between p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <a href="{{ route('admin.$($section.name).index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
            
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
"@
        
        Set-Content $filePath $newContent -Encoding UTF8
        Write-Host "    ✅ $($section.name)/edit.blade.php transformé" -ForegroundColor Green
    }
}

# Exécution de la transformation
foreach ($section in $sections) {
    Write-Host "`n📁 Traitement de la section: $($section.name)" -ForegroundColor Magenta
    
    $basePath = "resources\views\admin\$($section.name)"
    
    if (Test-Path $basePath) {
        # Transformer les vues
        Transform-IndexView "$basePath\index.blade.php" $section
        Transform-CreateView "$basePath\create.blade.php" $section
        Transform-EditView "$basePath\edit.blade.php" $section
    } else {
        Write-Host "  ⚠️  Le dossier $basePath n'existe pas" -ForegroundColor Red
    }
}

Write-Host "`n🎉 Transformation terminée!" -ForegroundColor Green
Write-Host "✨ Toutes les vues admin ont été modernisées avec le branding IRI:" -ForegroundColor Cyan
Write-Host "  - Breadcrumbs centralisés avec navigation cohérente" -ForegroundColor White
Write-Host "  - En-têtes avec gradients IRI (iri-primary to iri-secondary)" -ForegroundColor White
Write-Host "  - Boutons et actions harmonisés avec transitions" -ForegroundColor White
Write-Host "  - Icônes FontAwesome cohérentes" -ForegroundColor White
Write-Host "  - Layout responsive et moderne" -ForegroundColor White
