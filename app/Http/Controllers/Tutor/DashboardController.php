<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\DailyClassSchedule;
use App\Models\Notice;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\TutorTodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the tutor dashboard.
     */
    public function index()
    {
        // Get the authenticated tutor
        $user = Auth::user();
        $tutor = $user ? $user->tutor : null;

        if (!$tutor) {
            return redirect()->route('login')
                ->with('error', 'You do not have a tutor profile associated with your account. Please contact the administrator.');
        }

        // Cache dashboard stats for 5 minutes
        $cacheKey = 'tutor_dashboard_' . $tutor->id;

        $stats = Cache::remember($cacheKey, 300, function () use ($tutor) {
            $students = $tutor->students()->active()->get();

            return [
                'studentsCount' => $students->count(),
                'studentIds' => $students->pluck('id')->toArray(),
                'pendingAttendanceCount' => $tutor->attendanceRecords()
                    ->where('status', 'pending')
                    ->count(),
                'reportsCount' => $tutor->reports()->count(),
                'draftReportsCount' => $tutor->reports()->where('status', 'draft')->count(),
                'submittedReportsCount' => $tutor->reports()->where('status', 'submitted')->count(),
                'submittedThisMonth' => $tutor->reports()
                    ->whereIn('status', ['submitted', 'approved', 'director_approved'])
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'pendingReportsCount' => $tutor->reports()
                    ->whereIn('status', ['draft', 'returned'])
                    ->count(),
            ];
        });

        // Get students with eager loading
        $students = $tutor->students()->active()->get();
        $studentsCount = $stats['studentsCount'];
        $studentIds = $stats['studentIds'];
        $pendingAttendanceCount = $stats['pendingAttendanceCount'];
        $reportsCount = $stats['reportsCount'];
        $draftReportsCount = $stats['draftReportsCount'];
        $submittedReportsCount = $stats['submittedReportsCount'];
        $submittedThisMonth = $stats['submittedThisMonth'];
        $pendingReportsCount = $stats['pendingReportsCount'];

        // Get recent attendance records with eager loading
        $recentAttendance = $tutor->attendanceRecords()
            ->with('student')
            ->orderBy('class_date', 'desc')
            ->take(5)
            ->get();

        // Get recent reports
        $recentReports = $tutor->reports()
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get unread notifications
        $unreadNotifications = $tutor->notifications()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $unreadNotificationsCount = $tutor->notifications()->unread()->count();

        // Get upcoming availability (current week)
        $upcomingAvailability = $tutor->availabilities()
            ->active()
            ->get();

        // Get today's classes from DailyClassSchedule
        $todaySchedule = DailyClassSchedule::where('schedule_date', Carbon::today())
            ->where('status', 'posted')
            ->first();

        $todayClasses = collect();
        $todayRescheduledClasses = collect();
        $schedulePosted = false;

        if ($todaySchedule) {
            $schedulePosted = true;

            // Process regular classes
            if ($todaySchedule->classes) {
                // Filter classes for this tutor's students
                $todayClasses = collect($todaySchedule->classes)->filter(function ($class) use ($studentIds, $tutor) {
                    // Include if student is assigned to this tutor OR tutor_id matches
                    return (isset($class['student_id']) && in_array($class['student_id'], $studentIds))
                        || (isset($class['tutor_id']) && $class['tutor_id'] == $tutor->id);
                })->map(function ($class) use ($students) {
                    // Enrich with student data if available
                    if (isset($class['student_id'])) {
                        $student = $students->firstWhere('id', $class['student_id']);
                        if ($student) {
                            $class['student_name'] = $student->first_name . ' ' . $student->last_name;

                            // If class_time is not set, get it from student's class_schedule
                            if (empty($class['class_time']) && $student->class_schedule && is_array($student->class_schedule)) {
                                $today = Carbon::today()->format('l'); // Get day name (e.g., Monday)
                                foreach ($student->class_schedule as $schedule) {
                                    if (isset($schedule['day']) && $schedule['day'] === $today && isset($schedule['time'])) {
                                        $class['class_time'] = $schedule['time'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    return $class;
                })->sortBy('class_time')->values();
            }

            // Process rescheduled classes
            if ($todaySchedule->rescheduled_classes) {
                $todayRescheduledClasses = collect($todaySchedule->rescheduled_classes)->filter(function ($class) use ($studentIds, $tutor) {
                    return (isset($class['student_id']) && in_array($class['student_id'], $studentIds))
                        || (isset($class['tutor_id']) && $class['tutor_id'] == $tutor->id);
                })->map(function ($class) use ($students) {
                    if (isset($class['student_id'])) {
                        $student = $students->firstWhere('id', $class['student_id']);
                        if ($student) {
                            $class['student_name'] = $student->first_name . ' ' . $student->last_name;
                        }
                    }
                    $class['is_rescheduled'] = true;
                    return $class;
                })->sortBy('class_time')->values();
            }

            // Merge both collections for total count
            $todayClasses = $todayClasses->merge($todayRescheduledClasses)->sortBy('class_time')->values();
        }

        // Get this week's classes
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        $weekSchedules = DailyClassSchedule::whereBetween('schedule_date', [$weekStart, $weekEnd])
            ->where('status', 'posted')
            ->orderBy('schedule_date')
            ->get();

        $weekClasses = collect();
        foreach ($weekSchedules as $schedule) {
            if ($schedule->classes) {
                $filteredClasses = collect($schedule->classes)->filter(function ($class) use ($studentIds, $tutor) {
                    return (isset($class['student_id']) && in_array($class['student_id'], $studentIds))
                        || (isset($class['tutor_id']) && $class['tutor_id'] == $tutor->id);
                });

                foreach ($filteredClasses as $class) {
                    $class['schedule_date'] = $schedule->schedule_date;

                    // Enrich with class time from student's schedule if not set
                    if (isset($class['student_id']) && empty($class['class_time'])) {
                        $student = $students->firstWhere('id', $class['student_id']);
                        if ($student && $student->class_schedule && is_array($student->class_schedule)) {
                            $scheduleDay = Carbon::parse($schedule->schedule_date)->format('l');
                            foreach ($student->class_schedule as $studentSchedule) {
                                if (isset($studentSchedule['day']) && $studentSchedule['day'] === $scheduleDay && isset($studentSchedule['time'])) {
                                    $class['class_time'] = $studentSchedule['time'];
                                    break;
                                }
                            }
                        }
                    }

                    $weekClasses->push($class);
                }
            }
        }

        // Classes today count
        $classesTodayCount = $todayClasses->count();

        // Get recent notices (visible to tutors)
        $recentNotices = Notice::where('status', 'published')
            ->whereJsonContains('visible_to', 'tutor')
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Get custom todos for this tutor
        $customTodos = TutorTodo::where('tutor_id', $tutor->id)
            ->incomplete()
            ->orderByPriority()
            ->orderBy('due_date')
            ->get();

        // Today's Birthdays (only show students assigned to this tutor)
        $todaysBirthdays = $this->getTodaysBirthdays($tutor->id);

        return view('tutor.dashboard', compact(
            'tutor',
            'students',
            'studentsCount',
            'recentAttendance',
            'pendingAttendanceCount',
            'reportsCount',
            'draftReportsCount',
            'submittedReportsCount',
            'submittedThisMonth',
            'pendingReportsCount',
            'recentReports',
            'unreadNotifications',
            'unreadNotificationsCount',
            'upcomingAvailability',
            'todayClasses',
            'weekClasses',
            'classesTodayCount',
            'schedulePosted',
            'recentNotices',
            'customTodos',
            'todaysBirthdays'
        ));
    }

    /**
     * Store a new custom todo.
     */
    public function storeTodo(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return back()->with('error', 'Tutor profile not found.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|date_format:H:i',
        ]);

        TutorTodo::create([
            'tutor_id' => $tutor->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'due_date' => $validated['due_date'] ?? null,
            'due_time' => $validated['due_time'] ?? null,
        ]);

        return back()->with('success', 'Todo added successfully.');
    }

    /**
     * Update an existing todo.
     */
    public function updateTodo(Request $request, TutorTodo $todo)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $todo->tutor_id !== $tutor->id) {
            return back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|date_format:H:i',
        ]);

        $todo->update($validated);

        return back()->with('success', 'Todo updated successfully.');
    }

    /**
     * Toggle todo completion status.
     */
    public function toggleTodo(TutorTodo $todo)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $todo->tutor_id !== $tutor->id) {
            return back()->with('error', 'Unauthorized.');
        }

        $todo->update([
            'completed' => !$todo->completed,
            'completed_at' => !$todo->completed ? now() : null,
        ]);

        return back()->with('success', 'Todo updated.');
    }

    /**
     * Delete a todo.
     */
    public function deleteTodo(TutorTodo $todo)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $todo->tutor_id !== $tutor->id) {
            return back()->with('error', 'Unauthorized.');
        }

        $todo->delete();

        return back()->with('success', 'Todo deleted.');
    }

    /**
     * Get today's birthdays for students assigned to this tutor.
     */
    private function getTodaysBirthdays(int $tutorId): array
    {
        $today = Carbon::today();
        $birthdays = [];

        // Get students with birthday today (only assigned to this tutor)
        $studentBirthdays = Student::whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->where('tutor_id', $tutorId)
            ->where('status', 'active')
            ->get();

        foreach ($studentBirthdays as $student) {
            $birthdays[] = [
                'name' => $student->first_name . ' ' . $student->last_name,
                'role' => 'Student',
                'type' => 'student',
            ];
        }

        return $birthdays;
    }
}
