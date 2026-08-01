<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    /**
     * Exibe o Seletor Visual de Perfis de Demonstração (1-Click Demo Hub).
     */
    public function index()
    {
        $profiles = [
            [
                'role' => 'admin',
                'name' => 'Direção / Administrador',
                'description' => 'Acesso completo a indicadores estratégicos, configurações, logs, relatórios e gestão geral.',
                'icon' => 'fas fa-user-shield',
                'color' => 'emerald',
                'email' => 'admin@visionarios.co.mz',
            ],
            [
                'role' => 'secretary',
                'name' => 'Secretaria Escolar & Finanças',
                'description' => 'Gestão de matrículas, encarregados, propinas, cobranças, emissão de recibos e documentos.',
                'icon' => 'fas fa-calculator',
                'color' => 'blue',
                'email' => 'secretaria@visionarios.co.mz',
            ],
            [
                'role' => 'pedagogy',
                'name' => 'Coordenação Pedagógica',
                'description' => 'Gestão de turmas, disciplinas, atribuição de professores, revisão de notas e pautas.',
                'icon' => 'fas fa-graduation-cap',
                'color' => 'purple',
                'email' => 'pedagogia@visionarios.co.mz',
            ],
            [
                'role' => 'teacher',
                'name' => 'Portal do Professor',
                'description' => 'Lançamento de notas ACS/ACP/ACF, marcação de assiduidade e consulta das suas turmas.',
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'amber',
                'email' => 'professor@visionarios.co.mz',
            ],
            [
                'role' => 'parent',
                'name' => 'Portal dos Pais / Encarregados',
                'description' => 'Acompanhamento do boletim de notas, faltas, situação financeira e comunicados dos educandos.',
                'icon' => 'fas fa-users',
                'color' => 'teal',
                'email' => 'pai@visionarios.co.mz',
            ],
        ];

        return view('public.demo-access', compact('profiles'));
    }

    /**
     * Autenticação 1-Click instantânea por Perfil de Demonstração.
     */
    public function loginAsRole(Request $request, string $role)
    {
        $roleEmails = [
            'super_admin' => 'superadmin@visionarios.co.mz',
            'admin' => 'admin@visionarios.co.mz',
            'secretary' => 'secretaria@visionarios.co.mz',
            'pedagogy' => 'pedagogia@visionarios.co.mz',
            'teacher' => 'professor@visionarios.co.mz',
            'parent' => 'pai@visionarios.co.mz',
        ];

        $targetEmail = $roleEmails[$role] ?? 'admin@visionarios.co.mz';
        $user = User::where('email', $targetEmail)->first();

        if (!$user) {
            // Fallback para qualquer usuário com o papel
            $user = User::role($role)->first() ?? User::first();
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'Nenhum utilizador encontrado para este perfil de demonstração.');
        }

        Auth::login($user);

        $roleNames = [
            'admin' => 'Direção / Administrador',
            'secretary' => 'Secretaria Escolar',
            'pedagogy' => 'Coordenação Pedagógica',
            'teacher' => 'Professor',
            'parent' => 'Pai / Encarregado de Educação',
        ];

        $destinationRoute = match ($role) {
            'teacher' => route('teacher.dashboard'),
            'parent' => route('parent.dashboard'),
            default => route('dashboard'),
        };

        return redirect($destinationRoute)->with('success', 'Demonstração ativada! Autenticado como ' . ($roleNames[$role] ?? $user->name));
    }

    /**
     * Alias de acesso rápido prévio
     */
    public function access()
    {
        return $this->loginAsRole(request(), 'admin');
    }
}
