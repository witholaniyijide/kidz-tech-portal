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
        return view('dashboards.manager');
    }
    
    private function tutorDashboard()
    {
        return view('dashboards.tutor');
    }
    
    private function parentDashboard()
    {
        return view('dashboards.parent');
    }
}
