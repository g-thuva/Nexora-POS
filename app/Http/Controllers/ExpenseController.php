<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Show expense create form.
     */
    public function create(Request $request)
    {
        $shopId = $request->user()->shop_id ?? null;

        $base = Expense::query();
        if ($shopId) {
            $base->where('shop_id', $shopId);
        }

        // Use KpiService which calls stored procedure to get per-shop expense aggregates
        $kpiService = new \App\Services\KpiService();
        $expenseKpis = $kpiService->getExpenseKpisByShop($shopId);

        $totalExpenses = $expenseKpis->total_expenses ?? 0; // in cents
        $monthTotal = $expenseKpis->last_30_days_expenses ?? 0; // approximate recent period
        $weekTotal = 0; // week-level cached proc not implemented; compute on demand if needed
        $typesCount = $expenseKpis->types_count ?? 0;

    $recent = (clone $base)->latest('expense_date')->latest()->limit(10)->get();

        return view('expenses.create', [
            'totalExpenses' => $totalExpenses,
            'monthTotal' => $monthTotal,
            'weekTotal' => $weekTotal,
            'typesCount' => $typesCount,
            'expenses' => $recent,
        ]);
    }

    /**
     * Show edit form for an expense.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update an expense.
     */
    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'type' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $expense->update([
            'type' => $data['type'] ?? $expense->type,
            'amount' => (int)round($data['amount'] * 100),
            'expense_date' => $data['expense_date'] ?? $expense->expense_date,
            'notes' => $data['notes'] ?? $expense->notes,
        ]);

        return redirect()->route('expenses.edit', $expense)->with('status', 'Expense updated');
    }
    /**
     * Store an expense record.
     * Expected payload: type, amount (decimal), expense_date, notes
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $expense = Expense::create([
            'type' => $data['type'] ?? null,
            'amount' => (int)round($data['amount'] * 100),
            'expense_date' => $data['expense_date'] ?? now(),
            'notes' => $data['notes'] ?? null,
            'shop_id' => $request->user()->shop_id ?? null,
            'created_by' => $request->user()->id ?? null,
        ]);

        // If the client expects JSON (API or AJAX), return JSON. Otherwise redirect
        // to the expense edit/view page so the user can see the created record in the UI.
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'ok', 'expense_id' => $expense->id], 201);
        }

        return redirect()
            ->route('expenses.edit', $expense)
            ->with('success', 'Expense recorded successfully');
    }
}
