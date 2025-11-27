# Student/Parent Portal - Final Documentation

## Overview

This document provides comprehensive documentation for the **Student** and **Parent** portals in the KidzTech Coding Club management system. These portals enable students and parents to track learning progress, view reports, manage attendance, and access learning resources.

**Phase 6 (FINAL)** completes the portal with settings pages, notification integration, security hardening, and comprehensive testing.

---

## Table of Contents

1. [Architecture](#architecture)
2. [Parent Portal](#parent-portal)
3. [Student Portal](#student-portal)
4. [Routes](#routes)
5. [Controllers](#controllers)
6. [Models & Relationships](#models--relationships)
7. [Views & Components](#views--components)
8. [Security & Authorization](#security--authorization)
9. [Notifications](#notifications)
10. [Testing](#testing)
11. [Accessibility](#accessibility)
12. [Best Practices](#best-practices)

---

## Architecture

### Design System

- **Color Scheme**: Sky Blue → Cyan gradients
- **UI Style**: Glassmorphism with soft shadows
- **Layout**: Responsive mobile-first design
- **Accessibility**: WCAG AA compliant

### Tech Stack

- **Framework**: Laravel 10.x
- **Frontend**: Blade Templates + Alpine.js
- **Styling**: Tailwind CSS
- **Authentication**: Laravel Sanctum/Breeze

---

## Parent Portal

### Features

- **Dashboard**: Overview of all children's progress
- **Student Reports**: View director-approved tutor reports
- **Attendance Summary**: Track children's attendance
- **Progress Tracking**: Monitor curriculum roadmap progress
- **Notifications**: Receive updates via email and in-app
- **Settings**: Manage profile, password, and notification preferences

### Parent-Student Relationship

Parents are linked to students via the `guardian_student` pivot table:

```php
// User model (Parent)
public function guardiansOf()
{
    return $this->belongsToMany(Student::class, 'guardian_student', 'user_id', 'student_id')
                ->withPivot('relationship', 'primary_contact')
                ->withTimestamps();
}

// Student model
public function guardians()
{
    return $this->belongsToMany(User::class, 'guardian_student', 'student_id', 'user_id')
                ->withPivot('relationship', 'primary_contact')
                ->withTimestamps();
}
```

### Parent Settings

#### Profile Information
- Editable fields: name, email, phone
- Validation: unique email, Nigerian phone format (`/^(070|080|081|090|091)\d{8}$/`)

#### Password Management
- Current password verification required
- Minimum 8 characters with confirmation
- Uses Laravel's password hashing

#### Notification Preferences
- `notify_email`: Receive email notifications
- `notify_in_app`: Show in-app notifications
- `notify_daily_summary`: Daily activity summary

---

## Student Portal

### Features

- **Dashboard**: Personal learning overview
- **Progress Timeline**: View milestones and achievements
- **Curriculum Roadmap**: Visual learning path
- **Reports**: Access approved tutor reports
- **Attendance**: View attendance records
- **Settings (Read-Only)**: View profile and enrollment information

### Student-User Relationship

Students are linked to user accounts via email matching:

```php
// In controllers
$student = Student::where('email', $user->email)->firstOrFail();
```

### Student Settings (Read-Only)

Students can **view only** the following:

- Full name
- Email address
- Student ID
- Enrollment date
- Class schedule
- Class/Google Classroom links
- Tutor information (name, phone, email)
- Progress percentage
- Current roadmap stage
- Completed classes count

**Note**: Students cannot modify their settings. Changes must be made by tutors or administrators.

---

## Routes

### Parent Routes

All parent routes are prefixed with `/parent` and protected by `auth`, `verified`, and `role:parent` middleware.

```php
Route::prefix('parent')
    ->middleware(['auth', 'verified', 'role:parent'])
    ->name('parent.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [StudentPortalHomeController::class, 'parentDashboard'])
            ->name('dashboard');

        // Students
        Route::get('/students', [StudentProfileController::class, 'index'])
            ->name('students.index');
        Route::get('/students/{student}', [StudentProfileController::class, 'show'])
            ->name('students.show');

        // Student Progress
        Route::get('/students/{student}/progress', [StudentProgressController::class, 'index'])
            ->name('students.progress');

        // Student Reports
        Route::get('/students/{student}/reports', [ParentReportController::class, 'index'])
            ->name('students.reports');
        Route::get('/students/{student}/reports/{report}', [ParentReportController::class, 'show'])
            ->name('students.reports.show');

        // Notifications
        Route::get('/notifications', [ParentNotificationController::class, 'index'])
            ->name('notifications.index');
        Route::post('/notifications/{notification}/read', [ParentNotificationController::class, 'markAsRead'])
            ->name('notifications.read');

        // Settings (Phase 6)
        Route::get('/settings', [ParentSettingsController::class, 'index'])
            ->name('settings.index');
        Route::put('/settings/profile', [ParentSettingsController::class, 'updateProfile'])
            ->name('settings.profile');
        Route::put('/settings/password', [ParentSettingsController::class, 'updatePassword'])
            ->name('settings.password');
        Route::put('/settings/notifications', [ParentSettingsController::class, 'updateNotifications'])
            ->name('settings.notifications');
    });
```

### Student Routes

All student routes are prefixed with `/student` and protected by `auth`, `verified`, and `role:student` middleware.

```php
Route::prefix('student')
    ->middleware(['auth', 'verified', 'role:student'])
    ->name('student.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [StudentPortalHomeController::class, 'studentDashboard'])
            ->name('dashboard');

        // Profile
        Route::get('/profile', [StudentProfileController::class, 'index'])
            ->name('profile');

        // Progress
        Route::get('/progress', [StudentProgressController::class, 'index'])
            ->name('progress.index');
        Route::get('/progress/{milestone}', [StudentProgressController::class, 'show'])
            ->name('progress.show');

        // Reports
        Route::get('/reports', [StudentReportController::class, 'index'])
            ->name('reports.index');
        Route::get('/reports/{report}', [StudentReportController::class, 'show'])
            ->name('reports.show');
        Route::get('/reports/{report}/pdf', [StudentReportController::class, 'exportPdf'])
            ->name('reports.pdf');
        Route::get('/reports/{report}/print', [StudentReportController::class, 'print'])
            ->name('reports.print');

        // Settings (Read-Only - Phase 6)
        Route::get('/settings', [StudentSettingsController::class, 'index'])
            ->name('settings.index');
    });
```

### Generic Notification Routes

Available to all authenticated users:

```php
// Mark all notifications as read
Route::get('/notifications/mark-all-read', function() {
    Auth::user()->unreadNotifications->markAsRead();
    return redirect()->back()->with('success', 'All notifications marked as read');
})->name('notifications.markAllRead');

// Mark single notification as read (AJAX)
Route::post('/notifications/{id}/mark-read', function($id) {
    Auth::user()->notifications()->where('id', $id)->first()->markAsRead();
    return response()->json(['success' => true]);
})->name('notifications.markRead');
```

---

## Controllers

### ParentSettingsController

**Location**: `app/Http/Controllers/Parent/ParentSettingsController.php`

**Methods**:

- `index()` - Display settings page
- `updateProfile(Request $request)` - Update name, email, phone
- `updatePassword(Request $request)` - Change password
- `updateNotifications(Request $request)` - Update notification preferences

**Validation**:

```php
// Profile
'name' => 'required|string|max:255'
'email' => 'required|email|unique:users,email,' . $user->id
'phone' => 'nullable|string|regex:/^(070|080|081|090|091)\d{8}$/'

// Password
'current_password' => 'required|current_password'
'new_password' => 'required|confirmed|' . Password::defaults()

// Notifications
'notify_email' => 'boolean'
'notify_in_app' => 'boolean'
'notify_daily_summary' => 'boolean'
```

### StudentSettingsController

**Location**: `app/Http/Controllers/Student/StudentSettingsController.php`

**Methods**:

- `index()` - Display read-only settings page

**Security**:

```php
// Ensure student can only view their own record
abort_if(
    $student->email !== $user->email,
    403,
    'Unauthorized access to student settings.'
);
```

### ParentReportController

**Location**: `app/Http/Controllers/Parent/ParentReportController.php`

**Security Checks** (Phase 6 Hardened):

```php
// Verify guardian relationship
abort_unless(
    $student->guardians->contains(Auth::id()) || Auth::user()->hasRole('admin'),
    403,
    'Unauthorized: You can only view reports for your own children.'
);

// Verify report belongs to student
abort_unless(
    $report->student_id === $student->id,
    403,
    'Unauthorized: This report does not belong to this student.'
);

// Verify report is director-approved
abort_unless(
    $report->status === 'approved-by-director',
    403,
    'Unauthorized: Only director-approved reports are visible to parents.'
);
```

---

## Models & Relationships

### User Model

**Location**: `app/Models/User.php`

**Fillable**:
- name, email, password, phone
- notify_email, notify_in_app, notify_daily_summary
- status, last_login, profile_photo

**Relationships**:
```php
roles() // Many-to-many with Role
guardiansOf() // Many-to-many with Student (parents)
tutor() // One-to-one with Tutor
```

### Student Model

**Location**: `app/Models/Student.php`

**Key Fields**:
- student_id, first_name, last_name, email
- enrollment_date, tutor_id
- roadmap_stage, roadmap_progress
- completed_periods, total_periods
- class_schedule (JSON), google_classroom_link

**Relationships**:
```php
guardians() // Many-to-many with User (parents)
tutor() // Belongs to Tutor
progress() // Has many StudentProgress
approvedReports() // Has many TutorReport (director-approved only)
```

**Methods**:
```php
fullName() // Returns full name string
progressPercentage() // Calculates progress %
```

### TutorReport Model

**Status Values**:
- `draft` - Tutor is editing
- `pending` - Awaiting manager review
- `approved-by-manager` - Manager approved, awaiting director
- `approved-by-director` - Director approved (visible to parents)
- `rejected` - Rejected by manager/director

---

## Views & Components

### Layouts

- `layouts/app.blade.php` - Main application layout (parents)
- `layouts/student.blade.php` - Student portal layout with sidebar
- `layouts/navigation.blade.php` - Shared navigation with notifications

### Parent Views

**Directory**: `resources/views/parent/`

- `dashboard.blade.php` - Parent dashboard
- `settings/index.blade.php` - Settings page (Phase 6)
- `reports/index.blade.php` - Reports list
- `reports/show.blade.php` - Report detail view

### Student Views

**Directory**: `resources/views/student/`

- `dashboard.blade.php` - Student dashboard
- `settings/index.blade.php` - Read-only settings (Phase 6)
- `progress/index.blade.php` - Progress timeline
- `reports/index.blade.php` - Reports list

### Reusable Components

**Location**: `resources/views/components/ui/`

#### Empty State Component

```blade
<x-ui.empty-state
    title="No reports available"
    description="Once your tutor submits and the director approves, your reports will appear here."
    icon="document"
/>
```

**Supported Icons**: inbox, document, bell, chart

---

## Security & Authorization

### Guardian Relationship Verification

**CRITICAL**: Always verify guardian relationship when parents access student data:

```php
// ✅ CORRECT
abort_unless(
    $student->guardians->contains(Auth::id()),
    403,
    'Unauthorized access.'
);

// ❌ INCORRECT (parent_id field doesn't exist)
if ($student->parent_id !== Auth::id()) {
    abort(403);
}
```

### Report Access Control

Parents can **only** view:
1. Reports for **their own children** (guardian relationship verified)
2. Reports with status **`approved-by-director`**

### Student Access Control

Students can **only** view:
1. Their own data (matched by email)
2. Reports approved by director
3. **Cannot modify** settings or data

### Middleware Protection

```php
// Parent routes
->middleware(['auth', 'verified', 'role:parent'])

// Student routes
->middleware(['auth', 'verified', 'role:student'])
```

---

## Notifications

### Notification Bell Dropdown

**Location**: `layouts/navigation.blade.php` (lines 122-181)

**Features**:
- Red badge with unread count
- Dropdown shows last 10 unread notifications
- AJAX mark-as-read on click
- "Mark all as read" action

**Implementation**:

```javascript
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
}
```

### Parent Notifications

**Model**: `ParentNotification`

**Triggers**:
- Report approved by director
- Student progress updated
- Attendance issues

---

## Testing

### Test Suites (Phase 6)

#### ParentPortal/SettingsTest.php

Tests parent settings functionality:
- ✅ View settings page
- ✅ Update profile (success & validation)
- ✅ Change password (success & validation)
- ✅ Update notification preferences
- ✅ Authorization (non-parents blocked)

#### StudentPortal/SettingsTest.php

Tests student settings (read-only):
- ✅ View settings page
- ✅ Display correct student data
- ✅ Cannot modify settings (405 error)
- ✅ Can only view own settings
- ✅ Authorization (non-students blocked)

#### ParentPortal/SecurityBoundaryTest.php

Tests security boundaries:
- ✅ Parent cannot view unlinked student reports
- ✅ Parent can view own students' reports
- ✅ Parent cannot access another parent's student
- ✅ Parent cannot access student settings page
- ✅ Parent can only view director-approved reports
- ✅ Parent with multiple children can access all

### Running Tests

```bash
# Run all parent portal tests
php artisan test --testsuite=Feature --filter=ParentPortal

# Run specific test
php artisan test --filter=SecurityBoundaryTest

# Run with coverage
php artisan test --coverage
```

---

## Accessibility

### WCAG AA Compliance

#### Form Elements
- All inputs have `<label>` with `for` attribute
- Required fields marked with `<span class="text-red-500">*</span>`
- Error messages have `role="alert"`

#### Interactive Elements
- Toggle switches have `role="switch"` and `aria-checked`
- Buttons have descriptive text or `aria-label`
- Links have clear context

#### Keyboard Navigation
- All interactive elements are keyboard accessible
- Focus states visible with `focus:ring-*` classes
- Tab order follows logical flow

#### Color Contrast
- Text meets AA standards (4.5:1 minimum)
- Interactive elements have sufficient contrast
- Error messages use high-contrast colors

---

## Best Practices

### Adding New Progress Milestones

```php
use App\Models\StudentProgress;

StudentProgress::create([
    'student_id' => $student->id,
    'milestone_type' => 'lesson', // lesson, project, quiz, assessment
    'title' => 'Introduction to Variables',
    'description' => 'Completed lesson on JavaScript variables',
    'completed' => true,
    'completed_at' => now(),
]);
```

### Calculating Attendance Rate

```php
$totalClasses = $student->total_periods;
$attendedClasses = $student->attendanceRecords()
    ->where('status', 'present')
    ->count();

$attendanceRate = $totalClasses > 0
    ? round(($attendedClasses / $totalClasses) * 100)
    : 0;
```

### Progress Calculation

```php
// Method 1: Use roadmap_progress field
$progressPercentage = $student->roadmap_progress;

// Method 2: Calculate from progress items
$total = $student->progress()->count();
$completed = $student->progress()->where('completed', true)->count();
$progressPercentage = $total > 0 ? (int)(($completed / $total) * 100) : 0;
```

### API Endpoint Examples (Future)

For AJAX operations:

```javascript
// Fetch student progress
fetch('/api/student/progress')
    .then(response => response.json())
    .then(data => console.log(data));

// Mark notification as read
fetch(`/notifications/${id}/mark-read`, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json'
    }
});
```

---

## Deployment Notes

### Environment Variables

Ensure these are set:

```env
APP_NAME="KidzTech Coding Club"
APP_URL=https://yourapp.com

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@kidztech.com
MAIL_FROM_NAME="KidzTech Coding Club"
```

### Database Migrations

Run in order:

```bash
php artisan migrate
php artisan db:seed --class=RolesSeeder
php artisan db:seed --class=UsersSeeder
php artisan db:seed --class=StudentsSeeder
```

### Optimization

```bash
# Cache routes and views
php artisan route:cache
php artisan view:cache
php artisan config:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

---

## Support & Maintenance

For issues or feature requests:
- Submit GitHub issues
- Contact: tech@kidztech.com
- Slack: #portal-support

**Version**: Phase 6 (Final)
**Last Updated**: November 2024
**Status**: Production Ready ✅
