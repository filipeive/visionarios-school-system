# Guia de Onboarding e Configuração de Clientes — ZamEdu

Este documento guia a equipa da **FDS Software** no processo de implementação e personalização do **ZamEdu** para um novo cliente (escola ou instituto).

---

## 📋 Pré-requisitos do Servidor

- PHP >= 8.2 (com extensões `pdo_mysql`, `mbstring`, `openssl`, `gd` ou `imagick`)
- MySQL 8.0+ ou MariaDB 10.5+
- Composer 2.x
- Nginx / Apache com mod_rewrite habilitado

---

## 🛠️ Passo a Passo de Onboarding

### 1. Preparação do Ambiente e BD
Crie a base de dados no MySQL para o novo tenant (ex: `zamedu_escola_x`):

```sql
CREATE DATABASE zamedu_escola_x CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Configurar o Ficheiro `.env`
Copie o modelo `.env.example` e atualize as seguintes variáveis essenciais:

```ini
APP_NAME="ZamEdu"
APP_ENV=production
APP_URL=https://gestao.escolax.co.mz

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zamedu_escola_x
DB_USERNAME=usuario_db
DB_PASSWORD=senha_segura
```

### 3. Executar o Script de Instalação Base
Execute no terminal da raiz do projeto:

```bash
./new-school-setup.sh
```

O script efetuará:
- Geração da `APP_KEY`
- Execução das migrações e seeders obrigatórios (`PermissionSeeder`, `SuperAdminSeeder`, `SettingSeeder`)
- Ajuste de permissões de pastas
- Limpeza/Otimização de caches

---

## 🎨 Personalização de Marca e Parâmetros (Painel Admin)

Aceda ao sistema com as credenciais SuperAdmin e navegue até `/admin/settings` para definir:

| Chave de Configuração | Descrição | Exemplo |
| :--- | :--- | :--- |
| `school_name` | Nome completo da instituição | Escola Secundária de Maputo |
| `school_short_name` | Sigla ou nome curto | ESM |
| `primary_color` | Cor principal da interface | `#1e40af` |
| `secondary_color` | Cor de destaque | `#06b6d4` |
| `institution_type` | Tipo de escola | `escola_privada`, `escola_publica` |
| `grading_scale` | Escala de avaliação | `0_20`, `0_10`, `percentage` |

---

## 🔑 Ativação da Licença

Navegue até ao menu **Licença do Sistema** (ou `/admin/license`) para inserir a chave de licença fornecida pela FDS Software. Isto validará o prazo de vigência e o plano contratado (Standard, Premium, Enterprise).

---

## ⚙️ Operações e Comandos Administrativos (Artisan)

O ZamEdu conta com comandos Artisan específicos para rotinas automatizadas e diagnósticos de suporte técnico.

### 1. Verificação da Saúde do Sistema (`health:check`)
Para fazer um diagnóstico completo da base de dados, tabelas, cache, permissões, storage e índices de performance, execute:
```bash
php artisan health:check
```
* **Opções**:
  * `--detailed`: Exibe relatórios detalhados para cada verificação.
  * `--fix`: Tenta resolver problemas comuns automaticamente (como migrar tabelas em falta ou semear permissões obrigatórias).

### 2. Geração de Propinas Mensais (`payments:generate-monthly-fees`)
Gera em lote as propinas/mensalidades para todos os alunos com matrículas ativas.
```bash
php artisan payments:generate-monthly-fees
```
* **Opções**:
  * `--month=X`: Define o mês de referência (1-12). O padrão é o mês atual.
  * `--school-year=YYYY`: Especifica o ano letivo.
  * `--calendar-year=YYYY`: Especifica o ano civil.
  * `--no-notify`: Executa a geração sem enviar e-mails/alertas de notificação aos encarregados de educação.

---

## 🔄 Transição de Ano Lectivo

Para fechar o período letivo corrente e abrir o próximo:
1. Vá ao menu **Ano Lectivo & Transição** (`/admin/academic-years`).
2. Siga as instruções do painel para realizar a transição das turmas e alunos.
3. Os alunos que transitarem com sucesso devem ser promovidos através da funcionalidade **Passagem de Classe** (`/admin/promotion`).
4. Alunos retidos ou inativos podem ser movidos para a secção de **Alunos Arquivados** (`/admin/students-archive`).

---

## 💾 Backups, Logs e Auditoria

### Backups do Sistema
Aceda ao menu **Backup do Sistema** (`/admin/backup`) para gerar cópias de segurança do banco de dados e arquivos de storage com um clique.

### Auditoria e Rastreabilidade
* **Registo de Auditoria** (`/admin/audit`): Rastreia todas as ações críticas realizadas por utilizadores (criação, edição e remoção de registos, logins, etc.).
* **Logs do Sistema** (`/admin/logs`): Exibe os logs de erro de execução para suporte técnico avançado.

