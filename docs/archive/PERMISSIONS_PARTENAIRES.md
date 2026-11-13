# Permissions et Policies pour les Partenaires

## 🔐 Permissions créées dans le modèle User

### Méthodes ajoutées au modèle `User.php`

```php
/**
 * Permissions pour les partenaires
 */
public function canViewPartenaires(): bool
public function canCreatePartenaires(): bool  
public function canUpdatePartenaires(): bool
public function canDeletePartenaires(): bool
public function canModeratePartenaires(): bool
```

### Mise à jour de `canModerate()`
- Ajout de `'moderate_partenaires'` dans la liste des permissions de modération

## 🛡️ Policy créée

### `PartenairePolicy.php`
- **viewAny()** : Voir la liste des partenaires
- **view()** : Voir un partenaire spécifique  
- **create()** : Créer un nouveau partenaire
- **update()** : Modifier un partenaire existant
- **delete()** : Supprimer un partenaire
- **restore()** : Restaurer un partenaire supprimé
- **forceDelete()** : Suppression définitive (super-admin uniquement)
- **moderate()** : Modérer les partenaires (publier/dépublier)
- **manageLogo()** : Gérer les logos
- **changeVisibility()** : Changer la visibilité publique

## 📋 Permissions dans la base de données

### Permissions créées
1. `view_partenaires` - Voir les partenaires
2. `create_partenaires` - Créer des partenaires
3. `update_partenaires` - Modifier des partenaires  
4. `delete_partenaires` - Supprimer des partenaires
5. `moderate_partenaires` - Modérer les partenaires

### Attribution par rôle

| Rôle | view | create | update | delete | moderate |
|------|------|--------|--------|--------|----------|
| **super-admin** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **admin** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **moderator** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **editor** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **contributor** | ✅ | ❌ | ❌ | ❌ | ❌ |

## 🔧 Contrôleur mis à jour

### `PartenaireController.php`
- Toutes les méthodes utilisent maintenant `$this->authorize()`
- Protection par policies au lieu de commentaires
- Vérifications automatiques des permissions

## 🎨 Vues mises à jour

### Directives Blade
- Utilisation de `@can('create', App\Models\Partenaire::class)` pour la création
- Utilisation de `@can('view', $partenaire)` pour les actions sur modèles spécifiques
- Protection des boutons d'action selon les permissions via policies

### Format des permissions
- **Ancien format** : `view partenaires`, `create partenaires`
- **Nouveau format** : `view_partenaires`, `create_partenaires` 
- **Harmonisation** avec le format général `action_model` utilisé dans le projet

## 📦 Configuration

### `AuthServiceProvider.php`
- Ajout de `Partenaire::class => PartenairePolicy::class` dans `$policies`
- Import des classes nécessaires

## 🚀 Installation

### 1. Exécuter le seeder des permissions
```bash
php artisan db:seed --class=PartenairePermissionsSeeder
```

### 2. Ou exécuter le script standalone
```bash
php create_partenaire_permissions.php
```

### 3. Cache des permissions
```bash
php artisan permission:cache-reset
```

### 4. Test des permissions
```bash
php test_partenaire_permissions.php
```

## ✅ Fonctionnalités de sécurité

### Niveaux de protection
1. **Contrôleur** : Vérification via policies
2. **Vues** : Masquage conditionnel des éléments
3. **Routes** : Protection par middleware auth
4. **Base de données** : Permissions granulaires

### Cas d'usage
- **Super-admin** : Accès complet, peut tout faire
- **Admin** : Gestion complète des partenaires
- **Moderator** : Peut créer, modifier et modérer mais pas supprimer
- **Editor** : Peut voir, créer et modifier seulement
- **Contributor** : Lecture seule

### Sécurité renforcée
- Suppression réservée aux admins et super-admins
- Modération (visibilité) réservée aux modérateurs et plus
- Gestion des logos liée aux permissions de modification
- Contrôles multiples (policy + méthodes User + vues)

Le système de permissions est maintenant complet et sécurisé pour la gestion des partenaires ! 🎯

## 🔧 Corrections apportées

### Format des permissions harmonisé
- **Ancien format** : `view partenaires` (avec espaces)
- **Nouveau format** : `view_partenaires` (avec underscores)
- **Conformité** avec le pattern `action_model` utilisé dans le projet

### Policy mise à jour
- Utilisation directe de `hasPermissionTo()` avec le garde `web`
- Cohérence avec les autres policies du projet
- Fallback sur les rôles pour la compatibilité

### Tests fonctionnels
- Script de test créé : `test_partenaire_permissions.php`
- Validation des permissions pour tous les rôles
- Vérification des policies et méthodes du modèle User

### Cache des permissions
- Commande `php artisan permission:cache-reset` exécutée
- Prise en compte immédiate des nouvelles permissions

### Résultats des tests
✅ Permissions créées et assignées avec succès  
✅ Policies fonctionnelles pour tous les rôles  
✅ Méthodes du modèle User opérationnelles  
✅ Interface admin prête à l'utilisation
