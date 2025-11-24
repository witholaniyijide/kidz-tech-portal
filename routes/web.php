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
Route::middleware(['auth', 'verified'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ParentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/child/{student}', [App\Http\Controllers\ParentDashboardController::class, 'showChild'])->name('child.show');
    Route::get('/child/{student}/reports', [App\Http\Controllers\ParentDashboardController::class, 'childReports'])->name('child.reports');
    Route::get('/child/{student}/reports/{report}', [App\Http\Controllers\ParentDashboardController::class, 'viewReport'])->name('child.report.view');
    Route::get('/child/{student}/attendance', [App\Http\Controllers\ParentDashboardController::class, 'childAttendance'])->name('child.attendance');
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
