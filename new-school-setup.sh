#!/bin/bash

# ==============================================================================
# ZamEdu - Script de Instalação e Onboarding de Nova Escola / Tenant
# FDS Software
# ==============================================================================

set -e

echo "🚀 Iniciando configuração do ZamEdu para uma nova instituição de ensino..."
echo ""

# 1. Verificar .env
if [ ! -f .env ]; then
    echo "📋 Criando ficheiro .env a partir do modelo .env.example..."
    cp .env.example .env
    echo "⚠️ Certifique-se de configurar as credenciais da base de dados e APP_URL no .env"
fi

# 2. Gerar chave de aplicação
echo "🔑 Gerando chave de encriptação da aplicação..."
php artisan key:generate --force

# 3. Executar Migrações e Seeders Estruturais (Sem dados demo de teste)
echo "🗄️ Executando migrações e criando tabelas base (Permissions, Roles, Settings)..."
APP_ENV=production php artisan migrate --seed --force

# 4. Configurar Permissões das Pastas
echo "🔒 Configurando permissões nas pastas storage e bootstrap/cache..."
chmod -R 775 storage bootstrap/cache || true

# 5. Otimização de Produção
echo "⚡ Otimizando configurações e rotas do Laravel..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo ""
echo "=============================================================================="
echo "✅ Instalação base do ZamEdu concluída!"
echo ""
echo "📌 PASSOS DE VERIFICAÇÃO:"
echo " 1. Efetue login com o utilizador SuperAdmin configurado no Seeder."
echo " 2. Aceda a /admin/settings para personalizar o nome da escola, cores e contactos."
echo " 3. Aceda a /admin/license para validar o estado da licença do cliente."
echo "=============================================================================="
