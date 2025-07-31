# Système d'Authentification IRI-UCBC

## Vue d'ensemble

Un système complet d'authentification a été créé pour l'application IRI-UCBC avec les fonctionnalités suivantes :

### ✅ Fonctionnalités Implémentées

1. **Authentification des utilisateurs**
   - Page de connexion (`/login`)
   - Page d'inscription (`/register`)
   - Déconnexion (`/logout`)
   - Vérification d'email

2. **Gestion des rôles et permissions (Spatie Permission)**
   - Rôles : admin, moderator, editor, user
   - Permissions granulaires pour chaque module
   - Attribution automatique de permissions par rôle

3. **Interface d'administration**
   - Dashboard administrateur (`/admin`)
   - Gestion complète des utilisateurs (`/admin/users`)
   - Interface de gestion des permissions par utilisateur

4. **Sécurité**
   - Middleware de permissions personnalisé
   - Protection des routes administratives
   - Contrôle d'accès basé sur les rôles

## Structure des Fichiers

### Contrôleurs
- `app/Http/Controllers/Auth/AuthController.php` - Authentification
- `app/Http/Controllers/Admin/UserController.php` - Gestion des utilisateurs
- `app/Http/Controllers/Admin/DashboardController.php` - Dashboard admin

### Vues
- `resources/views/auth/login.blade.php` - Page de connexion
- `resources/views/auth/register.blade.php` - Page d'inscription
- `resources/views/admin/users/index.blade.php` - Liste des utilisateurs
- `resources/views/admin/users/create.blade.php` - Créer un utilisateur
- `resources/views/admin/users/manage_permissions.blade.php` - Gérer les permissions

### Middleware
- `app/Http/Middleware/PermissionMiddleware.php` - Vérification des permissions
- `app/Http/Middleware/CanModerate.php` - Modération (existant)

### Modèles et Base de Données
- Modèle `User` utilise le trait `HasRoles` de Spatie
- Tables Spatie Permission créées (roles, permissions, model_has_roles, etc.)
- Seeders pour rôles/permissions et utilisateurs par défaut

## Rôles et Permissions

### Rôles Disponibles

#### 🔴 Administrateur (`admin`)
- Accès complet à toutes les fonctionnalités
- Gestion des utilisateurs et permissions
- Accès système complet

#### 🟡 Modérateur (`moderator`)  
- Modération des contenus
- Gestion partielle des utilisateurs
- Accès aux outils de modération

#### 🔵 Éditeur (`editor`)
- Création et édition de contenus
- Gestion des publications
- Accès limité à l'administration

#### ⚪ Utilisateur (`user`)
- Accès de base en lecture
- Fonctionnalités utilisateur standard

### Permissions par Catégorie

#### Système
- `access admin` - Accès interface admin
- `manage system` - Gestion système
- `view logs` - Consultation des logs

#### Utilisateurs
- `manage users` - Gestion complète
- `view users` - Consultation
- `create users` - Création
- `edit users` - Modification
- `delete users` - Suppression

#### Services
- `manage services` - Gestion complète
- `moderate services` - Modération
- `toggle service menu` - Gestion menu
- `view/create/edit/delete services` - CRUD

#### Contenus (Actualités, Publications, Événements, Projets)
- `manage [type]` - Gestion complète
- `moderate [type]` - Modération
- `view/create/edit/delete [type]` - CRUD

#### Médias et Autres
- `manage media` - Gestion médias
- `manage newsletter` - Newsletter
- `view contacts` - Contacts
- `manage jobs` - Emplois

## Routes d'Authentification

### Routes Publiques (Invités)
```php
GET  /login     - Page de connexion
POST /login     - Traitement connexion
GET  /register  - Page d'inscription  
POST /register  - Traitement inscription
```

### Routes Protégées (Authentifiés)
```php
POST /logout           - Déconnexion
GET  /admin            - Dashboard admin
GET  /admin/users      - Liste utilisateurs
GET  /admin/users/create - Créer utilisateur
POST /admin/users      - Sauvegarder utilisateur
GET  /admin/users/{user} - Voir utilisateur
GET  /admin/users/{user}/edit - Modifier utilisateur
PUT  /admin/users/{user} - Mettre à jour utilisateur
DELETE /admin/users/{user} - Supprimer utilisateur
GET  /admin/users/{user}/permissions - Gérer permissions
PUT  /admin/users/{user}/permissions - Sauvegarder permissions
```

## Configuration et Installation

### 1. Base de Données
```bash
# Migrations déjà exécutées
php artisan migrate

# Créer rôles et permissions de base
php artisan db:seed --class=RolePermissionSeeder

# Créer utilisateurs par défaut
php artisan db:seed --class=AdminUserSeeder
```

### 2. Utilisateurs par Défaut

#### Administrateur
- **Email**: `admin@iriucbc.com`
- **Mot de passe**: `admin123`
- **Rôle**: Administrateur

#### Modérateur de Test
- **Email**: `moderator@iriucbc.com` 
- **Mot de passe**: `moderator123`
- **Rôle**: Modérateur

#### Éditeur de Test
- **Email**: `editor@iriucbc.com`
- **Mot de passe**: `editor123`
- **Rôle**: Éditeur

#### Utilisateur de Test
- **Email**: `user@iriucbc.com`
- **Mot de passe**: `user123`
- **Rôle**: Utilisateur

### 3. Middleware Configuré
Le middleware de permissions est enregistré dans `bootstrap/app.php` :
```php
'permission' => \App\Http\Middleware\PermissionMiddleware::class
```

## Utilisation

### Connexion
1. Aller sur `/login`
2. Utiliser un des comptes par défaut
3. Redirection automatique selon les permissions

### Gestion des Utilisateurs
1. Se connecter en tant qu'admin
2. Aller sur `/admin/users`
3. Créer, modifier, supprimer des utilisateurs
4. Gérer leurs rôles et permissions

### Protection des Routes
```php
// Protéger une route avec une permission
Route::middleware(['auth', 'permission:manage users'])->group(function () {
    // Routes protégées
});

// Vérifier une permission dans un contrôleur
if (auth()->user()->can('manage users')) {
    // Action autorisée
}
```

## Sécurité

### Fonctionnalités de Sécurité
- ✅ Hashage des mots de passe avec bcrypt
- ✅ Protection CSRF sur tous les formulaires
- ✅ Validation des emails et mots de passe
- ✅ Middleware d'authentification
- ✅ Contrôle d'accès granulaire
- ✅ Sessions sécurisées

### Bonnes Pratiques Implémentées
- Séparation des rôles et permissions
- Interface utilisateur intuitive
- Messages d'erreur informatifs
- Redirection appropriée après actions
- Protection contre l'auto-suppression

## Prochaines Étapes

### Améliorations Suggérées
1. **Récupération de mot de passe**
   - Route de reset password
   - Envoi d'emails de réinitialisation

2. **Vérification d'email**
   - Email de vérification à l'inscription
   - Middleware de vérification

3. **Audit et Logs**
   - Log des actions utilisateurs
   - Historique des modifications

4. **Interface Utilisateur**
   - Profil utilisateur
   - Changement de mot de passe
   - Préférences utilisateur

### Maintenance
- Nettoyer régulièrement les sessions expirées
- Surveiller les tentatives de connexion
- Mettre à jour les permissions selon les besoins
- Sauvegarder les données utilisateurs

## Support Technique

Le système est prêt à être utilisé. Les composants principaux sont :
- ✅ Base de données configurée
- ✅ Rôles et permissions créés
- ✅ Utilisateurs par défaut créés
- ✅ Interface d'administration fonctionnelle
- ✅ Sécurité implémentée

Pour toute question technique, se référer à la documentation Laravel et Spatie Permission.
