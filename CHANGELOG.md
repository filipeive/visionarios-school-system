# Changelog — ZamEdu

Todas as alterações significativas ao ZamEdu são registadas neste ficheiro.

O formato segue [Keep a Changelog](https://keepachangelog.com/pt-PT/1.0.0/) e esta projeto adere ao [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Próxima Versão] — 2026-08-05

### Adicionado
- Módulo de gestão de despesas com categorias financeiras (`FinancialCategory`, `Expense`)
- Suporte a turnos escolares (`morning`, `afternoon`, `night`) nas turmas
- Configuração parametrizável de cálculo de médias (pesos MACS/ACP, mínimo de trimestres)
- Widget de calendário integrado nos dashboards (admin, secretaria, pedagogia, professor)
- Card KPI "Despesas do Mês" no dashboard administrativo
- Menu sidebar atualizado com link para Despesas
- Cards de aniversariante com turma e botão "Ver Perfil" em todas as vistas
- Role `security` para a Portaria com permissão `view_gatekeeper_logs`
- Permissões `manage_expenses` e `view_audit_logs`

### Corrigido
- Cálculo de média final (`calculateMFD`) retornava a média dos trimestres disponíveis em vez de `null` quando nem todos os trimestres tinham notas
- Exposição pública da rota `payment-check` removida (risco de segurança)
- Erro `RouteNotFoundException` para `public.payment-check` no `welcome.blade.php`
- Erros de variável indefinida e método inexistente no fluxo de presenças
- Coluna `status` ambígua em consultas SQL de presenças
- Erro ao ler propriedade `attendance_status` em array

### Alterado
- Dashboard admin: 5 cards KPI alinhados numa linha no desktop (`col-6 col-xl`)
- Configurações de avaliação movidas para aba "Estrutura Académica" com novos campos
- Pesos de cálculo de médias agora são lidos das configurações do sistema (não mais hardcoded)

---

## [0.1.0] — 2026-01-04

### Adicionado
- Lançamento inicial do ZamEdu
- Gestão de alunos, matrículas, turmas, disciplinas
- Sistema de avaliações com ACS1/ACS2/ACS3, ACP, ACF
- Gestão de presenças com Portaria (GateKeeper)
- Gestão financeira com propinas e cobranças
- Portais para professores e encarregados de educação
- Sistema de licenciamento SaaS
