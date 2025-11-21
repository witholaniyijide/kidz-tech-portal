<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\AttendanceRecord;
use App\Models\Report;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    if ($user->hasRole('director')) {
        return $this->directorDashboard();
    } elseif ($user->hasRole('admin')) {
        return $this->adminDashboard();
    } elseif ($user->hasRole('manager')) {
        return $this->managerDashboard();
    } elseif ($user->hasRole('tutor')) {
        return $this->tutorDashboard();
    } elseif ($user->hasRole('parent')) {
        return redirect()->route('parent.dashboard');
    }
    
    return view('dashboard');
}    
    private function directorDashboard()
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->format('Y');
        
        $monthlyReports = Report::where('month', $currentMonth)
                                ->where('year', $currentYear)
                                ->count();
        
        $approvedReports = Report::where('status', 'approved')
                                 ->where('month', $currentMonth)
                                 ->where('year', $currentYear)
                                 ->count();
        
        $pendingApprovals = Report::where('status', 'submitted')->count();
        
        $todayAttendance = AttendanceRecord::whereDate('attendance_date', Carbon::today())
                                          ->where('status', 'present')
                                          ->count();
        
        $totalAttendanceToday = AttendanceRecord::whereDate('attendance_date', Carbon::today())->count();
        
        $attendanceRate = $totalAttendanceToday > 0 
            ? round(($todayAttendance / $totalAttendanceToday) * 100, 1) 
            : 0;
        
        $data = [
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'totalTutors' => 16,
            'monthlyRevenue' => 2310000,
            'attendanceRate' => $attendanceRate,
            'recentReports' => $approvedReports,
            'monthlyReports' => $monthlyReports,
            'pendingApprovals' => $pendingApprovals,
        ];
        
        return view('dashboards.director', $data);
    }
    
    private function adminDashboard()
    {
        return view('dashboards.admin');
    }
    
    private function managerDashboard()
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $inactiveStudents = Student::where('status', 'inactive')->count();

        $totalTutors = \App\Models\Tutor::count();
        $activeTutors = \App\Models\Tutor::where('status', 'active')->count();

        $totalRevenue = \App\Models\Payment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = \App\Models\Payment::where('status', 'completed')
                                            ->whereMonth('payment_date', Carbon::now()->month)
                                            ->whereYear('payment_date', Carbon::now()->year)
                                            ->sum('amount');

        $pendingReports = Report::where('status', 'submitted')->count();
        $approvedReports = Report::where('status', 'approved')
                                 ->whereMonth('created_at', Carbon::now()->month)
                                 ->count();

        $todayAttendance = AttendanceRecord::whereDate('attendance_date', Carbon::today())->count();
        $presentToday = AttendanceRecord::whereDate('attendance_date', Carbon::today())
                                       ->where('status', 'present')
                                       ->count();

        $attendanceRate = $todayAttendance > 0
            ? round(($presentToday / $todayAttendance) * 100, 1)
            : 0;

        $recentStudents = Student::latest()->take(5)->get();
        $recentReports = Report::with(['student', 'instructor'])
                              ->latest()
                              ->take(5)
                              ->get();

        return view('dashboards.manager', compact(
            'totalStudents', 'activeStudents', 'inactiveStudents',
            'totalTutors', 'activeTutors',
            'totalRevenue', 'monthlyRevenue',
            'pendingReports', 'approvedReports',
            'attendanceRate', 'todayAttendance', 'presentToday',
            'recentStudents', 'recentReports'
        ));
    }

    private function tutorDashboard()
    {
        $user = Auth::user();

        // Get tutor record
        $tutor = \App\Models\Tutor::where('user_id', $user->id)->first();

        // Get assigned students count (you may need to adjust based on your student-tutor relationship)
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();

        // Get reports submitted by this tutor
        $myReports = Report::where('instructor_id', $user->id)->count();
        $pendingReports = Report::where('instructor_id', $user->id)
                               ->where('status', 'submitted')
                               ->count();
        $approvedReports = Report::where('instructor_id', $user->id)
                                ->where('status', 'approved')
                                ->count();

        // Get today's attendance records submitted by this tutor
        $todayAttendance = AttendanceRecord::whereDate('attendance_date', Carbon::today())
                                          ->where('submitted_by', $user->id)
                                          ->count();

        $presentToday = AttendanceRecord::whereDate('attendance_date', Carbon::today())
                                       ->where('submitted_by', $user->id)
                                       ->where('status', 'present')
                                       ->count();

        // Recent students
        $recentStudents = Student::latest()->take(8)->get();

        // Recent reports by this tutor
        $recentReports = Report::with('student')
                              ->where('instructor_id', $user->id)
                              ->latest()
                              ->take(5)
                              ->get();

        // Upcoming sessions or tasks (placeholder - adjust based on your needs)
        $upcomingSessions = [];

        return view('dashboards.tutor', compact(
            'tutor', 'totalStudents', 'activeStudents',
            'myReports', 'pendingReports', 'approvedReports',
            'todayAttendance', 'presentToday',
            'recentStudents', 'recentReports', 'upcomingSessions'
        ));
    }
    
    private function parentDashboard()
    {
        return view('dashboards.parent');
    }
}
