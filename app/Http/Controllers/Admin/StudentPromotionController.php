<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentPromotionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manage_enrollments');

        $classes = ClassRoom::currentYear()->active()->orderBy('name')->get();
        $classId = $request->get('class_id');
        $filter = $request->get('filter', 'all');

        $students = [];
        if ($classId) {
            $query = Student::whereHas('enrollments', function ($q) use ($classId) {
                $q->where('class_id', $classId)
                    ->where('status', 'active');
            })->with(['grades']);

            // Aplicar filtros
            if ($filter === 'eligible') {
                $query->where('status', 'active');
            } elseif ($filter === 'not_eligible') {
                $query->whereIn('status', ['inactive', 'transferred']);
            } elseif ($filter === 'pending') {
                $query->where('status', 'pending_renewal');
            }

            $students = $query->get();

            // Calcular médias e elegibilidade
            foreach ($students as $student) {
                $mf = Grade::calculateMF($student->id, null, $classId, current_school_year());
                $student->calculated_mf = $mf;
                $student->is_eligible = $mf !== null && $mf >= 10;
            }

            // Ordenar por elegibilidade
            if ($request->get('sort') === 'mf') {
                $students = $students->sortByDesc('calculated_mf')->values();
            }
        }

        return view('admin.promotion.index', compact('classes', 'students', 'classId', 'filter'));
    }

    public function promote(PromotionRequest $request)
    {
        $this->authorize('manage_enrollments');

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'action' => 'required|in:promote,retain',
            'target_class_id' => 'required_if:action,promote|nullable|exists:classes,id',
        ]);

        try {
            DB::beginTransaction();

            $promotedCount = 0;
            $retainedCount = 0;

            foreach ($request->student_ids as $studentId) {
                $student = Student::findOrFail($studentId);
                $currentEnrollment = $student->currentEnrollment;

                if ($request->action === 'promote') {
                    // Marcar matrícula atual como transferida
                    if ($currentEnrollment && $currentEnrollment->status === 'active') {
                        $currentEnrollment->update([
                            'status' => 'transferred',
                            'cancellation_date' => now(),
                        ]);
                    }

                    // Atualizar status do aluno
                    $student->update(['status' => 'pending_renewal']);

                    // Se tiver target_class, criar nova matrícula pendente
                    if ($request->filled('target_class_id')) {
                        Enrollment::create([
                            'student_id' => $student->id,
                            'class_id' => $request->target_class_id,
                            'school_year' => current_school_year() + 1,
                            'status' => 'pending',
                            'enrollment_date' => now(),
                            'monthly_fee' => $currentEnrollment->monthly_fee ?? 0,
                        ]);
                    }

                    $promotedCount++;

                    Log::info('Aluno promovido', [
                        'student_id' => $student->id,
                        'target_class' => $request->target_class_id,
                        'admin_id' => auth()->id(),
                    ]);
                } else {
                    // Reprovação - manter na mesma turma
                    $student->update(['status' => 'pending_renewal']);
                    $retainedCount++;

                    Log::info('Aluno retido', [
                        'student_id' => $student->id,
                        'class_id' => $currentEnrollment?->class_id,
                        'admin_id' => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            $message = 'Processo concluído! ';
            $message .= "{$promotedCount} alunos promovidos, ";
            $message .= "{$retainedCount} alunos retidos.";

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar promoção: '.$e->getMessage());

            return redirect()->back()->with('error', 'Erro ao processar. Contacte o administrador.');
        }
    }

    /**
     * Calcular estatísticas de promoção para uma turma
     */
    public function getStatistics(Request $request)
    {
        $classId = $request->get('class_id');

        if (! $classId) {
            return response()->json(['error' => 'Turma não especificada'], 400);
        }

        $students = Student::whereHas('enrollments', function ($q) use ($classId) {
            $q->where('class_id', $classId)->where('status', 'active');
        })->get();

        $stats = [
            'total' => $students->count(),
            'eligible' => 0,
            'not_eligible' => 0,
            'average_mf' => 0,
        ];

        $mfs = [];
        foreach ($students as $student) {
            $mf = Grade::calculateMF($student->id, null, $classId, current_school_year());
            if ($mf !== null) {
                $mfs[] = $mf;
                if ($mf >= 10) {
                    $stats['eligible']++;
                } else {
                    $stats['not_eligible']++;
                }
            }
        }

        if (! empty($mfs)) {
            $stats['average_mf'] = round(array_sum($mfs) / count($mfs), 1);
        }

        return response()->json($stats);
    }
}
