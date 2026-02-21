<?php

namespace App\Http\Controllers;

use App\Models\Observation;
use App\Models\Student;
use App\Models\StudentRecord;
use Illuminate\Http\Request;

class StudentSupportController extends Controller
{
    public function index(Student $student)
    {
        $this->authorize('view_students');

        $observations = $student->observations()
            ->with('creator')
            ->latest()
            ->paginate(8, ['*'], 'observations_page');

        $records = $student->studentRecords()
            ->with('creator')
            ->latest('record_date')
            ->latest()
            ->paginate(8, ['*'], 'records_page');

        return view('students.support.index', compact('student', 'observations', 'records'));
    }

    public function storeObservation(Request $request, Student $student)
    {
        $this->authorize('create_observations');

        $validated = $request->validate([
            'observations' => 'required|string|max:5000',
            'special_needs' => 'nullable|boolean',
        ]);

        Observation::create([
            'student_id' => $student->id,
            'special_needs' => (bool) ($validated['special_needs'] ?? false),
            'observations' => $validated['observations'],
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Observação adicionada com sucesso.');
    }

    public function destroyObservation(Student $student, Observation $observation)
    {
        $this->authorize('manage_observations');

        if ($observation->student_id !== $student->id) {
            abort(404);
        }

        $observation->delete();

        return back()->with('success', 'Observação removida com sucesso.');
    }

    public function storeRecord(Request $request, Student $student)
    {
        $this->authorize('manage_student_records');

        $validated = $request->validate([
            'record_type' => 'required|in:academic,disciplinary,health,achievement,other',
            'record_details' => 'required|string|max:5000',
            'record_date' => 'required|date',
        ]);

        StudentRecord::create([
            'student_id' => $student->id,
            'record_type' => $validated['record_type'],
            'record_details' => $validated['record_details'],
            'record_date' => $validated['record_date'],
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Registo adicionado com sucesso.');
    }

    public function destroyRecord(Student $student, StudentRecord $record)
    {
        $this->authorize('manage_student_records');

        if ($record->student_id !== $student->id) {
            abort(404);
        }

        $record->delete();

        return back()->with('success', 'Registo removido com sucesso.');
    }
}
