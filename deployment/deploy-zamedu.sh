#!/bin/bash

# Script de Deploy Automático do ZAMEDU SIGE
# Servidor de Produção Oracle Cloud

set -e

SERVER_USER="ubuntu"
SERVER_IP="146.235.224.99"
SSH_KEY="$HOME/.ssh/oracle-2025"
REMOTE_APP_DIR="/var/www/zamedu"
REPO_URL="git@github.com:filipeive/visionarios-school-system.git"

echo "==================================================================="
echo "   🚀 Deploy Automático — ZAMEDU SIGE (Produção)"
echo "==================================================================="
echo ""
echo "Servidor: $SERVER_USER@$SERVER_IP"
echo "Diretório Remoto: $REMOTE_APP_DIR"
echo "Repositório: $REPO_URL"
echo ""

# Verificar chave SSH
if [ ! -f "$SSH_KEY" ]; then
    echo "❌ ERRO: Chave SSH não encontrada em $SSH_KEY"
    exit 1
fi

echo "🔐 Testando conexão SSH com o servidor..."
ssh -i "$SSH_KEY" -o ConnectTimeout=10 "$SERVER_USER@$SERVER_IP" "echo '✅ Conexão SSH bem-sucedida!'"

echo "📦 Executando rotina de deploy no servidor remoto..."
ssh -i "$SSH_KEY" "$SERVER_USER@$SERVER_IP" << 'ENDSSH'
set -e

# Criar base de dados no MySQL
mysql -ufdsms -pfdsadmin -e "CREATE DATABASE IF NOT EXISTS zamedu_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" || true

# Criar diretório se não existir
sudo mkdir -p /var/www/zamedu
sudo chown -R ubuntu:www-data /var/www/zamedu

if [ ! -d "/var/www/zamedu/.git" ]; then
    echo "📥 Clonando repositório..."
    git clone git@github.com:filipeive/visionarios-school-system.git /var/www/zamedu
else
    echo "🔄 Atualizando repositório..."
    cd /var/www/zamedu
    git fetch origin
    git reset --hard origin/main
fi

cd /var/www/zamedu

# Configurar permissões
sudo chown -R ubuntu:www-data /var/www/zamedu
sudo chmod -R 775 /var/www/zamedu/storage /var/www/zamedu/bootstrap/cache

# Instalar dependências Composer
echo "🎼 Instalando dependências Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

# Criar ou atualizar ficheiro .env para produção
echo "⚙️ Configurando .env para produção..."
cat > .env << 'ENVEOF'
APP_NAME="ZamEdu - SIGE"
APP_ENV=production
APP_KEY=base64:3WAeZowb2U8CefDgtdfbzeD0TIvaUrphK94tbxIblhc=
APP_DEBUG=false
APP_URL=http://146.235.224.99

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zamedu_db
DB_USERNAME=fdsms
DB_PASSWORD=fdsadmin

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
ENVEOF

php artisan key:generate --force

# Executar migrations e seeders de dados iniciais
echo "🗄️ Executando migrações e dados iniciais da base de dados..."
php artisan migrate --force
php artisan db:seed --force

# Otimizar caches Laravel
echo "⚡ Otimizando caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Backup da config do nginx se existir
if [ -f "/etc/nginx/sites-available/zamedu" ]; then
    sudo cp /etc/nginx/sites-available/zamedu /etc/nginx/sites-available/zamedu.bak.$(date +%F_%H%M%S)
fi

# Configurar Nginx para o ZamEdu
echo "🌐 Configurando Nginx para zamedu..."
sudo bash -c 'cat > /etc/nginx/sites-available/zamedu << "NGINXCONF"
server {
    listen 80;
    server_name 146.235.224.99;

    root /var/www/zamedu/public;
    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINXCONF'

# Ativar o site no Nginx se não estiver ativado
sudo ln -sf /etc/nginx/sites-available/zamedu /etc/nginx/sites-enabled/zamedu
sudo nginx -t
sudo systemctl reload nginx

echo "✅ Deploy do ZAMEDU em produção concluído com sucesso!"
ENDSSH

echo ""
echo "==================================================================="
echo "   🎉 DEPLOY CONCLUÍDO COM SUCESSO!"
echo "==================================================================="
echo "Aceda a: http://146.235.224.99"
echo ""
