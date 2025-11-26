# Director Portal — Notifications & Communication System

## Overview

Phase 5 implements a comprehensive notification and communication layer that connects Directors → Managers → Tutors → Parents/Students. This system ensures all stakeholders are informed when reports and assessments are approved.

---

## Architecture

### Notification Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    DIRECTOR APPROVES REPORT                      │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ├─────────────────────────────────────┐
                           │                                     │
                           ▼                                     ▼
                    ┌─────────────┐                      ┌─────────────┐
                    │   TUTOR     │                      │   MANAGER   │
                    │             │                      │             │
                    │ • Email     │                      │ • Email     │
                    │ • In-App    │                      │ • In-App    │
                    │ • Database  │                      │ • Database  │
                    └─────────────┘                      └─────────────┘

                           ▼
                    ┌─────────────┐
                    │   PARENT    │
                    │             │
                    │ • Email (3) │◄── Parent user account
                    │   - parent@ │◄── Father email
                    │   - father@ │◄── Mother email
                    │   - mother@ │
                    │ • In-App    │
                    │ • Database  │
                    └─────────────┘

                           ▼
                    ┌─────────────┐
                    │   STUDENT   │
                    │             │
                    │ • Portal    │
                    │   Visibility│
                    └─────────────┘
```

---

## Notification Classes

### 1. TutorReportApprovedNotification

**Location:** `app/Notifications/TutorReportApprovedNotification.php`

**Purpose:** Sent to tutors and managers when a report receives final director approval.

**Channels:**
- `database` - Always enabled
- `mail` - Controlled by `notify_email` preference

**Payload Example:**
```json
{
    "type": "report_approved",
    "report_id": 123,
    "student_id": 45,
    "student_name": "John Doe",
    "month": "2025-01",
    "performance_rating": "excellent",
    "attendance_score": 95,
    "director_comment": "Outstanding progress this month!",
    "approved_at": "2025-01-15 14:30:00",
    "link": "https://app.com/tutor/reports/123"
}
```

---

### 2. AssessmentApprovedNotification

**Location:** `app/Notifications/AssessmentApprovedNotification.php`

**Purpose:** Sent to tutors when their assessment is approved by the director.

**Channels:**
- `database` - Always enabled
- `mail` - Controlled by `notify_email` preference

**Payload Example:**
```json
{
    "type": "assessment_approved",
    "assessment_id": 67,
    "assessment_month": "2025-01",
    "performance_score": 88,
    "professionalism_rating": 5,
    "communication_rating": 4,
    "punctuality_rating": 5,
    "director_comment": "Keep up the excellent work!",
    "approved_at": "2025-01-15 16:45:00",
    "link": "https://app.com/tutor/assessments/67"
}
```

---

### 3. ParentReportAvailableNotification

**Location:** `app/Notifications/ParentReportAvailableNotification.php`

**Purpose:** Sent to parents when a report for their child is approved and published.

**Channels:**
- `database` - Always enabled for parent user
- `mail` - Always enabled for parents (critical notification)

**Payload Example:**
```json
{
    "type": "parent_report_available",
    "report_id": 123,
    "student_id": 45,
    "student_name": "John Doe",
    "month": "2025-01",
    "performance_rating": "excellent",
    "attendance_score": 95,
    "tutor_name": "Jane Smith",
    "approved_at": "2025-01-15 14:30:00",
    "link": "https://app.com/parent/reports/45/123"
}
```

---

### 4. DirectorActionSummaryNotification

**Location:** `app/Notifications/DirectorActionSummaryNotification.php`

**Purpose:** Daily summary email sent to directors at 7:00 PM (requires scheduled job).

**Channels:**
- `database` - Always enabled
- `mail` - Only if `notify_daily_summary` is enabled

**Payload Example:**
```json
{
    "type": "director_daily_summary",
    "date": "2025-01-15",
    "reports_approved_today": 12,
    "assessments_approved_today": 5,
    "reports_pending": 8,
    "assessments_pending": 3,
    "link": "https://app.com/director/dashboard"
}
```

---

## Email Templates (Mailable Classes)

### 1. DirectorFinalApprovalMail

**Location:** `app/Mail/DirectorFinalApprovalMail.php`
**View:** `resources/views/emails/director/final-approval.blade.php`

**Recipients:** Tutor + Managers

**Features:**
- PDF attachment of the report
- Displays student performance summary
- Shows director and manager comments
- Green gradient theme (approval/success)

**Queued:** Yes (implements `ShouldQueue`)

---

### 2. ParentReportReadyMail

**Location:** `app/Mail/ParentReportReadyMail.php`
**View:** `resources/views/emails/parent/report-ready.blade.php`

**Recipients:**
- Parent user account (via `parent.email`)
- Father email (via `student.father_email`)
- Mother email (via `student.mother_email`)

**Features:**
- PDF attachment
- Full report summary with strengths, weaknesses, next steps
- CTA buttons: "View on Portal" and "Download PDF"
- Blue/purple gradient theme

**Queued:** Yes (implements `ShouldQueue`)

---

### 3. SystemNotificationMail

**Location:** `app/Mail/SystemNotificationMail.php`
**View:** `resources/views/emails/system/notification.blade.php`

**Purpose:** Generic template for system-wide notifications

**Parameters:**
- `title` - Email subject
- `body` - Main message content
- `actionText` - Optional CTA button text
- `actionUrl` - Optional CTA button URL
- `metadata` - Optional key-value array for details table

**Queued:** Yes

---

## Notification Preferences

### Database Schema

**Migration:** `2025_11_26_020000_add_notification_preferences_to_users_table.php`

**Fields Added to `users` table:**

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `notify_email` | boolean | `true` | Receive email notifications |
| `notify_in_app` | boolean | `true` | Receive in-app notifications |
| `notify_daily_summary` | boolean | `false` | Receive daily summary emails (Directors only) |

---

### Settings UI

**Route:** `/director/settings`
**Controller:** `DirectorSettingsController`
**View:** `resources/views/director/settings/index.blade.php`

**Features:**
1. **Notification Preferences**
   - Email Notifications toggle
   - In-App Notifications toggle
   - Daily Summary Email toggle (7:00 PM delivery)

2. **Profile Information**
   - Name, Email, Phone
   - Update form with validation

3. **Password Change**
   - Current password verification
   - New password with confirmation
   - Minimum 8 characters

---

## Parent/Student Portal Integration

### Student Portal

**Routes:**
```php
GET  /student/reports           → StudentReportController@index
GET  /student/reports/{report}  → StudentReportController@show
GET  /student/reports/{report}/pdf → StudentReportController@exportPdf
GET  /student/reports/{report}/print → StudentReportController@print
```

**Features:**
- Students see ONLY their own reports
- Only director-approved reports are visible
- Beautiful blue/cyan gradient theme
- Download PDF and print functionality
- Responsive card-based layout

**Views:**
- `resources/views/student/reports/index.blade.php` - Report listing
- `resources/views/student/reports/show.blade.php` - Full report view
- `resources/views/student/reports/print.blade.php` - Print-friendly view

**Authentication:**
- Students must be logged in with 'student' role
- Students linked to `users.id` via `students.user_id`

---

### Parent Portal

**Already Implemented in Phase 4**

Parents can view reports for their children at:
```
GET /parent/reports/{student}/{report}
```

---

## DirectorApprovalService Integration

**Location:** `app/Services/DirectorApprovalService.php`

### Enhanced `approveTutorReport()` Method

When a director approves a report, the following actions occur:

1. **Database Updates:**
   - Report status → `'approved-by-director'`
   - Set `director_id`, `director_comment`, `director_signature`
   - Set `approved_by_director_at` timestamp

2. **Audit Logging:**
   - Create `AuditLog` entry
   - Create `DirectorActivityLog` entry

3. **Notifications Dispatched:**
   - ✅ Tutor → `TutorReportApprovedNotification` (database + email)
   - ✅ Managers → `TutorReportApprovedNotification` (database + email)
   - ✅ Parent → `ParentReportAvailableNotification` (database + email)

4. **Emails Sent (Queued):**
   - ✅ Tutor → `DirectorFinalApprovalMail` (with PDF)
   - ✅ Parent (user account) → `ParentReportReadyMail` (with PDF)
   - ✅ Father email → `ParentReportReadyMail` (with PDF)
   - ✅ Mother email → `ParentReportReadyMail` (with PDF)

**Error Handling:**
- Parent notification failures are logged but don't fail the approval
- All operations wrapped in database transaction

---

## Queue Configuration

**Default:** `QUEUE_CONNECTION=sync` (immediate execution)

**Recommended for Production:**
```env
QUEUE_CONNECTION=database
# or
QUEUE_CONNECTION=redis
```

### Running Queue Workers

```bash
# Development
php artisan queue:work

# Production (with Supervisor)
php artisan queue:work --sleep=3 --tries=3
```

**Supervisor Configuration Example:**
```ini
[program:kidz-tech-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
```

---

## Daily Summary Job (Future Implementation)

**Purpose:** Send directors a daily summary at 7:00 PM

**Implementation Required:**

1. Create console command: `app/Console/Commands/SendDirectorDailySummary.php`
2. Schedule in `app/Console/Kernel.php`:
   ```php
   $schedule->command('director:daily-summary')->dailyAt('19:00');
   ```
3. Query statistics:
   - Reports approved today
   - Assessments approved today
   - Pending reports count
   - Pending assessments count
4. Send `DirectorActionSummaryNotification` to all directors with `notify_daily_summary = true`

---

## Testing

**Test File:** `tests/Feature/Director/NotificationsTest.php`

**Test Coverage:**

✅ Director approval triggers tutor notification
✅ Director approval triggers manager notification
✅ Parent receives email when report is approved
✅ Parent receives notification when report is approved
✅ Tutor receives email with correct metadata
✅ Assessment approval triggers correct notifications
✅ Notification preferences are respected
✅ Report approval creates audit log
✅ Report approval creates director activity log
✅ Notification contains correct data structure
✅ Emails are queued for background processing

**Run Tests:**
```bash
php artisan test --filter NotificationsTest
```

---

## API Endpoints (Future Enhancement)

For mobile app or external integrations:

```
GET  /api/v1/notifications           → List user notifications
POST /api/v1/notifications/{id}/read → Mark notification as read
GET  /api/v1/reports/{id}            → Get report details
GET  /api/v1/reports/{id}/pdf        → Download report PDF
```

---

## Security Considerations

1. **Authorization:**
   - Students can only view their own reports
   - Parents can only view reports for their children
   - Tutors can only see reports they created
   - Directors can see all reports

2. **Data Privacy:**
   - Parent emails are validated before sending
   - Email failures are logged for audit
   - Sensitive comments only visible to authorized users

3. **Rate Limiting:**
   - Consider rate limiting email sends to prevent abuse
   - Queue system provides natural throttling

4. **GDPR Compliance:**
   - Parents can manage notification preferences
   - All notifications logged for audit trail
   - Users can request data export/deletion

---

## Troubleshooting

### Emails Not Sending

1. Check `.env` mail configuration:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-username
   MAIL_PASSWORD=your-password
   MAIL_FROM_ADDRESS=noreply@kidztech.com
   MAIL_FROM_NAME="KidzTech"
   ```

2. Check queue is running:
   ```bash
   php artisan queue:work
   ```

3. Check failed jobs:
   ```bash
   php artisan queue:failed
   ```

4. Retry failed jobs:
   ```bash
   php artisan queue:retry all
   ```

### Notifications Not Appearing

1. Check user notification preferences in database
2. Verify notification channels in notification class
3. Check `notifications` table for entries
4. Ensure user has correct role assigned

### PDF Generation Issues

1. Verify `barryvdh/laravel-dompdf` is installed
2. Check report PDF view exists: `resources/views/tutor/reports/pdf.blade.php`
3. Ensure storage directory is writable

---

## Performance Optimization

### Eager Loading

Always load relationships to avoid N+1 queries:
```php
$report->load(['student', 'tutor', 'director']);
```

### Caching

Consider caching frequently accessed data:
```php
Cache::remember('pending-reports-count', 300, function () {
    return TutorReport::where('status', 'approved-by-manager')->count();
});
```

### Database Indexes

Ensure indexes exist on:
- `tutor_reports.status`
- `tutor_reports.approved_by_director_at`
- `notifications.notifiable_id` and `notifiable_type`

---

## Future Enhancements

1. **SMS Notifications**
   - Integrate Twilio for SMS alerts
   - Add `notify_sms` preference field

2. **Push Notifications**
   - Implement Firebase Cloud Messaging (FCM)
   - Mobile app integration

3. **Notification Bell UI**
   - Real-time notification dropdown in header
   - Unread count badge
   - Mark all as read functionality

4. **Notification Center**
   - Dedicated page to view all notifications
   - Filter by type, date, read/unread
   - Pagination and search

5. **Email Digest Options**
   - Weekly digest instead of daily
   - Custom time preferences

---

## File Structure

```
app/
├── Http/Controllers/
│   ├── Director/
│   │   └── DirectorSettingsController.php
│   └── Student/
│       └── StudentReportController.php
├── Mail/
│   ├── DirectorFinalApprovalMail.php
│   ├── ParentReportReadyMail.php
│   └── SystemNotificationMail.php
├── Notifications/
│   ├── AssessmentApprovedNotification.php
│   ├── DirectorActionSummaryNotification.php
│   ├── ParentReportAvailableNotification.php
│   └── TutorReportApprovedNotification.php
└── Services/
    └── DirectorApprovalService.php (updated)

database/migrations/
└── 2025_11_26_020000_add_notification_preferences_to_users_table.php

resources/views/
├── director/
│   └── settings/
│       └── index.blade.php
├── emails/
│   ├── director/
│   │   └── final-approval.blade.php
│   ├── parent/
│   │   └── report-ready.blade.php
│   └── system/
│       └── notification.blade.php
└── student/
    └── reports/
        ├── index.blade.php
        ├── show.blade.php
        └── print.blade.php

routes/
└── web.php (updated with director settings + student routes)

tests/Feature/Director/
└── NotificationsTest.php
```

---

## Changelog

### Phase 5 - Notifications & Communication Layer

**Added:**
- 4 notification classes with database + email channels
- 3 mailable classes with professional HTML email templates
- Notification preferences system (3 fields in users table)
- Director settings page (profile, password, notification preferences)
- Student portal with report viewing capabilities
- Comprehensive parent notification system (3 email addresses)
- Feature tests for notification system
- Full documentation

**Updated:**
- DirectorApprovalService to dispatch all notifications
- User model with notification preference fields
- Routes file with director settings and student routes

**Total Files:**
- Created: 23 files
- Modified: 3 files

---

## Support

For issues or questions about the notification system:
1. Check the troubleshooting section above
2. Review test cases for expected behavior
3. Check logs in `storage/logs/laravel.log`
4. Review failed jobs in `failed_jobs` table

---

**Document Version:** 1.0
**Last Updated:** November 26, 2025
**Phase:** 5 - Notifications & Communication
