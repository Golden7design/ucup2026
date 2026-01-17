#!/usr/bin/env bash

# 🎯 Installation Automatique - Script Quick Setup

echo "================================================"
echo "🏆 U-Cup Tournament - Installation Rapide"
echo "================================================"
echo ""

# Couleurs
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Step 1: Installer les dépendances
echo -e "${BLUE}📦 Étape 1: Installation des dépendances${NC}"
echo "Installation de GSAP, Recharts et autres librairies..."
npm install

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Dépendances installées${NC}"
else
    echo "❌ Erreur lors de l'installation des dépendances"
    exit 1
fi

echo ""

# Step 2: Build
echo -e "${BLUE}🔨 Étape 2: Build des assets${NC}"
echo "Construction des fichiers CSS/JS optimisés..."
npm run build

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Build réussi${NC}"
else
    echo "❌ Erreur lors du build"
    exit 1
fi

echo ""

# Step 3: Cached config & routes
echo -e "${BLUE}⚡ Étape 3: Optimisation Laravel${NC}"
echo "Mise en cache de la configuration et des routes..."
php artisan config:cache
php artisan route:cache

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Configuration cachée${NC}"
else
    echo "❌ Erreur lors de la mise en cache"
    exit 1
fi

echo ""

# Step 4: Cleanup
echo -e "${BLUE}🧹 Étape 4: Nettoyage${NC}"
echo "Suppression des fichiers temporaires..."
php artisan cache:clear

echo ""
echo "================================================"
echo -e "${GREEN}✅ INSTALLATION COMPLÈTE!${NC}"
echo "================================================"
echo ""
echo "Prochaines étapes:"
echo "1. Mettez à jour HomeController (voir QUICK_START.md)"
echo "2. Exécutez: php artisan serve"
echo "3. Visitez: http://localhost:8000"
echo ""
echo "Documentation:"
echo "📖 QUICK_START.md - Démarrage rapide"
echo "📖 CODE_SNIPPETS.md - Code des contrôleurs"
echo "📖 IMPLEMENTATION_GUIDE.md - Guide complet"
echo "📖 DEPLOYMENT_CHECKLIST.md - Déploiement"
echo ""
