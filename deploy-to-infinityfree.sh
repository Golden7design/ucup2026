#!/bin/bash
# Script de déploiement pour InfinityFree
# U-Cup Tournament - Elmish Moukouanga

echo "🚀 Préparation du déploiement pour InfinityFree"

# 1. Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

# 2. Installer les dépendances en mode production
echo "📦 Installation des dépendances..."
composer install --no-dev --optimize-autoloader

# 3. Générer la clé d'application si nécessaire
if grep -q "APP_KEY=" .env && [ "$(grep "APP_KEY=" .env | cut -d "=" -f 2)" = "" ]; then
    echo "🔑 Génération de la clé d'application..."
    php artisan key:generate
fi

# 4. Optimiser Laravel
echo "⚡ Optimisation de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Créer le lien de stockage
echo "🔗 Création du lien de stockage..."
php artisan storage:link

# 6. Préparer les permissions
echo "🔐 Configuration des permissions..."
chmod -R 755 bootstrap/cache
chmod -R 755 storage
chmod -R 755 public

# 7. Nettoyer les fichiers inutiles
echo "🧹 Nettoyage des fichiers inutiles..."
rm -rf node_modules
rm -rf .git
rm -f .env
rm -f deploy-to-infinityfree.sh
rm -f .htaccess

# 8. Créer un fichier .htaccess optimisé pour InfinityFree
cat > .htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

<IfModule mod_php.c>
    php_value memory_limit 256M
    php_value upload_max_filesize 64M
    php_value post_max_size 64M
    php_value max_execution_time 300
</IfModule>
EOF

# 9. Créer un fichier de configuration pour le déploiement
cat > deploy_instructions.txt << 'EOF'
📋 INSTRUCTIONS DE DÉPLOIEMENT POUR INFINITYFREE
================================================

1. Connectez-vous à votre compte InfinityFree
2. Allez dans le gestionnaire de fichiers
3. Supprimez tous les fichiers existants
4. Téléchargez tous les fichiers de ce dossier
5. Importez votre fichier .env.infinityfree en le renommant en .env
6. Mettez à jour les informations de base de données dans .env
7. Exécutez les migrations via le terminal en ligne:
   php artisan migrate --force
8. Votre site devrait maintenant être accessible à:
   https://votre-sous-domaine.epizy.com

⚠️ NOTES IMPORTANTES:
- Ne téléchargez pas le dossier .git
- Assurez-vous que storage/ et bootstrap/cache/ sont accessibles en écriture
- Pour les uploads, utilisez le stockage local (configuré dans .env)
- Les queues et jobs seront exécutés de manière synchrone
EOF

echo "✅ Préparation terminée!"
echo ""
echo "📂 Fichiers prêts pour le déploiement:"
echo "   - Tous les fichiers Laravel optimisés"
echo "   - .htaccess configuré pour InfinityFree"
echo "   - deploy_instructions.txt avec les instructions"
echo ""
echo "💡 Prochaine étape:"
echo "   1. Téléchargez ces fichiers sur InfinityFree via FTP"
echo "   2. Renommez .env.infinityfree en .env"
echo "   3. Mettez à jour les informations de base de données"
echo "   4. Exécutez les migrations via le terminal InfinityFree"
