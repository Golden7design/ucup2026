# 🚀 Guide de Déploiement AWS Free Tier pour U-Cup Tournament

Ce guide vous explique comment déployer votre application U-Cup Tournament sur AWS en utilisant le niveau gratuit (Free Tier) valable pendant 12 mois.

## 📋 Prérequis

1. **Compte AWS** avec le Free Tier activé
2. **AWS CLI** installé sur votre machine
3. **Terraform** installé sur votre machine
4. **Git** installé
5. **Clé SSH** générée (`ssh-keygen -t rsa -b 4096`)

## 🛠️ Étapes de Déploiement

### 1. Configurer AWS CLI

```bash
# Installer AWS CLI
curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
unzip awscliv2.zip
sudo ./aws/install

# Configurer AWS CLI avec vos identifiants
aws configure
```

### 2. Configurer Terraform

```bash
# Installer Terraform
wget -O- https://apt.releases.hashicorp.com/gpg | gpg --dearmor | sudo tee /usr/share/keyrings/hashicorp-archive-keyring.gpg

echo "deb [signed-by=/usr/share/keyrings/hashicorp-archive-keyring.gpg] https://apt.releases.hashicorp.com $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/hashicorp.list

sudo apt update && sudo apt install terraform
```

### 3. Initialiser Terraform

```bash
cd terraform/aws
terraform init
```

### 4. Créer un fichier terraform.tfvars

Créez un fichier `terraform.tfvars` dans le dossier `terraform/aws` avec le contenu suivant :

```hcl
aws_region = "us-east-1"
db_password = "votre_mot_de_passe_secure_ici"
ssh_public_key_path = "~/.ssh/id_rsa.pub"
```

### 5. Planifier et Appliquer l'Infrastructure

```bash
# Vérifier la configuration
terraform plan

# Appliquer la configuration (créer les ressources AWS)
terraform apply
```

### 6. Configurer le Serveur

Une fois l'instance EC2 créée, connectez-vous via SSH :

```bash
ssh -i ~/.ssh/id_rsa ubuntu@<IP_PUBLIQUE_DE_VOTRE_INSTANCE>
```

Puis exécutez le script de configuration :

```bash
# Copier votre projet sur le serveur
scp -r -i ~/.ssh/id_rsa /chemin/vers/u-cup-tournament ubuntu@<IP_PUBLIQUE>:/home/ubuntu/

# Se connecter au serveur
ssh -i ~/.ssh/id_rsa ubuntu@<IP_PUBLIQUE>

# Exécuter le script de configuration
chmod +x /home/ubuntu/u-cup-tournament/deploy/scripts/setup-server.sh
sudo /home/ubuntu/u-cup-tournament/deploy/scripts/setup-server.sh
```

### 7. Configurer la Base de Données

Mettez à jour votre fichier `.env` avec les informations de la base de données RDS :

```env
DB_CONNECTION=mysql
DB_HOST=<RDS_ENDPOINT>
DB_PORT=3306
DB_DATABASE=u_cup_tournament
DB_USERNAME=ucupadmin
DB_PASSWORD=votre_mot_de_passe_secure_ici
```

### 8. Configurer le Stockage S3

Mettez à jour votre fichier `.env` avec les informations S3 :

```env
AWS_ACCESS_KEY_ID=<VOTRE_ACCESS_KEY>
AWS_SECRET_ACCESS_KEY=<VOTRE_SECRET_KEY>
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=<NOM_DU_BUCKET>
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### 9. Exécuter les Migrations

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 10. Configurer le Domaine (Optionnel)

Si vous avez un domaine, configurez-le pour pointer vers l'IP publique de votre instance EC2.

## 🔧 Gestion Post-Déploiement

### Mettre à Jour l'Application

```bash
# Sur votre machine locale
git pull origin main

# Copier les changements sur le serveur
rsync -avz -e "ssh -i ~/.ssh/id_rsa" --exclude='.git' --exclude='node_modules' /chemin/vers/u-cup-tournament/ ubuntu@<IP_PUBLIQUE>:/home/ubuntu/u-cup-tournament/

# Se connecter au serveur et mettre à jour
ssh -i ~/.ssh/id_rsa ubuntu@<IP_PUBLIQUE>
cd /home/ubuntu/u-cup-tournament
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
```

### Sauvegarder la Base de Données

```bash
# Sauvegarder
mysqldump -h <RDS_ENDPOINT> -u ucupadmin -p u_cup_tournament > backup.sql

# Restaurer
mysql -h <RDS_ENDPOINT> -u ucupadmin -p u_cup_tournament < backup.sql
```

### Surveiller les Logs

```bash
# Logs Nginx
sudo tail -f /var/log/nginx/error.log

# Logs PHP
sudo tail -f /var/log/php8.1-fpm.log

# Logs Laravel
tail -f /home/ubuntu/u-cup-tournament/storage/logs/laravel.log
```

## 💰 Coûts et Optimisation

### Services Gratuits Utilisés

- **EC2 t2.micro** : 750 heures/mois gratuit pendant 12 mois
- **RDS db.t2.micro** : 750 heures/mois gratuit pendant 12 mois
- **S3** : 5 Go de stockage gratuit pendant 12 mois
- **CloudFront** : 1 To de transfert de données gratuit pendant 12 mois

### Conseils pour Rester dans le Free Tier

1. **Arrêtez l'instance EC2** lorsque vous ne l'utilisez pas
2. **Utilisez des snapshots** pour sauvegarder votre base de données
3. **Surveillez votre utilisation** dans le tableau de bord AWS Billing
4. **Configurez des alertes** pour éviter les surprises

## 🚨 Dépannage

### Problèmes Courants

1. **Erreur de connexion à la base de données**
   - Vérifiez les groupes de sécurité RDS
   - Vérifiez que le nom d'utilisateur et le mot de passe sont corrects

2. **Erreur 502 Bad Gateway**
   - Vérifiez que PHP-FPM est en cours d'exécution
   - Vérifiez les permissions des fichiers

3. **Problèmes de performance**
   - Activez OPcache dans PHP
   - Configurez le cache Laravel

### Commandes Utiles

```bash
# Redémarrer Nginx
sudo systemctl restart nginx

# Redémarrer PHP-FPM
sudo systemctl restart php8.1-fpm

# Vérifier l'état des services
sudo systemctl status nginx
sudo systemctl status php8.1-fpm

# Recharger la configuration Nginx
sudo nginx -t && sudo systemctl reload nginx
```

## 🎉 Félicitations !

Votre application U-Cup Tournament est maintenant déployée sur AWS et accessible à l'adresse :
```
http://<IP_PUBLIQUE_DE_VOTRE_INSTANCE>
```

N'oubliez pas de configurer un nom de domaine et un certificat SSL pour une expérience professionnelle !

## 📚 Ressources Supplémentaires

- [Documentation AWS Free Tier](https://aws.amazon.com/free/)
- [Documentation Terraform](https://www.terraform.io/docs/)
- [Documentation Laravel Deployment](https://laravel.com/docs/deployment)

Si vous avez des questions ou rencontrez des problèmes, n'hésitez pas à demander de l'aide !