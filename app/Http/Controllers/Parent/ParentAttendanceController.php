<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentAttendanceController extends Controller
{
    /**
     * Display attendance records for all children.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $children = $user->visibleChildren()->get();

        if ($children->isEmpty()) {
            return view('parent.no-children');
        }

        // Get student IDs
        $studentIds = $children->pluck('id');

        // Get selected child filter
        $selectedChildId = $request->get('child_id');

        // Date range filter
        $startDate = $request->get('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Build query
        $query = Attendance::whereIn('student_id', $studentIds)
            ->with(['student', 'tutor'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc');

        if ($selectedChildId) {
            $query->where('student_id', $selectedChildId);
        }

        $attendanceRecords = $query->paginate(20);

        // Calculate stats for each child
        $stats = [];
        foreach ($children as $child) {
            $childAttendance = Attendance::where('student_id', $child->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $present = $childAttendance->where('status', 'present')->count();
            $absent = $childAttendance->where('status', 'absent')->count();
            $total = $childAttendance->count();

            $stats[$child->id] = [
                'name' => $child->first_name,
                'present' => $present,
                'absent' => $absent,
                'total' => $total,
                'percentage' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        }

        return view('parent.attendance.index', compact(
            'children',
            'attendanceRecords',
            'stats',
            'selectedChildId',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display a specific attendance record.
     */
    public function show(Attendance $attendance)
    {
        $user = Auth::user();
        $studentIds = $user->guardiansOf()->pluck('id');

        // Verify this attendance belongs to one of the parent's children
        if (!$studentIds->contains($attendance->student_id)) {
            abort(403, 'Unauthorized');
        }

        $attendance->load(['student', 'tutor']);

        return view('parent.attendance.show', compact('attendance'));
    }
}
