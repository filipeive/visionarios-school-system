<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Expense::class, 'expense');
    }

    public function index(Request $request)
    {
        $query = Expense::with(['category', 'creator', 'approver']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $expenses = $query->orderByDesc('expense_date')->paginate(20)->withQueryString();
        $categories = FinancialCategory::active()->orderBy('name')->get();

        $stats = [
            'total' => Expense::sum('amount'),
            'this_month' => Expense::whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount'),
            'pending_approval' => Expense::whereNull('approved_at')->count(),
        ];

        return view('expenses.index', compact('expenses', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = FinancialCategory::active()->orderBy('name')->get();

        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:financial_categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|in:cash,bank,mpesa,emola,multicaixa',
            'receipt_number' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['created_by'] = auth()->id();

        $expense = Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Despesa registada com sucesso!');
    }

    public function show(Expense $expense)
    {
        $expense->load(['category', 'creator', 'approver']);

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = FinancialCategory::active()->orderBy('name')->get();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:financial_categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|in:cash,bank,mpesa,emola,multicaixa',
            'receipt_number' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Despesa atualizada com sucesso!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Despesa removida com sucesso!');
    }

    public function approve(Request $request, Expense $expense)
    {
        $this->authorize('approve', $expense);

        $expense->update([
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Despesa aprovada com sucesso!');
    }
}
