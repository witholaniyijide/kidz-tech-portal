<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of attendance records.
     * Admin reviews tutor-submitted attendance and approves/marks late/deletes.
     */
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['student', 'tutor', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('class_date', $request->date);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by tutor
        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        // Filter late submissions only
        if ($request->boolean('late_only')) {
            $query->where('is_late', true);
        }

        $perPage = $request->get('per_page', 20);
        $attendances = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Statistics
        $stats = [
            'total' => AttendanceRecord::count(),
            'approved' => AttendanceRecord::where('status', 'approved')->count(),
            'pending' => AttendanceRecord::where('status', 'pending')->count(),
            'late' => AttendanceRecord::where('is_late', true)->count(),
        ];

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $tutors = Tutor::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.attendance.index', compact('attendances', 'stats', 'students', 'tutors'));
    }

    /**
     * Display the specified attendance record.
     */
    public function show(AttendanceRecord $attendance)
    {
        $attendance->load(['student', 'tutor', 'approver']);
        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * Approve an attendance record.
     */
    public function approve(AttendanceRecord $attendance)
    {
        DB::transaction(function() use ($attendance) {
            $attendance->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'approved',
                'description' => "Approved attendance for {$attendance->student->first_name} by {$attendance->tutor->first_name}",
                'model_type' => AttendanceRecord::class,
                'model_id' => $attendance->id,
            ]);
        });

        return redirect()->back()->with('success', 'Attendance approved successfully.');
    }

    /**
     * Mark an attendance record as late (submission was late).
     */
    public function markLate(AttendanceRecord $attendance)
    {
        DB::transaction(function() use ($attendance) {
            $attendance->update([
                'is_late' => true,
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'marked_late',
                'description' => "Marked attendance as late submission - {$attendance->student->first_name} by {$attendance->tutor->first_name}",
                'model_type' => AttendanceRecord::class,
                'model_id' => $attendance->id,
            ]);
        });

        return redirect()->back()->with('success', 'Attendance marked as late and approved.');
    }

    /**
     * Delete attendance record (Admin asks tutor to recreate and resubmit).
     */
    public function destroy(AttendanceRecord $attendance)
    {
        $studentName = $attendance->student->first_name ?? 'Unknown';
        $tutorName = $attendance->tutor->first_name ?? 'Unknown';
        $classDate = $attendance->class_date?->format('M j, Y') ?? 'Unknown';

        DB::transaction(function() use ($attendance, $studentName, $tutorName, $classDate) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'description' => "Deleted attendance for {$studentName} by {$tutorName} on {$classDate} (requested resubmission)",
                'model_type' => AttendanceRecord::class,
                'model_id' => $attendance->id,
            ]);

            $attendance->delete();
        });

        return redirect()
            ->route('admin.attendance.index')
            ->with('success', "Attendance deleted. Please notify {$tutorName} to resubmit.");
    }

    // Note: Admin does NOT create or edit attendance.
    // Tutors submit attendance after completing classes.
    // Admin only reviews, approves, marks late, or deletes for resubmission.
}
