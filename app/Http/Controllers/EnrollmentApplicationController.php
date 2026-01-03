<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentApplication;
use App\Models\EnrollmentDocument;
use App\Models\FeeType;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EnrollmentApplicationController extends Controller
{
    /**
     * Show the public pre-enrollment form.
     */
    public function create()
    {
        $classes = ClassRoom::all();
        $academicYear = 2026; // Default for next year
        $fees = FeeType::where('academic_year', $academicYear)->get();

        return view('enrollments.applications.create', compact('classes', 'academicYear', 'fees'));
    }

    /**
     * Store a new pre-enrollment application.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student.first_name' => 'required|string|max:255',
            'student.last_name' => 'required|string|max:255',
            'student.birth_date' => 'required|date',
            'student.gender' => 'required|in:male,female',
            'student.grade_level' => 'required|string',
            'parent.first_name' => 'required|string|max:255',
            'parent.last_name' => 'required|string|max:255',
            'parent.phone' => 'required|string|max:20',
            'parent.email' => 'nullable|email|max:255',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $academicYear = 2026;

            // Calculate total fees
            $gradeLevel = $request->input('student.grade_level');
            $fees = FeeType::where('academic_year', $academicYear)
                ->where(function ($query) use ($gradeLevel) {
                    $query->where('grade_level', $gradeLevel)
                        ->orWhereNull('grade_level');
                })
                ->where('is_mandatory', true)
                ->get();

            $totalAmount = $fees->sum('amount');

            $application = EnrollmentApplication::create([
                'type' => 'NEW',
                'status' => 'PENDING',
                'student_data' => $request->student,
                'parent_data' => $request->parent,
                'academic_year' => $academicYear,
                'total_amount' => $totalAmount,
                'submitted_at' => now(),
            ]);

            // Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $type => $file) {
                    $path = $file->store('enrollment_documents/' . $application->id, 'public');
                    EnrollmentDocument::create([
                        'application_id' => $application->id,
                        'document_type' => $type,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('public.pre-enrollment.success', $application->id)
                ->with('success', 'Sua pré-inscrição foi enviada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao enviar pré-inscrição: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the renewal form for parents.
     */
    public function renewalCreate(Student $student)
    {
        // Verificar se o aluno pertence ao pai logado
        $parent = \App\Models\ParentModel::where('user_id', auth()->id())->firstOrFail();
        if ($student->parent_id !== $parent->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        $academicYear = 2026;
        $fees = FeeType::where('academic_year', $academicYear)->get();

        // Buscar dívidas pendentes
        $pendingDebts = Payment::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->get();

        return view('enrollments.applications.renewal', compact('student', 'academicYear', 'fees', 'pendingDebts'));
    }

    /**
     * Store a renewal application.
     */
    public function renewalStore(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $request->validate([
            'parent.phone' => 'required|string|max:20',
            'parent.email' => 'nullable|email|max:255',
            'student.address' => 'required|string|max:500',
            'student.emergency_contact' => 'required|string|max:255',
            'student.emergency_phone' => 'required|string|max:20',
            'student.has_special_needs' => 'boolean',
            'student.special_needs_description' => 'nullable|string',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'terms_accepted' => 'accepted',
        ]);

        try {
            DB::beginTransaction();

            $academicYear = 2026;

            // Calculate total fees
            $gradeLevel = $student->currentClass->grade_level ?? 'primary'; // Fallback ou buscar da última matrícula
            $fees = FeeType::where('academic_year', $academicYear)
                ->where(function ($query) use ($gradeLevel) {
                    $query->where('grade_level', $gradeLevel)
                        ->orWhereNull('grade_level');
                })
                ->where('is_mandatory', true)
                ->get();

            $totalAmount = $fees->sum('amount');

            $studentData = array_merge($request->student, [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'birth_date' => $student->birth_date,
                'gender' => $student->gender,
            ]);

            // Handle file uploads
            if ($request->hasFile('medical_certificate')) {
                $studentData['medical_certificate'] = $request->file('medical_certificate')->store('student_documents/' . $student->id, 'public');
            }
            if ($request->hasFile('passport_photo')) {
                $studentData['passport_photo'] = $request->file('passport_photo')->store('student_photos/' . $student->id, 'public');
            }

            $application = EnrollmentApplication::create([
                'type' => 'RENEWAL',
                'status' => 'PENDING',
                'student_id' => $student->id,
                'student_data' => $studentData,
                'parent_data' => $request->parent,
                'academic_year' => $academicYear,
                'total_amount' => $totalAmount,
                'submitted_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('parent.dashboard')
                ->with('success', 'Pedido de renovação enviado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao enviar renovação: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Secretary: List applications.
     */
    public function index(Request $request)
    {
        $query = EnrollmentApplication::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $applications = $query->latest()->paginate(20);

        return view('enrollments.applications.index', compact('applications'));
    }

    /**
     * Secretary: Show application details.
     */
    public function show(EnrollmentApplication $application)
    {
        $application->load('documents', 'student');
        return view('enrollments.applications.show', compact('application'));
    }

    /**
     * Secretary: Update status.
     */
    public function updateStatus(Request $request, EnrollmentApplication $application)
    {
        $request->validate([
            'status' => 'required|string',
            'admin_notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        if ($request->status === 'APPROVED') {
            $application->update(['approved_at' => now()]);
        }

        return back()->with('success', 'Status da aplicação atualizado!');
    }

    /**
     * Secretary: Confirm payment.
     */
    public function confirmPayment(Request $request, EnrollmentApplication $application)
    {
        $request->validate([
            'payment_reference' => 'required|string',
            'payment_date' => 'required|date',
            'payment_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        $application->update([
            'payment_status' => 'PAID',
            'payment_reference' => $request->payment_reference,
            'payment_date' => $request->payment_date,
            'payment_proof_path' => $path,
            'status' => 'DOCUMENT_DELIVERED', // Automatically move to next step
        ]);

        return back()->with('success', 'Pagamento confirmado com sucesso!');
    }

    /**
     * Secretary: Finalize enrollment (Convert to Student).
     */
    public function finalize(Request $request, EnrollmentApplication $application)
    {
        if ($application->status !== 'APPROVED' || $application->payment_status !== 'PAID') {
            return back()->with('error', 'A aplicação deve estar APROVADA e PAGA para ser finalizada.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create or update Parent
            $parent = \App\Models\SchoolParent::updateOrCreate(
                ['phone' => $application->parent_data['phone']],
                [
                    'first_name' => $application->parent_data['first_name'],
                    'last_name' => $application->parent_data['last_name'],
                ]
            );

            // 2. Create Student
            if ($application->type === 'NEW') {
                $student = Student::create([
                    'first_name' => $application->student_data['first_name'],
                    'last_name' => $application->student_data['last_name'],
                    'birth_date' => $application->student_data['birth_date'],
                    'gender' => $application->student_data['gender'],
                    'parent_id' => $parent->id,
                    'status' => 'active',
                    'student_number' => 'ST' . date('Y') . str_pad($application->id, 4, '0', STR_PAD_LEFT),
                ]);
            } else {
                $student = Student::find($application->student_id);

                // Update student data from application
                $student->update([
                    'address' => $application->student_data['address'] ?? $student->address,
                    'emergency_contact' => $application->student_data['emergency_contact'] ?? $student->emergency_contact,
                    'emergency_phone' => $application->student_data['emergency_phone'] ?? $student->emergency_phone,
                    'has_special_needs' => $application->student_data['has_special_needs'] ?? $student->has_special_needs,
                    'special_needs_description' => $application->student_data['special_needs_description'] ?? $student->special_needs_description,
                    'medical_certificate' => $application->student_data['medical_certificate'] ?? $student->medical_certificate,
                    'passport_photo' => $application->student_data['passport_photo'] ?? $student->passport_photo,
                ]);
            }

            // 3. Create Enrollment
            \App\Models\Enrollment::create([
                'student_id' => $student->id,
                'class_id' => $request->class_id,
                'school_year' => $application->academic_year,
                'enrollment_date' => now(),
                'status' => 'active',
                'monthly_fee' => 2300.00, // Default tuition
                'payment_day' => 10,
            ]);

            // 4. Update Application
            $application->update([
                'status' => 'ENROLLED',
                'student_id' => $student->id,
            ]);

            DB::commit();

            return redirect()->route('students.show', $student->id)
                ->with('success', 'Matrícula finalizada com sucesso! Aluno agora está ativo.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao finalizar matrícula: ' . $e->getMessage());
        }
    }
}
