#!/bin/bash

# Script de test de configuration email pour IRI-UCBC
# Utilisation: ./test-email-config.sh [email@example.com]

EMAIL=${1:-"test@example.com"}

echo "==================================="
echo "🔧 TEST DE CONFIGURATION EMAIL"
echo "==================================="
echo ""

echo "📧 Configuration actuelle:"
echo "   Serveur: iri.ledinitiatives.com"
echo "   Port: 465 (SSL)"
echo "   Utilisateur: info@iri.ledinitiatives.com"
echo "   Email de test: $EMAIL"
echo ""

echo "🔍 Vérification de la connectivité..."
timeout 10 telnet iri.ledinitiatives.com 465 2>/dev/null && echo "✅ Port 465 accessible" || echo "❌ Port 465 inaccessible"
echo ""

echo "🧹 Nettoyage du cache Laravel..."
php artisan config:clear
php artisan route:clear
echo "✅ Cache nettoyé"
echo ""

echo "📤 Test d'envoi d'email..."
php artisan email:test "$EMAIL"

echo ""
echo "==================================="
echo "✅ Test terminé"
echo ""
echo "💡 Pour tester via l'interface web:"
echo "   1. Connectez-vous à l'admin"
echo "   2. Allez sur /admin/email-test"
echo "   3. Utilisez l'interface de test"
echo "==================================="
