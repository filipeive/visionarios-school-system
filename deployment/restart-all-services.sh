#!/bin/bash

# Script de Restart Completo de Todos os Serviços
# Use quando precisar forçar o restart de todos os serviços

LOG_FILE="/var/log/service-restart.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

echo "[$DATE] === Reiniciando todos os serviços ===" >> "$LOG_FILE"

# Reiniciar PHP-FPM
echo "[$DATE] Reiniciando PHP 8.3 FPM..." >> "$LOG_FILE"
systemctl restart php8.3-fpm
sleep 2

# Reiniciar Nginx
echo "[$DATE] Reiniciando Nginx..." >> "$LOG_FILE"
systemctl restart nginx
sleep 2

# Reiniciar MySQL
echo "[$DATE] Reiniciando MySQL..." >> "$LOG_FILE"
systemctl restart mysql
sleep 2

# Verificar status
echo "[$DATE] === Status dos serviços ===" >> "$LOG_FILE"
systemctl status php8.3-fpm --no-pager | grep "Active:" >> "$LOG_FILE"
systemctl status nginx --no-pager | grep "Active:" >> "$LOG_FILE"
systemctl status mysql --no-pager | grep "Active:" >> "$LOG_FILE"

echo "[$DATE] === Restart concluído ===" >> "$LOG_FILE"
echo "Todos os serviços foram reiniciados!"
