<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    /**
     * Listagem de pagamentos com filtros
     */
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'enrollment.class']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('enrollment', fn($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('student_number', 'like', "%{$search}%");
                    });
            });
        }

        // Ordenação
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $payments = $query->paginate(15)->withQueryString();

        // Estatísticas
        $stats = [
            'total' => Payment::sum('amount'),
            'paid' => Payment::where('status', 'paid')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->sum('amount'),
            'overdue' => Payment::where('status', 'overdue')
                ->orWhere(fn($q) => $q->where('status', 'pending')->where('due_date', '<', now()))
                ->sum('amount'),
            'count_pending' => Payment::where('status', 'pending')->count(),
            'count_overdue' => Payment::where('status', 'overdue')
                ->orWhere(fn($q) => $q->where('status', 'pending')->where('due_date', '<', now()))
                ->count(),
        ];

        $classes = ClassRoom::active()->orderBy('name')->get();
        $months = $this->getMonths();

        return view('payments.index', compact('payments', 'stats', 'classes', 'months'));
    }

    /**
     * Formulário de criação
     */
    public function create(Request $request)
    {
        $students = Student::active()
            ->with('currentEnrollment')
            ->whereHas('currentEnrollment')
            ->orderBy('first_name')
            ->get();

        $selectedStudent = null;
        if ($request->filled('student_id')) {
            $selectedStudent = Student::with('currentEnrollment.class')->find($request->student_id);
        }

        $types = $this->getPaymentTypes();
        $months = $this->getMonths();

        return view('payments.create', compact('students', 'selectedStudent', 'types', 'months'));
    }

    /**
     * Armazenar novo pagamento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:matricula,mensalidade,material,uniforme,outro',
            'amount' => 'required|numeric|min:0',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
            'due_date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::with('currentEnrollment')->findOrFail($validated['student_id']);

            // Gerar referência única
            $reference = $this->generateUniqueReference($student->id, $validated['year']);

            $payment = Payment::create([
                'reference_number' => $reference,
                'student_id' => $student->id,
                'enrollment_id' => $student->currentEnrollment?->id,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'month' => $validated['month'],
                'year' => $validated['year'],
                'due_date' => $validated['due_date'],
                'status' => 'pending',
                'discount' => $validated['discount'] ?? 0,
                'notes' => $validated['notes'],
            ]);

            DB::commit();

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Pagamento criado com sucesso! Referência: ' . $reference);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar pagamento: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao criar pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Exibir detalhes do pagamento
     */
    /**
     * Retornar dados do pagamento em JSON para modais
     */
    public function show(Payment $payment)
    {
        $payment->load(['student', 'enrollment.class']);

        // Se a requisição é AJAX/JSON, retorna JSON
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'id' => $payment->id,
                'reference_number' => $payment->reference_number,
                'amount' => $payment->amount,
                'penalty' => $payment->penalty,
                'penalty_percentage' => $payment->penalty_percentage,
                'total_amount' => $payment->total_amount,
                'due_date' => $payment->due_date,
                'status' => $payment->status,
                'days_late' => $payment->days_late,
                'suggested_penalty_percentage' => $payment->suggested_penalty_percentage,
                'student' => [
                    'full_name' => $payment->student->full_name,
                    'student_number' => $payment->student->student_number,
                ],
                'enrollment' => [
                    'class' => [
                        'name' => $payment->enrollment?->class?->name,
                    ]
                ]
            ]);
        }

        // Se não for AJAX, retorna a view normal
        return view('payments.show', compact('payment'));
    }

    /**
     * Processar/Confirmar pagamento
     */
    public function process(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,mpesa,emola,bank,multicaixa',
            'transaction_id' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $payment->update([
                'status' => 'paid',
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'payment_date' => $validated['payment_date'],
                'notes' => $payment->notes . "\n" . ($validated['notes'] ?? ''),
            ]);

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Pagamento processado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento: ' . $e->getMessage());
            return back()->with('error', 'Erro ao processar pagamento.');
        }
    }

    /**
     * Cancelar pagamento
     */
    public function cancel(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if ($payment->status === 'paid') {
            return back()->with('error', 'Não é possível cancelar um pagamento já processado.');
        }

        $payment->update([
            'status' => 'cancelled',
            'notes' => $payment->notes . "\nCancelado: " . $request->reason,
        ]);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Pagamento cancelado com sucesso.');
    }

    /**
     * Pagamentos em atraso
     */
    public function overdue(Request $request)
    {
        $payments = Payment::with(['student', 'enrollment.class'])
            ->where(function ($q) {
                $q->where('status', 'overdue')
                    ->orWhere(function ($sq) {
                        $sq->where('status', 'pending')
                            ->where('due_date', '<', now());
                    });
            })
            ->orderBy('due_date', 'asc')
            ->paginate(20);

        // Atualizar status para overdue
        Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        return view('payments.overdue', compact('payments'));
    }

    /**
     * Página de referências de pagamento
     */
    public function references(Request $request)
    {
        $query = Payment::with(['student', 'enrollment.class'])
            ->whereIn('status', ['pending', 'overdue']);

        if ($request->filled('class_id')) {
            $query->whereHas('enrollment', fn($q) => $q->where('class_id', $request->class_id));
        }

        $references = $query->orderBy('due_date')->paginate(20);
        $classes = ClassRoom::active()->orderBy('name')->get();

        return view('payments.references', compact('references', 'classes'));
    }

    /**
     * Gerar referência de pagamento
     */
    public function generateReference(Request $request)
    {
        // Verificar se é geração em massa
        if ($request->has('bulk')) {
            return $this->generateBulkReferences($request);
        }

        // Geração individual
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:matricula,mensalidade,material,uniforme,outro',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'required|integer',
        ]);

        $student = Student::with('currentEnrollment')->findOrFail($validated['student_id']);

        // Verificar se já existe referência para este mês/tipo
        $exists = Payment::where('student_id', $student->id)
            ->where('type', $validated['type'])
            ->where('year', $validated['year'])
            ->when($validated['month'], fn($q) => $q->where('month', $validated['month']))
            ->exists();

        if ($exists) {
            return back()->with('error', 'Já existe uma referência para este período.');
        }

        $reference = $this->generateUniqueReference($student->id, $validated['year']);

        $amount = $validated['type'] === 'mensalidade'
            ? ($student->currentEnrollment?->monthly_fee ?? $student->monthly_fee ?? 500)
            : 500;

        $payment = Payment::create([
            'reference_number' => $reference,
            'student_id' => $student->id,
            'enrollment_id' => $student->currentEnrollment?->id,
            'type' => $validated['type'],
            'amount' => $amount,
            'month' => $validated['month'],
            'year' => $validated['year'],
            'due_date' => Carbon::create($validated['year'], $validated['month'] ?? 1, 10),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Referência gerada: ' . $reference);
    }

    /**
     * Geração em massa de referências
     */
    private function generateBulkReferences(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'bulk_month' => 'required|integer|min:1|max:12',
            'bulk_year' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $students = Student::whereHas('currentEnrollment', function ($q) use ($validated) {
                $q->where('class_id', $validated['class_id'])
                    ->where('status', 'active');
            })->get();

            $generated = 0;
            $errors = [];

            foreach ($students as $student) {
                $exists = Payment::where('student_id', $student->id)
                    ->where('type', 'mensalidade')
                    ->where('year', $validated['bulk_year'])
                    ->where('month', $validated['bulk_month'])
                    ->exists();

                if (!$exists) {
                    $reference = $this->generateUniqueReference($student->id, $validated['bulk_year']);

                    Payment::create([
                        'reference_number' => $reference,
                        'student_id' => $student->id,
                        'enrollment_id' => $student->currentEnrollment?->id,
                        'type' => 'mensalidade',
                        'amount' => $student->currentEnrollment?->monthly_fee ?? 500,
                        'month' => $validated['bulk_month'],
                        'year' => $validated['bulk_year'],
                        'due_date' => Carbon::create($validated['bulk_year'], $validated['bulk_month'], 10),
                        'status' => 'pending',
                    ]);

                    $generated++;
                } else {
                    $errors[] = "{$student->full_name} já tem referência para este mês";
                }
            }

            DB::commit();

            $message = "{$generated} referências geradas com sucesso!";
            if (!empty($errors)) {
                $message .= " Erros: " . implode(', ', array_slice($errors, 0, 3));
            }

            return redirect()
                ->route('payments.references')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro na geração em massa: ' . $e->getMessage());
            return back()->with('error', 'Erro na geração em massa: ' . $e->getMessage());
        }
    }


    /**
     * Download da referência
     */
    public function downloadReference(Payment $payment)
    {
        $payment->load(['student', 'enrollment.class']);
        return view('payments.print-reference', compact('payment'));
    }

    /**
     * Impressão em massa
     */
    public function printBulk(Request $request)
    {
        $ids = explode(',', $request->ids);

        $references = Payment::with(['student', 'enrollment.class'])
            ->whereIn('id', $ids)
            ->get();

        return view('payments.print-bulk', compact('references'));
    }

    /**
     * Relatórios financeiros
     */
    public function reports(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');

        // Receita mensal
        $monthlyRevenue = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->where('year', $year)
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->pluck('total', 'month')
            ->toArray();

        // Receita por tipo
        $revenueByType = Payment::selectRaw('type, SUM(amount) as total')
            ->where('status', 'paid')
            ->where('year', $year)
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        // Inadimplência por turma
        $defaultersByClass = Payment::selectRaw('classes.name, COUNT(*) as count, SUM(payments.amount) as total')
            ->join('enrollments', 'payments.enrollment_id', '=', 'enrollments.id')
            ->join('classes', 'enrollments.class_id', '=', 'classes.id')
            ->whereIn('payments.status', ['pending', 'overdue'])
            ->where('payments.due_date', '<', now())
            ->groupBy('classes.id', 'classes.name')
            ->get();

        $stats = [
            'total_year' => Payment::where('status', 'paid')->where('year', $year)->sum('amount'),
            'total_pending' => Payment::whereIn('status', ['pending', 'overdue'])->sum('amount'),
            'total_students_debt' => Payment::whereIn('status', ['pending', 'overdue'])
                ->distinct('student_id')->count('student_id'),
        ];

        return view('payments.reports', compact(
            'monthlyRevenue',
            'revenueByType',
            'defaultersByClass',
            'stats',
            'year'
        ));
    }

    /**
     * Aplicar multa manualmente
     */
    public function applyPenalty(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'penalty_percentage' => 'required|numeric|min:0|max:100',
            'reason' => 'required|string|max:500',
        ]);

        try {
            $penaltyAmount = ($payment->amount * $validated['penalty_percentage']) / 100;

            $payment->update([
                'penalty_percentage' => $validated['penalty_percentage'],
                'penalty' => $penaltyAmount,
                'penalty_applied_at' => now(),
                'notes' => $payment->notes . "\nMulta aplicada: " . $validated['penalty_percentage'] . "% - " . $validated['reason'],
            ]);

            activity()
                ->performedOn($payment)
                ->log("Multa manual aplicada: {$validated['penalty_percentage']}% - Motivo: {$validated['reason']}");

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', "Multa de {$validated['penalty_percentage']}% aplicada com sucesso!");

        } catch (\Exception $e) {
            Log::error('Erro ao aplicar multa: ' . $e->getMessage());
            return back()->with('error', 'Erro ao aplicar multa.');
        }
    }

    /**
     * Remover multa
     */
    public function removePenalty(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'penalty_percentage' => 0,
            'penalty' => 0,
            'penalty_applied_at' => null,
            'notes' => $payment->notes . "\nMulta removida: " . $request->reason,
        ]);

        activity()
            ->performedOn($payment)
            ->log("Multa removida - Motivo: {$request->reason}");

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Multa removida com sucesso!');
    }

    /**
     * Pagamentos com multa
     */
    public function withPenalties(Request $request)
    {
        $query = Payment::with(['student', 'enrollment.class'])
            ->withPenalty()
            ->orderBy('penalty', 'desc');

        if ($request->filled('class_id')) {
            $query->whereHas('enrollment', fn($q) => $q->where('class_id', $request->class_id));
        }

        $payments = $query->paginate(20);
        $classes = \App\Models\ClassRoom::active()->orderBy('name')->get();

        $totalPenalties = Payment::withPenalty()->sum('penalty');

        return view('payments.with-penalties', compact('payments', 'classes', 'totalPenalties'));
    }

    /**
     * Aplicar multa em lote
     */
    public function applyBulkPenalties(Request $request)
    {
        $validated = $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id',
            'penalty_percentage' => 'required|numeric|min:0|max:100',
            'reason' => 'required|string|max:500',
        ]);

        $appliedCount = 0;

        foreach ($validated['payment_ids'] as $paymentId) {
            $payment = Payment::find($paymentId);

            if ($payment && $payment->status === 'pending') {
                $penaltyAmount = ($payment->amount * $validated['penalty_percentage']) / 100;

                $payment->update([
                    'penalty_percentage' => $validated['penalty_percentage'],
                    'penalty' => $penaltyAmount,
                    'penalty_applied_at' => now(),
                    'notes' => $payment->notes . "\nMulta em lote: " . $validated['penalty_percentage'] . "% - " . $validated['reason'],
                ]);

                activity()
                    ->performedOn($payment)
                    ->log("Multa em lote aplicada: {$validated['penalty_percentage']}%");

                $appliedCount++;
            }
        }

        return redirect()
            ->route('payments.with-penalties')
            ->with('success', "Multa de {$validated['penalty_percentage']}% aplicada em {$appliedCount} pagamentos!");
    }

    // ========== MÉTODOS AUXILIARES ==========
    private function generateUniqueReference($studentId, $year)
    {
        do {
            $reference = 'VIS' . str_pad($studentId, 4, '0', STR_PAD_LEFT) . substr($year, -2);
            $random = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            $fullRef = $reference . $random;
        } while (Payment::where('reference_number', $fullRef)->exists());

        return $fullRef;
    }

    private function getMonths()
    {
        return [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];
    }
    // ========== WEBHOOKS ==========
    public function webhookMpesa(Request $request)
    {
        $success = $this->paymentService->processWebhook('mpesa', $request->all());
        return response()->json(['status' => $success ? 'processed' : 'failed']);
    }

    public function webhookEmola(Request $request)
    {
        $success = $this->paymentService->processWebhook('emola', $request->all());
        return response()->json(['status' => $success ? 'processed' : 'failed']);
    }

    public function webhookMulticaixa(Request $request)
    {
        $success = $this->paymentService->processWebhook('multicaixa', $request->all());
        return response()->json(['status' => $success ? 'processed' : 'failed']);
    }

    // ========== PAGAMENTO ONLINE ==========
    public function initiateOnlinePayment(Request $request, Payment $payment)
    {
        $request->validate([
            'phone_number' => 'required|string|min:9|max:15',
            'provider' => 'required|in:mpesa,emola,mkesh',
        ]);

        try {
            $result = $this->paymentService->initiateMobilePayment(
                $payment,
                $request->phone_number,
                $request->provider
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
