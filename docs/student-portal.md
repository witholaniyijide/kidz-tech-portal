# Student/Parent Portal Documentation

## Overview

The Student/Parent Portal provides students and their parents with access to approved reports, progress tracking, roadmap visualization, attendance summaries, and notifications.

**Phase 5** implemented full backend integration including:
- Controller endpoints for reports, roadmap, progress, and attendance
- PDF export functionality
- Chart.js data endpoints
- Notification management
- Comprehensive tests

---

## Controllers & Endpoints

### 1. StudentReportController

**Namespace:** `App\Http\Controllers\Student\StudentReportController`

#### Methods

##### `index(Request $request)`
Lists director-approved reports for the current student with filtering support.

**Route:** `GET /student/reports`
**Name:** `student.reports.index`

**Filters:**
- `q` (string): Keyword search in progress_summary, strengths, next_steps, month
- `month` (string): Filter by month (e.g., "2025-01")
- `sort` (string): Sort order - `newest` (default), `oldest`, `rating`

**Response:**
- View: `student.reports.index`
- Variables: `$student`, `$reports` (paginated, 20 per page)

**Example:**
```
GET /student/reports?q=Python&sort=rating
```

##### `show(TutorReport $report)`
Display a single director-approved report with radar chart data.

**Route:** `GET /student/reports/{report}`
**Name:** `student.reports.show`

**Response:**
- View: `student.reports.show`
- Variables: `$student`, `$report`, `$radarData`

**radarData Structure:**
```php
[
    'labels' => ['Attendance', 'Performance', 'Progress', 'Engagement', 'Technical Skills'],
    'values' => [90, 80, 75, 85, 70] // 0-100 scale
]
```

##### `exportPdf(TutorReport $report)`
Generate and download PDF version of the report.

**Route:** `GET /student/reports/{report}/pdf`
**Name:** `student.reports.pdf`

**Response:**
- Content-Type: `application/pdf`
- File: `Report-{id}-{month}.pdf`

**PDF Settings:**
- Paper: A4
- Orientation: Portrait
- View Template: `resources/views/student/reports/print.blade.php`

##### `download(TutorReport $report)`
Alias for `exportPdf()` - same functionality.

**Route:** `GET /student/reports/{report}/download`
**Name:** `student.reports.download`

##### `print(TutorReport $report)`
Return print-friendly HTML view of the report.

**Route:** `GET /student/reports/{report}/print`
**Name:** `student.reports.print`

**Response:**
- View: `student.reports.print`
- Variables: `$student`, `$report`

---

### 2. StudentRoadmapController

**Namespace:** `App\Http\Controllers\Student\StudentRoadmapController`

#### Methods

##### `full()`
Display the complete curriculum roadmap with student progress.

**Route:** `GET /student/roadmap`
**Name:** `student.roadmap.full`

**Response:**
- View: `student.roadmap.full`
- Variables: `$student`, `$stages`, `$currentStage`, `$completedStages`, `$remainingStages`, `$totalMilestones`, `$completedMilestones`

**Stages Array Structure:**
```php
[
    'name' => 'Intro to CS',
    'slug' => 'intro-to-cs',
    'duration' => '4 weeks',
    'prerequisites' => 'None',
    'outcomes' => ['...'],
    'color' => 'indigo',
    'code' => 'ICS'
]
```

##### `stageShow($stageSlug)`
Show detailed milestones for a specific curriculum stage.

**Route:** `GET /student/roadmap/stage/{stageSlug}`
**Name:** `student.roadmap.stage`

**Response:**
- View: `student.roadmap.stage`
- Variables: `$student`, `$stageName`, `$stageData`, `$milestones` (paginated, 20 per page)

---

### 3. StudentProgressController

**Namespace:** `App\Http\Controllers\Student\StudentProgressController`

#### Methods

##### `index()`
List all progress milestones for the authenticated student.

**Route:** `GET /student/progress`
**Name:** `student.progress.index`

**Response:**
- View: `student.progress.index`
- Variables: `$student`, `$progressItems`

##### `show(StudentProgress $milestone)`
Display detailed view of a specific milestone.

**Route:** `GET /student/progress/{milestone}`
**Name:** `student.progress.show`

**Response:**
- View: `student.progress.show`
- Variables: `$milestone`, `$student`

##### `markComplete(Request $request, StudentProgress $progress)`
Mark a milestone as complete or incomplete (Tutor/Admin only).

**Route:** `POST /student/progress/{progress}/complete`
**Name:** `student.progress.complete`

**Authorization:** Requires `update` permission (Tutor/Admin only)

**Request Body:**
```json
{
    "completed": true
}
```

**Response:**
- Redirect back with success message
- Automatically recalculates `roadmap_progress` percentage

---

### 4. StudentAttendanceController

**Namespace:** `App\Http\Controllers\Student\StudentAttendanceController`

#### Methods

##### `index(Request $request)`
Display attendance summary with statistics and chart data.

**Route:** `GET /student/attendance`
**Name:** `student.attendance.index`

**Query Parameters:**
- `month` (optional): Filter by month (default: current month, format: "Y-m")

**Response:**
- View: `student.attendance.index`
- Variables: `$student`, `$attendanceRecords` (paginated, 20 per page), `$attendanceRate`, `$completedClasses`, `$missedClasses`, `$currentStreak`, `$monthlyData`, `$selectedDate`

**Statistics:**
- **attendanceRate** (float): Percentage of classes attended (0-100)
- **completedClasses** (int): Total classes marked as "present"
- **missedClasses** (int): Total classes marked as "absent"
- **currentStreak** (int): Consecutive classes attended

##### `attendanceChart(Student $student, $month)`
Return JSON Chart.js data for a specific month.

**Route:** `GET /student/attendance/chart/{month}`
**Name:** `student.attendance.chart`

**Response:**
```json
{
    "labels": ["Jan 1", "Jan 5", "Jan 10"],
    "datasets": [
        {
            "label": "Present",
            "data": [1, 2, 1],
            "backgroundColor": "rgba(34, 197, 94, 0.5)",
            "borderColor": "rgba(34, 197, 94, 1)",
            "borderWidth": 2
        },
        {
            "label": "Absent",
            "data": [0, 0, 1],
            "backgroundColor": "rgba(239, 68, 68, 0.5)",
            "borderColor": "rgba(239, 68, 68, 1)",
            "borderWidth": 2
        }
    ]
}
```

**Chart.js Integration Example:**
```javascript
const ctx = document.getElementById('attendanceChart');
fetch(`/student/attendance/chart/${selectedMonth}`)
    .then(response => response.json())
    .then(data => {
        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
```

##### `show(AttendanceRecord $record)`
Display detailed view of a specific attendance record.

**Route:** `GET /student/attendance/{record}`
**Name:** `student.attendance.show`

**Response:**
- View: `student.attendance.show`
- Variables: `$student`, `$record`

---

### 5. ParentNotificationController

**Namespace:** `App\Http\Controllers\Parent\ParentNotificationController`

#### Methods

##### `index()`
List all notifications for the authenticated parent.

**Route:** `GET /parent/notifications`
**Name:** `parent.notifications.index`

**Response:**
- View: `parent.notifications.index`
- Variables: `$notifications` (paginated, 20 per page)

##### `markAsRead(ParentNotification $notification)`
Mark a specific notification as read.

**Route:** `POST /parent/notifications/{notification}/read`
**Name:** `parent.notifications.read`

**AJAX Support:** Returns JSON when `Accept: application/json` header is present.

**Response (JSON):**
```json
{
    "success": true,
    "message": "Notification marked as read."
}
```

**Response (Web):**
- Redirect back with success message

##### `markAllRead()`
Mark all notifications as read for the current parent.

**Route:** `POST /parent/notifications/mark-all-read`
**Name:** `parent.notifications.markAllRead`

**AJAX Support:** Returns JSON when `Accept: application/json` header is present.

**Response (JSON):**
```json
{
    "success": true,
    "message": "All notifications marked as read."
}
```

**AJAX Example:**
```javascript
fetch('/parent/notifications/mark-all-read', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    console.log(data.message);
});
```

---

## PDF Export Configuration

### DomPDF Package
- Package: `barryvdh/laravel-dompdf`
- Version: `^3.1`

### PDF Generation

Controllers use the `Barryvdh\DomPDF\Facade\Pdf` facade:

```php
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('student.reports.print', compact('report'))
    ->setPaper('a4', 'portrait');

return $pdf->download('Report-' . $report->id . '-' . $report->month . '.pdf');
```

### Print View Requirements
- Template: `resources/views/student/reports/print.blade.php`
- Must use absolute URLs for images: `asset('path/to/image.jpg')`
- Inline CSS for styling (external stylesheets not supported)
- Variables passed: `$report` (with loaded relationships: tutor, student, director)

---

## Chart.js Integration

### Radar Chart (Report Performance)

**Data Structure:**
```php
$radarData = [
    'labels' => ['Attendance', 'Performance', 'Progress', 'Engagement', 'Technical Skills'],
    'values' => [90, 80, 75, 85, 70]
];
```

**Blade Template:**
```blade
<canvas id="performanceRadar"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const radarData = @json($radarData);
const ctx = document.getElementById('performanceRadar');

new Chart(ctx, {
    type: 'radar',
    data: {
        labels: radarData.labels,
        datasets: [{
            label: 'Student Performance',
            data: radarData.values,
            backgroundColor: 'rgba(14, 165, 233, 0.2)',
            borderColor: 'rgba(14, 165, 233, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            r: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
</script>
```

### Bar Chart (Monthly Attendance)

See `attendanceChart()` method documentation above for JSON structure.

---

## Authorization & Security

### Middleware
All student/parent routes use:
- `auth`: User must be authenticated
- `verified`: Email must be verified
- `role:student` or `role:parent`: Role-based access

### Policies
- **StudentProgressPolicy**: Controls milestone viewing and editing
- **ParentNotificationPolicy**: Controls notification access
- **TutorReportPolicy**: Controls report viewing (only director-approved)

### Access Rules
1. Students can only view their own data
2. Parents can only view data for their linked students
3. Only director-approved reports are visible to students/parents
4. Only tutors and admins can mark milestones as complete

---

## Testing

### Test Files
- `tests/Feature/ParentPortal/ReportControllerTest.php`
- `tests/Feature/ParentPortal/AttendanceControllerTest.php`
- `tests/Feature/ParentPortal/ReportViewerTest.php`

### Run Tests
```bash
php artisan test --filter=ParentPortal
```

### Key Test Cases
1. Report filters (keyword, month, sort)
2. PDF download authentication
3. Radar data presence in report view
4. Attendance chart JSON structure
5. Authorization checks
6. Pagination
7. Streak calculation

---

## Migrations

No new migrations are required for Phase 5. Existing tables are used:
- `students`
- `tutor_reports`
- `student_progress`
- `attendance_records`
- `parent_notifications`

---

## Deployment Checklist

### Local/CI
```bash
php artisan test --filter=ParentPortal
php artisan migrate --env=testing
npm run build
composer install --no-dev
```

### Server Deploy
```bash
cd /home/portal.kidztech.edubeta.net.ng/public_html
git fetch origin
git checkout -b claude/student-parent-phase5-backend-<shortid>
git pull origin <branch>
composer install --no-dev -o
php artisan migrate --force
npm install && npm run build
php artisan optimize:clear && php artisan optimize
php artisan queue:restart
```

---

## Environment Variables

No new environment variables required for Phase 5.

For email notifications (already configured in earlier phases):
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kidztech.com
MAIL_FROM_NAME="KidzTech Portal"
```

---

## Future Enhancements

Potential improvements for Phase 6+:
- Real-time notifications using WebSockets
- Advanced analytics dashboard for students
- Gamification with badges and achievements
- Mobile app API endpoints
- Downloadable progress reports in Excel format
- Parent-teacher messaging system

---

## Support & Troubleshooting

### Common Issues

**Issue:** PDF generation fails
**Solution:** Ensure `barryvdh/laravel-dompdf` is installed and print view uses inline CSS

**Issue:** Chart.js not rendering
**Solution:** Verify Chart.js CDN is loaded before chart initialization script

**Issue:** Attendance chart returns 403
**Solution:** Verify student ID in URL matches authenticated student

**Issue:** Tests failing
**Solution:** Run `php artisan migrate --env=testing` to set up test database

### Debug Mode
Enable debug logging for controllers:
```php
\Log::info('Report data:', ['report' => $report, 'radarData' => $radarData]);
```

---

## API Reference Quick Links

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/student/reports` | GET | List reports with filters |
| `/student/reports/{report}` | GET | View single report |
| `/student/reports/{report}/pdf` | GET | Download PDF |
| `/student/roadmap` | GET | View curriculum roadmap |
| `/student/progress` | GET | List milestones |
| `/student/attendance` | GET | Attendance summary |
| `/student/attendance/chart/{month}` | GET | Chart.js JSON data |
| `/parent/notifications` | GET | List notifications |
| `/parent/notifications/mark-all-read` | POST | Mark all as read |

---

**Documentation Version:** 1.0
**Last Updated:** Phase 5 Implementation
**Maintained By:** KidzTech Development Team
