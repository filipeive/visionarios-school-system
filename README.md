# ZamEdu — Sistema de Gestão Escolar Multitenant & SaaS Ready

**ZamEdu** é um ERP e sistema completo de Gestão Escolar comercial desenvolvido pela **FDS Software**, concebido para servir escolas primárias e secundárias através de uma arquitetura limpa, moderna, parametrizável e de alto desempenho.

---

## 🌟 Principais Funcionalidades

- **Design System Parametrizável:** Personalização de marca (nome da escola, logótipo, cores primárias/secundárias, escala de notas) sem alterações de código.
- **Gestão Académica Integrada:** Matrículas, turmas, disciplinas, pautas de avaliação contínua (ACS, ACP, ACF), médias e boletins escolares.
- **Gestão Financeira & Cobranças:** Emissão de propinas/mensalidades, cálculo automático de multas por atraso, integrações de pagamento (M-Pesa, eMola, Multicaixa) e relatórios financeiros.
- **Portais Dedicados:** Portais otimizados para Professores (pautas, assiduidade, licenças) e Encarregados de Educação (propinas, desempenho, comunicados).
- **Sistema de Licenciamento SaaS:** Middleware de controlo de vigência de licença (`active`, `grace_period`, `suspended`) com período de carência configurável.
- **PWA & Acessibilidade:** Instalação nativa standalone em mobile/desktop e conformidade WCAG 2.1 AA.

---

## 🛠️ Tecnologias Utilizadas

- **Backend:** Laravel 10 / 12 com PHP 8.3.6
- **Frontend:** Blade, TailwindCSS, Alpine.js, Bootstrap 5, Chart.js 4.4.1 UMD
- **Base de Dados:** MySQL 8.0 / MariaDB (SQLite para ambiente de testes)
- **Segurança & Permissões:** Laravel Breeze, Spatie Laravel-Permission (RBAC)

---

## 📚 Índice da Documentação Técnica do Projeto

Toda a documentação oficial da plataforma está organizada e disponível na pasta `docs/`:

1. 📘 [ONBOARDING.md](ONBOARDING.md) — Passo a passo para implementação e configuração de novas escolas/tenants.
2. 🎨 [docs/DESIGN_SYSTEM.md](docs/DESIGN_SYSTEM.md) — Paleta de cores, tokens CSS, componentes UI canónicos, botões e Dark Mode.
3. 🏗️ [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) — Arquitetura de software, middlewares de licença, modelo RBAC e permissões.
4. 📋 [docs/MODULES_AND_FEATURES.md](docs/MODULES_AND_FEATURES.md) — Descrição detalhada dos 12 módulos funcionais do sistema.
5. 📊 [docs/DATABASE_SCHEMA.md](docs/DATABASE_SCHEMA.md) — Esquema relacional, ERD, dicionário de dados e modelos Eloquent.
6. 🖥️ [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md) — Manual de implantação em servidor, Nginx, SSL e scripts de monitorização/autocura.
7. 💳 [docs/API_AND_INTEGRATIONS.md](docs/API_AND_INTEGRATIONS.md) — Endpoints REST, webhooks e gateways de pagamento (M-Pesa, eMola, Multicaixa).
8. 📱 [docs/PWA_AND_ACCESSIBILITY.md](docs/PWA_AND_ACCESSIBILITY.md) — Suporte a PWA, Service Workers e conformidade WCAG 2.1 AA.
9. 🧪 [docs/QA_CHECKLIST.md](docs/QA_CHECKLIST.md) — Suíte de testes automatizados PHPUnit e checklist de qualidade de interface.
10. 🔄 [docs/UPGRADE_LARAVEL_12.md](docs/UPGRADE_LARAVEL_12.md) — Guia de atualização para a versão mais recente da infraestrutura Laravel.

---

## 🚀 Instalação Rápida para Nova Escola

Para instalar e configurar uma nova instância para um cliente:

```bash
# 1. Clonar o repositório
git clone https://github.com/fdssoftware/zamedu.git
cd zamedu

# 2. Executar o script automatizado de onboarding
./new-school-setup.sh
```

---

## 🧪 Testes Automatizados

A suíte de testes executa em SQLite em memória para verificação rápida e isolada:

```bash
./vendor/bin/phpunit
```

---

## 📄 Licença & Propriedade

Desenvolvido por **FDS Software**. Todos os direitos reservados.
