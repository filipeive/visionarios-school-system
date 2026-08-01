# Análise Competitiva & Benchmark Profissional: SoftGenial vs ZAMEDU SIGE

## 1. Resumo Executivo & Visão Geral

Este documento apresenta uma análise comparativa profunda e imparcial entre a plataforma **SoftGenial** (`softgenial.edu.mz`) e o **ZAMEDU SIGE** (Sistema Integrado de Gestão Escolar). O objetivo é identificar padrões de excelência de UX/UI, arquitetura de informação e funcionalidades essenciais no contexto do mercado de ensino em Moçambique, sem copiar elementos protegidos por direitos autorais, utilizando como inspiração as melhores diretrizes de design moderno (Microsoft 365, Linear, Notion, Salesforce Lightning).

---

## 2. Categorização de Funcionalidades e Módulos

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
   - *Atual*: View vertical longa dividida em cards.
   - *Melhoria*: Transformar em um **Hub 360º com Separadores (Tabs)** e **Timeline de Eventos do Aluno**, agrupando Dados Pessoais/Encarregados, Histórico Académico, Frequência, Situação Financeira, Registos Disciplinares/Acompanhamento e Atividade Recente.
2. **Empty States & Feedback Visual**:
   - *Atual*: Mensagens de texto simples quando não há registos.
   - *Melhoria*: Criar componentes ilustrados de *Empty State* com ícones temáticos, explicações claras e botões diretos de ação rápida.
3. **Navegação & Pesquisa Global**:
   - *Atual*: Pesquisa restrita por módulo.
   - *Melhoria*: Implementar um **Command Palette (Ctrl+K / Cmd+K)** acessível em qualquer ecrã para pesquisa instantânea de alunos, professores, turmas e atalhos rápidos de navegação.

---

### 🚀 Categoria C — Não possuímos e é um diferencial competitivo (A Implementar)
1. **Portaria Digital (Controlo de Acesso Escolar)**:
   - *Valor*: Permite que os encarregados da segurança/portaria confirmem o número do estudante, foto, turma e estado de matrícula em 1 segundo, registando a entrada/saída sem dar acesso a notas ou finanças.
   - *Impacto*: Elevado (segurança física da escola e tranquilidade dos encarregados).
   - *Complexidade*: Média.
2. **Simulador de Propostas / Planos na Landing Page**:
   - *Valor*: Permite que diretores escolares simulem o custo mensal com base no número de alunos e descarreguem/solicitem uma proposta em PDF personalizada.
   - *Impacto*: Elevado para conversão de novos clientes.
   - *Complexidade*: Baixa.

---

### ❌ Categoria D — Não agrega valor (Não Implementar)
- **Módulo de Centros de Formação Profissional/Cursos de Curta Duração**: O ZAMEDU é um ERP especializado no Ensino Geral, Primário e Secundário de Moçambique. Adicionar gestão de cursos livres desfocaria a Proposta de Valor principal da instituição escolar.

---

## 3. Matriz Comparativa de UX/UI & Arquitetura

| Funcionalidade / Componente | SoftGenial | ZAMEDU SIGE (Pós-Melhoria) |
|-----------------------------|------------|----------------------------|
| **Ficha 360º do Aluno** | Painel unificado por perfil | **Hub 360º em Tabs com Timeline e Gráfico de Desempenho** |
| **Portaria Digital** | Módulo de consulta simples | **Painel de Portaria com Validação Instantânea e Log de Acessos** |
| **Pesquisa Global** | Filtros locais por tabela | **Command Palette (Ctrl+K) com pesquisa universal** |
| **Central de Relatórios** | Lista estática de relatórios | **Dashboard com 4 Gráficos Chart.js e Exportação CSV/PDF** |
| **Notificações & Toasts** | Alertas padrão de página | **Sistema Global de Toasts + Modais de Confirmação Interativos** |
| **Navegação de Erros** | Redirecionamento fixo | **Botão Voltar Inteligente (`history.back()` com fallback)** |

---

## 4. Plano de Execução Priorizado

1. **Fase 1: Hub Perfil 360º do Estudante (`students/show.blade.php`)**
   - Estrutura baseada em separadores Bootstrap (Visão Geral, Dados Pessoais, Académico, Frequência, Financeiro, Acompanhamento, Timeline).
2. **Fase 2: Módulo de Portaria Digital (`/gatekeeper` / `portaria`)**
   - Rota e view simplificada para consulta rápida por número do aluno/pesquisa com indicador de permissão de entrada.
3. **Fase 3: Command Palette / Pesquisa Global (`Ctrl+K`)**
   - Modal de pesquisa rápida no layout principal (`app.blade.php`) com suporte a teclado.
4. **Fase 4: Landing Page & Simulador de Proposta (`welcome.blade.php`)**
   - Secção Hero modernizada, benefícios claros, FAQ interativo e simulador de planos para escolas.
