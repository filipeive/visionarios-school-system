<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentService
{
    /**
     * Gerar referência única de pagamento
     */
    public function generateReference(int $studentId, int $year): string
    {
        do {
            $base = 'VIS' . str_pad($studentId, 4, '0', STR_PAD_LEFT) . substr($year, -2);
            $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            $reference = $base . $random;
        } while (Payment::where('reference_number', $reference)->exists());

        return $reference;
    }

    /**
     * Criar novo pagamento
     */
    public function createPayment(array $data): Payment
    {
        $student = Student::with('currentEnrollment')->findOrFail($data['student_id']);

        return Payment::create([
            'reference_number' => $this->generateReference($student->id, $data['year']),
            'student_id' => $student->id,
            'enrollment_id' => $student->currentEnrollment?->id,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'month' => $data['month'] ?? null,
            'year' => $data['year'],
            'due_date' => $data['due_date'],
            'status' => 'pending',
            'discount' => $data['discount'] ?? 0,
            'penalty' => $data['penalty'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Processar pagamento
     */
    public function processPayment(Payment $payment, array $data): Payment
    {
        $payment->update([
            'status' => 'paid',
            'payment_method' => $data['payment_method'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'payment_date' => $data['payment_date'],
            'notes' => $payment->notes . "\n" . ($data['notes'] ?? ''),
        ]);

        return $payment->fresh();
    }

    /**
     * Cancelar pagamento
     */
    public function cancelPayment(Payment $payment, string $reason): Payment
    {
        if ($payment->status === 'paid') {
            throw new \Exception('Não é possível cancelar um pagamento já processado.');
        }

        $payment->update([
            'status' => 'cancelled',
            'notes' => $payment->notes . "\nCancelado: " . $reason,
        ]);

        return $payment->fresh();
    }

    /**
     * Gerar mensalidades para toda a turma
     */
    public function generateBulkMonthlyFees(int $classId, int $month, int $year): array
    {
        $enrollments = Enrollment::with('student')
            ->where('class_id', $classId)
            ->where('status', 'active')
            ->get();

        $created = [];
        $skipped = [];

        foreach ($enrollments as $enrollment) {
            // Verificar se já existe
            $exists = Payment::where('student_id', $enrollment->student_id)
                ->where('type', 'mensalidade')
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                $skipped[] = $enrollment->student->full_name;
                continue;
            }

            $payment = $this->createPayment([
                'student_id' => $enrollment->student_id,
                'type' => 'mensalidade',
                'amount' => $enrollment->monthly_fee,
                'month' => $month,
                'year' => $year,
                'due_date' => Carbon::create($year, $month, $enrollment->payment_day ?? 10),
            ]);

            $created[] = $payment;
        }

        return [
            'created' => $created,
            'skipped' => $skipped,
            'total_created' => count($created),
            'total_skipped' => count($skipped),
        ];
    }

    /**
     * Atualizar status de pagamentos em atraso
     */
    public function updateOverdueStatus(): int
    {
        return Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);
    }

    /**
     * Obter estatísticas financeiras
     */
    public function getStatistics(int $year = null): array
    {
        $year = $year ?? date('Y');

        return [
            'total_year' => Payment::where('status', 'paid')
                ->where('year', $year)
                ->sum('amount'),
            
            'total_pending' => Payment::whereIn('status', ['pending', 'overdue'])
                ->sum('amount'),
            
            'total_overdue' => Payment::where('status', 'overdue')
                ->orWhere(fn($q) => $q->where('status', 'pending')->where('due_date', '<', now()))
                ->sum('amount'),
            
            'count_pending' => Payment::where('status', 'pending')->count(),
            
            'count_overdue' => Payment::where('status', 'overdue')
                ->orWhere(fn($q) => $q->where('status', 'pending')->where('due_date', '<', now()))
                ->count(),
            
            'students_with_debt' => Payment::whereIn('status', ['pending', 'overdue'])
                ->distinct('student_id')
                ->count('student_id'),
        ];
    }

    /**
     * Obter receita mensal
     */
    public function getMonthlyRevenue(int $year): array
    {
        return Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereYear('payment_date', $year)
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->pluck('total', 'month')
            ->toArray();
    }

    /**
     * Obter receita por tipo
     */
    public function getRevenueByType(int $year): array
    {
        return Payment::selectRaw('type, SUM(amount) as total')
            ->where('status', 'paid')
            ->where('year', $year)
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }

    /**
     * Obter inadimplência por turma
     */
    public function getDefaultersByClass()
    {
        return Payment::selectRaw('classes.name, COUNT(*) as count, SUM(payments.amount) as total')
            ->join('enrollments', 'payments.enrollment_id', '=', 'enrollments.id')
            ->join('classes', 'enrollments.class_id', '=', 'classes.id')
            ->whereIn('payments.status', ['pending', 'overdue'])
            ->where('payments.due_date', '<', now())
            ->groupBy('classes.id', 'classes.name')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Obter histórico de pagamentos do aluno
     */
    public function getStudentPaymentHistory(int $studentId, int $limit = 12)
    {
        return Payment::where('student_id', $studentId)
            ->orderByDesc('due_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Verificar se aluno tem dívidas
     */
    public function studentHasDebt(int $studentId): bool
    {
        return Payment::where('student_id', $studentId)
            ->whereIn('status', ['pending', 'overdue'])
            ->where('due_date', '<', now())
            ->exists();
    }

    /**
     * Calcular total de dívida do aluno
     */
    public function getStudentTotalDebt(int $studentId): float
    {
        return Payment::where('student_id', $studentId)
            ->whereIn('status', ['pending', 'overdue'])
            ->sum(DB::raw('amount + penalty - discount'));
    }
}