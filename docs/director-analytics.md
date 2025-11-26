# Director Analytics Dashboard — Phase 4 Documentation

## Overview

The Director Analytics Dashboard provides comprehensive data visualization and reporting capabilities for the Kidz Tech Portal. This phase introduces executive-level insights through interactive charts, real-time metrics, and data export functionality with performance optimization through strategic caching.

## Table of Contents

1. [Features](#features)
2. [Routes](#routes)
3. [Controller Methods](#controller-methods)
4. [Caching Strategy](#caching-strategy)
5. [Chart Types & Data](#chart-types--data)
6. [Export Functionality](#export-functionality)
7. [Database Indexes](#database-indexes)
8. [Testing](#testing)
9. [Troubleshooting](#troubleshooting)

---

## Features

### Executive Dashboard Stats (6 Stat Cards)
- **Total Students**: Overall student count
- **Active Students**: Currently enrolled students
- **Total Tutors**: All registered tutors
- **Active Tutors**: Currently active tutors
- **Pending Reports**: Manager-approved reports awaiting director approval
- **Pending Assessments**: Manager-approved assessments awaiting director approval

### Analytics Charts (8 Chart.js Visualizations)
1. **Enrollment Trends** — Line chart showing 12-month enrollment growth
2. **Monthly Reports** — Line chart tracking report submissions over time
3. **Reports by Status** — Doughnut chart showing workflow distribution
4. **Students per Tutor** — Horizontal bar chart showing tutor workload
5. **Attendance by Tutor** — Horizontal bar chart comparing tutor attendance rates
6. **Performance Score Trend** — Line chart tracking assessment scores
7. **Rating Distribution** — Pie chart showing professionalism ratings
8. **Communication Ratings** — Pie chart for communication assessment scores

### Export Capabilities
- **CSV Reports Export**: Download monthly tutor reports with full details
- **CSV Tutors Export**: Export tutor performance data with metrics
- Both use chunked streaming to handle large datasets efficiently

---

## Routes

All analytics routes are protected by `['auth', 'verified', 'role:director']` middleware.

| Method | URI | Controller Method | Route Name | Description |
|--------|-----|-------------------|------------|-------------|
| GET | `/director/analytics` | `index()` | `director.analytics.index` | Main analytics dashboard page |
| GET | `/director/analytics/enrollments` | `getEnrollmentsData()` | `director.analytics.enrollments` | JSON: Enrollment data for charts |
| GET | `/director/analytics/reports` | `getReportsData()` | `director.analytics.reports` | JSON: Report workflow analytics |
| GET | `/director/analytics/tutors` | `getTutorPerformanceData()` | `director.analytics.tutors` | JSON: Tutor performance metrics |
| GET | `/director/analytics/assessments` | `getAssessmentData()` | `director.analytics.assessments` | JSON: Assessment analytics |
| GET | `/director/analytics/reports/export?month=YYYY-MM` | `exportReportsCsv()` | `director.analytics.reports.export` | CSV: Monthly reports export |
| GET | `/director/analytics/tutors/export` | `exportTutorsCsv()` | `director.analytics.tutors.export` | CSV: Tutor performance export |

---

## Controller Methods

### `index()`
Renders the main analytics dashboard with 6 stat cards.

**View**: `resources/views/director/analytics/index.blade.php`

**Data Passed**:
```php
[
    'total_students' => int,
    'active_students' => int,
    'total_tutors' => int,
    'active_tutors' => int,
    'pending_reports' => int,
    'pending_assessments' => int
]
```

**Cache**: 5 minutes (300 seconds)

---

### `getEnrollmentsData()`
Returns JSON data for enrollment trend line chart.

**Query Details**:
- Aggregates students by month for last 12 months
- Groups by `created_at` month
- Separates new enrollments vs. active students

**Cache**: 1 hour (3600 seconds)

**Response Structure**:
```json
{
  "labels": ["2024-01", "2024-02", ...],
  "datasets": [
    {
      "label": "New Enrollments",
      "data": [12, 15, 10, ...],
      "borderColor": "rgb(30, 64, 175)",
      "backgroundColor": "rgba(30, 64, 175, 0.1)",
      "tension": 0.4
    },
    {
      "label": "Active Students",
      "data": [50, 62, 68, ...],
      "borderColor": "rgb(124, 58, 237)",
      "backgroundColor": "rgba(124, 58, 237, 0.1)",
      "tension": 0.4
    }
  ],
  "table": [...]
}
```

---

### `getReportsData()`
Returns JSON data for monthly reports line chart and status doughnut chart.

**Query Details**:
- Monthly aggregation for last 12 months
- Status breakdown (submitted, approved-by-manager, approved-by-director, rejected)

**Cache**: 10 minutes (600 seconds)

**Response Structure**:
```json
{
  "monthly": {
    "labels": [...],
    "datasets": [...]
  },
  "byStatus": {
    "labels": ["Submitted", "Manager Approved", "Director Approved", "Rejected"],
    "datasets": [...]
  }
}
```

---

### `getTutorPerformanceData()`
Returns JSON for students-per-tutor and attendance bar charts.

**Query Details**:
- Uses `withCount(['students', 'tutorReports'])` for efficiency
- Filters active tutors only
- Limits to top 10 tutors by report count

**Cache**: 10 minutes (600 seconds)

**Response Structure**:
```json
{
  "studentsPerTutor": {
    "labels": ["John Doe", "Jane Smith", ...],
    "datasets": [...]
  },
  "attendanceByTutor": {
    "labels": [...],
    "datasets": [...]
  }
}
```

---

### `getAssessmentData()`
Returns JSON for performance trend line chart and rating distribution pie charts.

**Query Details**:
- Aggregates assessments by month with average scores
- Calculates rating distributions for professionalism, communication, punctuality

**Cache**: 30 minutes (1800 seconds)

**Response Structure**:
```json
{
  "performanceTrend": {
    "labels": [...],
    "datasets": [...]
  },
  "ratingDistribution": {
    "labels": ["Poor", "Fair", "Good", "Very Good", "Excellent"],
    "datasets": [...]
  }
}
```

---

### `exportReportsCsv(Request $request)`
Exports monthly tutor reports to CSV with chunked streaming.

**Parameters**:
- `month` (required): Format `YYYY-MM` (e.g., `2024-11`)

**Validation**:
```php
$request->validate([
    'month' => 'required|date_format:Y-m'
]);
```

**CSV Columns**:
1. Report ID
2. Student Name
3. Tutor Name
4. Month
5. Status
6. Manager Comment
7. Director Comment
8. Created At
9. Approved By Manager At
10. Approved By Director At

**Performance**:
- Uses `chunk(100)` to process reports in batches
- Streams directly to output buffer with `fopen('php://output')`
- Logs export action to `director_activity_logs`

**Usage Example**:
```
GET /director/analytics/reports/export?month=2024-11
```

---

### `exportTutorsCsv()`
Exports tutor performance metrics to CSV.

**CSV Columns**:
1. Tutor Name
2. Email
3. Status
4. Total Students
5. Total Reports
6. Total Assessments
7. Avg Performance Score

**Performance**:
- Uses `chunk(50)` for batch processing
- Eager loads relationships with `->with(['students', 'tutorReports', 'tutorAssessments'])`
- Calculates average scores in-memory during export

**Usage Example**:
```
GET /director/analytics/tutors/export
```

---

## Caching Strategy

Strategic caching improves performance and reduces database load.

| Data Type | Cache Key | TTL | Reasoning |
|-----------|-----------|-----|-----------|
| Dashboard Stats | `director.analytics.dashboard.stats` | 5 min (300s) | Frequently viewed, needs freshness |
| Enrollments | `director.analytics.enrollments` | 1 hour (3600s) | Historical data, changes infrequently |
| Reports Data | `director.analytics.reports` | 10 min (600s) | Active workflow, moderate freshness |
| Tutor Performance | `director.analytics.tutors` | 10 min (600s) | Performance metrics, moderate updates |
| Assessments | `director.analytics.assessments` | 30 min (1800s) | Less frequently updated than reports |

### Cache Usage Pattern

All analytics methods use Laravel's `Cache::remember()`:

```php
$data = Cache::remember('cache.key', $ttl, function () {
    // Expensive database query
    return $results;
});
```

### Clearing Cache

To manually clear analytics cache:

```bash
# Clear all cache
php artisan cache:clear

# Clear specific keys (in tinker or code)
Cache::forget('director.analytics.dashboard.stats');
Cache::forget('director.analytics.enrollments');
```

**Note**: Cache automatically expires based on TTL. Manual clearing is rarely needed.

---

## Chart Types & Data

### Chart.js Configuration

All charts use Chart.js 4.4.0 loaded via CDN:

```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
```

### Color Palette

Consistent with Director Portal design:

- **Royal Blue**: `rgb(30, 64, 175)` / `#1E40AF`
- **Purple**: `rgb(124, 58, 237)` / `#7C3AED`
- **Indigo**: `rgb(99, 102, 241)` / `#6366F1`
- **Green**: `rgb(34, 197, 94)` / `#22C55E`
- **Yellow**: `rgb(234, 179, 8)` / `#EAB308`
- **Red**: `rgb(239, 68, 68)` / `#EF4444`

### Common Chart Options

```javascript
{
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top'
        }
    }
}
```

---

## Export Functionality

### CSV Headers

Both export methods set appropriate HTTP headers:

```php
$headers = [
    'Content-Type' => 'text/csv; charset=UTF-8',
    'Content-Disposition' => 'attachment; filename="filename.csv"',
    'Cache-Control' => 'no-cache, no-store, must-revalidate',
    'Pragma' => 'no-cache',
    'Expires' => '0'
];
```

### Streaming Pattern

```php
$callback = function() use ($data) {
    $file = fopen('php://output', 'w');
    fputcsv($file, ['Header1', 'Header2', ...]);

    Model::with(['relations'])->chunk(100, function($records) use ($file) {
        foreach ($records as $record) {
            fputcsv($file, [$record->field1, $record->field2, ...]);
        }
    });

    fclose($file);
};

return response()->stream($callback, 200, $headers);
```

### Activity Logging

All exports are logged to `director_activity_logs`:

```php
DirectorActivityLog::create([
    'user_id' => auth()->id(),
    'action' => 'export_reports_csv',
    'model_type' => 'TutorReport',
    'model_id' => null,
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```

---

## Database Indexes

Migration: `2025_11_26_010300_add_indexes_for_director_analytics.php`

### Students Table
- `students_created_at_index` — Enrollment analytics
- `students_status_index` — Active student filtering

### Tutor Reports Table
- `tutor_reports_status_index` — Status filtering
- `tutor_reports_month_index` — Monthly grouping
- `tutor_reports_tutor_id_index` — Performance queries
- `tutor_reports_director_approved_at_index` — Approval tracking

### Tutor Assessments Table
- `tutor_assessments_status_index` — Status filtering
- `tutor_assessments_tutor_id_index` — Performance analytics
- `tutor_assessments_created_at_index` — Time-based queries
- `tutor_assessments_director_approved_at_index` — Approval tracking

### Tutors Table
- `tutors_status_index` — Active tutor filtering

### Director Activity Logs Table
- `director_activity_logs_model_type_index` — Model filtering
- `director_activity_logs_action_index` — Action filtering
- `director_activity_logs_created_at_index` — Date filtering
- `director_activity_logs_user_id_index` — User tracking

### Running Migrations

```bash
php artisan migrate
```

### Performance Impact

Indexes improve query performance for:
- Large date range aggregations (enrollments over 12 months)
- Status-based filtering (pending reports/assessments)
- JOIN operations (tutor performance with reports)
- GROUP BY operations (monthly/status grouping)

**Trade-off**: Slightly slower INSERT/UPDATE operations, but analytics queries are read-heavy.

---

## Testing

### Test File

`tests/Feature/Director/AnalyticsControllerTest.php`

### Running Tests

```bash
# Run all analytics tests
php artisan test --filter=AnalyticsControllerTest

# Run specific test
php artisan test --filter=director_can_access_analytics_index_page

# Run all director tests
php artisan test tests/Feature/Director/
```

### Test Coverage

The test suite covers:

✅ **Authorization**
- Unauthenticated users redirected to login
- Non-director users receive 403 Forbidden
- Directors can access all analytics endpoints

✅ **JSON Endpoints**
- All endpoints return expected JSON structure
- Data includes labels, datasets, and optional tables
- Cache is properly set after first request

✅ **CSV Exports**
- Validation (month parameter required for reports)
- Correct headers and content-type
- Activity logging for all exports

✅ **Caching**
- Dashboard stats cached with 5-minute TTL
- Enrollment data cached with 1-hour TTL
- Cache keys are properly set

### Sample Test

```php
/** @test */
public function enrollments_endpoint_returns_json_data()
{
    Student::factory()->count(5)->create([
        'created_at' => now()->subMonths(1),
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->director)
        ->get(route('director.analytics.enrollments'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'labels',
        'datasets' => [
            '*' => ['label', 'data', 'borderColor', 'backgroundColor', 'tension']
        ],
        'table'
    ]);
}
```

---

## Troubleshooting

### Issue: Charts Not Rendering

**Symptoms**: Blank chart containers on analytics page

**Solutions**:
1. Check browser console for JavaScript errors
2. Verify Chart.js CDN is loading:
   ```html
   <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
   ```
3. Ensure JSON endpoints return valid data:
   ```bash
   curl -H "Cookie: laravel_session=..." http://localhost/director/analytics/enrollments
   ```
4. Check network tab for failed AJAX requests

---

### Issue: CSV Export Fails

**Symptoms**: Download doesn't start or file is empty

**Solutions**:
1. **For Reports Export**: Ensure `month` parameter is provided and valid:
   ```
   /director/analytics/reports/export?month=2024-11
   ```
2. Check if data exists for the specified month:
   ```php
   TutorReport::where('month', '2024-11')->count();
   ```
3. Verify proper Laravel session authentication
4. Check `director_activity_logs` for error details

---

### Issue: Slow Performance

**Symptoms**: Analytics page loads slowly, timeouts

**Solutions**:
1. **Clear expired cache**:
   ```bash
   php artisan cache:clear
   ```
2. **Run database migrations** to ensure indexes exist:
   ```bash
   php artisan migrate
   ```
3. **Check index usage** in MySQL:
   ```sql
   EXPLAIN SELECT * FROM students WHERE status = 'active';
   ```
4. **Verify cache driver** is configured properly in `.env`:
   ```
   CACHE_DRIVER=redis  # or memcached for production
   ```
5. **Reduce cache TTL** if data is stale (edit `AnalyticsController.php`)

---

### Issue: Permission Denied (403)

**Symptoms**: Director user receives 403 Forbidden

**Solutions**:
1. Verify user has 'director' role:
   ```php
   User::find($id)->roles()->pluck('name'); // Should include 'director'
   ```
2. Check middleware in `routes/web.php`:
   ```php
   ->middleware(['auth', 'verified', 'role:director'])
   ```
3. Ensure `RoleMiddleware` is properly registered in `app/Http/Kernel.php`

---

### Issue: Data Not Updating

**Symptoms**: Stats or charts show old data

**Solutions**:
1. **Cache is stale** — wait for TTL expiration or clear cache:
   ```bash
   php artisan cache:clear
   ```
2. **Database not updated** — verify recent records exist:
   ```php
   Student::latest()->first();
   TutorReport::latest()->first();
   ```
3. **Check cache TTLs** in controller:
   - Dashboard stats: 5 minutes
   - Enrollments: 1 hour
   - Reports/Tutors: 10 minutes
   - Assessments: 30 minutes

---

## Maintenance

### Regular Tasks

1. **Monitor Cache Hit Rates**:
   - Use Redis monitoring or Laravel Telescope to track cache efficiency
   - Adjust TTLs if hit rate is low

2. **Review Index Performance**:
   - Periodically run `EXPLAIN` on slow queries
   - Add additional indexes if new query patterns emerge

3. **Export Log Cleanup**:
   - Archive or delete old `director_activity_logs` records
   - Consider adding retention policy (e.g., 90 days)

4. **Chart.js Updates**:
   - Check for Chart.js security updates
   - Test charts after upgrading CDN version

---

## Additional Resources

- **Chart.js Documentation**: https://www.chartjs.org/docs/latest/
- **Laravel Caching**: https://laravel.com/docs/10.x/cache
- **Database Indexing**: https://laravel.com/docs/10.x/migrations#indexes
- **CSV Streaming**: https://laravel.com/docs/10.x/responses#streamed-downloads

---

## Related Documentation

- [Director UI Documentation](./director-ui.md) — Phase 3 UI components
- [Director Portal Backend](./director-backend.md) — Phase 2 policies & services
- [Director Portal Database](./director-database.md) — Phase 1 migrations & models

---

**Last Updated**: November 26, 2025
**Phase**: 4 — Analytics & Dashboard
**Version**: 1.0.0
