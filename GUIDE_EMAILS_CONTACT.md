# Guide du Système d'Emails de Contact IRI-UCBC

## 📧 Présentation

Le système d'emails de contact a été amélioré pour inclure un système de copie automatique configurable. Voici comment il fonctionne :

## 🔄 Processus d'envoi d'emails

### Lorsqu'un utilisateur envoie un message via le formulaire de contact :

1. **📝 Enregistrement** : Le message est sauvegardé dans la base de données
2. **📧 Email principal** : Envoyé à l'adresse principale configurée (par défaut : info@iri.ledinitiatives.com)
3. **📋 Copies automatiques** : Envoyées aux adresses configurées (par défaut : iri@ucbc.org, s.vutegha@gmail.com)
4. **✅ Confirmation** : L'expéditeur reçoit une copie de confirmation
5. **📬 Newsletter** : L'expéditeur est automatiquement ajouté à la liste de diffusion

## ⚙️ Configuration des adresses email

### Interface d'administration

Accédez à `/admin/email-settings` pour :
- ✏️ Modifier les adresses de réception
- ➕ Ajouter de nouvelles adresses de copie
- ❌ Supprimer des adresses existantes
- 🧪 Tester les configurations
- 🔧 Activer/désactiver des configurations

### Types de configuration

1. **contact_main_email** (Obligatoire)
   - Adresse principale qui reçoit tous les messages
   - Par défaut : info@iri.ledinitiatives.com

2. **contact_copy_emails** (Obligatoire)
   - Adresses qui reçoivent une copie de chaque message
   - Par défaut : iri@ucbc.org, s.vutegha@gmail.com

3. **newsletter_copy_emails** (Optionnel)
   - Notification lors des nouvelles inscriptions newsletter
   - Par défaut : iri@ucbc.org

## 🧪 Tests et validation

### Test via interface admin
1. Allez sur `/admin/email-settings`
2. Cliquez sur "Tester la configuration" 
3. Saisissez votre email de test
4. Vérifiez la réception

### Test via commande Artisan
```bash
php artisan contact:test-email --email=votre@email.com
```

### Test via script PowerShell
```powershell
.\test-contact-email.ps1 -Email "votre@email.com"
```

## 📋 Modèles d'emails

### Email administrateur
- Template : `emails.contact-message-admin`
- Contient : Détails complets du message, actions recommandées
- Permet : Réponse directe via "Répondre"

### Email de confirmation 
- Template : `emails.contact-message-copy`
- Contient : Copie du message, informations de suivi
- But : Rassurer l'expéditeur

## 🔧 Configuration technique

### Variables d'environnement (.env)
```env
# Configuration SMTP (déjà configuré)
MAIL_MAILER=smtp
MAIL_HOST=iri.ledinitiatives.com
MAIL_PORT=465
MAIL_USERNAME=info@iri.ledinitiatives.com
MAIL_PASSWORD=@Congo1960
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@iri.ledinitiatives.com
MAIL_FROM_NAME="IRI-UCBC"
```

### Structure de la base de données

Table `email_settings` :
- `key` : Identifiant unique de la configuration
- `label` : Libellé pour l'interface admin
- `emails` : Tableau JSON des adresses email
- `description` : Description pour l'admin
- `active` : Statut d'activation
- `required` : Obligatoire ou non

### Modèles Laravel

- `EmailSetting` : Gestion des configurations
- `ContactMessageWithCopy` : Mailable avec système de copie
- `Contact` : Modèle des messages de contact

## 📊 Surveillance et logs

### Logs Laravel
- Localisation : `storage/logs/laravel.log`
- Rechercher : "Message de contact", "EmailSetting", "ContactMessageWithCopy"

### Statistiques disponibles
- Nombre d'emails envoyés par test
- Erreurs d'envoi
- Adresses de réception configurées

## 🚨 Résolution des problèmes

### Email non reçu
1. ✅ Vérifier la configuration SMTP
2. ✅ Tester avec la commande artisan
3. ✅ Vérifier les logs Laravel
4. ✅ Contrôler le dossier spam

### Erreur de configuration
1. ✅ Vérifier les adresses email configurées
2. ✅ Tester la connectivité SMTP
3. ✅ Nettoyer les caches : `php artisan config:clear`

### Performance
- Les emails sont envoyés de façon synchrone
- Pour de gros volumes, considérer l'utilisation de queues

## 🔄 Maintenance

### Sauvegardes recommandées
- Table `email_settings`
- Configuration `.env`
- Templates d'emails

### Mises à jour
- Tester après modification des configurations
- Valider les templates après mise à jour Laravel

## 📞 Support

Pour toute question sur ce système :
- 📧 Contact technique : s.vutegha@gmail.com
- 📋 Documentation : Ce fichier
- 🔗 Interface admin : `/admin/email-settings`

---

**Version** : 1.0  
**Date** : Janvier 2025  
**Auteur** : GitHub Copilot pour IRI-UCBC
