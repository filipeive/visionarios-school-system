<?php
// routes/web.php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TeacherPortalController;
use App\Http\Controllers\ParentPortalController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página inicial - redireciona para login ou dashboard
/* Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
}); */

Route::get('/', function () {
    return view('welcome');
})->name('welcome');   

// Rotas de autenticação (Laravel Breeze)
require __DIR__.'/auth.php';

// Rotas protegidas por autenticação
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // API para contadores do dashboard
    Route::get('/api/dashboard/counters', [DashboardController::class, 'counters'])->name('api.dashboard.counters');
    
    // Perfil do Usuário
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ========== GESTÃO DE ALUNOS ==========
    Route::middleware('permission:view_students')->prefix('students')->name('students.')->group(function () {
        Route::middleware('permission:create_students')->group(function () {
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/', [StudentController::class, 'store'])->name('store');
        });
        
         Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/{student}', [StudentController::class, 'show'])->name('show');

        Route::middleware('permission:edit_students')->group(function () {
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::patch('/{student}', [StudentController::class, 'update'])->name('update');
        });
        
        Route::middleware('permission:delete_students')->group(function () {
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
        });

        // Funcionalidades específicas
        Route::post('/{student}/upload-photo', [StudentController::class, 'uploadPhoto'])->name('upload-photo');
        Route::get('/{student}/grades', [StudentController::class, 'grades'])->name('grades');
        Route::get('/{student}/attendance', [StudentController::class, 'attendance'])->name('attendance');
        Route::get('/{student}/payments', [StudentController::class, 'payments'])->name('payments');
    });

    // ========== GESTÃO DE PROFESSORES ==========
    Route::middleware('permission:view_teachers')->prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('index');
        Route::middleware('permission:create_teachers')->group(function () {
            Route::get('/create', [TeacherController::class, 'create'])->name('create');
            Route::post('/', [TeacherController::class, 'store'])->name('store');
        });
        Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show');
    
        
         Route::post('/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('toggle-status');

        Route::post('/{teacher}/assign-class', [TeacherController::class, 'assignClass'])->name('assign-class');
        Route::middleware('permission:edit_teachers')->group(function () {
            Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit');
            Route::patch('/{teacher}', [TeacherController::class, 'update'])->name('update');
        });
        
        Route::middleware('permission:delete_teachers')->group(function () {
            Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
        });


        // Funcionalidades específicas
        Route::get('/{teacher}/classes', [TeacherController::class, 'classes'])->name('classes');
        Route::get('/{teacher}/schedule', [TeacherController::class, 'schedule'])->name('schedule');
    });
    
    // ========== PORTAL DO PROFESSOR ==========
    Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher-portal.')->group(function () {
        Route::get('/classes', [TeacherPortalController::class, 'myClasses'])->name('classes');
        Route::get('/dashboard', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/classes/{classId}', [TeacherPortalController::class, 'classDetails'])->name('class-detail');
        Route::get('/classes/{classId}/attendance', [TeacherPortalController::class, 'classattendance'])->name('attendance');
        Route::post('/classes/{classId}/attendance', [TeacherPortalController::class, 'storeAttendance'])->name('store-attendance');
        Route::get('/classes/{classId}/grades', [TeacherPortalController::class, 'grades'])->name('grades');
        Route::post('/grades', [TeacherPortalController::class, 'storeGrade'])->name('store-grade');
        Route::get('/communications', [TeacherPortalController::class, 'communications'])->name('communications');
        Route::get('/profile', [TeacherPortalController::class, 'profile'])->name('profile');
        Route::post('/profile', [TeacherPortalController::class, 'updateProfile'])->name('update-profile');
    });

    // ========== GESTÃO DE TURMAS ==========
    Route::middleware('permission:view_classes')->prefix('classes')->name('classes.')->group(function () {
        Route::get('/', [ClassRoomController::class, 'index'])->name('index');
        Route::middleware('permission:create_classes')->group(function () {
            Route::get('/create', [ClassRoomController::class, 'create'])->name('create');
            Route::post('/', [ClassRoomController::class, 'store'])->name('store');
        });
        Route::get('/{class}', [ClassRoomController::class, 'show'])->name('show');
        Route::middleware('permission:edit_classes')->group(function () {
            Route::get('/{class}/edit', [ClassRoomController::class, 'edit'])->name('edit');
            Route::patch('/{class}', [ClassRoomController::class, 'update'])->name('update');
        });
        
        Route::middleware('permission:delete_classes')->group(function () {
            Route::delete('/{class}', [ClassRoomController::class, 'destroy'])->name('destroy');
        });

        // Funcionalidades específicas
        Route::get('/{class}/students', [ClassRoomController::class, 'students'])->name('students');
        Route::post('/{class}/assign-teacher', [ClassRoomController::class, 'assignTeacher'])->name('assign-teacher');
        //add students to class
        Route::post('/{class}/add-students', [ClassRoomController::class, 'addStudent'])->name('add-student');
        Route::post('/{class}/remove-student/{student}', [ClassRoomController::class, 'removeStudent'])->name('remove-student');
    });

    // ========== GESTÃO DE DISCIPLINAS ==========
   // Gestão de Disciplinas
    Route::middleware('permission:view_subjects')->prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/{subject}', [SubjectController::class, 'show'])->name('show');
        
        Route::middleware('permission:create_subjects')->group(function () {
            Route::get('/create', [SubjectController::class, 'create'])->name('create');
            Route::post('/', [SubjectController::class, 'store'])->name('store');
        });
        
        Route::middleware('permission:edit_subjects')->group(function () {
            Route::get('/{subject}/edit', [SubjectController::class, 'edit'])->name('edit');
            Route::patch('/{subject}', [SubjectController::class, 'update'])->name('update');
        });
        
        Route::middleware('permission:delete_subjects')->group(function () {
            Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('destroy');
        });

        // Funcionalidades específicas
        Route::get('/{subject}/classes', [SubjectController::class, 'classes'])->name('classes');
        Route::get('/{subject}/grades', [SubjectController::class, 'grades'])->name('grades');
        
        Route::middleware('permission:manage_subjects')->group(function () {
            Route::post('/{subject}/assign-to-class', [SubjectController::class, 'assignToClass'])->name('assign-to-class');
            Route::delete('/{subject}/remove-from-class/{class}', [SubjectController::class, 'removeFromClass'])->name('remove-from-class');
        });
    });
    // ========== GESTÃO DE MATRÍCULAS ==========
    Route::middleware('permission:view_enrollments')->prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('index');
        Route::middleware('permission:create_enrollments')->group(function () {
            Route::get('/create', [EnrollmentController::class, 'create'])->name('create');
            Route::post('/', [EnrollmentController::class, 'store'])->name('store');
        });
        Route::middleware('permission:edit_enrollments')->group(function () {
            Route::get('/{enrollment}/edit', [EnrollmentController::class, 'edit'])->name('edit');
            Route::patch('/{enrollment}', [EnrollmentController::class, 'update'])->name('update');
        });
        Route::get('/{enrollment}', [EnrollmentController::class, 'show'])->name('show');
        Route::get('/{enrollment}/print', [EnrollmentController::class, 'print'])->name('print'); // ← ADICIONAR ESTA
        Route::post('/{enrollment}/confirm-payment', [EnrollmentController::class, 'confirmPayment'])->name('confirm-payment');

        // Funcionalidades específicas
        Route::post('/{enrollment}/activate', [EnrollmentController::class, 'activate'])->name('activate');
        Route::post('/{enrollment}/cancel', [EnrollmentController::class, 'cancel'])->name('cancel');
        Route::post('/{enrollment}/transfer', [EnrollmentController::class, 'transfer'])->name('transfer');
    });

    // ========== GESTÃO FINANCEIRA ==========
    Route::middleware('permission:view_payments')->prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        
        Route::middleware('permission:create_payments')->group(function () {
            Route::get('/create', [PaymentController::class, 'create'])->name('create');
            Route::post('/', [PaymentController::class, 'store'])->name('store');
        });
        
        Route::middleware('permission:process_payments')->group(function () {
            Route::post('/{payment}/process', [PaymentController::class, 'process'])->name('process');
            Route::post('/{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
        });

        // Referências de pagamento
        Route::middleware('permission:generate_payment_references')->group(function () {
            Route::get('/references', [PaymentController::class, 'references'])->name('references');
            Route::post('/generate-reference', [PaymentController::class, 'generateReference'])->name('generate-reference');
            Route::get('/reference/{reference}/download', [PaymentController::class, 'downloadReference'])->name('download-reference');
        });

        // Relatórios financeiros
        Route::get('/reports', [PaymentController::class, 'reports'])->name('reports');
        Route::get('/overdue', [PaymentController::class, 'overdue'])->name('overdue');
    });

    // ========== GESTÃO DE PRESENÇAS ==========
    Route::middleware('permission:view_attendances')->prefix('attendances')->name('attendances.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        
        Route::middleware('permission:mark_attendances')->group(function () {
            Route::get('/mark', [AttendanceController::class, 'mark'])->name('mark');
            Route::post('/mark', [AttendanceController::class, 'storeMark'])->name('store-mark');
            Route::get('/class/{class}/mark', [AttendanceController::class, 'markByClass'])->name('mark-by-class');
            Route::post('/class/{class}/mark', [AttendanceController::class, 'storeMarkByClass'])->name('store-mark-by-class');
        });

        // Relatórios de presença
        Route::get('/reports', [AttendanceController::class, 'reports'])->name('reports');
        Route::get('/class/{class}/report', [AttendanceController::class, 'classReport'])->name('class-report');
        Route::get('/student/{student}/report', [AttendanceController::class, 'studentReport'])->name('student-report');
    });

    // ========== GESTÃO DE NOTAS/AVALIAÇÕES ==========
    Route::middleware('permission:view_grades')->prefix('grades')->name('grades.')->group(function () {
        Route::get('/', [GradeController::class, 'index'])->name('index');
        
        Route::middleware('permission:create_grades')->group(function () {
            Route::get('/create', [GradeController::class, 'create'])->name('create');
            Route::post('/', [GradeController::class, 'store'])->name('store');
            Route::get('/batch-create', [GradeController::class, 'batchCreate'])->name('batch-create');
            Route::post('/batch-store', [GradeController::class, 'batchStore'])->name('batch-store');
        });
        
        Route::middleware('permission:edit_grades')->group(function () {
            Route::get('/{grade}/edit', [GradeController::class, 'edit'])->name('edit');
            Route::patch('/{grade}', [GradeController::class, 'update'])->name('update');
        });

        // Boletins e relatórios
        Route::get('/student/{student}/report-card', [GradeController::class, 'reportCard'])->name('student-report-card');
        Route::get('/class/{class}/report', [GradeController::class, 'classReport'])->name('class-report');
        Route::get('/class/{class}/grade-sheet', [GradeController::class, 'gradeSheet'])->name('grade-sheet');
    });

    // ========== GESTÃO DE EVENTOS ==========
    Route::middleware('permission:view_events')->prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
        
        Route::middleware('permission:create_events')->group(function () {
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::post('/', [EventController::class, 'store'])->name('store');
        });
        
        Route::middleware('permission:edit_events')->group(function () {
            Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
            Route::patch('/{event}', [EventController::class, 'update'])->name('update');
        });
        
        Route::middleware('permission:delete_events')->group(function () {
            Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        });

        // Funcionalidades específicas
        Route::get('/calendar', [EventController::class, 'calendar'])->name('calendar');
        Route::post('/{event}/send-notification', [EventController::class, 'sendNotification'])->name('send-notification');
    });

    // ========== COMUNICAÇÕES E NOTIFICAÇÕES ==========
    Route::prefix('communications')->name('communications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        
        Route::middleware('permission:send_notifications')->group(function () {
            Route::get('/create', [NotificationController::class, 'create'])->name('create');
            Route::post('/send', [NotificationController::class, 'send'])->name('send');
        });
        
        Route::middleware('permission:send_bulk_notifications')->group(function () {
            Route::get('/bulk', [NotificationController::class, 'bulk'])->name('bulk');
            Route::post('/bulk-send', [NotificationController::class, 'bulkSend'])->name('bulk-send');
        });
    });

    // Notificações do usuário
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'userNotifications'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');
    });

    // ========== RELATÓRIOS ==========
    Route::middleware('permission:view_reports')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        
        // Relatórios Acadêmicos
        Route::get('/academic', [ReportController::class, 'academic'])->name('academic');
        Route::get('/academic/performance', [ReportController::class, 'performance'])->name('academic.performance');
        Route::get('/academic/attendance', [ReportController::class, 'attendanceReport'])->name('academic.attendance');
        
        // Relatórios Financeiros
        Route::middleware('permission:view_financial_reports')->group(function () {
            Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
            Route::get('/financial/revenue', [ReportController::class, 'revenue'])->name('financial.revenue');
            Route::get('/financial/defaulters', [ReportController::class, 'defaulters'])->name('financial.defaulters');
        });

        // Exportações
        Route::middleware('permission:export_reports')->group(function () {
            Route::get('/export/students', [ReportController::class, 'exportStudents'])->name('export.students');
            Route::get('/export/payments', [ReportController::class, 'exportPayments'])->name('export.payments');
            Route::get('/export/grades', [ReportController::class, 'exportGrades'])->name('export.grades');
        });
    });

    // ========== ADMINISTRAÇÃO ==========
    Route::middleware('permission:manage_users')->prefix('admin')->name('admin.')->group(function () {
        // Gestão de Usuários
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::patch('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Configurações do Sistema
        Route::middleware('permission:manage_settings')->group(function () {
            Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
            Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
        });

        // Backup e Logs
        Route::middleware('permission:backup_system')->group(function () {
            Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
            Route::post('/backup/create', [SettingsController::class, 'createBackup'])->name('backup.create');
        });
        
        Route::middleware('permission:view_logs')->group(function () {
            Route::get('/logs', [SettingsController::class, 'logs'])->name('logs');
        });
    });

    // ========== PORTAL DOS PAIS ==========
    Route::middleware('role:parent')->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentPortalController::class, 'dashboard'])->name('dashboard');
        
        // Informações dos filhos
        Route::get('/children', [ParentPortalController::class, 'children'])->name('children');
        Route::get('/student/{student}', [ParentPortalController::class, 'studentDetails'])->name('student-details');
        Route::get('/student/{student}/grades', [ParentPortalController::class, 'studentGrades'])->name('student-grades');
        Route::get('/student/{student}/attendance', [ParentPortalController::class, 'studentAttendance'])->name('student-attendance');
        
        // Pagamentos
        Route::get('/payments', [ParentPortalController::class, 'payments'])->name('payments');
        Route::get('/student/{student}/payments', [ParentPortalController::class, 'studentPayments'])->name('student-payments');
        Route::get('/student/{student}/payment-references', [ParentPortalController::class, 'paymentReferences'])->name('payment-references');
        Route::post('/generate-payment-reference', [ParentPortalController::class, 'generatePaymentReference'])->name('generate-payment-reference');
        
        // Comunicações
        Route::get('/communications', [ParentPortalController::class, 'communications'])->name('communications');
        Route::post('/send-message', [ParentPortalController::class, 'sendMessage'])->name('send-message');
    });

    // ========== PORTAL DO PROFESSOR ==========
    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
        
        // Turmas do professor
        Route::get('/classes', [TeacherPortalController::class, 'classes'])->name('classes');
        Route::get('/class/{class}', [TeacherPortalController::class, 'classDetails'])->name('class-details');
        Route::get('/class/{class}/students', [TeacherPortalController::class, 'classStudents'])->name('class-students');
        Route::get('/grades', [TeacherPortalController::class, 'grades'])->name('grades');
        // Página do caderno de notas (gradebook) - USE ESTA
        Route::get('/class/{class}/gradebook', [TeacherPortalController::class, 'gradebook'])->name('gradebook');

        // Presenças
        Route::get('/attendance', [TeacherPortalController::class, 'classAttendance'])->name('attendance');
        Route::get('/attendance/today', [TeacherPortalController::class, 'todayAttendance'])->name('attendance.today');
        Route::get('/class/{class}/attendance', [TeacherPortalController::class, 'classAttendance'])->name('attendance');
        Route::post('/class/{class}/mark-attendance', [TeacherPortalController::class, 'markAttendance'])->name('mark-attendance');
        
        // REMOVER ESTAS ROTAS DUPLICADAS:
        // Route::get('/grades/pending', [TeacherPortalController::class, 'pendingGrades'])->name('grades.pending');
        // Route::get('/classes/{classId}/grades', [TeacherPortalController::class, 'grades'])->name('grades');
        
        // Manter apenas esta para atualização em lote
        Route::post('/grades/batch-update', [TeacherPortalController::class, 'batchUpdateGrades'])->name('grades.batch-update');
        
        // Comunicações
        Route::get('/communications', [TeacherPortalController::class, 'communications'])->name('communications');
        Route::get('/communications/create', [TeacherPortalController::class, 'createCommunication'])->name('communications.create');
        Route::post('/communications/send', [TeacherPortalController::class, 'sendCommunication'])->name('communications.send');
        
        // Licenças
        Route::get('/leave-requests', [TeacherPortalController::class, 'leaveRequests'])->name('leave-requests');
        Route::get('/leave-request/create', [TeacherPortalController::class, 'createLeaveRequest'])->name('leave-request.create');
        Route::post('/leave-request', [TeacherPortalController::class, 'storeLeaveRequest'])->name('leave-request.store');
        
        // Relatórios do professor
        Route::get('/reports/attendance', [TeacherPortalController::class, 'attendanceReports'])->name('reports.attendance');
        Route::get('/reports/grades', [TeacherPortalController::class, 'gradeReports'])->name('reports.grades');
    });
    // Pesquisa
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/api/search/live', [SearchController::class, 'liveSearch'])->name('api.search.live');
        // ========== AJAX/API ROUTES ==========
    Route::prefix('api')->name('api.')->group(function () {
        
        // Contadores do dashboard
        Route::get('/dashboard/counters', function() {
            return response()->json([
                'notifications' => auth()->user()->unreadNotifications->count(),
                'orders_pending' => 0, // Não aplicável ao sistema escolar
                'students_active' => App\Models\Student::active()->count(),
                'payments_overdue' => App\Models\Payment::overdue()->count(),
            ]);
        })->name('dashboard.counters');

        // Verificação de notificações para pais
        Route::get('/parent/notifications-check', function() {
            $hasNewNotifications = auth()->user()->unreadNotifications()
                ->where('created_at', '>', now()->subMinutes(5))
                ->exists();
                
            return response()->json([
                'hasNewNotifications' => $hasNewNotifications,
                'count' => auth()->user()->unreadNotifications->count()
            ]);
        })->name('parent.notifications-check');

        // Dados para gráficos
        Route::get('/charts/revenue-monthly', [ReportController::class, 'monthlyRevenueChart'])->name('charts.revenue-monthly');
        Route::get('/charts/attendance-weekly', [ReportController::class, 'weeklyAttendanceChart'])->name('charts.attendance-weekly');
        Route::get('/charts/students-by-grade', [ReportController::class, 'studentsByGradeChart'])->name('charts.students-by-grade');

        // Logs de erro JavaScript
        Route::post('/log-js-error', function(Illuminate\Http\Request $request) {
            Log::error('JavaScript Error', $request->all());
            return response()->json(['status' => 'logged']);
        })->name('log-js-error');
    });
});

// ========== ROTAS PÚBLICAS ==========
// Página de informações da escola
Route::get('/about', function () {
    return view('public.about');
})->name('about');

// Contato
Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');

// Matrícula online (pré-matrícula)
Route::get('/pre-enrollment', function () {
    return view('public.pre-enrollment');
})->name('pre-enrollment');

Route::post('/pre-enrollment', function (Illuminate\Http\Request $request) {
    // Lógica para processar pré-matrícula
    // Salvar dados temporários e notificar secretaria
    
    return redirect()->route('pre-enrollment')
        ->with('success', 'Pré-matrícula enviada com sucesso! Entraremos em contato em breve.');
})->name('pre-enrollment.store');

// Verificação de pagamentos por referência (sem login)
Route::get('/payment-check', function () {
    return view('public.payment-check');
})->name('payment-check');

Route::post('/payment-check', function (Illuminate\Http\Request $request) {
    $reference = $request->get('reference');
    
    $payment = App\Models\Payment::where('reference_number', $reference)->first();
    
    if (!$payment) {
        return back()->with('error', 'Referência não encontrada.');
    }
    
    return view('public.payment-status', compact('payment'));
})->name('payment-check.verify');

// ========== WEBHOOKS (para integrações de pagamento) ==========
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    
    // MPesa webhook
    Route::post('/mpesa', function (Illuminate\Http\Request $request) {
        // Processar callback do MPesa
        Log::info('MPesa Webhook', $request->all());
        
        // Lógica para processar pagamento via MPesa
        
        return response()->json(['status' => 'received']);
    })->name('mpesa');
    
    // eMola webhook
    Route::post('/emola', function (Illuminate\Http\Request $request) {
        // Processar callback do eMola
        Log::info('eMola Webhook', $request->all());
        
        return response()->json(['status' => 'received']);
    })->name('emola');
    
    // Multicaixa webhook
    Route::post('/multicaixa', function (Illuminate\Http\Request $request) {
        // Processar callback do Multicaixa
        Log::info('Multicaixa Webhook', $request->all());
        
        return response()->json(['status' => 'received']);
    })->name('multicaixa');
});

// ========== MIDDLEWARE PERSONALIZADO ==========
// Middleware para verificar se o usuário completou o perfil
Route::middleware(['auth', 'profile.complete'])->group(function () {
    // Rotas que requerem perfil completo
});

// ========== FALLBACK ROUTES ==========
// Rota 404 personalizada
Route::fallback(function () {
    return view('errors.404');
});

/*
|--------------------------------------------------------------------------
| Middleware Aliases para o Sistema Escolar
|--------------------------------------------------------------------------
| Estes aliases são registrados no Kernel.php
|
| 'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
| 'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
| 'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
| 'profile.complete' => \App\Http\Middleware\EnsureProfileComplete::class,
|
*/