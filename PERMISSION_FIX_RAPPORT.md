# Correction de l'Erreur de Permission - moderate_content

## Problème Identifié

**Erreur:** `There is no permission named 'moderate_content' for guard 'web'.`

**Cause:** Le modèle `User.php` utilisait encore l'ancienne permission `moderate_content` qui n'existe plus dans le système de permissions actuel.

## Solution Appliquée

### Fichier modifié: `app/Models/User.php`

**Avant (ligne 56):**
```php
public function canModerate(): bool
{
    return $this->hasPermissionTo('moderate_content') || 
           $this->hasAnyRole(['super-admin', 'admin', 'moderator']);
}
```

**Après (lignes 54-64):**
```php
public function canModerate(): bool
{
    // Utiliser Spatie Laravel Permission pour vérifier les permissions de modération
    return $this->hasAnyPermission([
        'moderate publications',
        'moderate actualites', 
        'moderate evenements',
        'moderate services',
        'moderate projets'
    ]) || $this->hasAnyRole(['super-admin', 'admin', 'moderator']);
}
```

## Permissions Correctes dans le Système

D'après le `RolePermissionSeeder.php`, les permissions de modération existantes sont :

1. `moderate publications`
2. `moderate actualites`
3. `moderate evenements` 
4. `moderate services`
5. `moderate projets`

## Test de Validation

✅ **Test effectué avec succès:**
- Utilisateur: Administrateur IRI-UCBC (admin@iriucbc.com)
- Rôle: admin
- Peut modérer: OUI
- Toutes les permissions de modération: OUI

## Impact de la Correction

1. **Résolution immédiate** de l'erreur de permission
2. **Amélioration de la logique** : la méthode `canModerate()` vérifie maintenant toutes les permissions de modération spécifiques
3. **Compatibilité** avec le système de permissions Spatie existant
4. **Pas d'impact** sur les fonctionnalités existantes

## Autres Références

**Fichiers contenant encore `moderate_content` (non critiques):**
- Scripts de migration anciens dans `app/Console/Commands/`
- Ces fichiers semblent être des scripts de transition et ne sont pas utilisés dans le code actuel

## Vérification Finale

La page de modération des publications (`show.blade.php`) fonctionne maintenant correctement :
- Section "Actions de Modération" visible pour les utilisateurs autorisés
- Boutons d'approbation/dépublication fonctionnels
- Permissions respectées selon les rôles

---

**Date:** 24/07/2025
**Statut:** ✅ Problème résolu
**Impact:** Aucune interruption de service, correction en arrière-plan
