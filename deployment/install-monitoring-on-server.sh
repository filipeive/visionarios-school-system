#!/bin/bash

# Script para criar sistema de monitoramento diretamente na instância remota
# Executa uma vez e fica configurado permanentemente

SERVER_USER="ubuntu"
SERVER_IP="146.235.224.99"
SSH_KEY="$HOME/.ssh/oracle-2025"

echo "=========================================="
echo "  Configurando Monitoramento Permanente"
echo "=========================================="
echo ""

ssh -i "$SSH_KEY" "$SERVER_USER@$SERVER_IP" << 'ENDSSH'

echo "📝 Criando script de monitoramento..."

# Criar script de monitoramento
sudo tee /usr/local/bin/monitor-services.sh > /dev/null << 'EOF'
#!/bin/bash

LOG_FILE="/var/log/service-monitor.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

log_message() {
    echo "[$DATE] $1" >> "$LOG_FILE"
}

check_and_restart() {
    SERVICE=$1
    
    if ! systemctl is-active --quiet "$SERVICE"; then
        log_message "ALERTA: $SERVICE não está rodando. Reiniciando..."
        systemctl restart "$SERVICE"
        
        if systemctl is-active --quiet "$SERVICE"; then
            log_message "SUCESSO: $SERVICE reiniciado com sucesso"
        else
            log_message "ERRO: Falha ao reiniciar $SERVICE"
        fi
    fi
}

log_message "=== Verificando serviços ==="
check_and_restart "php8.3-fpm"
check_and_restart "nginx"
check_and_restart "mysql"
log_message "=== Verificação concluída ==="
EOF

echo "🔄 Criando script de restart manual..."

# Criar script de restart manual
sudo tee /usr/local/bin/restart-all-services.sh > /dev/null << 'EOF'
#!/bin/bash

LOG_FILE="/var/log/service-restart.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

echo "[$DATE] === Reiniciando todos os serviços ===" >> "$LOG_FILE"

echo "[$DATE] Reiniciando PHP 8.3 FPM..." >> "$LOG_FILE"
systemctl restart php8.3-fpm
sleep 2

echo "[$DATE] Reiniciando Nginx..." >> "$LOG_FILE"
systemctl restart nginx
sleep 2

echo "[$DATE] Reiniciando MySQL..." >> "$LOG_FILE"
systemctl restart mysql
sleep 2

echo "[$DATE] === Status dos serviços ===" >> "$LOG_FILE"
systemctl status php8.3-fpm --no-pager | grep "Active:" >> "$LOG_FILE"
systemctl status nginx --no-pager | grep "Active:" >> "$LOG_FILE"
systemctl status mysql --no-pager | grep "Active:" >> "$LOG_FILE"

echo "[$DATE] === Restart concluído ===" >> "$LOG_FILE"
echo "✅ Todos os serviços foram reiniciados!"
EOF

echo "🔧 Configurando permissões..."

# Dar permissões de execução
sudo chmod +x /usr/local/bin/monitor-services.sh
sudo chmod +x /usr/local/bin/restart-all-services.sh

# Criar arquivos de log
sudo touch /var/log/service-monitor.log
sudo touch /var/log/service-restart.log
sudo chmod 666 /var/log/service-monitor.log
sudo chmod 666 /var/log/service-restart.log

echo "⏰ Configurando cron jobs..."

# Remover cron jobs antigos se existirem
crontab -l 2>/dev/null | grep -v "monitor-services.sh" | grep -v "restart-all-services.sh" | crontab - 2>/dev/null || true

# Adicionar cron jobs
(crontab -l 2>/dev/null; echo "*/5 * * * * /usr/local/bin/monitor-services.sh") | crontab -
(crontab -l 2>/dev/null; echo "0 3 * * * /usr/local/bin/restart-all-services.sh") | crontab -

echo ""
echo "=========================================="
echo "  ✅ Configuração Concluída!"
echo "=========================================="
echo ""
echo "📋 Scripts criados:"
echo "   • /usr/local/bin/monitor-services.sh"
echo "   • /usr/local/bin/restart-all-services.sh"
echo ""
echo "⏰ Tarefas agendadas:"
crontab -l | grep -E "(monitor-services|restart-all-services)"
echo ""
echo "📝 Logs:"
echo "   • /var/log/service-monitor.log"
echo "   • /var/log/service-restart.log"
echo ""
echo "🔄 Comandos úteis:"
echo "   • Restart manual: sudo /usr/local/bin/restart-all-services.sh"
echo "   • Ver logs: tail -f /var/log/service-monitor.log"
echo ""

ENDSSH

echo ""
echo "✅ Sistema de monitoramento configurado permanentemente na instância!"
echo ""
echo "O que foi configurado:"
echo "  ✓ Monitoramento automático a cada 5 minutos"
echo "  ✓ Restart preventivo diário às 3h da manhã"
echo "  ✓ Logs em /var/log/service-*.log"
echo ""
echo "Para verificar se está funcionando:"
echo "  ssh -i ~/.ssh/oracle-2025 ubuntu@146.235.224.99 'crontab -l'"
echo ""
