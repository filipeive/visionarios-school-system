<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    /**
     * Lista de Encarregados de Educação.
     */
    public function index(Request $request)
    {
        $this->authorize('view_students');

        $query = ParentModel::with(['user', 'students']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('bi_number', 'like', "%{$search}%");
            });
        }

        $parents = $query->orderBy('first_name')->paginate(15);
        $totalParents = ParentModel::count();

        return view('parents.index', compact('parents', 'totalParents'));
    }

    /**
     * Formulário de novo encarregado.
     */
    public function create()
    {
        $this->authorize('create_students');

        return view('parents.create');
    }

    /**
     * Registar novo encarregado de educação.
     */
    public function store(Request $request)
    {
        $this->authorize('create_students');

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:30',
            'bi_number' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:100',
            'workplace' => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($validated, &$parent) {
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole('parent');

            $parent = ParentModel::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'bi_number' => $validated['bi_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'profession' => $validated['profession'] ?? null,
                'workplace' => $validated['workplace'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_phone' => $validated['emergency_phone'] ?? null,
            ]);
        });

        return redirect()->route('parents.index')
            ->with('success', 'Encarregado de Educação registado com sucesso!');
    }

    /**
     * Exibir detalhes do encarregado.
     */
    public function show($id)
    {
        $this->authorize('view_students');

        $parent = ParentModel::with(['user', 'students.currentEnrollment.class'])->findOrFail($id);

        return view('parents.show', compact('parent'));
    }

    /**
     * Formulário de edição.
     */
    public function edit($id)
    {
        $this->authorize('edit_students');

        $parent = ParentModel::findOrFail($id);

        return view('parents.edit', compact('parent'));
    }

    /**
     * Atualizar encarregado de educação.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('edit_students');

        $parent = ParentModel::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'bi_number' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:100',
            'workplace' => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|max:30',
        ]);

        $parent->update($validated);

        if ($parent->user) {
            $parent->user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            ]);
        }

        return redirect()->route('parents.index')
            ->with('success', 'Dados do encarregado atualizados com sucesso!');
    }
}
