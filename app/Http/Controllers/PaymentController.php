<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'recordedBy']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            })->orWhere('payment_id', 'like', "%{$search}%")
              ->orWhere('reference_number', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_type') && $request->payment_type) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->has('month') && $request->month) {
            $query->where('month', $request->month);
        }

        if ($request->has('year') && $request->year) {
            $query->where('year', $request->year);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);

        $totalRevenue = Payment::completed()->sum('amount');
        $monthlyRevenue = Payment::completed()
                                 ->whereMonth('payment_date', date('m'))
                                 ->whereYear('payment_date', date('Y'))
                                 ->sum('amount');

        return view('payments.index', compact('payments', 'totalRevenue', 'monthlyRevenue'));
    }

    public function create()
    {
        $students = Student::active()->orderBy('first_name')->get();
        return view('payments.create', compact('students'));
    }

    public function store(Request $request)
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

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load(['student', 'recordedBy']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $students = Student::active()->orderBy('first_name')->get();
        return view('payments.edit', compact('payment', 'students'));
    }

    public function update(Request $request, Payment $payment)
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
            'status' => 'required|in:pending,completed,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully!');
    }

    private function generatePaymentId()
    {
        $lastPayment = Payment::orderBy('id', 'desc')->first();
        $number = $lastPayment ? intval(substr($lastPayment->payment_id, 3)) + 1 : 1;
        
        return 'PAY' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
