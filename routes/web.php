<?php

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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Informações públicas da escola
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/about', fn() => view('public.about'))->name('about');
    Route::get('/contact', fn() => view('public.contact'))->name('contact');

    // Pré-matrícula online
    Route::get('/pre-enrollment', fn() => view('public.pre-enrollment'))->name('pre-enrollment');
    Route::post('/pre-enrollment', function (Illuminate\Http\Request $request) {
        // TODO: Implementar lógica de pré-matrícula
        return redirect()->route('public.pre-enrollment')
            ->with('success', 'Pré-matrícula enviada com sucesso! Entraremos em contato em breve.');
    })->name('pre-enrollment.store');

    // Verificação de pagamentos
    Route::get('/payment-check', fn() => view('public.payment-check'))->name('payment-check');
    Route::post('/payment-check', function (Illuminate\Http\Request $request) {
        $payment = App\Models\Payment::where('reference_number', $request->reference)->first();

        if (!$payment) {
            return back()->with('error', 'Referência não encontrada.');
        }

        return view('public.payment-status', compact('payment'));
    })->name('payment-check.verify');
});

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Autenticadas)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil do Usuário
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // Pesquisa Global
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    /*
    |--------------------------------------------------------------------------
    | Gestão de Alunos
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_students')
        ->prefix('students')
        ->name('students.')
        ->controller(StudentController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{student}', 'show')->name('show');
            Route::get('/{student}/grades', 'grades')->name('grades');
            Route::get('/{student}/attendance', 'attendance')->name('attendance');
            Route::get('/{student}/payments', 'payments')->name('payments');

            Route::middleware('permission:create_students')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::middleware('permission:edit_students')->group(function () {
                Route::get('/{student}/edit', 'edit')->name('edit');
                Route::patch('/{student}', 'update')->name('update');
                Route::post('/{student}/upload-photo', 'uploadPhoto')->name('upload-photo');
            });

            Route::middleware('permission:delete_students')->group(function () {
                Route::delete('/{student}', 'destroy')->name('destroy');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Gestão de Professores
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_teachers')
        ->prefix('teachers')
        ->name('teachers.')
        ->controller(TeacherController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{teacher}', 'show')->name('show');
            Route::get('/{teacher}/classes', 'classes')->name('classes');
            Route::get('/{teacher}/schedule', 'schedule')->name('schedule');

            Route::middleware('permission:create_teachers')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::middleware('permission:edit_teachers')->group(function () {
                Route::get('/{teacher}/edit', 'edit')->name('edit');
                Route::patch('/{teacher}', 'update')->name('update');
                Route::post('/{teacher}/toggle-status', 'toggleStatus')->name('toggle-status');
                Route::post('/{teacher}/assign-class', 'assignClass')->name('assign-class');
            });

            Route::middleware('permission:delete_teachers')->group(function () {
                Route::delete('/{teacher}', 'destroy')->name('destroy');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Gestão de Turmas
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_classes')
        ->prefix('classes')
        ->name('classes.')
        ->controller(ClassRoomController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{class}', 'show')->name('show');
            Route::get('/{class}/students', 'students')->name('students');

            Route::middleware('permission:create_classes')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::middleware('permission:edit_classes')->group(function () {
                Route::get('/{class}/edit', 'edit')->name('edit');
                Route::patch('/{class}', 'update')->name('update');
                Route::post('/{class}/assign-teacher', 'assignTeacher')->name('assign-teacher');
                Route::post('/{class}/add-students', 'addStudent')->name('add-student');
                Route::post('/{class}/remove-student/{student}', 'removeStudent')->name('remove-student');
            });

            Route::middleware('permission:delete_classes')->group(function () {
                Route::delete('/{class}', 'destroy')->name('destroy');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Gestão de Disciplinas
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_subjects')
        ->prefix('subjects')
        ->name('subjects.')
        ->controller(SubjectController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{subject}', 'show')->name('show');
            Route::get('/{subject}/classes', 'classes')->name('classes');
            Route::get('/{subject}/grades', 'grades')->name('grades');

            Route::middleware('permission:create_subjects')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::middleware('permission:edit_subjects')->group(function () {
                Route::get('/{subject}/edit', 'edit')->name('edit');
                Route::patch('/{subject}', 'update')->name('update');
            });

            Route::middleware('permission:delete_subjects')->group(function () {
                Route::delete('/{subject}', 'destroy')->name('destroy');
            });

            Route::middleware('permission:manage_subjects')->group(function () {
                Route::post('/{subject}/assign-to-class', 'assignToClass')->name('assign-to-class');
                Route::delete('/{subject}/remove-from-class/{class}', 'removeFromClass')->name('remove-from-class');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Gestão de Matrículas
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_enrollments')
        ->prefix('enrollments')
        ->name('enrollments.')
        ->controller(EnrollmentController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{enrollment}', 'show')->name('show');
            Route::get('/{enrollment}/print', 'print')->name('print');

            Route::middleware('permission:create_enrollments')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::middleware('permission:edit_enrollments')->group(function () {
                Route::get('/{enrollment}/edit', 'edit')->name('edit');
                Route::patch('/{enrollment}', 'update')->name('update');
                Route::post('/{enrollment}/activate', 'activate')->name('activate');
                Route::post('/{enrollment}/cancel', 'cancel')->name('cancel');
                Route::post('/{enrollment}/transfer', 'transfer')->name('transfer');
                Route::post('/{enrollment}/confirm-payment', 'confirmPayment')->name('confirm-payment');
            });
        });

    /*
|--------------------------------------------------------------------------
| Gestão Financeira
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Gestão Financeira
|--------------------------------------------------------------------------
*/

Route::middleware('permission:view_payments')
    ->prefix('payments')
    ->name('payments.')
    ->group(function () {
        
        // Rotas principais (GET)
        Route::get('/', [App\Http\Controllers\PaymentController::class, 'index'])->name('index');
        Route::get('/references', [App\Http\Controllers\PaymentController::class, 'references'])->name('references');
        Route::get('/reports', [App\Http\Controllers\PaymentController::class, 'reports'])->name('reports');
        Route::get('/overdue', [App\Http\Controllers\PaymentController::class, 'overdue'])->name('overdue');
        Route::get('/with-penalties', [App\Http\Controllers\PaymentController::class, 'withPenalties'])->name('with-penalties');
        Route::get('/{payment}', [App\Http\Controllers\PaymentController::class, 'show'])->name('show');
        Route::get('/print-bulk', [App\Http\Controllers\PaymentController::class, 'printBulk'])->name('print-bulk');

        // Rotas de criação
        Route::middleware('permission:create_payments')->group(function () {
            Route::get('/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\PaymentController::class, 'store'])->name('store');
        });

        // Rotas de processamento
        Route::middleware('permission:process_payments')->group(function () {
            Route::post('/{payment}/process', [App\Http\Controllers\PaymentController::class, 'process'])->name('process');
            Route::post('/{payment}/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('cancel');
            Route::post('/{payment}/apply-penalty', [App\Http\Controllers\PaymentController::class, 'applyPenalty'])->name('apply-penalty');
            Route::post('/{payment}/remove-penalty', [App\Http\Controllers\PaymentController::class, 'removePenalty'])->name('remove-penalty');
            Route::post('/apply-bulk-penalties', [App\Http\Controllers\PaymentController::class, 'applyBulkPenalties'])->name('apply-bulk-penalties');
        });

        // Rotas de geração de referências
        Route::middleware('permission:generate_payment_references')->group(function () {
            Route::post('/generate-reference', [App\Http\Controllers\PaymentController::class, 'generateReference'])->name('generate-reference');
            Route::get('/reference/{payment}/download', [App\Http\Controllers\PaymentController::class, 'downloadReference'])->name('download-reference');
        });
    });
    /*
    |--------------------------------------------------------------------------
    | Gestão de Presenças
    |--------------------------------------------------------------------------
    */

   /*  Route::middleware('permission:view_attendances')
        ->prefix('attendances')
        ->name('attendances.')
        ->controller(AttendanceController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/reports', 'reports')->name('reports');
            Route::get('/class/{class}/report', 'classReport')->name('class-report');
            Route::get('/student/{student}/report', 'studentReport')->name('student-report');

            Route::middleware('permission:mark_attendances')->group(function () {
                Route::get('/mark', 'mark')->name('mark');
                Route::post('/mark', 'storeMark')->name('store-mark');
                Route::get('/class/{class}/mark', 'markByClass')->name('mark-by-class');
                Route::post('/class/{class}/mark', 'storeMarkByClass')->name('store-mark-by-class');
            });
        }); */


        // ========== GESTÃO DE PRESENÇAS ==========
        Route::middleware('permission:view_attendances')->prefix('attendances')->name('attendances.')->group(function () {
            // Listagem
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            
            Route::get('/{attendance}', [AttendanceController::class, 'show'])->name('show');
            Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');

            // Marcar presenças
            Route::middleware('permission:mark_attendances')->group(function () {
                Route::get('/mark', [AttendanceController::class, 'mark'])->name('mark');
                Route::post('/mark', [AttendanceController::class, 'storeMark'])->name('store-mark');
                
                Route::get('/class/{class}/mark', [AttendanceController::class, 'markByClass'])->name('mark-by-class');
                Route::post('/class/{class}/mark', [AttendanceController::class, 'storeMarkByClass'])->name('store-mark-by-class');
                
                Route::patch('/{attendance}', [AttendanceController::class, 'update'])->name('update');
                Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
            });

            // Relatórios
            Route::get('/reports', [AttendanceController::class, 'reports'])->name('reports');
            Route::get('/class/{class}/report', [AttendanceController::class, 'classReport'])->name('class-report');
            Route::get('/student/{student}/report', [AttendanceController::class, 'studentReport'])->name('student-report');
            
            // Exportação
            Route::middleware('permission:export_reports')->group(function () {
                Route::get('/export', [AttendanceController::class, 'export'])->name('export');
            });
        });

        // ========== API ROUTES PARA ATTENDANCES ==========
        Route::prefix('api')->name('api.')->group(function () {
            // Obter alunos de uma turma (usado na interface de marcar presenças)
            Route::get('/classes/{class}/students', [AttendanceController::class, 'getClassStudents'])
                ->name('classes.students');
            
            // Estatísticas de presenças
            Route::get('/attendances/stats', [App\Http\Controllers\Api\AttendanceApiController::class, 'getStats'])
                ->name('attendances.stats');
        });

    /*
    |--------------------------------------------------------------------------
    | Gestão de Notas/Avaliações
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_grades')
        ->prefix('grades')
        ->name('grades.')
        ->controller(GradeController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/student/{student}/report-card', 'reportCard')->name('student-report-card');
            Route::get('/class/{class}/report', 'classReport')->name('class-report');
            Route::get('/class/{class}/grade-sheet', 'gradeSheet')->name('grade-sheet');

            Route::middleware('permission:create_grades')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/batch-create', 'batchCreate')->name('batch-create');
                Route::post('/batch-store', 'batchStore')->name('batch-store');
            });

            Route::middleware('permission:edit_grades')->group(function () {
                Route::get('/{grade}/edit', 'edit')->name('edit');
                Route::patch('/{grade}', 'update')->name('update');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Gestão de Eventos
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_events')
        ->prefix('events')
        ->name('events.')
        ->controller(EventController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/calendar', 'calendar')->name('calendar');
            Route::get('/{event}', 'show')->name('show');

            Route::middleware('permission:create_events')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::middleware('permission:edit_events')->group(function () {
                Route::get('/{event}/edit', 'edit')->name('edit');
                Route::patch('/{event}', 'update')->name('update');
                Route::post('/{event}/send-notification', 'sendNotification')->name('send-notification');
            });

            Route::middleware('permission:delete_events')->group(function () {
                Route::delete('/{event}', 'destroy')->name('destroy');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Comunicações e Notificações
    |--------------------------------------------------------------------------
    */

    Route::prefix('communications')->name('communications.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');

        Route::middleware('permission:send_notifications')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/send', 'send')->name('send');
        });

        Route::middleware('permission:send_bulk_notifications')->group(function () {
            Route::get('/bulk', 'bulk')->name('bulk');
            Route::post('/bulk-send', 'bulkSend')->name('bulk-send');
        });
    });

    // Notificações do Usuário
    Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'userNotifications')->name('index');
        Route::post('/{notification}/read', 'markAsRead')->name('mark-read');
        Route::post('/mark-all-read', 'markAllAsRead')->name('mark-all-read');
        Route::delete('/clear-all', 'clearAll')->name('clear-all');
    });

    /*
    |--------------------------------------------------------------------------
    | Relatórios
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_reports')
        ->prefix('reports')
        ->name('reports.')
        ->controller(ReportController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');

            // Relatórios Acadêmicos
            Route::get('/academic', 'academic')->name('academic');
            Route::get('/academic/performance', 'performance')->name('academic.performance');
            Route::get('/academic/attendance', 'attendanceReport')->name('academic.attendance');

            // Relatórios Financeiros
            Route::middleware('permission:view_financial_reports')->group(function () {
                Route::get('/financial', 'financial')->name('financial');
                Route::get('/financial/revenue', 'revenue')->name('financial.revenue');
                Route::get('/financial/defaulters', 'defaulters')->name('financial.defaulters');
            });

            // Exportações
            Route::middleware('permission:export_reports')->group(function () {
                Route::get('/export/students', 'exportStudents')->name('export.students');
                Route::get('/export/payments', 'exportPayments')->name('export.payments');
                Route::get('/export/grades', 'exportGrades')->name('export.grades');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | Portal dos Pais
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:parent')
        ->prefix('parent')
        ->name('parent.')
        ->controller(ParentPortalController::class)
        ->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');

            // Informações dos filhos
            Route::get('/children', 'children')->name('children');
            Route::get('/student/{student}', 'studentDetails')->name('student-details');
            Route::get('/student/{student}/grades', 'studentGrades')->name('student-grades');
            Route::get('/student/{student}/attendance', 'studentAttendance')->name('student-attendance');

            // Pagamentos
            Route::get('/payments', 'payments')->name('payments');
            Route::get('/student/{student}/payments', 'studentPayments')->name('student-payments');
            Route::get('/student/{student}/payment-references', 'paymentReferences')->name('payment-references');
            Route::post('/generate-payment-reference', 'generatePaymentReference')->name('generate-payment-reference');

            // Comunicações
            Route::get('/communications', 'communications')->name('communications');
            Route::post('/send-message', 'sendMessage')->name('send-message');
        });

    /*
    |--------------------------------------------------------------------------
    | Portal do Professor
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:teacher'])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [TeacherPortalController::class, 'dashboard'])->name('dashboard');

            // Turmas
            Route::prefix('classes')->name('classes.')->group(function () {
                Route::get('/', [TeacherPortalController::class, 'classes'])->name('index');
                Route::get('/{classId}', [TeacherPortalController::class, 'classDetails'])->name('detail');
                Route::get('/{classId}/students', [TeacherPortalController::class, 'classStudents'])->name('students');
            });

            // Presenças
            Route::prefix('attendance')->name('attendance.')->group(function () {
                // Presenças de hoje
                Route::get('/today/{classId}', [TeacherPortalController::class, 'todayAttendance'])->name('today');
                // Formulário de presenças para uma turma
                Route::get('/class/{classId}', [TeacherPortalController::class, 'Classattendance'])->name('class');
                // Salvar presenças
                Route::post('/class/{classId}', [TeacherPortalController::class, 'storeAttendance'])->name('store');
                // Marcar presença individual
                Route::post('/mark/{classId}', [TeacherPortalController::class, 'markAttendance'])->name('mark');
            });

            // Caderno de Notas (Gradebook)
            Route::get('/gradebook/{class}', [TeacherPortalController::class, 'gradebook'])->name('gradebook');

            // Notas/Avaliações
            Route::prefix('grades')->name('grades.')->group(function () {
                Route::get('/pending', [TeacherPortalController::class, 'pendingGrades'])->name('pending');
                Route::post('/store', [TeacherPortalController::class, 'storeGrade'])->name('store');
                Route::post('/batch-update', [TeacherPortalController::class, 'batchUpdateGrades'])->name('batch-update');
            });

            // Comunicações
            Route::prefix('communications')->name('communications.')->group(function () {
                Route::get('/', [TeacherPortalController::class, 'communications'])->name('index');
                Route::get('/create', [TeacherPortalController::class, 'createCommunication'])->name('create');
                Route::post('/send', [TeacherPortalController::class, 'sendCommunication'])->name('send');
            });

            // Licenças
            Route::prefix('leave-requests')->name('leave-requests.')->group(function () {
                Route::get('/', [TeacherPortalController::class, 'leaveRequests'])->name('index');
                Route::get('/create', [TeacherPortalController::class, 'createLeaveRequest'])->name('create');
                Route::post('/', [TeacherPortalController::class, 'storeLeaveRequest'])->name('store');
            });

            // Perfil
            Route::get('/profile', [TeacherPortalController::class, 'profile'])->name('profile');
            Route::post('/profile', [TeacherPortalController::class, 'updateProfile'])->name('update-profile');
        });

        
    /*
    |--------------------------------------------------------------------------
    | Administração
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage_users')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Gestão de Usuários
            Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{user}', 'show')->name('show');
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::patch('/{user}', 'update')->name('update');
                Route::delete('/{user}', 'destroy')->name('destroy');
                Route::post('/{user}/toggle-status', 'toggleStatus')->name('toggle-status');
            });

            // Configurações do Sistema
            Route::middleware('permission:manage_settings')
                ->prefix('settings')
                ->name('settings.')
                ->controller(SettingsController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::patch('/', 'update')->name('update');
                });

            // Backup
            Route::middleware('permission:backup_system')->group(function () {
                Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
                Route::post('/backup/create', [SettingsController::class, 'createBackup'])->name('backup.create');
            });

            // Logs
            Route::middleware('permission:view_logs')->group(function () {
                Route::get('/logs', [SettingsController::class, 'logs'])->name('logs');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | API Routes (Internas)
    |--------------------------------------------------------------------------
    */

    Route::prefix('api')->name('api.')->group(function () {

        // Contadores do Dashboard
        Route::get('/dashboard/counters', [DashboardController::class, 'counters'])->name('dashboard.counters');

        // Pesquisa ao vivo
        Route::get('/search/live', [SearchController::class, 'liveSearch'])->name('search.live');

        // Notificações para pais
        Route::get('/parent/notifications-check', function () {
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

        // Log de erros JavaScript
        Route::post('/log-js-error', function (Illuminate\Http\Request $request) {
            Log::error('JavaScript Error', $request->all());
            return response()->json(['status' => 'logged']);
        })->name('log-js-error');
    });
});

/*
|--------------------------------------------------------------------------
| Webhooks (Pagamentos)
|--------------------------------------------------------------------------
*/

Route::prefix('webhooks')->name('webhooks.')->group(function () {

    // MPesa
    Route::post('/mpesa', function (Illuminate\Http\Request $request) {
        Log::info('MPesa Webhook', $request->all());
        // TODO: Implementar lógica de processamento MPesa
        return response()->json(['status' => 'received']);
    })->name('mpesa');

    // eMola
    Route::post('/emola', function (Illuminate\Http\Request $request) {
        Log::info('eMola Webhook', $request->all());
        // TODO: Implementar lógica de processamento eMola
        return response()->json(['status' => 'received']);
    })->name('emola');

    // Multicaixa
    Route::post('/multicaixa', function (Illuminate\Http\Request $request) {
        Log::info('Multicaixa Webhook', $request->all());
        // TODO: Implementar lógica de processamento Multicaixa
        return response()->json(['status' => 'received']);
    })->name('multicaixa');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return view('errors.404');
});
