#!/bin/bash

# Script de Deploy Automático do Sistema de Monitoramento
# Configura tudo automaticamente na instância remota

SERVER_USER="ubuntu"
SERVER_IP="146.235.224.99"
SSH_KEY="$HOME/.ssh/oracle-2025"
REMOTE_DIR="/home/ubuntu/monitoring"

echo "==================================================================="
echo "   Deploy Automático do Sistema de Monitoramento de Serviços"
echo "==================================================================="
echo ""
echo "Servidor: $SERVER_USER@$SERVER_IP"
echo "Diretório remoto: $REMOTE_DIR"
echo ""

# Verificar se a chave SSH existe
if [ ! -f "$SSH_KEY" ]; then
    echo "❌ ERRO: Chave SSH não encontrada em $SSH_KEY"
    exit 1
fi

echo "📁 Criando diretório remoto..."
ssh -i "$SSH_KEY" "$SERVER_USER@$SERVER_IP" "mkdir -p $REMOTE_DIR"

echo "📤 Enviando scripts para o servidor..."
scp -i "$SSH_KEY" deployment/monitor-services.sh "$SERVER_USER@$SERVER_IP:$REMOTE_DIR/"
scp -i "$SSH_KEY" deployment/restart-all-services.sh "$SERVER_USER@$SERVER_IP:$REMOTE_DIR/"
scp -i "$SSH_KEY" deployment/setup-monitoring.sh "$SERVER_USER@$SERVER_IP:$REMOTE_DIR/"

echo "🔧 Configurando permissões..."
ssh -i "$SSH_KEY" "$SERVER_USER@$SERVER_IP" "chmod +x $REMOTE_DIR/*.sh"

echo "⚙️  Instalando e configurando monitoramento automático..."
ssh -i "$SSH_KEY" "$SERVER_USER@$SERVER_IP" << 'ENDSSH'
cd /home/ubuntu/monitoring

# Criar logs
sudo mkdir -p /var/log
sudo touch /var/log/service-monitor.log
sudo touch /var/log/service-restart.log
sudo chmod 666 /var/log/service-monitor.log
sudo chmod 666 /var/log/service-restart.log

# Copiar scripts para /usr/local/bin
sudo cp monitor-services.sh /usr/local/bin/monitor-services.sh
sudo cp restart-all-services.sh /usr/local/bin/restart-all-services.sh
sudo chmod +x /usr/local/bin/monitor-services.sh
sudo chmod +x /usr/local/bin/restart-all-services.sh

# Remover cron jobs antigos se existirem
crontab -l 2>/dev/null | grep -v "monitor-services.sh" | grep -v "restart-all-services.sh" | crontab - 2>/dev/null || true

# Adicionar novos cron jobs
(crontab -l 2>/dev/null; echo "*/5 * * * * /usr/local/bin/monitor-services.sh") | crontab -
(crontab -l 2>/dev/null; echo "0 3 * * * /usr/local/bin/restart-all-services.sh") | crontab -

echo "✅ Monitoramento configurado com sucesso!"
echo ""
echo "Cron jobs ativos:"
crontab -l | grep -E "(monitor-services|restart-all-services)"
ENDSSH

echo ""
echo "==================================================================="
echo "   ✅ Deploy Concluído com Sucesso!"
echo "==================================================================="
echo ""
echo "📋 Configurações instaladas:"
echo "   • Monitor de serviços a cada 5 minutos"
echo "   • Restart automático diário às 3h da manhã"
echo ""
echo "📁 Scripts instalados em:"
echo "   • /usr/local/bin/monitor-services.sh"
echo "   • /usr/local/bin/restart-all-services.sh"
echo ""
echo "📝 Logs disponíveis em:"
echo "   • /var/log/service-monitor.log"
echo "   • /var/log/service-restart.log"
echo ""
echo "🔍 Para verificar os cron jobs no servidor:"
echo "   ssh -i $SSH_KEY $SERVER_USER@$SERVER_IP 'crontab -l'"
echo ""
echo "📊 Para ver os logs em tempo real:"
echo "   ssh -i $SSH_KEY $SERVER_USER@$SERVER_IP 'tail -f /var/log/service-monitor.log'"
echo ""
echo "🔄 Para forçar restart manual agora:"
echo "   ssh -i $SSH_KEY $SERVER_USER@$SERVER_IP 'sudo /usr/local/bin/restart-all-services.sh'"
echo ""
