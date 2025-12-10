<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\DirectorActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class DirectorFinanceController extends Controller
{
    /**
     * Display the finance dashboard.
     */
    public function index(Request $request)
    {
        $query = Payment::query();

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        // Filter by type (income/expense)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        // Calculate totals
        $totalRevenue = Payment::where('type', 'income')->sum('amount');
        $thisMonthIncome = Payment::where('type', 'income')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');
        $totalExpenses = Payment::where('type', 'expense')->sum('amount');
        $thisMonthExpenses = Payment::where('type', 'expense')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        // Get transactions
        $transactions = $query->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get monthly trend data for chart
        $monthlyTrend = $this->getMonthlyTrendData();

        return view('director.finance.index', compact(
            'transactions',
            'totalRevenue',
            'thisMonthIncome',
            'totalExpenses',
            'thisMonthExpenses',
            'monthlyTrend'
        ));
    }

    /**
     * Store a new income record.
     */
    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'category' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
        ]);

        try {
            $validated['type'] = 'income';
            $validated['reference'] = $validated['reference'] ?? 'INC-' . strtoupper(uniqid());
            $validated['recorded_by'] = Auth::id();

            $payment = Payment::create($validated);

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'income_recorded',
                'model_type' => 'Payment',
                'model_id' => $payment->id,
                'payload' => json_encode([
                    'amount' => $validated['amount'],
                    'description' => $validated['description'],
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Income recorded successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to record income: ' . $e->getMessage());
        }
    }

    /**
     * Store a new expense record.
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'category' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
        ]);

        try {
            $validated['type'] = 'expense';
            $validated['reference'] = $validated['reference'] ?? 'EXP-' . strtoupper(uniqid());
            $validated['recorded_by'] = Auth::id();

            $payment = Payment::create($validated);

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'expense_recorded',
                'model_type' => 'Payment',
                'model_id' => $payment->id,
                'payload' => json_encode([
                    'amount' => $validated['amount'],
                    'description' => $validated['description'],
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Expense recorded successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to record expense: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing transaction.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'category' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
        ]);

        try {
            $oldAmount = $payment->amount;
            $oldDescription = $payment->description;

            $payment->update($validated);

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'transaction_updated',
                'model_type' => 'Payment',
                'model_id' => $payment->id,
                'payload' => json_encode([
                    'old_amount' => $oldAmount,
                    'new_amount' => $validated['amount'],
                    'old_description' => $oldDescription,
                    'new_description' => $validated['description'],
                    'type' => $payment->type,
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

    /**
     * Delete a transaction.
     */
    public function destroy(Request $request, Payment $payment)
    {
        try {
            $paymentData = [
                'amount' => $payment->amount,
                'description' => $payment->description,
                'type' => $payment->type,
                'payment_date' => $payment->payment_date,
            ];

            $payment->delete();

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'transaction_deleted',
                'model_type' => 'Payment',
                'model_id' => null,
                'payload' => json_encode($paymentData),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }

    /**
     * Export transactions to CSV.
     */
    public function export(Request $request)
    {
        $query = Payment::query();

        // Apply same filters as index
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('payment_date', 'desc')->get();

        $filename = 'finance_report_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Date',
                'Type',
                'Category',
                'Description',
                'Amount',
                'Payment Method',
                'Reference',
            ]);

            // Data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->payment_date?->format('Y-m-d') ?? '',
                    ucfirst($transaction->type),
                    $transaction->category ?? '',
                    $transaction->description,
                    $transaction->amount,
                    $transaction->payment_method ?? '',
                    $transaction->reference ?? '',
                ]);
            }

            fclose($file);
        };

        // Log export activity
        DirectorActivityLog::create([
            'director_id' => Auth::id(),
            'action_type' => 'finance_exported',
            'model_type' => 'Payment',
            'model_id' => null,
            'payload' => json_encode([
                'filters' => $request->only(['type', 'start_date', 'end_date']),
                'count' => $transactions->count(),
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Get monthly trend data for charts.
     */
    private function getMonthlyTrendData()
    {
        $months = collect();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $income = Payment::where('type', 'income')
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
                
            $expense = Payment::where('type', 'expense')
                ->whereMonth('payment_date', $date->month)
                ->whereYear('payment_date', $date->year)
                ->sum('amount');
            
            $months->push([
                'month' => $monthName,
                'income' => $income,
                'expense' => $expense,
            ]);
        }
        
        return $months;
    }
}
