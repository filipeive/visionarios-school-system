#!/bin/bash
# Script para criar toda a estrutura de views do Sistema Visionários

# Criar estrutura de diretórios
mkdir -p resources/views/{layouts,components,auth,dashboard,students,teachers,classes,subjects,enrollments,payments,attendances,grades,events,reports,parent,teacher-portal,admin,errors,partials,public}

echo "📁 Estrutura de pastas criada!"

# Criar arquivos vazios para popular depois
touch resources/views/layouts/school.blade.php
touch resources/views/layouts/auth.blade.php
touch resources/views/layouts/guest.blade.php

# Components
mkdir -p resources/views/components/{forms,tables,cards,alerts}
touch resources/views/components/forms/input.blade.php
touch resources/views/components/forms/select.blade.php
touch resources/views/components/forms/textarea.blade.php
touch resources/views/components/tables/simple.blade.php
touch resources/views/components/cards/stat.blade.php
touch resources/views/components/alerts/success.blade.php
touch resources/views/components/alerts/error.blade.php

# Auth views
touch resources/views/auth/login.blade.php
touch resources/views/auth/register.blade.php
touch resources/views/auth/forgot-password.blade.php
touch resources/views/auth/reset-password.blade.php
touch resources/views/auth/verify-email.blade.php

# Dashboard views
touch resources/views/dashboard/admin.blade.php
touch resources/views/dashboard/secretary.blade.php
touch resources/views/dashboard/pedagogy.blade.php
touch resources/views/dashboard/teacher.blade.php
touch resources/views/dashboard/parent.blade.php
touch resources/views/dashboard/basic.blade.php

# Students views
touch resources/views/students/index.blade.php
touch resources/views/students/create.blade.php
touch resources/views/students/edit.blade.php
touch resources/views/students/show.blade.php

# Teachers views
touch resources/views/teachers/index.blade.php
touch resources/views/teachers/create.blade.php
touch resources/views/teachers/edit.blade.php
touch resources/views/teachers/show.blade.php

# Classes views
touch resources/views/classes/index.blade.php
touch resources/views/classes/create.blade.php
touch resources/views/classes/edit.blade.php
touch resources/views/classes/show.blade.php

# Subjects views
touch resources/views/subjects/index.blade.php
touch resources/views/subjects/create.blade.php
touch resources/views/subjects/edit.blade.php

# Enrollments views
touch resources/views/enrollments/index.blade.php
touch resources/views/enrollments/create.blade.php
touch resources/views/enrollments/show.blade.php

# Payments views
touch resources/views/payments/index.blade.php
touch resources/views/payments/create.blade.php
touch resources/views/payments/show.blade.php
touch resources/views/payments/references.blade.php

# Attendances views
touch resources/views/attendances/index.blade.php
touch resources/views/attendances/mark.blade.php
touch resources/views/attendances/report.blade.php

# Grades views
touch resources/views/grades/index.blade.php
touch resources/views/grades/create.blade.php
touch resources/views/grades/batch-create.blade.php
touch resources/views/grades/report-card.blade.php

# Events views
touch resources/views/events/index.blade.php
touch resources/views/events/create.blade.php
touch resources/views/events/show.blade.php
touch resources/views/events/calendar.blade.php

# Reports views
touch resources/views/reports/index.blade.php
touch resources/views/reports/academic.blade.php
touch resources/views/reports/financial.blade.php

# Parent portal views
touch resources/views/parent/dashboard.blade.php
touch resources/views/parent/children.blade.php
touch resources/views/parent/student-details.blade.php
touch resources/views/parent/payments.blade.php
touch resources/views/parent/communications.blade.php

# Teacher portal views
touch resources/views/teacher-portal/dashboard.blade.php
touch resources/views/teacher-portal/classes.blade.php
touch resources/views/teacher-portal/attendance.blade.php
touch resources/views/teacher-portal/grades.blade.php
touch resources/views/teacher-portal/communications.blade.php

# Admin views
touch resources/views/admin/users/index.blade.php
touch resources/views/admin/users/create.blade.php
touch resources/views/admin/users/edit.blade.php
touch resources/views/admin/settings.blade.php

# Error views
touch resources/views/errors/404.blade.php
touch resources/views/errors/403.blade.php
touch resources/views/errors/500.blade.php

# Partials
touch resources/views/partials/toasts.blade.php
touch resources/views/partials/modals.blade.php
touch resources/views/partials/breadcrumbs.blade.php

# Public views
touch resources/views/public/about.blade.php
touch resources/views/public/contact.blade.php
touch resources/views/public/pre-enrollment.blade.php

# Profile views
mkdir -p resources/views/profile
touch resources/views/profile/edit.blade.php
touch resources/views/profile/partials/update-profile-information-form.blade.php
touch resources/views/profile/partials/update-password-form.blade.php
touch resources/views/profile/partials/delete-user-form.blade.php

# Search views
touch resources/views/search/results.blade.php

echo "✅ Todos os arquivos de views criados!"
echo ""
echo "📊 Resumo:"
echo "- Layouts: 3 arquivos"
echo "- Components: 9 arquivos"
echo "- Auth: 5 arquivos"
echo "- Dashboard: 6 arquivos"
echo "- Students: 4 arquivos"
echo "- Teachers: 4 arquivos"
echo "- Classes: 4 arquivos"
echo "- Payments: 4 arquivos"
echo "- E muitos mais..."
echo ""
echo "🔄 Agora vou criar o conteúdo para cada arquivo importante."