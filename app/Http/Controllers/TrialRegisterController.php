<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\License;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TrialRegisterController extends Controller
{
    /**
     * Formulário de Registo de Escola para Testar Grátis (Trial 15 Dias).
     */
    public function create()
    {
        return view('public.trial-register');
    }

    /**
     * Processa o registo automático da escola e criação do ambiente de teste.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'director_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:30',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'estimated_students' => 'nullable|integer|min:10|max:5000',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'school_name.required' => 'Por favor, indique o nome da escola.',
            'director_name.required' => 'Por favor, indique o nome do responsável.',
            'email.required' => 'O endereço de e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já se encontra registado no sistema.',
            'password.required' => 'Por favor, defina uma palavra-passe.',
            'password.min' => 'A palavra-passe deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da palavra-passe não coincide.',
        ]);

        DB::transaction(function () use ($validated, &$user) {
            // 1. Criar Utilizador Administrador da Escola
            $user = User::create([
                'name' => $validated['director_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole('admin');

            // 2. Definir Configurações Iniciais da Escola
            Setting::set('school_name', $validated['school_name']);
            Setting::set('school_phone', $validated['phone']);
            Setting::set('school_province', $validated['province'] ?? 'Maputo');
            Setting::set('school_district', $validated['district'] ?? '');
            Setting::set('estimated_students', $validated['estimated_students'] ?? 250);

            // 3. Gerar Licença de Avaliação Gratuita (15 Dias)
            License::updateOrCreate(
                ['id' => 1],
                [
                    'client_name' => $validated['school_name'],
                    'license_key' => 'TRIAL-' . strtoupper(Str::random(12)),
                    'activated_at' => now(),
                    'expires_at' => now()->addDays(15),
                    'grace_period_days' => 5,
                    'status' => 'active',
                    'plan' => 'Gestão Completa (15 Dias Grátis)',
                    'signature' => hash('sha256', $validated['school_name'] . now()),
                ]
            );
        });

        // 4. Autenticar Automaticamente o Novo Administrador
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "🎉 Parabéns! O ambiente de avaliação de 15 dias da escola '{$validated['school_name']}' foi criado com sucesso!");
    }
}
