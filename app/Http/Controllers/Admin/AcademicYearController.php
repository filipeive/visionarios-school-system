<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AcademicYearService;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    protected $academicYearService;

    public function __construct(AcademicYearService $academicYearService)
    {
        $this->academicYearService = $academicYearService;
    }

    /**
     * Show the academic year management page.
     */
    public function index()
    {
        $currentYear = $this->academicYearService->getCurrentYear();
        $nextYear = $this->academicYearService->getNextYear();

        return view('admin.academic-years.index', compact('currentYear', 'nextYear'));
    }

    /**
     * Start the transition to the next academic year.
     */
    public function transition(Request $request)
    {
        $this->authorize('manage_settings'); // Adjust permission as needed

        if ($this->academicYearService->transitionToNextYear()) {
            return redirect()->route('admin.academic-years.index')
                ->with('success', 'Transição para o novo ano lectivo iniciada com sucesso! As matrículas antigas foram concluídas e os alunos estão prontos para renovação.');
        }

        return back()->with('error', 'Erro ao processar a transição do ano lectivo.');
    }
}
