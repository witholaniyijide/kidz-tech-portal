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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('attendance', App\Http\Controllers\AttendanceController::class);
    Route::post('attendance/{attendance}/approve', [App\Http\Controllers\AttendanceController::class, 'approve'])->name('attendance.approve');
    Route::post('attendance/{attendance}/reject', [App\Http\Controllers\AttendanceController::class, 'reject'])->name('attendance.reject');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('reports', App\Http\Controllers\ReportController::class);
    Route::post('reports/{report}/approve', [App\Http\Controllers\ReportController::class, 'approve'])->name('reports.approve');
    Route::post('reports/{report}/reject', [App\Http\Controllers\ReportController::class, 'reject'])->name('reports.reject');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tutors', App\Http\Controllers\TutorController::class);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('payments', App\Http\Controllers\PaymentController::class);
});

// Parent Portal Routes
Route::middleware(['auth', 'verified'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ParentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/child/{student}', [App\Http\Controllers\ParentDashboardController::class, 'showChild'])->name('child.show');
    Route::get('/child/{student}/reports', [App\Http\Controllers\ParentDashboardController::class, 'childReports'])->name('child.reports');
    Route::get('/child/{student}/reports/{report}', [App\Http\Controllers\ParentDashboardController::class, 'viewReport'])->name('child.report.view');
    Route::get('/child/{student}/attendance', [App\Http\Controllers\ParentDashboardController::class, 'childAttendance'])->name('child.attendance');
});

// Analytics Route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');
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

// Dashboard Route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
