<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Student Management Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('students', App\Http\Controllers\StudentController::class);
    Route::get('/students/status/inactive', [App\Http\Controllers\StudentController::class, 'inactive'])->name('students.inactive');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('attendance', App\Http\Controllers\AttendanceController::class);
    Route::get('/attendance/calendar/view', [App\Http\Controllers\AttendanceController::class, 'calendar'])->name('attendance.calendar');
    Route::post('attendance/{attendance}/approve', [App\Http\Controllers\AttendanceController::class, 'approve'])->name('attendance.approve');
    Route::post('attendance/{attendance}/reject', [App\Http\Controllers\AttendanceController::class, 'reject'])->name('attendance.reject');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('reports', App\Http\Controllers\ReportController::class);
    Route::get('/reports/filter/by-student', [App\Http\Controllers\ReportController::class, 'byStudent'])->name('reports.by-student');
    Route::get('/reports/filter/by-tutor', [App\Http\Controllers\ReportController::class, 'byTutor'])->name('reports.by-tutor');
    Route::get('/reports/filter/by-month', [App\Http\Controllers\ReportController::class, 'byMonth'])->name('reports.by-month');
    Route::post('reports/{report}/approve', [App\Http\Controllers\ReportController::class, 'approve'])->name('reports.approve');
    Route::post('reports/{report}/reject', [App\Http\Controllers\ReportController::class, 'reject'])->name('reports.reject');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tutors', App\Http\Controllers\TutorController::class);
    Route::get('/tutors/assign', [App\Http\Controllers\TutorController::class, 'assign'])->name('tutors.assign');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('payments', App\Http\Controllers\PaymentController::class);
});

// Admin Dashboard Route
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [App\Http\Controllers\DashboardController::class, 'admin'])->name('dashboard.admin');
});

// Schedule Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/schedule/today', [App\Http\Controllers\ScheduleController::class, 'showToday'])->name('schedule.today');
    Route::post('/schedule/post', [App\Http\Controllers\ScheduleController::class, 'postSchedule'])->name('schedule.post');
    Route::get('/schedule/generate', [App\Http\Controllers\ScheduleController::class, 'generateTodaySchedule'])->name('schedule.generate');
    Route::get('/schedule/settings', [App\Http\Controllers\ScheduleController::class, 'settings'])->name('schedule.settings');
    Route::get('/schedule/weekly', [App\Http\Controllers\ScheduleController::class, 'weekly'])->name('schedule.weekly');
});

// Attendance Approval Routes (Admin Only)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/attendance/pending', [App\Http\Controllers\AttendanceController::class, 'pending'])->name('attendance.pending');
    Route::post('/attendance/bulk-approve', [App\Http\Controllers\AttendanceController::class, 'bulkApprove'])->name('attendance.bulk.approve');
    Route::post('/attendance/bulk-reject', [App\Http\Controllers\AttendanceController::class, 'bulkReject'])->name('attendance.bulk.reject');
});

// Notice Board Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('noticeboard', App\Http\Controllers\NoticeController::class);
});

// Parent Portal Routes
Route::middleware(['auth', 'verified', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Student\StudentPortalHomeController::class, 'parentDashboard'])
        ->name('dashboard');

    // Students
    Route::get('/students', [App\Http\Controllers\Student\StudentProfileController::class, 'index'])
        ->name('students.index');
    Route::get('/students/{student}', [App\Http\Controllers\Student\StudentProfileController::class, 'show'])
        ->name('students.show');

    // Student Progress
    Route::get('/students/{student}/progress', [App\Http\Controllers\Student\StudentProgressController::class, 'index'])
        ->name('students.progress');

    // Student Reports (Director-Approved)
    Route::get('/students/{student}/reports', [App\Http\Controllers\Parent\ParentReportController::class, 'index'])
        ->name('students.reports');
    Route::get('/students/{student}/reports/{report}', [App\Http\Controllers\Parent\ParentReportController::class, 'show'])
        ->name('students.reports.show');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Parent\ParentNotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\Parent\ParentNotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    // Settings
    Route::get('/settings', [App\Http\Controllers\Parent\ParentSettingsController::class, 'index'])
        ->name('settings.index');
    Route::put('/settings/profile', [App\Http\Controllers\Parent\ParentSettingsController::class, 'updateProfile'])
        ->name('settings.profile');
    Route::put('/settings/password', [App\Http\Controllers\Parent\ParentSettingsController::class, 'updatePassword'])
        ->name('settings.password');
    Route::put('/settings/notifications', [App\Http\Controllers\Parent\ParentSettingsController::class, 'updateNotifications'])
        ->name('settings.notifications');
});

// Student Portal Routes
Route::prefix('student')
    ->middleware(['auth', 'verified', 'role:student'])
    ->name('student.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Student\StudentPortalHomeController::class, 'studentDashboard'])
            ->name('dashboard');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Student\StudentProfileController::class, 'index'])
            ->name('profile');

        // Progress
        Route::get('/progress', [App\Http\Controllers\Student\StudentProgressController::class, 'index'])
            ->name('progress.index');
        Route::get('/progress/{milestone}', [App\Http\Controllers\Student\StudentProgressController::class, 'show'])
            ->name('progress.show');
        Route::post('/progress/{progress}/complete', [App\Http\Controllers\Student\StudentProgressController::class, 'markComplete'])
            ->name('progress.complete');

        // Roadmap
        Route::get('/roadmap', [App\Http\Controllers\Student\StudentRoadmapController::class, 'full'])
            ->name('roadmap.full');
        Route::get('/roadmap/stage/{stageSlug}', [App\Http\Controllers\Student\StudentRoadmapController::class, 'stageShow'])
            ->name('roadmap.stage');

        // Attendance
        Route::get('/attendance', [App\Http\Controllers\Student\StudentAttendanceController::class, 'index'])
            ->name('attendance.index');
        Route::get('/attendance/chart/{month}', [App\Http\Controllers\Student\StudentAttendanceController::class, 'attendanceChart'])
            ->name('attendance.chart');
        Route::get('/attendance/{record}', [App\Http\Controllers\Student\StudentAttendanceController::class, 'show'])
            ->name('attendance.show');

        // Student Reports (Director-Approved Reports for Students)
        Route::get('/reports', [App\Http\Controllers\Student\StudentReportController::class, 'index'])
            ->name('reports.index');
        Route::get('/reports/{report}', [App\Http\Controllers\Student\StudentReportController::class, 'show'])
            ->name('reports.show');
        Route::get('/reports/{report}/pdf', [App\Http\Controllers\Student\StudentReportController::class, 'exportPdf'])
            ->name('reports.pdf');
        Route::get('/reports/{report}/download', [App\Http\Controllers\Student\StudentReportController::class, 'download'])
            ->name('reports.download');
        Route::get('/reports/{report}/print', [App\Http\Controllers\Student\StudentReportController::class, 'print'])
            ->name('reports.print');

        // Settings (Read-Only)
        Route::get('/settings', [App\Http\Controllers\Student\StudentSettingsController::class, 'index'])
            ->name('settings.index');
    });

// Manager Portal Routes
Route::prefix('manager')
    ->middleware(['auth', 'verified', 'role:manager'])
    ->name('manager.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Manager\ManagerDashboardController::class, 'index'])
            ->name('dashboard');

        // Students (read only)
        Route::get('/students', [App\Http\Controllers\Manager\ManagerStudentController::class, 'index'])
            ->name('students.index');
        Route::get('/students/{student}', [App\Http\Controllers\Manager\ManagerStudentController::class, 'show'])
            ->name('students.show');
        Route::get('/students/{student}/progress', [App\Http\Controllers\Manager\ManagerStudentController::class, 'progress'])
            ->name('students.progress');
        Route::get('/students/{student}/attendance', [App\Http\Controllers\Manager\ManagerStudentController::class, 'attendance'])
            ->name('students.attendance');
        Route::get('/students/{student}/reports', [App\Http\Controllers\Manager\ManagerStudentController::class, 'reports'])
            ->name('students.reports');

        // Tutors (read only)
        Route::get('/tutors', [App\Http\Controllers\Manager\ManagerTutorController::class, 'index'])
            ->name('tutors.index');
        Route::get('/tutors/{tutor}', [App\Http\Controllers\Manager\ManagerTutorController::class, 'show'])
            ->name('tutors.show');
        Route::get('/tutors/{tutor}/performance', [App\Http\Controllers\Manager\ManagerTutorController::class, 'performance'])
            ->name('tutors.performance');

        // Attendance (with approval capability)
        Route::get('/attendance', [App\Http\Controllers\Manager\ManagerAttendanceController::class, 'index'])
            ->name('attendance.index');
        Route::get('/attendance/pending', [App\Http\Controllers\Manager\ManagerAttendanceController::class, 'pending'])
            ->name('attendance.pending');
        Route::get('/attendance/{record}', [App\Http\Controllers\Manager\ManagerAttendanceController::class, 'show'])
            ->name('attendance.show');
        Route::post('/attendance/{attendance}/approve', [App\Http\Controllers\Manager\ManagerAttendanceController::class, 'approve'])
            ->name('attendance.approve');
        Route::post('/attendance/{attendance}/reject', [App\Http\Controllers\Manager\ManagerAttendanceController::class, 'reject'])
            ->name('attendance.reject');
        Route::post('/attendance/bulk-approve', [App\Http\Controllers\Manager\ManagerAttendanceController::class, 'bulkApprove'])
            ->name('attendance.bulkApprove');
        Route::get('/attendance/calendar/view', [App\Http\Controllers\Manager\ManagerAttendanceController::class, 'calendar'])
            ->name('attendance.calendar');

        // Tutor Reports (with approval capability)
        Route::get('/reports', [App\Http\Controllers\Manager\ManagerReportsController::class, 'index'])
            ->name('reports.index');
        Route::get('/reports/{report}', [App\Http\Controllers\Manager\ManagerReportsController::class, 'show'])
            ->name('reports.show');
        Route::post('/reports/{report}/comment', [App\Http\Controllers\Manager\ManagerReportsController::class, 'comment'])
            ->name('reports.comment');
        Route::post('/reports/{report}/approve', [App\Http\Controllers\Manager\ManagerReportsController::class, 'approve'])
            ->name('reports.approve');
        Route::post('/reports/{report}/request-changes', [App\Http\Controllers\Manager\ManagerReportsController::class, 'requestChanges'])
            ->name('reports.requestChanges');

        // Tutor Portal Reports Review (New Tutor Portal)
        Route::get('/tutor-reports', [App\Http\Controllers\Manager\ReportReviewController::class, 'index'])
            ->name('tutor-reports.index');
        Route::get('/tutor-reports/{report}', [App\Http\Controllers\Manager\ReportReviewController::class, 'show'])
            ->name('tutor-reports.show');
        Route::post('/tutor-reports/{report}/approve', [App\Http\Controllers\Manager\ReportReviewController::class, 'approve'])
            ->name('tutor-reports.approve');
        Route::post('/tutor-reports/{report}/correction', [App\Http\Controllers\Manager\ReportReviewController::class, 'sendBackForCorrection'])
            ->name('tutor-reports.correction');
        Route::get('/tutor-reports/{report}/pdf', [App\Http\Controllers\Manager\ReportReviewController::class, 'exportPdf'])
            ->name('tutor-reports.pdf');
        Route::get('/tutor-reports/{report}/print', [App\Http\Controllers\Manager\ReportReviewController::class, 'print'])
            ->name('tutor-reports.print');

        // Notice Board (can create, with restrictions)
        Route::get('/notices', [App\Http\Controllers\Manager\ManagerNoticeController::class, 'index'])
            ->name('notices.index');
        Route::get('/notices/create', [App\Http\Controllers\Manager\ManagerNoticeController::class, 'create'])
            ->name('notices.create');
        Route::post('/notices', [App\Http\Controllers\Manager\ManagerNoticeController::class, 'store'])
            ->name('notices.store');
        Route::get('/notices/{notice}', [App\Http\Controllers\Manager\ManagerNoticeController::class, 'show'])
            ->name('notices.show');
        Route::get('/notices/{notice}/edit', [App\Http\Controllers\Manager\ManagerNoticeController::class, 'edit'])
            ->name('notices.edit');
        Route::put('/notices/{notice}', [App\Http\Controllers\Manager\ManagerNoticeController::class, 'update'])
            ->name('notices.update');
        Route::delete('/notices/{notice}', [App\Http\Controllers\Manager\ManagerNoticeController::class, 'destroy'])
            ->name('notices.destroy');

        // Assessments
        Route::get('/assessments', [App\Http\Controllers\Manager\AssessmentController::class, 'index'])
            ->name('assessments.index');
        Route::get('/assessments/{assessment}', [App\Http\Controllers\Manager\AssessmentController::class, 'show'])
            ->name('assessments.show');
        Route::post('/assessments/{assessment}/comment', [App\Http\Controllers\Manager\AssessmentController::class, 'comment'])
            ->name('assessments.comment');
    });

// Director Portal Routes
Route::prefix('director')
    ->middleware(['auth', 'verified', 'role:director'])
    ->name('director.')
    ->group(function () {
        // Director Final Approval for Tutor Reports (Legacy - ReportApprovalController)
        // Keeping these routes for backward compatibility
        Route::get('/reports-legacy', [App\Http\Controllers\Director\ReportApprovalController::class, 'index'])
            ->name('reports-legacy.index');
        Route::get('/reports-legacy/{report}', [App\Http\Controllers\Director\ReportApprovalController::class, 'show'])
            ->name('reports-legacy.show');
        Route::post('/reports-legacy/{report}/approve', [App\Http\Controllers\Director\ReportApprovalController::class, 'approve'])
            ->name('reports-legacy.approve');
        Route::post('/reports-legacy/{report}/reject', [App\Http\Controllers\Director\ReportApprovalController::class, 'reject'])
            ->name('reports-legacy.reject');
        Route::get('/reports-legacy/{report}/export', [App\Http\Controllers\Director\ReportApprovalController::class, 'export'])
            ->name('reports-legacy.export');

        // Director Reports (New - DirectorReportController)
        Route::get('/reports', [App\Http\Controllers\Director\DirectorReportController::class, 'index'])
            ->name('reports.index');
        Route::get('/reports/{report}', [App\Http\Controllers\Director\DirectorReportController::class, 'show'])
            ->name('reports.show');
        Route::post('/reports/{report}/approve', [App\Http\Controllers\Director\DirectorReportController::class, 'approve'])
            ->name('reports.approve');
        Route::post('/reports/{report}/comment', [App\Http\Controllers\Director\DirectorReportController::class, 'comment'])
            ->name('reports.comment');
        Route::get('/reports/{report}/pdf', [App\Http\Controllers\Director\DirectorReportController::class, 'exportPdf'])
            ->name('reports.pdf');
        Route::get('/reports/{report}/print', [App\Http\Controllers\Director\DirectorReportController::class, 'print'])
            ->name('reports.print');

        // Director Assessments
        Route::get('/assessments', [App\Http\Controllers\Director\DirectorAssessmentController::class, 'index'])
            ->name('assessments.index');
        Route::get('/assessments/{assessment}', [App\Http\Controllers\Director\DirectorAssessmentController::class, 'show'])
            ->name('assessments.show');
        Route::post('/assessments/{assessment}/approve', [App\Http\Controllers\Director\DirectorAssessmentController::class, 'approve'])
            ->name('assessments.approve');
        Route::post('/assessments/{assessment}/comment', [App\Http\Controllers\Director\DirectorAssessmentController::class, 'comment'])
            ->name('assessments.comment');

        // Director Activity Logs
        Route::get('/activity-logs', [App\Http\Controllers\Director\DirectorActivityController::class, 'index'])
            ->name('activity-logs.index');

        // Director Analytics & Dashboard
        Route::get('/analytics', [App\Http\Controllers\Director\AnalyticsController::class, 'index'])
            ->name('analytics.index');
        Route::get('/analytics/enrollments', [App\Http\Controllers\Director\AnalyticsController::class, 'getEnrollmentsData'])
            ->name('analytics.enrollments');
        Route::get('/analytics/reports', [App\Http\Controllers\Director\AnalyticsController::class, 'getReportsData'])
            ->name('analytics.reports');
        Route::get('/analytics/tutors', [App\Http\Controllers\Director\AnalyticsController::class, 'getTutorPerformanceData'])
            ->name('analytics.tutors');
        Route::get('/analytics/assessments', [App\Http\Controllers\Director\AnalyticsController::class, 'getAssessmentData'])
            ->name('analytics.assessments');
        Route::get('/analytics/reports/export', [App\Http\Controllers\Director\AnalyticsController::class, 'exportReportsCsv'])
            ->name('analytics.reports.export');
        Route::get('/analytics/tutors/export', [App\Http\Controllers\Director\AnalyticsController::class, 'exportTutorsCsv'])
            ->name('analytics.tutors.export');

        // Director Settings
        Route::get('/settings', [App\Http\Controllers\Director\DirectorSettingsController::class, 'index'])
            ->name('settings.index');
        Route::put('/settings/notifications', [App\Http\Controllers\Director\DirectorSettingsController::class, 'updateNotificationPreferences'])
            ->name('settings.notifications.update');
        Route::put('/settings/profile', [App\Http\Controllers\Director\DirectorSettingsController::class, 'updateProfile'])
            ->name('settings.profile.update');
        Route::put('/settings/password', [App\Http\Controllers\Director\DirectorSettingsController::class, 'updatePassword'])
            ->name('settings.password.update');

        // Director Dashboard (explicit route)
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'director'])
            ->name('dashboard');

        // Director Students (Full CRUD)
        Route::resource('students', App\Http\Controllers\Director\DirectorStudentController::class)
            ->names('students');

        // Director Tutors (Full CRUD)
        Route::resource('tutors', App\Http\Controllers\Director\DirectorTutorController::class)
            ->names('tutors');

        // Director Attendance
        Route::get('/attendance', [App\Http\Controllers\Director\DirectorAttendanceController::class, 'index'])
            ->name('attendance.index');
        Route::get('/attendance/{attendance}', [App\Http\Controllers\Director\DirectorAttendanceController::class, 'show'])
            ->name('attendance.show');
        Route::post('/attendance/{attendance}/approve', [App\Http\Controllers\Director\DirectorAttendanceController::class, 'approve'])
            ->name('attendance.approve');

        // Director Finance
        Route::get('/finance', [App\Http\Controllers\Director\DirectorFinanceController::class, 'index'])
            ->name('finance.index');
        Route::post('/finance/income', [App\Http\Controllers\Director\DirectorFinanceController::class, 'storeIncome'])
            ->name('finance.income.store');
        Route::post('/finance/expense', [App\Http\Controllers\Director\DirectorFinanceController::class, 'storeExpense'])
            ->name('finance.expense.store');
        Route::get('/finance/export', [App\Http\Controllers\Director\DirectorFinanceController::class, 'export'])
            ->name('finance.export');

        // Director Notices (Full CRUD)
        Route::resource('notices', App\Http\Controllers\Director\DirectorNoticeController::class)
            ->names('notices');
    });

// Tutor Portal Routes
Route::prefix('tutor')
    ->middleware(['auth', 'verified', 'role:tutor'])
    ->name('tutor.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Tutor\DashboardController::class, 'index'])
            ->name('dashboard');

        // Attendance
        Route::get('/attendance/create', [App\Http\Controllers\Tutor\AttendanceController::class, 'create'])
            ->name('attendance.create');
        Route::post('/attendance', [App\Http\Controllers\Tutor\AttendanceController::class, 'store'])
            ->name('attendance.store');
        Route::get('/attendance/{attendance}', [App\Http\Controllers\Tutor\AttendanceController::class, 'show'])
            ->name('attendance.show');

        // Reports
        Route::resource('reports', App\Http\Controllers\Tutor\ReportController::class);
        Route::post('reports/{report}/submit', [App\Http\Controllers\Tutor\ReportController::class, 'submit'])
            ->name('reports.submit');
        Route::get('reports/{report}/pdf', [App\Http\Controllers\Tutor\ReportController::class, 'exportPdf'])
            ->name('reports.pdf');
        Route::get('reports/{report}/print', [App\Http\Controllers\Tutor\ReportController::class, 'print'])
            ->name('reports.print');
        Route::post('reports/{report}/comments', [App\Http\Controllers\Tutor\CommentController::class, 'store'])
            ->name('reports.comments.store');

        // Students (view-only)
        Route::get('/students', [App\Http\Controllers\Tutor\StudentController::class, 'index'])
            ->name('students.index');
        Route::get('/students/{student}', [App\Http\Controllers\Tutor\StudentController::class, 'show'])
            ->name('students.show');

        // Schedule (view-only)
        Route::get('/schedule/today', [App\Http\Controllers\Tutor\ScheduleController::class, 'today'])
            ->name('schedule.today');

        // Availability
        Route::get('/availability', [App\Http\Controllers\Tutor\AvailabilityController::class, 'index'])
            ->name('availability.index');
        Route::post('/availability', [App\Http\Controllers\Tutor\AvailabilityController::class, 'store'])
            ->name('availability.store');
        Route::put('/availability/{availability}', [App\Http\Controllers\Tutor\AvailabilityController::class, 'update'])
            ->name('availability.update');
        Route::delete('/availability/{availability}', [App\Http\Controllers\Tutor\AvailabilityController::class, 'destroy'])
            ->name('availability.destroy');

        // Profile
        Route::get('/profile/edit', [App\Http\Controllers\Tutor\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Tutor\ProfileController::class, 'update'])
            ->name('profile.update');
    });

// Analytics Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/students', [App\Http\Controllers\AnalyticsController::class, 'students'])->name('analytics.students');
    Route::get('/analytics/tutors', [App\Http\Controllers\AnalyticsController::class, 'tutors'])->name('analytics.tutors');
    Route::get('/analytics/attendance', [App\Http\Controllers\AnalyticsController::class, 'attendance'])->name('analytics.attendance');
    Route::get('/analytics/reports', [App\Http\Controllers\AnalyticsController::class, 'reports'])->name('analytics.reports');
});

// Notifications Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications/mark-all-read', function() {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read');
    })->name('notifications.markAllRead');
    
    Route::post('/notifications/{id}/mark-read', function($id) {
        Auth::user()->notifications()->where('id', $id)->first()->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.markRead');
});

// Classes Routes (Placeholder)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/classes', function() {
        return redirect()->route('dashboard')->with('info', 'Classes module coming soon');
    })->name('classes.index');

    Route::get('/classes/create', function() {
        return redirect()->route('dashboard')->with('info', 'Classes module coming soon');
    })->name('classes.create');
});

// Assessments Routes (Placeholder)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/assessments', function() {
        return redirect()->route('dashboard')->with('info', 'Assessments module coming soon');
    })->name('assessments.index');

    Route::get('/assessments/pending', function() {
        return redirect()->route('dashboard')->with('info', 'Assessments module coming soon');
    })->name('assessments.pending');

    Route::get('/assessments/create', function() {
        return redirect()->route('dashboard')->with('info', 'Assessments module coming soon');
    })->name('assessments.create');
});

// Help Center Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/help', function() {
        return view('help.index');
    })->name('help.index');
});

// Settings Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/settings', function() {
        return view('settings.index');
    })->name('settings.index');
});

// Dashboard Route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
