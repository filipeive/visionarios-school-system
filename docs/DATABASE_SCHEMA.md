# Dicionário e Esquema da Base de Dados — ZamEdu

Este documento descreve a estrutura relacional de dados, os 30 modelos Eloquent, migrações e chaves estrangeiras da base de dados do **ZamEdu**.

---

## 📊 Diagrama Entidade-Relação Simplificado (ERD)

```
 [User] 1 <---> 0..1 [Teacher]  1 <---> N [ClassRoom]
   |                     |                    |
   |                     |                    +---> N [Enrollment] <---> 1 [Student]
   v                     v                    |                              |
[Roles/Perms]    [StaffLeaveRequest]   [ClassSubject]                         v
                                              |                         [Payment]
                                              v                             |
                                           [Grade] <------------------------+
```

---

## 📁 Principais Tabelas & Dicionário de Dados

### 1. `users` (Utilizadores & Autenticação)
- `id` (BIGINT, PK): Identificador único do utilizador.
- `name` (VARCHAR): Nome completo.
- `email` (VARCHAR, UNIQUE): E-mail de acesso.
- `password` (VARCHAR): Hash Bcrypt da palavra-passe.
- `created_at`, `updated_at` (TIMESTAMP).

### 2. `students` (Estudantes)
- `id` (BIGINT, PK): Identificador do aluno.
- `student_number` (VARCHAR, UNIQUE): Código de matrícula (ex: `ZAM20260165`).
- `first_name`, `last_name` (VARCHAR): Nome e apelido.
- `gender` (ENUM: `male`, `female`).
- `birthdate` (DATE): Data de nascimento.
- `registration_date` (DATE): Data de inscrição inicial.
- `monthly_fee` (DECIMAL 10,2): Valor base da mensalidade ajustado ao aluno.
- `parent_id` (BIGINT, FK -> `parents.user_id`, Nullable).
- `has_special_needs` (BOOLEAN): Indicador de necessidades especiais.
- `status` (ENUM: `active`, `inactive`, `transferred`, `graduated`).

### 3. `teachers` (Docentes)
- `id` (BIGINT, PK): Identificador do professor.
- `user_id` (BIGINT, FK -> `users.id`, Nullable).
- `first_name`, `last_name` (VARCHAR): Nome e apelido.
- `email`, `phone` (VARCHAR): Contactos.
- `hire_date` (DATE): Data de admissão na instituição.
- `qualification` (VARCHAR): Grau académico (ex: Licenciatura).
- `specialization` (VARCHAR): Área de especialidade.
- `salary` (DECIMAL 10,2): Salário base mensal.
- `status` (ENUM: `active`, `inactive`).

### 4. `classes` (Turmas)
- `id` (BIGINT, PK): Identificador da turma.
- `name` (VARCHAR): Nome da turma (ex: `4ª Classe A`).
- `grade_level` (VARCHAR): Nível escolar.
- `academic_year` (INT): Ano lectivo.
- `teacher_id` (BIGINT, FK -> `teachers.id`, Nullable): Director de turma.
- `capacity` (INT): Lotação máxima.
- `status` (ENUM: `active`, `inactive`).

### 5. `enrollments` (Matrículas)
- `id` (BIGINT, PK).
- `student_id` (BIGINT, FK -> `students.id`).
- `class_id` (BIGINT, FK -> `classes.id`).
- `academic_year` (INT): Ano lectivo da matrícula.
- `status` (ENUM: `active`, `pending`, `transferred`, `cancelled`, `pending_renewal`).

### 6. `payments` (Propinas & Mensalidades)
- `id` (BIGINT, PK).
- `student_id` (BIGINT, FK -> `students.id`).
- `payment_reference` (VARCHAR, UNIQUE): Referência de pagamento.
- `fee_type` (VARCHAR): Tipo de cobrança (`monthly_fee`, `enrollment`, `uniform`, etc.).
- `amount` (DECIMAL 10,2): Valor base.
- `penalty_amount` (DECIMAL 10,2): Multa acumulada por mora.
- `due_date` (DATE): Data limite de pagamento.
- `paid_at` (TIMESTAMP, Nullable): Data de liquidação.
- `payment_method` (VARCHAR, Nullable): `mpesa`, `emola`, `multicaixa`, `cash`.
- `status` (ENUM: `pending`, `paid`, `overdue`, `cancelled`).

### 7. `grades` (Notas & Avaliações)
- `id` (BIGINT, PK).
- `student_id` (BIGINT, FK -> `students.id`).
- `subject_id` (BIGINT, FK -> `subjects.id`).
- `class_id` (BIGINT, FK -> `classes.id`).
- `teacher_id` (BIGINT, FK -> `teachers.id`).
- `trimester` (INT): 1, 2 ou 3.
- `acs` (DECIMAL 4,2, Nullable): Avaliação Contínua e Sistemática.
- `acp` (DECIMAL 4,2, Nullable): Avaliação Parcial.
- `acf` (DECIMAL 4,2, Nullable): Avaliação Final.
- `final_grade` (DECIMAL 4,2, Nullable): Média final calculada.

### 8. `licenses` (Licenciamento SaaS)
- `id` (BIGINT, PK).
- `client_name` (VARCHAR): Nome da escola contratante.
- `license_key` (VARCHAR, UNIQUE): Chave cifrada da licença.
- `expires_at` (DATETIME): Data limite de vigência.
- `grace_period_days` (INT): Dias de tolerância pós-expiração (padrão: 7).
- `status` (ENUM: `active`, `grace_period`, `suspended`).
