# Documentation - Interface Admin Harmonisée

## Vue d'ensemble

L'interface d'administration d'IRI-UCBC a été complètement harmonisée avec un design moderne et cohérent utilisant **Tailwind CSS** et une architecture basée sur des **composants Blade réutilisables**.

## Composants Blade

### 1. `x-admin-layout` - Layout principal pour les vues de liste

```php
<x-admin-layout 
    title="Titre de la page"
    subtitle="Description de la page"
    create-route="{{ route('admin.example.create') }}"
    create-label="Nouveau Element"
    :show-search="true"
    search-placeholder="Rechercher..."
    :filters="[
        [
            'name' => 'status',
            'label' => 'Statut',
            'type' => 'select',
            'options' => ['active' => 'Actif', 'inactive' => 'Inactif']
        ]
    ]"
    :stats="[
        [
            'label' => 'Total',
            'value' => 100,
            'icon' => 'bi-folder',
            'bg_color' => 'bg-coral',
            'icon_color' => 'text-coral'
        ]
    ]"
>
    <!-- Contenu de la table -->
</x-admin-layout>
```

**Propriétés:**
- `title` : Titre principal de la page
- `subtitle` : Sous-titre descriptif
- `create-route` : Route pour créer un nouvel élément
- `create-label` : Texte du bouton de création
- `show-search` : Afficher la barre de recherche (true/false)
- `search-placeholder` : Texte d'aide de la recherche
- `filters` : Array de filtres (nom, label, type, options)
- `stats` : Array de statistiques à afficher

### 2. `x-admin-form` - Wrapper pour les formulaires

```php
<x-admin-form 
    title="Créer un élément"
    subtitle="Ajouter un nouvel élément"
    :action="route('admin.example.store')"
    method="POST"
    back-route="{{ route('admin.example.index') }}"
    back-label="Retour à la liste"
    submit-label="Créer"
    :multipart="false"
>
    <!-- Champs du formulaire -->
</x-admin-form>
```

**Propriétés:**
- `title` : Titre du formulaire
- `subtitle` : Sous-titre du formulaire
- `action` : URL d'action du formulaire
- `method` : Méthode HTTP (POST, PUT, DELETE)
- `back-route` : Route de retour
- `back-label` : Texte du bouton retour
- `submit-label` : Texte du bouton de soumission
- `multipart` : Formulaire avec upload de fichier (true/false)

### 3. `x-form-field` - Champs de formulaire universels

```php
<x-form-field
    name="titre"
    type="text"
    label="Titre"
    placeholder="Entrez le titre"
    :value="old('titre')"
    required="true"
    help="Texte d'aide pour ce champ"
/>
```

**Types supportés:**
- `text` : Champ texte simple
- `email` : Champ email avec validation
- `password` : Champ mot de passe
- `textarea` : Zone de texte multi-lignes
- `select` : Liste déroulante
- `checkbox` : Case à cocher
- `radio` : Boutons radio
- `file` : Upload de fichier
- `date` : Sélecteur de date
- `time` : Sélecteur d'heure
- `number` : Champ numérique
- `url` : Champ URL

**Propriétés:**
- `name` : Nom du champ
- `type` : Type de champ
- `label` : Libellé du champ
- `placeholder` : Texte d'aide
- `value` : Valeur du champ
- `required` : Champ obligatoire (true/false)
- `help` : Texte d'aide sous le champ
- `options` : Options pour select/radio (array)
- `rows` : Nombre de lignes pour textarea
- `min/max` : Valeurs min/max pour number
- `step` : Pas pour number
- `accept` : Types de fichiers acceptés pour file

## Couleurs et Thème

### Couleur principale : Coral
- Classe CSS : `text-coral`, `bg-coral`, `border-coral`
- Code couleur : Défini dans Tailwind config

### Palettes de statuts
- **Succès** : `bg-green-100 text-green-800`
- **Attention** : `bg-yellow-100 text-yellow-800`
- **Erreur** : `bg-red-100 text-red-800`
- **Info** : `bg-blue-100 text-blue-800`
- **Neutre** : `bg-gray-100 text-gray-800`

## Structure des vues

### Vue de liste (index.blade.php)
```php
@extends('layouts.admin')
@section('title', 'Titre')
@section('subtitle', 'Description')

@section('content')
<x-admin-layout ... >
    <!-- Table avec pagination -->
</x-admin-layout>
@endsection
```

### Vue de création (create.blade.php)
```php
@extends('layouts.admin')
@section('title', 'Créer')

@section('content')
<x-admin-form ... >
    <!-- Sections groupées de champs -->
</x-admin-form>
@endsection
```

### Vue d'édition (edit.blade.php)
```php
@extends('layouts.admin')
@section('title', 'Modifier')

@section('content')
<x-admin-form ... >
    <!-- Champs pré-remplis + infos de publication -->
</x-admin-form>
@endsection
```

## Système de modération

### Champs ajoutés aux tables
- `is_published` : Statut de publication (boolean)
- `published_at` : Date de publication (timestamp)
- `published_by` : ID de l'utilisateur qui a publié (foreign key)
- `moderation_comment` : Commentaire de modération (text)

### Routes de modération
```php
// Publier
Route::post('/admin/{model}/{id}/publish', [Controller::class, 'publish'])->name('admin.model.publish');

// Dépublier
Route::post('/admin/{model}/{id}/unpublish', [Controller::class, 'unpublish'])->name('admin.model.unpublish');
```

### Méthodes dans les contrôleurs
```php
public function publish(Request $request, Model $item)
{
    $item->update([
        'is_published' => true,
        'published_at' => now(),
        'published_by' => auth()->id(),
        'moderation_comment' => $request->input('comment')
    ]);
    
    return response()->json(['success' => true]);
}
```

## Icônes Bootstrap

Le système utilise **Bootstrap Icons** avec la classe `bi bi-*` :
- `bi-folder` : Dossiers/Projets
- `bi-calendar-event` : Événements
- `bi-file-earmark-text` : Rapports
- `bi-people` : Contacts
- `bi-eye` : Voir
- `bi-pencil` : Modifier
- `bi-trash` : Supprimer
- `bi-plus` : Ajouter

## Bonnes pratiques

### 1. Groupement des champs
Utilisez des sections avec arrière-plan pour grouper les champs logiquement :
```php
<div class="bg-gray-50 p-6 rounded-lg mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">
        <i class="bi bi-calendar mr-2 text-coral"></i>
        Dates et horaires
    </h3>
    <!-- Champs de dates -->
</div>
```

### 2. Messages d'état
Affichez l'état de publication clairement :
```php
@if($item->is_published)
    <div class="bg-green-50 p-4 rounded-lg">
        <i class="bi bi-check-circle text-green-600"></i>
        Publié le {{ $item->published_at->format('d/m/Y') }}
    </div>
@endif
```

### 3. Validation et erreurs
Les erreurs sont automatiquement affichées dans les composants de champs.

### 4. Responsive design
Utilisez les classes responsive de Tailwind :
- `grid-cols-1 md:grid-cols-2` : 1 colonne sur mobile, 2 sur desktop
- `md:col-span-2` : Champ sur 2 colonnes sur desktop

## Migration vers le nouveau système

Pour migrer une vue existante :

1. **Remplacer le layout** : Utiliser `x-admin-layout` au lieu du HTML custom
2. **Restructurer les filtres** : Convertir en array pour le composant
3. **Ajouter les statistiques** : Définir les métriques importantes
4. **Harmoniser les actions** : Utiliser les boutons standardisés
5. **Moderniser les formulaires** : Utiliser `x-admin-form` et `x-form-field`

L'interface est maintenant **unifiée, moderne et maintenable** ! 🎉
