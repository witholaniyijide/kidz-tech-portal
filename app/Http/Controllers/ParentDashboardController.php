<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Report;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $parent = Auth::user();
        
        // Get all children of this parent
        $children = Student::where('parent_id', $parent->id)->get();
        
        if ($children->isEmpty()) {
            return view('parent.no-children');
        }
        
        // Get student IDs
        $studentIds = $children->pluck('id');
        
        // Get recent reports for all children
        $recentReports = Report::whereIn('student_id', $studentIds)
                              ->with('student')
                              ->orderBy('created_at', 'desc')
                              ->take(5)
                              ->get();
        
        // Get attendance summary
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->format('Y');
        
        $totalAttendance = AttendanceRecord::whereIn('student_id', $studentIds)
                                          ->whereMonth('attendance_date', Carbon::now()->month)
                                          ->whereYear('attendance_date', Carbon::now()->year)
                                          ->count();
        
        $presentCount = AttendanceRecord::whereIn('student_id', $studentIds)
                                       ->whereMonth('attendance_date', Carbon::now()->month)
                                       ->whereYear('attendance_date', Carbon::now()->year)
                                       ->where('status', 'present')
                                       ->count();
        
        $attendanceRate = $totalAttendance > 0 
            ? round(($presentCount / $totalAttendance) * 100, 1) 
            : 0;
        
        return view('parent.dashboard', compact(
            'children',
            'recentReports',
            'attendanceRate',
            'currentMonth',
            'currentYear'
        ));
    }
    
    public function showChild(Student $student)
    {
        // Ensure this student belongs to the logged-in parent
        if ($student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('parent.child-profile', compact('student'));
    }
    
    public function childReports(Student $student)
    {
        // Ensure this student belongs to the logged-in parent
        if ($student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        $reports = Report::where('student_id', $student->id)
                        ->where('status', 'approved')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('parent.child-reports', compact('student', 'reports'));
    }
    
    public function viewReport(Student $student, Report $report)
    {
        // Ensure this student belongs to the logged-in parent
        if ($student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Ensure this report belongs to this student
        if ($report->student_id !== $student->id) {
            abort(403, 'Unauthorized access');
        }
        
        return view('parent.view-report', compact('student', 'report'));
    }
    
    public function childAttendance(Student $student)
    {
        // Ensure this student belongs to the logged-in parent
        if ($student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        $attendance = AttendanceRecord::where('student_id', $student->id)
                                     ->orderBy('attendance_date', 'desc')
                                     ->paginate(20);
        
        return view('parent.child-attendance', compact('student', 'attendance'));
    }
}
