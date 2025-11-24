<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\DailyClassSchedule;
use App\Models\Report;
use App\Models\Notice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ManagerDashboardController extends Controller
{
    /**
     * Display the manager dashboard.
     */
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'totalStudents' => Student::count(),
            'activeStudents' => Student::where('status', 'active')->count(),
            'inactiveStudents' => Student::where('status', 'inactive')->count(),

            'totalTutors' => Tutor::count(),
            'activeTutors' => Tutor::where('status', 'active')->count(),
            'onLeaveTutors' => Tutor::where('status', 'on_leave')->count(),

            'todayClasses' => $this->getTodayClassesCount(),

            // Count tutor reports that need manager review
            'pendingAssessments' => Report::where('status', 'submitted_to_manager')
                ->orWhere('status', 'submitted')
                ->count(),
        ];

        // Get today's schedule (admin creates, manager views)
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'published')
            ->first();

        // Get recent notices (visible to managers, not director-only)
        $notices = Notice::where('status', 'published')
            ->where(function($query) {
                $query->whereJsonContains('visible_to', 'manager')
                      ->orWhereJsonContains('visible_to', 'all');
            })
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent students (last 5 added)
        $recentStudents = Student::with('tutor')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get recent tutors (last 5 added)
        $recentTutors = Tutor::withCount('students')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('dashboards.manager', compact(
            'stats',
            'todaySchedule',
            'notices',
            'recentStudents',
            'recentTutors'
        ));
    }

    /**
     * Get count of today's scheduled classes
     */
    private function getTodayClassesCount()
    {
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'published')
            ->first();

        if (!$todaySchedule || !$todaySchedule->classes) {
            return 0;
        }

        return count($todaySchedule->classes);
    }
}
