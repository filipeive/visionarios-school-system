# Especificação Funcional dos Módulos — ZamEdu (SIGE)

Este documento contém a descrição exaustiva de todos os módulos funcionais da plataforma **ZamEdu — Sistema de Gestão Escolar (SIGE)**.

---

## 📋 Índice dos Módulos

1. [Dashboard Executivo & Analytics](#1-dashboard-executivo--analytics)
2. [Gestão de Alunos](#2-gestão-de-alunos)
3. [Gestão de Professores & Pessoal](#3-gestão-de-professores--pessoal)
4. [Turmas, Salas & Horários](#4-turmas-salas--horários)
5. [Disciplinas & Matriz Curricular](#5-disciplinas--matriz-curricular)
6. [Matrículas & Inscrições](#6-matrículas--inscrições)
7. [Propinas, Pagamentos & Multas](#7-propinas-pagamentos--multas)
8. [Gestão de Despesas](#8-gestão-de-despesas)
9. [Lançamento de Notas, Pautas & Boletins](#9-lançamento-de-notas-pautas--boletins)
10. [Assiduidade & Presenças](#10-assiduidade--presenças)
11. [Comunicações & Notificações em Massa](#11-comunicações--notificações-em-massa)
12. [Portais Dedicados (Pais e Professores)](#12-portais-dedicados-pais-e-professores)
13. [Gestão de Licenças & Configurações da Escola](#13-gestão-de-licenças--configurações-da-escola)

---

## 1. Dashboard Executivo & Analytics

- **Rota**: `/dashboard` (`DashboardController@index`)
- **Público-alvo**: Administradores, Diretores, Secretaria, Pedagogia.
- **Funcionalidades**:
  - Cartões de Indicadores Chave (KPIs): Receita Mensal, Alunos Ativos, Taxa Global de Assiduidade, Média Geral Académica, Propinas em Atraso, Total de Professores e Despesas do Mês.
  - Widget de Calendário com navegação mensal, pontos de eventos e lista de eventos upcoming.
  - Gráfico Interativo de Evolução da Receita (Chart.js UMD) nos últimos 12 meses.
  - Gráfico de Distribuição de Alunos por Turma.
  - Quadro de Honra com o Top 5 Alunos com maior média académica global.
  - Ranking de Turmas com melhor desempenho pedagógico.
  - Smart Insights (alertas automáticos de assiduidade e cobranças).

---

## 2. Gestão de Alunos

- **Rota**: `/students` (`StudentController`)
- **Funcionalidades**:
  - Listagem paginada de estudantes com avatares circulares e filtros por nome, número, turma, status e género.
  - Registo completo de alunos com foto tipo passe, data de nascimento, endereço, contactos de emergência e necessidades educativas especiais.
  - Ficha individual do aluno (`/students/{student}`): histórico de matrículas, pagamentos associados, notas por trimestre e relatórios de assiduidade.
  - Operações CRUD completas com permissões granularizadas (`view_students`, `create_students`, `edit_students`, `delete_students`).

---

## 3. Gestão de Professores & Pessoal

- **Rota**: `/teachers` (`TeacherController`)
- **Funcionalidades**:
  - Cadastro de docentes com número de BI, qualificação académica, especialização, data de admissão e salário.
  - Cálculo automático e formatado dos anos de experiência docente.
  - Atribuição de turmas e disciplinas a cada professor.
  - Gestão de solicitações de licença/ausência do pessoal docente e administrativo (`/staff-leave-requests`).

---

## 4. Turmas, Salas & Horários

- **Rota**: `/classes` (`ClassRoomController`)
- **Funcionalidades**:
  - Criação e gestão de turmas por ano lectivo e nível de ensino (ex: 1ª Classe a 12ª Classe).
  - Associação de Director de Turma.
  - Definição de turno escolar (`morning`, `afternoon`, `night`) com horários de atraso configuráveis por turno.
  - Definição de horários de aula por dia da semana (`ClassSchedule`).
  - Alocação e transferência de alunos entre turmas.

---

## 5. Disciplinas & Matriz Curricular

- **Rota**: `/subjects` (`SubjectController`)
- **Funcionalidades**:
  - Cadastro de disciplinas com carga horária semanal.
  - Vinculação de disciplinas às respetivas turmas e professores ministrantes (`ClassSubject`).

---

## 6. Matrículas & Inscrições

- **Rota**: `/enrollments` (`EnrollmentController` / `EnrollmentApplicationController`)
- **Funcionalidades**:
  - Inscrições online e pré-matrículas públicas (`/public/pre-enrollment`).
  - Confirmação de matrículas de novos alunos e renovação anual automatizada (`/admin/enrollments/renewals`).
  - Impressão de comprovativos de matrícula em PDF/Formatado.

---

## 7. Propinas, Pagamentos & Multas

- **Rota**: `/payments` (`PaymentController`)
- **Funcionalidades**:
  - Geração automática de propinas mensais por lote.
  - Cálculo de multas e juros por mora configuráveis.
  - Geração de referências bancárias de pagamento (M-Pesa, eMola, Multicaixa).
  - Emissão de recibos e relatórios de liquidez financeira.
  - Escopo de dados por role: professores veem apenas pagamentos das suas turmas; encarregados veem apenas pagamentos dos seus filhos.

---

## 8. Gestão de Despesas

- **Rota**: `/expenses` (`ExpenseController`)
- **Público-alvo**: Administradores, Secretaria.
- **Funcionalidades**:
  - Criação, edição, visualização e exclusão de despesas financeiras.
  - Categorização de despesas por categoria financeira (`FinancialCategory`).
  - Relatórios de despesas por período e categoria.
  - Integração com relatórios financeiros para cálculo de resultado líquido (receita - despesas).
  - Permissão granular: `manage_expenses`.

---

## 9. Lançamento de Notas, Pautas & Boletins

- **Rota**: `/grades` (`GradeController`)
- **Funcionalidades**:
  - Lançamento contínuo de notas (ACS - Avaliação Contínua e Sistemática, ACP - Avaliação Parcial, ACF - Avaliação Final).
  - Entrada de notas por lote para toda a turma (`/grades/batch-create`).
  - Emissão de pauta académica da turma e Boletim Informativo de Notas do aluno.
  - Cálculo de médias com pesos configuráveis (MACS peso, ACP peso) e mínimo de trimestres para média final.
  - Suporte a múltiplas escalas de avaliação (0-20, 0-10, percentagem).

---

## 10. Assiduidade & Presenças

- **Rota**: `/attendances` (`AttendanceController`)
- **Funcionalidades**:
  - Registo diário de presenças, faltas justificadas e faltas injustificadas por turma.
  - Relatório consolidado de assiduidade por período e aluno.

---

## 11. Comunicações & Notificações em Massa

- **Rota**: `/communications` (`NotificationController`)
- **Funcionalidades**:
  - Envio de comunicados gerais e avisos direcionados por turma ou encarregado.
  - Notificações no sistema e suporte a integração SMS/Email.

---

## 12. Portais Dedicados (Pais e Professores)

- **Portal do Encarregado (`/parent/dashboard`)**:
  - Acompanhamento do boletim de notas, faltas, mensalidades pendentes e pagamento via M-Pesa.
- **Portal do Professor (`/teacher/dashboard`)**:
  - Registo de sumários, chamada digital, lançamento de notas e solicitação de dispensas.

---

## 13. Gestão de Licenças & Configurações da Escola

- **Painel de Licenças (`/admin/license`)**:
  - Validação de chave SaaS, validade do contrato e estado de vigência (`active`, `grace_period`, `suspended`).
- **Configurações Globais (`/admin/settings`)**:
  - Personalização de nome da instituição, logótipo, cores primárias/secundárias e parâmetros académicos.
