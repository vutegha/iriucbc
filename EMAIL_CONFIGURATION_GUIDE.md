# Configuration et Test des Emails - IRI-UCBC

## Configuration Appliquée

### Paramètres Email
- **Serveur SMTP** : `iri.ledinitiatives.com`
- **Port** : `465` (SSL)
- **Encryption** : `ssl`
- **Username** : `info@iri.ledinitiatives.com`
- **Password** : `@Congo1960`
- **From Address** : `info@iri.ledinitiatives.com`
- **From Name** : `IRI-UCBC`

### Configuration Serveur
- **IMAP Port** : `993` (pour la réception)
- **POP3 Port** : `995` (pour la réception)
- **SMTP Port** : `465` (pour l'envoi - SSL)

## Fichiers Modifiés

### 1. Fichier `.env`
```env
MAIL_MAILER=smtp
MAIL_HOST=iri.ledinitiatives.com
MAIL_PORT=465
MAIL_USERNAME=info@iri.ledinitiatives.com
MAIL_PASSWORD=@Congo1960
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@iri.ledinitiatives.com"
MAIL_FROM_NAME="IRI-UCBC"
```

### 2. Fichier `config/mail.php`
Ajout du paramètre `encryption` dans la configuration SMTP.

## Outils de Test Créés

### 1. Commande Artisan
```bash
# Test via ligne de commande
php artisan email:test votre-email@exemple.com

# Aide de la commande
php artisan email:test --help
```

### 2. Interface Web d'Administration
- **URL** : `/admin/email-test`
- **Fonctionnalités** :
  - Affichage de la configuration actuelle
  - Test de connexion SMTP
  - Envoi d'emails de test
  - Guide de dépannage

### 3. Routes Ajoutées
```php
// Test d'email (admin seulement)
Route::middleware(['can:viewAny,App\Models\User'])->prefix('email-test')->name('email-test.')->group(function () {
    Route::get('/', [EmailTestController::class, 'index'])->name('index');
    Route::post('/send', [EmailTestController::class, 'send'])->name('send');
    Route::post('/connection', [EmailTestController::class, 'testConnection'])->name('connection');
});
```

## Tests Recommandés

### 1. Test de Configuration
```bash
# Vérifier la configuration
php artisan config:clear
php artisan config:cache
```

### 2. Test de Connexion
```bash
# Test avec la commande Artisan
php artisan email:test admin@votre-domaine.com
```

### 3. Test via Interface Web
1. Connectez-vous à l'administration
2. Allez sur `/admin/email-test`
3. Cliquez sur "Tester la Connexion SMTP"
4. Envoyez un email de test

## Dépannage

### Problèmes Courants

#### 1. Erreur de Connexion
- Vérifiez que le serveur `iri.ledinitiatives.com` est accessible
- Confirmez que le port `465` est ouvert sur votre réseau
- Testez avec telnet : `telnet iri.ledinitiatives.com 465`

#### 2. Erreur d'Authentification
- Vérifiez le nom d'utilisateur : `info@iri.ledinitiatives.com`
- Vérifiez le mot de passe : `@Congo1960`
- Assurez-vous que l'authentification SMTP est activée

#### 3. Erreur SSL/TLS
- Port 465 utilise SSL (pas STARTTLS)
- Vérifiez que votre serveur supporte SSL sur le port 465
- Alternative : essayez le port 587 avec TLS si disponible

#### 4. Timeout de Connexion
- Augmentez le timeout dans `config/mail.php`
- Vérifiez la connectivité réseau
- Testez depuis différents réseaux

### Commandes de Diagnostic

```bash
# Vérifier la configuration PHP
php -m | grep openssl

# Tester la connectivité
telnet iri.ledinitiatives.com 465

# Vérifier les logs Laravel
tail -f storage/logs/laravel.log

# Debug complet
php artisan email:test --verbose votre-email@exemple.com
```

## Logs et Monitoring

### Logs à surveiller
- `storage/logs/laravel.log` : Erreurs Laravel
- Logs serveur mail : Vérifiez les connexions sur le serveur
- Logs réseau : Vérifiez la connectivité

### Métriques importantes
- Temps de réponse SMTP
- Taux de succès des envois
- Erreurs d'authentification
- Bounces et rejets

## Sécurité

### Recommandations
1. **Certificats SSL** : Assurez-vous que les certificats sont valides
2. **Mots de passe** : Changez régulièrement le mot de passe
3. **Authentification** : Utilisez l'authentification SMTP obligatoire
4. **Monitoring** : Surveillez les tentatives de connexion suspectes

### Variables d'environnement sensibles
```env
# Ne jamais commiter ces valeurs
MAIL_USERNAME=info@iri.ledinitiatives.com
MAIL_PASSWORD=@Congo1960
```

## Contact et Support

En cas de problème persistant :
1. Vérifiez la configuration serveur mail avec votre hébergeur
2. Testez depuis un client mail externe (Outlook, Thunderbird)
3. Contactez l'équipe technique pour assistance

---
**Date de configuration** : {{ date('d/m/Y H:i:s') }}
**Version Laravel** : {{ app()->version() }}
**Environnement** : {{ config('app.env') }}
