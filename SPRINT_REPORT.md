# Relatório da Sprint — ZamEdu

## Objectivo
Implementar suporte a turnos escolares, corrigir cálculo de média final de notas, adicionar módulo de gestão de despesas, reforçar segurança (remover exposição pública de dados de pagamento), adicionar widget de calendário nos dashboards, e alinhar interface.

## Tarefas Realizadas
1. **Shift Support**: Adicionado campo `shift` às turmas com migração, modelo, controlador e views atualizadas.
2. **Grade Calculation Fix**: Corrigido `calculateMFD()` para retornar `null` quando menos de 3 trimestres têm notas. Adicionados pesos configuráveis e mínimo de trimestres.
3. **Expense Management**: Criado módulo completo com modelos, migrações, controlador, políticas, seeders e views.
4. **Payment Security**: Removida rota pública `payment-check`, adicionado escopo de dados por role no `PaymentController`.
5. **Dashboard Calendar**: Widget de calendário integrado em todos os dashboards.
6. **Sidebar Updates**: Menu de Despesas adicionado.
7. **Birthday Views**: Cards de aniversariante atualizados com turma e botão "Ver Perfil".
8. **Dashboard KPI Alignment**: 5 cards KPI alinhados numa linha no desktop.
9. **Permissions & Roles**: Adicionadas permissões `manage_expenses`, `view_audit_logs`, `view_gatekeeper_logs` e role `security`.

## Ficheiros Alterados
- `app/Models/Grade.php` — correção de cálculo de média + pesos configuráveis
- `app/Models/ClassRoom.php` — campo shift + accessor
- `app/Http/Controllers/ExpenseController.php` — novo
- `app/Models/Expense.php` — novo
- `app/Models/FinancialCategory.php` — novo
- `app/Policies/ExpensePolicy.php` — novo
- `app/Http/Controllers/PaymentController.php` — escopo por role
- `app/Http/Controllers/DashboardController.php` — dados do calendário
- `app/Http/Controllers/TeacherPortalController.php` — dados do calendário
- `app/Providers/AuthServiceProvider.php` — gate simplificado
- `app/Http/Controllers/AttendanceController.php` — fluxo baseado em turma
- `app/Http/Controllers/GateKeeperController.php` — status baseado em turno
- `app/Http/Controllers/ClassRoomController.php` — suporte a shift
- `app/Http/Controllers/ReportController.php` — despesas em relatórios
- `app/Http/Controllers/StudentController.php` — escopo por role
- `database/seeders/SettingSeeder.php` — novas configurações de avaliação
- `database/seeders/PermissionSeeder.php` — novas permissões e role security
- `database/seeders/DatabaseSeeder.php` — FinancialCategorySeeder
- `database/migrations/2026_08_04_140000_add_shift_to_classes_table.php` — novo
- `database/migrations/2026_08_05_100000_create_financial_categories_table.php` — novo
- `database/migrations/2026_08_05_100001_create_expenses_table.php` — novo
- `routes/web.php` — rotas de despesas, remoção de payment-check público
- `resources/views/layouts/school.blade.php` — menu sidebar atualizado
- `resources/views/dashboard/admin.blade.php` — KPI cards + despesas + calendário
- `resources/views/settings/index.blade.php` — configuração de notas
- `resources/views/classes/students.blade.php` — aniversariantes com turma + perfil
- `resources/views/classes/show.blade.php` — aniversariantes com turma + perfil
- `resources/views/teacher-portal/dashboard.blade.php` — aniversariantes com turma + perfil
- `resources/views/welcome.blade.php` — remoção de referência a rota pública
- `resources/views/expenses/` — 4 views novas
- `resources/views/partials/calendar.blade.php` — novo
- `resources/views/partials/admin-calendar.blade.php` — novo
- `resources/views/attendances/` — 4 views novas/reorganizadas
- `resources/views/students/pdf_grades.blade.php` — novo
- `docs/MODULES_AND_FEATURES.md` — atualizado com novos módulos
- `docs/DATABASE_SCHEMA.md` — atualizado com novas tabelas
- `README.md` — atualizado com novas funcionalidades
- `CHANGELOG.md` — novo

## Decisões Arquitecturais
- Pesos de cálculo de médias (MACS, ACP) agora são lidos das configurações do sistema em vez de hardcoded.
- Mínimo de trimestres para média final é configurável (padrão: 3).
- Despesas usam categorias financeiras para melhor organização.
- Role `security` separada para a Portaria com permissões específicas.
- Widget de calendário usa partials Blade para reutilização em múltiplos dashboards.

## Problemas Encontrados
- Erro `RouteNotFoundException` para `public.payment-check` após remoção da rota — resolvido limpando cache de views.
- `calculateMFD` calculava média parcial quando nem todos os trimestres tinham notas — corrigido para retornar `null`.
- Coluna `status` ambígua em consultas SQL de presenças — resolvido com qualificação de tabela.

## Problemas Resolvidos
- Todas as 41 PHPUnit tests passam.
- Sem regressões críticas introduzidas.
- Erros de runtime no fluxo de presenças eliminados.

## Melhorias Implementadas
- Módulo completo de gestão de despesas.
- Configuração parametrizável de cálculo de médias.
- Suporte a turnos escolares.
- Widget de calendário cross-dashboard.
- Escopo de dados por role para pagamentos.

## Melhorias Adiadas
- Testes de browser (Browser Testing) — requer ambiente de browser.
- Smoke Tests de produção — requer deploy.
- Documentação de API para endpoints de despesas.

## Riscos Conhecidos
- A mudança de `calculateMFD` para retornar `null` quando menos de 3 trimestres têm notas pode afetar relatórios existentes que assumiam que a média estava sempre disponível.
- O role `security` é novo e não tem permissões de gestão de despesas (apenas visualização de logs da portaria).

## Recomendações para a Próxima Sprint
- Implementar Browser Testing automatizado (Laravel Dusk ou Playwright).
- Adicionar testes de integração para o fluxo de despesas.
- Implementar exportação de relatórios financeiros (PDF/Excel).
- Adicionar notificações push para aniversariantes.
- Implementar dashboard de análise de despesas por categoria.

### Sidebar Menu Updates (additional)
- Added "Pagamentos Pendentes" and "Mensalidades em Atraso" links under Gestão Financeira (for users with `generate_payment_references` permission)
- Added "Relatório Financeiro" link under Gestão Financeira (for users with `view_financial_reports` permission)
- Added "Eventos & Calendário" section with events link
- Added "Relatórios" section with financial and academic reports links
- Added "Licenças" link under Administração (for users with `manage_leave_requests` permission)
- Updated `@canany` conditions to include `view_financial_reports` and `manage_leave_requests` permissions
