# Manual de Implantação & Monitorização de Servidor — ZamEdu

Este manual guia o processo de implantação, infraestrutura de produção, SSL, backups e automação de monitorização e autocura dos serviços do **ZamEdu**.

---

## 🖥️ 1. Requisitos do Servidor de Produção

- **OS**: Ubuntu 22.04 LTS ou Debian 12
- **PHP**: PHP 8.3 (extensões: `cli`, `fpm`, `mysql`, `mbstring`, `xml`, `bcmath`, `curl`, `gd`, `zip`)
- **Web Server**: Nginx com suporte a HTTP/2 e mod_rewrite
- **Base de Dados**: MySQL 8.0 / MariaDB 10.11
- **Process Manager**: Supervisor (para filas e agendamentos)
- **Node.js**: Node 20 LTS + NPM

---

## 🛠️ 2. Scripts de Monitorização e Autocura (`deployment/`)

O repositório inclui scripts automatizados instaláveis via SSH no servidor:

### Scripts Incluídos:
- `deployment/setup-monitoring.sh`: Instalação inicial dos scripts e cronjobs no servidor.
- `deployment/monitor-services.sh`: Script executado a cada 5 minutos para validar a saúde de Nginx, MySQL e PHP-FPM. Caso algum caia, o script faz restart automático e gera log.
- `deployment/restart-all-services.sh`: Script para reinício controlado e ordenado dos serviços de aplicação às 3h da manhã.
- `deployment/deploy-monitoring-to-server.sh`: Script de automação remota via SSH para implantar as tarefas de monitorização no servidor do cliente.

### Comandos de Gestão Remota:

```bash
# Verificar cronjobs ativos no servidor remoto
ssh -i ~/.ssh/id_rsa user@servidor 'crontab -l'

# Monitorizar logs em tempo real
ssh -i ~/.ssh/id_rsa user@servidor 'tail -f /var/log/service-monitor.log'

# Forçar reinício dos serviços no servidor
ssh -i ~/.ssh/id_rsa user@servidor 'sudo /usr/local/bin/restart-all-services.sh'
```

---

## 🌐 3. Configuração do Web Server (Nginx)

Exemplo de bloco Nginx otimizado para o ZamEdu:

```nginx
server {
    listen 80;
    server_name gestao.escolax.co.mz;
    root /var/www/zamedu/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

---

## 🔒 4. SSL & Certificados HTTPS (Certbot)

```bash
sudo apt update && sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d gestao.escolax.co.mz
```

---

## ⏰ 5. Agendamento do Laravel (`schedule:run`)

Adicionar ao `crontab -e` do utilizador da aplicação:

```cron
* * * * * cd /var/www/zamedu && php artisan schedule:run >> /dev/null 2>&1
```
