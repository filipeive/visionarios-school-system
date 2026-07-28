#!/bin/bash

# Script de Configuração do Monitoramento Automático
# Execute uma vez para configurar o cron job

echo "=== Configurando Monitoramento Automático de Serviços ==="

# Criar diretório de logs se não existir
sudo mkdir -p /var/log
sudo touch /var/log/service-monitor.log
sudo touch /var/log/service-restart.log
sudo chmod 666 /var/log/service-monitor.log
sudo chmod 666 /var/log/service-restart.log

# Copiar scripts para /usr/local/bin
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

sudo cp "$SCRIPT_DIR/monitor-services.sh" /usr/local/bin/monitor-services.sh
sudo cp "$SCRIPT_DIR/restart-all-services.sh" /usr/local/bin/restart-all-services.sh

sudo chmod +x /usr/local/bin/monitor-services.sh
sudo chmod +x /usr/local/bin/restart-all-services.sh

# Adicionar ao crontab (verifica a cada 5 minutos)
CRON_JOB="*/5 * * * * /usr/local/bin/monitor-services.sh"

# Verificar se o cron job já existe
if ! crontab -l 2>/dev/null | grep -q "monitor-services.sh"; then
    (crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -
    echo "✓ Cron job adicionado: Monitoramento a cada 5 minutos"
else
    echo "✓ Cron job já existe"
fi

# Adicionar restart diário às 3h da manhã
DAILY_RESTART="0 3 * * * /usr/local/bin/restart-all-services.sh"

if ! crontab -l 2>/dev/null | grep -q "restart-all-services.sh"; then
    (crontab -l 2>/dev/null; echo "$DAILY_RESTART") | crontab -
    echo "✓ Cron job adicionado: Restart diário às 3h da manhã"
else
    echo "✓ Cron job de restart diário já existe"
fi

echo ""
echo "=== Configuração Concluída ==="
echo ""
echo "Scripts instalados em:"
echo "  - /usr/local/bin/monitor-services.sh (verifica serviços a cada 5 min)"
echo "  - /usr/local/bin/restart-all-services.sh (restart manual ou diário)"
echo ""
echo "Logs salvos em:"
echo "  - /var/log/service-monitor.log"
echo "  - /var/log/service-restart.log"
echo ""
echo "Para executar manualmente:"
echo "  sudo /usr/local/bin/monitor-services.sh"
echo "  sudo /usr/local/bin/restart-all-services.sh"
echo ""
echo "Para ver os logs:"
echo "  tail -f /var/log/service-monitor.log"
echo "  tail -f /var/log/service-restart.log"
