<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ParentPaymentController extends Controller
{
    /**
     * Display payment history for all children.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $children = $user->guardiansOf()->get();

        if ($children->isEmpty()) {
            return view('parent.no-children');
        }

        // Get student IDs
        $studentIds = $children->pluck('id');

        // Filters
        $selectedChildId = $request->get('child_id');
        $status = $request->get('status');

        // Build query
        $query = Payment::whereIn('student_id', $studentIds)
            ->with(['student'])
            ->orderBy('created_at', 'desc');

        if ($selectedChildId) {
            $query->where('student_id', $selectedChildId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $payments = $query->paginate(20);

        // Calculate totals
        $totalPaid = Payment::whereIn('student_id', $studentIds)
            ->where('status', 'paid')
            ->sum('amount');

        $totalPending = Payment::whereIn('student_id', $studentIds)
            ->where('status', 'pending')
            ->sum('amount');

        $totalOverdue = Payment::whereIn('student_id', $studentIds)
            ->where('status', 'overdue')
            ->sum('amount');

        $outstandingBalance = $totalPending + $totalOverdue;

        return view('parent.payments.index', compact(
            'children',
            'payments',
            'selectedChildId',
            'status',
            'totalPaid',
            'totalPending',
            'totalOverdue',
            'outstandingBalance'
        ));
    }

    /**
     * Display a specific payment details.
     */
    public function show(Payment $payment)
    {
        $user = Auth::user();
        $studentIds = $user->guardiansOf()->pluck('id');

        // Verify this payment belongs to one of the parent's children
        if (!$studentIds->contains($payment->student_id)) {
            abort(403, 'Unauthorized');
        }

        $payment->load(['student']);

        return view('parent.payments.show', compact('payment'));
    }

    /**
     * Download payment receipt.
     */
    public function receipt(Payment $payment)
    {
        $user = Auth::user();
        $studentIds = $user->guardiansOf()->pluck('id');

        // Verify this payment belongs to one of the parent's children
        if (!$studentIds->contains($payment->student_id)) {
            abort(403, 'Unauthorized');
        }

        // Only allow receipt for paid payments
        if ($payment->status !== 'paid') {
            return back()->with('error', 'Receipt is only available for completed payments.');
        }

        $payment->load(['student']);

        // Generate PDF receipt
        try {
            $pdf = Pdf::loadView('parent.payments.receipt', compact('payment'));
            return $pdf->download("receipt-{$payment->id}.pdf");
        } catch (\Exception $e) {
            // Fallback to HTML view if PDF generation fails
            return view('parent.payments.receipt', compact('payment'));
        }
    }
}
