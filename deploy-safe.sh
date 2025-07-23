#!/bin/bash

# Script de déploiement sécurisé - IRIUCBC
# Usage: ./deploy-safe.sh [environnement]

set -e  # Arrêter en cas d'erreur

ENVIRONMENT=${1:-"local"}
BACKUP_DIR="backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo "🚀 Début du déploiement sécurisé - Environnement: $ENVIRONMENT"
echo "⏰ Timestamp: $TIMESTAMP"

# 1. Créer le dossier de sauvegarde
mkdir -p $BACKUP_DIR

# 2. Sauvegarde de la base de données
echo "💾 Sauvegarde de la base de données..."
if [ "$ENVIRONMENT" = "production" ]; then
    # Production - utiliser les vraies credentials
    mysqldump -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE > "$BACKUP_DIR/backup_$TIMESTAMP.sql"
else
    # Local/Development
    php artisan db:backup --path="$BACKUP_DIR/backup_$TIMESTAMP.sql" 2>/dev/null || \
    mysqldump -u root -p iriucbc > "$BACKUP_DIR/backup_$TIMESTAMP.sql"
fi

echo "✅ Sauvegarde créée: $BACKUP_DIR/backup_$TIMESTAMP.sql"

# 3. Mettre en mode maintenance (production seulement)
if [ "$ENVIRONMENT" = "production" ]; then
    echo "🔒 Activation du mode maintenance..."
    php artisan down --message="Mise à jour en cours..." --retry=60
fi

# 4. Mettre à jour les dépendances
echo "📦 Installation des dépendances..."
composer install --no-dev --optimize-autoloader

# 5. Vérifier l'intégrité avant migration
echo "🔍 Vérification de l'intégrité actuelle..."
php artisan db:verify-integrity

# 6. Exécuter les migrations
echo "🔄 Exécution des migrations..."
php artisan migrate --force

# 7. Vérifier l'intégrité après migration
echo "🔍 Vérification de l'intégrité après migration..."
php artisan db:verify-integrity --fix

# 8. Optimisations Laravel
echo "⚡ Optimisations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Vérification finale
echo "🧪 Tests de vérification..."
php artisan db:verify-integrity --detailed

# 10. Désactiver le mode maintenance
if [ "$ENVIRONMENT" = "production" ]; then
    echo "🔓 Désactivation du mode maintenance..."
    php artisan up
fi

echo "🎉 Déploiement terminé avec succès!"
echo "📊 Rapport de sauvegarde: $BACKUP_DIR/backup_$TIMESTAMP.sql"

# 11. Instructions post-déploiement
echo ""
echo "📋 ACTIONS POST-DÉPLOIEMENT RECOMMANDÉES:"
echo "1. Tester les fonctionnalités principales"
echo "2. Vérifier les relations many-to-many des publications"
echo "3. Tester la création/édition de contenu"
echo "4. Vérifier les médias et leurs relations"
echo ""
echo "🆘 En cas de problème:"
echo "   mysql -u [user] -p [database] < $BACKUP_DIR/backup_$TIMESTAMP.sql"
