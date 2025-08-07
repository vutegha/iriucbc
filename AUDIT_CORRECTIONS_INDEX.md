# PLAN DE CORRECTIONS - index.blade.php

## 🔥 CORRECTIONS URGENTES

### 1. Corriger l'affichage des actualités
```php
<!-- AVANT (ligne 31) -->
<a href="{{ route('site.actualite.show', ['slug' => $actualite->slug]) }}" 
   class="text-white hover:text-orange-300 font-medium text-sm transition-colors duration-200 break-words leading-tight">
</a>

<!-- APRÈS -->
<a href="{{ route('site.actualite.show', ['slug' => $actualite->slug]) }}" 
   class="text-white hover:text-orange-300 font-medium text-sm transition-colors duration-200 break-words leading-tight">
    {{ $actualite->titre }}
</a>
```

### 2. Corriger la classe CSS invalide
```php
<!-- AVANT (ligne 182) -->
<div class="text-center mb-&-">

<!-- APRÈS -->
<div class="text-center mb-16">
```

### 3. Corriger l'icône du bouton
```php
<!-- AVANT (ligne 72) -->
<svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
</svg>

<!-- APRÈS -->
<svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
```

## 🛡️ AMÉLIORATIONS SÉCURITÉ

### 1. Sécuriser les scripts externes
```php
<script async 
        src="https://platform.twitter.com/widgets.js" 
        charset="utf-8"
        integrity="sha384-..."
        crossorigin="anonymous"></script>
```

### 2. Validation des données
```php
@if(isset($actualites) && $actualites->count() > 0)
    @foreach($actualites as $actualite)
        <!-- Contenu sécurisé -->
    @endforeach
@endif
```

## 🚀 OPTIMISATIONS PERFORMANCE

### 1. Lazy loading des images
```php
<img src="{{ asset('storage/'.$actualite->image) }}" 
     alt="{{ $actualite->titre }}"
     class="w-full h-full object-cover"
     loading="lazy">
```

### 2. Chargement conditionnel de PDF.js
```php
@if($documentsRecents->isNotEmpty())
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
@endif
```

## ♿ AMÉLIORATIONS ACCESSIBILITÉ

### 1. ARIA labels pour le carousel
```php
<div class="splide publication-carousel" 
     id="project-carousel"
     role="region" 
     aria-label="Publications récentes">
```

### 2. Navigation clavier
```javascript
// Ajouter la navigation clavier au carousel
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') {
        splide.go('<');
    } else if (e.key === 'ArrowRight') {
        splide.go('>');
    }
});
```

## 📱 RESPONSIVE FIXES

### 1. Améliorer la grille des actualités
```php
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
```

### 2. Animation partenaires mobile-friendly
```css
@media (max-width: 768px) {
    .animate-scroll-smooth {
        animation-duration: 30s;
    }
}
```

## 🔍 MONITORING ET TESTS

### 1. Tests à effectuer
- [ ] Vérifier l'affichage des actualités
- [ ] Tester le carousel sur mobile
- [ ] Valider les performances Lighthouse
- [ ] Tests d'accessibilité avec screen reader
- [ ] Vérifier les erreurs console

### 2. Métriques à surveiller
- [ ] Core Web Vitals
- [ ] Temps de chargement
- [ ] Erreurs JavaScript
- [ ] Validation HTML/CSS

## 📋 CHECKLIST POST-CORRECTION

- [ ] Tous les liens fonctionnent
- [ ] Images s'affichent correctement
- [ ] Carousel responsive
- [ ] PDF preview fonctionne
- [ ] Section partenaires fluide
- [ ] Tests cross-browser
- [ ] Validation W3C
- [ ] Audit Lighthouse > 90
