<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DirectorFinanceController extends Controller
{
    /**
     * Display financial overview and records.
     */
    public function index(Request $request)
    {
        // Income (Payments) Query
        $incomeQuery = Payment::with(['student', 'recordedBy']);

        // Filter by search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $incomeQuery->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('student_id', 'like', "%{$search}%");
                })->orWhere('payment_id', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $incomeQuery->where('status', $request->status);
        }

        // Filter by payment type
        if ($request->has('payment_type') && $request->payment_type) {
            $incomeQuery->where('payment_type', $request->payment_type);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $incomeQuery->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $incomeQuery->whereDate('payment_date', '<=', $request->end_date);
        }

        // Get paginated income records
        $incomeRecords = $incomeQuery->orderBy('payment_date', 'desc')->paginate(20);

        // Calculate financial statistics
        $stats = [
            // Total revenue (all time)
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),

            // Current month revenue
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereYear('payment_date', Carbon::now()->year)
                ->whereMonth('payment_date', Carbon::now()->month)
                ->sum('amount'),

            // Current year revenue
            'yearly_revenue' => Payment::where('status', 'completed')
                ->whereYear('payment_date', Carbon::now()->year)
                ->sum('amount'),

            // Pending payments
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),

            // Payment counts
            'total_payments' => Payment::count(),
            'completed_payments' => Payment::where('status', 'completed')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),

            // This month stats
            'monthly_payment_count' => Payment::whereYear('payment_date', Carbon::now()->year)
                ->whereMonth('payment_date', Carbon::now()->month)
                ->count(),
        ];

        // Revenue by payment type (for current year)
        $revenueByType = Payment::where('status', 'completed')
            ->whereYear('payment_date', Carbon::now()->year)
            ->selectRaw('payment_type, SUM(amount) as total')
            ->groupBy('payment_type')
            ->get()
            ->pluck('total', 'payment_type');

        // Monthly revenue for the last 6 months (for charts)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => Payment::where('status', 'completed')
                    ->whereYear('payment_date', $date->year)
                    ->whereMonth('payment_date', $date->month)
                    ->sum('amount'),
            ];
        }

        return view('director.finance.index', compact(
            'incomeRecords',
            'stats',
            'revenueByType',
            'monthlyRevenue'
        ));
    }

    /**
     * Store a new income record (payment).
     */
    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'payment_type' => 'required|in:tuition,registration,materials,event,other',
            'month' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['payment_id'] = $this->generatePaymentId();
        $validated['status'] = 'completed';
        $validated['recorded_by'] = Auth::id();

        Payment::create($validated);

        return redirect()->route('director.finance.index')
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Store a new expense record.
     * Note: This is a placeholder for future expense tracking functionality.
     */
    public function storeExpense(Request $request)
    {
        // TODO: Implement expense tracking when Expense model is created
        // For now, return an informational message
        return back()->with('info', 'Expense tracking feature is coming soon!');
    }

    /**
     * Export financial data.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $type = $request->get('type', 'income'); // income or expense

        if ($type === 'income') {
            $query = Payment::with(['student', 'recordedBy']);

            // Apply filters if provided
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('payment_date', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('payment_date', '<=', $request->end_date);
            }

            $payments = $query->orderBy('payment_date', 'desc')->get();

            if ($format === 'csv') {
                $filename = 'income_report_' . Carbon::now()->format('Y-m-d') . '.csv';
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                ];

                $callback = function() use ($payments) {
                    $file = fopen('php://output', 'w');

                    // CSV Headers
                    fputcsv($file, [
                        'Payment ID',
                        'Date',
                        'Student',
                        'Amount',
                        'Payment Type',
                        'Payment Method',
                        'Reference Number',
                        'Status',
                        'Month/Year',
                        'Recorded By',
                        'Notes'
                    ]);

                    // CSV Data
                    foreach ($payments as $payment) {
                        fputcsv($file, [
                            $payment->payment_id,
                            $payment->payment_date,
                            $payment->student ? $payment->student->first_name . ' ' . $payment->student->last_name : 'N/A',
                            $payment->amount,
                            $payment->payment_type,
                            $payment->payment_method,
                            $payment->reference_number ?? 'N/A',
                            $payment->status,
                            ($payment->month ? $payment->month . '/' : '') . ($payment->year ?? ''),
                            $payment->recordedBy ? $payment->recordedBy->name : 'N/A',
                            $payment->notes ?? ''
                        ]);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }
        }

        return back()->with('error', 'Invalid export format or type.');
    }

    /**
     * Generate unique payment ID.
     */
    private function generatePaymentId()
    {
        $lastPayment = Payment::orderBy('id', 'desc')->first();
        $number = $lastPayment ? intval(substr($lastPayment->payment_id, 3)) + 1 : 1;

        return 'PAY' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
