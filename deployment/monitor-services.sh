#!/bin/bash

# Script de Monitoramento e Restart Automático de Serviços
# Para evitar erros de gateway e quedas de serviço

LOG_FILE="/var/log/service-monitor.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

# Função para logar mensagens
log_message() {
    echo "[$DATE] $1" >> "$LOG_FILE"
}

# Função para verificar e reiniciar serviço
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
    else
        log_message "OK: $SERVICE está rodando normalmente"
    fi
}

# Verificar e reiniciar serviços
log_message "=== Iniciando verificação de serviços ==="

check_and_restart "php8.3-fpm"
check_and_restart "nginx"
check_and_restart "mysql"

log_message "=== Verificação concluída ==="
