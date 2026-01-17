#!/bin/bash

# Script de déploiement pour Vercel
# Ce script prépare l'application Laravel + Vite pour le déploiement sur Vercel

echo "🚀 Déploiement sur Vercel en cours..."

# Étape 1: Nettoyage des caches
echo "🧹 Nettoyage des caches..."
npm run build:vercel

# Étape 2: Configuration de l'environnement
echo "🔧 Configuration de l'environnement..."
cp .env.vercel .env
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Étape 3: Build des assets
echo "📦 Build des assets..."
npx vite build

# Étape 4: Préparation des fichiers statiques
echo "📁 Préparation des fichiers statiques..."
mkdir -p public/build
cp -r resources/js/public/* public/build/ 2>/dev/null || true

# Étape 5: Configuration spécifique Vercel
echo "⚙️  Configuration spécifique Vercel..."
# Créer un fichier vercel-config.js si nécessaire
if [ ! -f "vercel-config.js" ]; then
    cat > vercel-config.js << 'EOF'
// Configuration supplémentaire pour Vercel
module.exports = {
    // Configuration spécifique pour les Serverless Functions
    functions: {
        api: {
            memory: 3008,
            maxDuration: 30
        }
    }
}
EOF
fi

echo "✅ Déploiement préparé avec succès !"
echo "💡 Vous pouvez maintenant déployer avec : vercel --prod"

# Afficher les instructions de déploiement
cat << 'EOF'

📋 Instructions pour le déploiement final :
1. Assurez-vous d'avoir installé Vercel CLI : npm install -g vercel
2. Connectez-vous à Vercel : vercel login
3. Déployez l'application : vercel --prod
4. Configurez les variables d'environnement dans le tableau de bord Vercel

🔑 Variables d'environnement nécessaires :
- APP_KEY (générée par : php artisan key:generate)
- DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- CORS_ALLOWED_ORIGINS (doit inclure votre domaine Vercel)

EOF