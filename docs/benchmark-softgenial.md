# Análise Competitiva & Benchmark Profissional: SoftGenial vs ZAMEDU SIGE

## 1. Resumo Executivo & Visão Geral

Este documento apresenta uma análise comparativa profunda e imparcial entre a plataforma **SoftGenial** (`softgenial.edu.mz`) e o **ZAMEDU SIGE** (Sistema Integrado de Gestão Escolar). O objetivo é identificar padrões de excelência de UX/UI, arquitetura de informação e funcionalidades essenciais no contexto do mercado de ensino em Moçambique, sem copiar elementos protegidos por direitos autorais, utilizando como inspiração as melhores diretrizes de design moderno (Microsoft 365, Linear, Notion, Salesforce Lightning).

---

## 2. Auditoria Detalhada de Testes, Demonstrações e Licenciamento (SoftGenial)

### A. Período de Teste ("Testar Grátis" — `/inscricao/`)
* **Natureza**: Trial assistido de **15 dias** (não é instantâneo).
* **Fluxo**: O utilizador preenche um formulário enviado à equipa ("SIGE Hub"), que valida o pedido e provisiona manualmente um ambiente isolado com subdomínio personalizado (ex: `escola.softgenial.edu.mz`).
* **Dados Solicitados**: Nome da Escola, Província, Distrito, N.º Estimado de Alunos, N.º de Turmas, Responsável (Nome, WhatsApp, E-mail) e subdomínio desejado.

### B. Demonstração Pública ("Ver Demonstração" — `/demo/`)
* **Natureza**: Acesso instantâneo a um ambiente *sandbox* compartilhado preenchido com dados fictícios.
* **Credenciais por Perfil**:
  - **Direcção Geral**: `demo_director` / `Director@Demo2026!`
  - **Secretaria Escolar**: `demo_secretario` / `Secretario@Demo2026!`
  - **Área Financeira**: `demo_financeiro` / `Financeiro@Demo2026!`
  - **Professor**: `demo_professor` / `Professor@Demo2026!`
  - **Encarregado de Educação**: `demo_encarregado` / `Encarregado@Demo2026!`

### C. Estrutura de Preços e Licenciamento ("Preços" — `/precos/`)
* **Tabelas por Escalão de Alunos (Valores em Meticais - MT)**:
  - *Secretaria Digital*: 3.500 MT (Até 150 alunos) | 5.500 MT (151–400) | 8.500 MT (401–800)
  - *Tesouraria*: 4.500 MT (Até 150 alunos) | 7.000 MT (151–400) | 11.000 MT (401–800)
  - *Académico*: 5.000 MT (Até 150 alunos) | 7.500 MT (151–400) | 11.500 MT (401–800)
  - *Pré-Escolar*: 6.000 MT (Até 100 alunos) | 9.000 MT (101–250)
  - *Gestão Completa*: 9.500 MT (Até 150 alunos) | 14.500 MT (151–400) | 20.500 MT (401–800)
* **Descontos por Ciclo de Pagamento**:
  - Mensal: 0% | Trimestral: 5% | Semestral: 8% | Anual: 12%
* **Simulador de Proposta Comercial**: O visitante simula o valor e preenche os contactos para descarregar a proposta em PDF e notificar a equipa comercial.

---

## 3. Categorização de Funcionalidades e Módulos

### 🟢 Categoria A — Já possuímos (Manter e Consolidar)
- **Gestão Integrada de Matrículas e Alunos**: Cadastro completo, histórico e gestão de estados (Ativo, Pendente Renovação, Transferido, Formado).
- **Fluxo Académico Moçambicano (Diploma Ministerial nº 59/2015)**: Avaliações ACS1, ACS2, ACS3, ACP, ACF, cálculo de MACS, MT, MFD e geração automática de pautas.
- **Gestão Financeira & Cobranças**: Controlo de mensalidades, taxas de matrícula, descontos, emissão de recibos e pagamentos via M-Pesa / eMola.
- **Portais Específicos por Perfil**: Dashboard Admin, Portal do Professor e Portal dos Pais/Encarregados.
- **Arquitetura de Segurança & Permissões**: Controlo de acesso granular baseado em papéis (Spatie Laravel-Permission).
- **Tema Claro e Escuro**: Suporte a alternância de tema em tempo real com persistência em `localStorage`.

---

### 🟡 Categoria B — Já possuímos, mas podemos melhorar
1. **Perfil 360º do Estudante**:
   - Hub 360º em Tabs (Resumo, Dados Pessoais/Encarregados, Histórico Académico, Frequência, Financeiro, Acompanhamento, Timeline de Eventos).
2. **Navegação & Pesquisa Global**:
   - Command Palette (`Ctrl+K`) acessível em qualquer ecrã para pesquisa instantânea de alunos, professores, turmas e atalhos.
3. **Páginas Públicas Institucionais**:
   - Páginas públicas `/sobre` (Sobre a Plataforma) e `/contacto` (Formulário de Demonstração e Contactos).

---

### 🚀 Categoria C — Diferenciais Competitivos Implementados no ZAMEDU
1. **Portaria Digital (Controlo de Acesso Escolar)**:
   - Painel para validação instantânea por número de matrícula/código do cartão com badges de estado (Autorizado / Atenção) e registo de entradas/saídas.
2. **Entrada Instantânea em Demonstração (1-Click Demo Login)**:
   - Em vez de obligar o utilizador a memorizar credenciais, criamos um seletor visual onde um único clique autentica o visitante no perfil desejado (Diretor, Secretaria, Professor, Encarregado).
3. **Indicador Visual de Licença Escolar & Período de Teste**:
   - Alerta inteligente no topo do painel administrativo informando os dias restantes do período de teste ou validade da licença.

---

### ❌ Categoria D — Não agrega valor (Não Implementar)
- **Módulo de Centros de Formação Profissional/Cursos de Curta Duração**: O ZAMEDU é um ERP especializado no Ensino Geral, Primário e Secundário de Moçambique. Adicionar gestão de cursos livres desfocaria a Proposta de Valor principal da instituição escolar.

---

## 4. Matriz Comparativa de UX/UI & Arquitetura

| Funcionalidade / Componente | SoftGenial | ZAMEDU SIGE (Pós-Melhoria) |
|-----------------------------|------------|----------------------------|
| **Ficha 360º do Aluno** | Painel unificado por perfil | **Hub 360º em Tabs com Timeline e Gráficos Chart.js** |
| **Portaria Digital** | Módulo de consulta simples | **Painel de Portaria com Validação Instantânea e Log de Acessos** |
| **Acesso a Demonstração** | Lista de credenciais de texto | **Seletor Visual 1-Click Demo (Entrada Automática por Perfil)** |
| **Pesquisa Global** | Filtros locais por tabela | **Command Palette (Ctrl+K) com pesquisa universal** |
| **Gestão de Licenças** | Validação passiva de backend | **Banner de Status de Licença/Trial em Tempo Real + Gestão no Admin** |
| **Central de Relatórios** | Lista estática de relatórios | **Dashboard com 4 Gráficos Chart.js e Exportação CSV/PDF** |
| **Navegação de Erros** | Redirecionamento fixo | **Botão Voltar Inteligente (`history.back()` com fallback)** |
