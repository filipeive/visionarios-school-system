<?php
// app/Http/Controllers/StudentController.php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $this->authorize('view_students');

        $query = Student::with(['currentEnrollment.class', 'parent']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('currentEnrollment', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $students = $query->latest()->paginate(20);
        $classes = ClassRoom::active()->get();
        $totalStudents = Student::count();
        $activeStudents = Student::active()->count();

        return view('students.index', compact('students', 'classes', 'totalStudents', 'activeStudents'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $this->authorize('create_students');

        $classes = ClassRoom::active()->get();
        $parents = ParentModel::with('user')->get();

        return view('students.create', compact('classes', 'parents'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $this->authorize('create_students');
        //dd($request->all());
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birthdate' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'parent_id' => 'required|exists:parents,user_id',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'medical_info' => 'nullable|string|max:1000',
            'monthly_fee' => 'required|numeric|min:0',
            'has_special_needs' => 'boolean',
            'special_needs_description' => 'nullable|string|max:1000',
            'observations' => 'nullable|string|max:1000',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Gerar número do estudante
            $studentNumber = $this->generateStudentNumber();

            $studentData = $request->except(['passport_photo', 'medical_info']);
            $studentData['student_number'] = $studentNumber;
            $studentData['registration_date'] = now();
            $studentData['medical_certificate'] = $request->medical_info; // Ajuste para o campo correto

            // Upload da foto do passaporte
            if ($request->hasFile('passport_photo')) {
                $photoPath = $request->file('passport_photo')->store('students/photos', 'public');
                $studentData['passport_photo'] = $photoPath;
            }

            $student = Student::create($studentData);

            // Se foi selecionada uma turma, criar matrícula
            if ($request->filled('class_id')) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'class_id' => $request->class_id,
                    'school_year' => now()->year,
                    'enrollment_date' => now(),
                    'monthly_fee' => $request->monthly_fee,
                    'status' => 'active',
                ]);
            }

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Aluno criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao criar aluno: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $this->authorize('view_students');

        $student->load([
            'enrollments.class.teacher',
            'parent.user',
            'payments' => function($query) {
                $query->latest()->take(10);
            },
            'attendances' => function($query) {
                $query->latest()->take(20);
            },
            'grades.subject',
            'observations'
        ]);

        $currentEnrollment = $student->currentEnrollment;
        $attendanceStats = $this->getAttendanceStats($student);

        return view('students.show', compact('student', 'currentEnrollment', 'attendanceStats'));
    }

    /**
     * Show the form for editing the student.
     */
    public function edit(Student $student)
    {
        $this->authorize('edit_students');

        $classes = ClassRoom::active()->get();
        $parents = ParentModel::with('user')->get();
        $currentEnrollment = $student->currentEnrollment;

        return view('students.edit', compact('student', 'classes', 'parents', 'currentEnrollment'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $this->authorize('edit_students');

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birthdate' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'parent_id' => 'required|exists:parents,user_id',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'medical_info' => 'nullable|string|max:1000',
            'monthly_fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,transferred,graduated',
            'has_special_needs' => 'boolean',
            'special_needs_description' => 'nullable|string|max:1000',
            'observations' => 'nullable|string|max:1000',
            'passport_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $studentData = $request->except(['passport_photo', 'medical_info']);
            $studentData['medical_certificate'] = $request->medical_info;

            // Upload da nova foto
            if ($request->hasFile('passport_photo')) {
                // Remover foto antiga se existir
                if ($student->passport_photo) {
                    Storage::disk('public')->delete($student->passport_photo);
                }
                
                $photoPath = $request->file('passport_photo')->store('students/photos', 'public');
                $studentData['passport_photo'] = $photoPath;
            }

            $student->update($studentData);

            // Atualizar matrícula atual se a turma foi alterada
            if ($request->filled('class_id')) {
                $currentEnrollment = $student->currentEnrollment;
                
                if ($currentEnrollment) {
                    if ($currentEnrollment->class_id != $request->class_id) {
                        // Transferir aluno para nova turma
                        $currentEnrollment->update(['status' => 'transferred']);
                        
                        Enrollment::create([
                            'student_id' => $student->id,
                            'class_id' => $request->class_id,
                            'school_year' => now()->year,
                            'enrollment_date' => now(),
                            'monthly_fee' => $request->monthly_fee,
                            'status' => 'active',
                        ]);
                    } else {
                        // Atualizar mensalidade na matrícula atual
                        $currentEnrollment->update(['monthly_fee' => $request->monthly_fee]);
                    }
                } else {
                    // Criar nova matrícula se não existir
                    Enrollment::create([
                        'student_id' => $student->id,
                        'class_id' => $request->class_id,
                        'school_year' => now()->year,
                        'enrollment_date' => now(),
                        'monthly_fee' => $request->monthly_fee,
                        'status' => 'active',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('students.show', $student)
                ->with('success', 'Aluno atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar aluno: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete_students');

        try {
            DB::beginTransaction();

            // Verificar se o aluno tem registros associados
            if ($student->payments()->exists() || $student->attendances()->exists() || $student->grades()->exists()) {
                return back()->with('error', 'Não é possível excluir o aluno pois existem registros associados. Altere o status para "inativo" instead.');
            }

            // Remover foto se existir
            if ($student->passport_photo) {
                Storage::disk('public')->delete($student->passport_photo);
            }

            // Excluir matrículas
            $student->enrollments()->delete();

            // Excluir aluno
            $student->delete();

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Aluno excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao excluir aluno: ' . $e->getMessage());
        }
    }

    /**
     * Upload student photo.
     */
    public function uploadPhoto(Request $request, Student $student)
    {
        $this->authorize('edit_students');

        $request->validate([
            'passport_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Remover foto antiga se existir
            if ($student->passport_photo) {
                Storage::disk('public')->delete($student->passport_photo);
            }

            // Upload da nova foto
            $photoPath = $request->file('passport_photo')->store('students/photos', 'public');
            $student->update(['passport_photo' => $photoPath]);

            return response()->json([
                'success' => true,
                'photo_url' => Storage::url($photoPath),
                'message' => 'Foto atualizada com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show student grades.
     */
    public function grades(Student $student)
    {
        $this->authorize('view_students');

        $student->load(['grades.subject', 'currentEnrollment.class']);
        $currentEnrollment = $student->currentEnrollment;

        return view('students.grades', compact('student', 'currentEnrollment'));
    }

    /**
     * Show student attendance.
     */
    
    public function attendance(Student $student)
    {
        $this->authorize('view_students');

        // Carregar atendances com relacionamentos
        $student->load(['attendances' => function($query) {
            $query->with(['class', 'markedBy'])
                ->latest()
                ->take(50);
        }]);

        $attendanceStats = $this->getAttendanceStats($student);

        return view('students.attendance', compact('student', 'attendanceStats'));
    }

    /**
     * Show student payments.
     */
    public function payments(Student $student)
    {
        $this->authorize('view_students');

        $student->load(['payments' => function($query) {
            $query->latest();
        }]);

        $paymentStats = [
            'total_paid' => $student->payments()->where('status', 'paid')->sum('amount'),
            'total_pending' => $student->payments()->where('status', 'pending')->sum('amount'),
            'total_overdue' => $student->payments()->where('status', 'overdue')->sum('amount'),
        ];

        return view('students.payments', compact('student', 'paymentStats'));
    }

    /**
     * Generate unique student number.
     */
    private function generateStudentNumber()
    {
        $year = date('Y');
        $lastStudent = Student::where('student_number', 'like', "VIS{$year}%")
            ->orderBy('student_number', 'desc')
            ->first();

        if ($lastStudent) {
            $lastNumber = intval(substr($lastStudent->student_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "VIS{$year}{$newNumber}";
    }

    /**
     * Get student attendance statistics.
     */
    private function getAttendanceStats(Student $student)
    {
        $totalClasses = $student->attendances()->count();
        $present = $student->attendances()->where('status', 'present')->count();
        $absent = $student->attendances()->where('status', 'absent')->count();
        $late = $student->attendances()->where('status', 'late')->count();

        $attendanceRate = $totalClasses > 0 ? round(($present / $totalClasses) * 100, 2) : 0;

        return [
            'total_classes' => $totalClasses,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'attendance_rate' => $attendanceRate,
        ];
    }
}