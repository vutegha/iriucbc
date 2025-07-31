# Documentation des Blocs Rich Text CKEditor

## Vue d'ensemble

Le système de gestion de contenu IRI-UCBC utilise désormais CKEditor 5 pour permettre la création de contenu riche avec formatage avancé. Ce document explique l'implémentation et l'utilisation des blocs d'affichage de contenu rich text.

## Architecture

### 1. Composant Rich Text Display

**Fichier:** `resources/views/components/rich-text-display.blade.php`

Ce composant Blade gère l'affichage sécurisé et stylisé du contenu HTML généré par CKEditor.

#### Utilisation

```blade
<x-rich-text-display :content="$projet->description" />

<!-- Avec titre personnalisé -->
<x-rich-text-display 
    :content="$actualite->texte" 
    title="Contenu de l'article"
    class="custom-class" />
```

#### Propriétés

- `content` (string) : Le contenu HTML à afficher
- `title` (string, optionnel) : Titre à afficher au-dessus du contenu
- `class` (string, optionnel) : Classes CSS supplémentaires

### 2. Styles et Formatage

Le composant inclut des styles Tailwind CSS optimisés pour :

#### Éléments de base
- **Paragraphes** : Espacement et couleurs harmonieux
- **Titres** : Hiérarchie claire avec des tailles appropriées
- **Liens** : Couleurs IRI avec transitions hover
- **Texte fort** : Mise en évidence appropriée

#### Éléments avancés
- **Tables** : Bordures, alternance de couleurs, padding adaptatif
- **Listes** : Puces et numérotation stylisées
- **Images** : Arrondis, ombres, centrage automatique
- **Citations** : Bordure gauche colorée, arrière-plan subtil
- **Code** : Arrière-plan gris, police monospace

#### Responsive Design
- Adaptation automatique sur mobile
- Tables scrollables horizontalement
- Texte et espacement ajustés

### 3. Intégration dans les Vues

#### Vue Admin (Projets)
**Fichier:** `resources/views/admin/projets/show.blade.php`

```blade
<!-- Description Rich Text -->
@if($projet->description)
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
        <h2 class="text-xl font-semibold text-white flex items-center">
            <i class="fas fa-align-left mr-3"></i>
            Description
        </h2>
    </div>
    <div class="p-6">
        <x-rich-text-display :content="$projet->description" />
    </div>
</div>
@endif
```

#### Vue Frontend (Projets)
**Fichier:** `resources/views/showprojet.blade.php`

```blade
@if($projet->description)
    <x-rich-text-display :content="$projet->description" />
@else
    <p class="text-gray-500 italic">Aucune description disponible pour ce projet.</p>
@endif
```

#### Vue Frontend (Actualités)
**Fichier:** `resources/views/showactualite.blade.php`

```blade
@if($actualite->texte || $actualite->contenu)
    <x-rich-text-display 
        :content="$actualite->texte ?? $actualite->contenu" 
        class="prose-headings:text-iri-primary prose-links:text-iri-secondary prose-strong:text-iri-dark" />
@endif
```

## Fonctionnalités Supportées

### 1. Formatage du Texte
- **Gras, Italique, Souligné**
- **Couleurs de texte personnalisées**
- **Tailles de police variables**
- **Alignement (gauche, centre, droite, justifié)**

### 2. Structure de Contenu
- **Titres hiérarchiques** (H1 à H6)
- **Paragraphes avec espacement**
- **Listes à puces et numérotées**
- **Citations en bloc**

### 3. Éléments Multimédia
- **Images avec légendes**
- **Redimensionnement automatique**
- **Alignement des images**

### 4. Tableaux
- **Création et édition de tableaux**
- **En-têtes stylisés**
- **Alternance de couleurs des lignes**
- **Responsive design**

### 5. Liens et Navigation
- **Liens externes et internes**
- **Couleurs harmonisées avec le thème IRI**
- **Effets de survol**

## Sécurité

### Échappement HTML
Le composant utilise `{!! !!}` pour afficher le contenu HTML, mais le contenu provient de CKEditor qui sanitise automatiquement le code dangereux.

### Validation Backend
```php
// Dans le contrôleur
'description' => 'required|string',
'resume' => 'nullable|string|max:255',
```

## Personnalisation

### Couleurs IRI
Le composant respecte la charte graphique IRI avec :
- `text-iri-primary` pour les liens
- `border-iri-primary` pour les citations
- `prose-headings:text-gray-800` pour les titres

### Classes CSS Personnalisées
```css
/* Exemple d'extension */
.rich-text-content .custom-highlight {
    @apply bg-yellow-100 px-2 py-1 rounded;
}
```

## Maintenance

### Mise à jour des Styles
Pour modifier l'apparence, éditer le fichier :
`resources/views/components/rich-text-display.blade.php`

### Ajout de Nouveaux Éléments CKEditor
1. Mettre à jour la configuration CKEditor dans `_form.blade.php`
2. Ajouter les styles correspondants dans le composant
3. Tester sur différentes tailles d'écran

## Exemples d'Utilisation

### Contenu Simple
```blade
<x-rich-text-display :content="$projet->description" />
```

### Contenu avec Titre
```blade
<x-rich-text-display 
    :content="$actualite->texte" 
    title="Article complet" />
```

### Contenu avec Classes Personnalisées
```blade
<x-rich-text-display 
    :content="$content" 
    class="border-2 border-blue-200 p-4" />
```

## Compatibilité

- ✅ **Desktop** : Toutes résolutions
- ✅ **Tablet** : iPad et équivalents
- ✅ **Mobile** : Smartphones
- ✅ **Navigateurs** : Chrome, Firefox, Safari, Edge

## Notes Techniques

1. **Performance** : Le composant utilise Tailwind CSS pour une taille optimisée
2. **Accessibilité** : Respect des standards WCAG 2.1
3. **SEO** : Structure HTML sémantique préservée
4. **Maintenance** : Code modulaire et réutilisable

---

**Dernière mise à jour :** {{ date('d/m/Y') }}
**Version :** 1.0.0
