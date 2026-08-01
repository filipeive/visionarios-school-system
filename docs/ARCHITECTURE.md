# Arquitetura de Software — ZamEdu (SIGE SaaS)

Este documento detalha a arquitetura técnica, modelo de permissões, Theme Engine e convenções do **ZamEdu**.

---

## 🏗️ 1. Arquitetura Geral do Sistema

O ZamEdu é construído em **Laravel 10 / 12** segundo os padrões MVC desacoplados, enriquecido com **Spatie Laravel-Permission**, **ThemeEngine Helper**, **Chart.js UMD**, **Bootstrap 5**, **TailwindCSS** e **Alpine.js**.

```
                           +------------------------+
                           |  Nginx / Reverse Proxy |
                           +-----------+------------+
                                       |
                                       v
                           +------------------------+
                           |  LicenseCheckMiddleware|
                           +-----------+------------+
                                       |
                                       v
                           +------------------------+
                           |  ThemeEngine Helper    |
                           |   (ThemeHelper.php)    |
                           +-----------+------------+
                                       |
                                       v
                           +------------------------+
                           |  Spatie Role & Perms   |
                           +-----------+------------+
                                       |
            +--------------------------+--------------------------+
            |                          |                          |
            v                          v                          v
  +------------------+       +------------------+       +------------------+
  | Admin Controller |       | Teacher Portal   |       | Parent Portal    |
  +------------------+       +------------------+       +------------------+
            |                          |                          |
            +--------------------------+--------------------------+
                                       |
                                       v
                           +------------------------+
                           |   Eloquent ORM Model   |
                           +-----------+------------+
                                       |
                                       v
                           +------------------------+
                           |  MySQL 8.0 / MariaDB   |
                           +------------------------+
```

---

## 🎨 2. Arquitetura do Theme Engine & White-Labeling

O **Theme Engine** desacopla a identidade visual da aplicação do código-fonte através do ajudante `App\Helpers\ThemeHelper`.

### Mapeamento de Configurações para Tokens CSS:
- `setting('primary_color')` ➜ `--primary` + variância dinâmica `color-mix()`
- `setting('secondary_color')` ➜ `--secondary` + variância dinâmica `color-mix()`
- `setting('accent_color')` ➜ `--accent`
- `setting('border_radius')` ➜ `--border-radius`

Toda alteração efetuada em `/admin/settings` invalida o cache do `setting()` e recalcula a paleta CSS em tempo real para todos os utilizadores do tenant.

---

## 🔑 3. Sistema de Licenciamento SaaS (`LicenseCheckMiddleware`)

O middleware `LicenseCheckMiddleware` interpela todas as requisições autenticadas para validar:

1. **Estado Ativo (`active`)**: Permite acesso irrestrito.
2. **Período de Carência (`grace_period`)**: Exibe aviso ao administrador no topo da interface.
3. **Suspenso / Expirado (`suspended`)**: Redireciona imediatamente para a rota `/license/suspended`.

---

## 👥 4. Perfis e Papéis do Sistema (RBAC)

O sistema suporta 5 perfis nativos via Spatie Permission:

1. **`super_admin`**: Gestão global de licenças, tenants e auditoria.
2. **`admin`**: Direção e administração escolar completa.
3. **`secretary`**: Matrículas, estudantes, propinas e comunicações.
4. **`teacher`**: Lançamento de notas, assiduidade, pedidos de licença e pautas.
5. **`parent`**: Portal de encarregados de educação (pagamentos, notas, avisos).
