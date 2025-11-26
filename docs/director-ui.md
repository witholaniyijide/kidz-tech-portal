# Director Portal UI - Phase 3 Documentation

## Overview

This document describes the UI implementation for the Director Portal Phase 3, which includes the frontend pages for report and assessment review, approval workflows, and activity tracking.

## Pages Added

### 1. Director Reports

**Index Page** (`/director/reports`)
- **Location**: `resources/views/director/reports/index.blade.php`
- **Features**:
  - Filterable table (by tutor, student, month, status)
  - Status badges with color coding (draft, submitted, approved, rejected)
  - PDF download links
  - Pagination (20 items per page)
  - Responsive design with mobile stacking

**Show Page** (`/director/reports/{report}`)
- **Location**: `resources/views/director/reports/show.blade.php`
- **Features**:
  - Two-column layout (65% content, 35% actions)
  - Full report display with sections (progress, strengths, weaknesses, next steps)
  - Director approval form with comment field
  - Optional signature upload (stored in `director_signature` field)
  - Audit trail display
  - Print and PDF export options
  - Manager comment display

### 2. Director Assessments

**Index Page** (`/director/assessments`)
- **Location**: `resources/views/director/assessments/index.blade.php`
- **Features**:
  - Tutor assessment list with filters
  - Performance scores display
  - Status badges
  - Review links

**Show Page** (`/director/assessments/{assessment}`)
- **Location**: `resources/views/director/assessments/show.blade.php`
- **Features**:
  - Assessment details with ratings
  - Progress bars for professionalism, communication, punctuality
  - Strengths, weaknesses, recommendations sections
  - Director approval form
  - Manager comment display

### 3. Director Activity Logs

**Index Page** (`/director/activity-logs`)
- **Location**: `resources/views/director/activity-logs/index.blade.php`
- **Features**:
  - Activity log table with filtering
  - Action type, model type, date range filters
  - IP address and user agent tracking
  - Pagination
  - Search functionality

## Reusable Components

### 1. Status Badge (`<x-ui.status-badge>`)
- **Location**: `resources/views/components/ui/status-badge.blade.php`
- **Usage**: `<x-ui.status-badge :status="$report->status" />`
- **Color Coding**:
  - Draft: Gray
  - Submitted: Indigo
  - Approved by Manager: Yellow
  - Approved by Director: Green
  - Rejected: Red

### 2. Progress Bar (`<x-ui.progress-bar>`)
- **Location**: `resources/views/components/ui/progress-bar.blade.php`
- **Usage**: `<x-ui.progress-bar :value="85" :max="100" label="Attendance" />`
- **Features**:
  - Color-coded based on percentage (green ≥90%, blue ≥75%, yellow ≥60%, red <60%)
  - Animated width transition
  - Accessible ARIA attributes

### 3. Action Modal (`<x-director.action-modal>`)
- **Location**: `resources/views/components/director/action-modal.blade.php`
- **Usage**:
```blade
<x-director.action-modal
    name="approve-modal"
    title="Approve Report"
    :showCommentField="true"
    :showSignature="true"
/>
```
- **Features**:
  - Modal overlay with Alpine.js
  - Optional comment textarea
  - Optional signature file upload
  - Focus trap for accessibility

### 4. Audit List (`<x-director.audit-list>`)
- **Location**: `resources/views/components/director/audit-list.blade.php`
- **Usage**: `<x-director.audit-list :audits="$report->audits" />`
- **Features**:
  - Chronological audit trail
  - Status change visualization
  - Comment snippets
  - User attribution

## Testing Approval Flows

### Report Approval Flow

1. **Navigate to Reports**: `/director/reports`
2. **Select a report** with status "approved-by-manager"
3. **Click "View"** to open the show page
4. **Add optional comment** in the textarea
5. **Upload optional signature** (PNG/JPG/WebP)
6. **Click "Approve (Final)"** button
7. **Confirm** in the browser confirmation dialog
8. **Redirected** to index with success message
9. **Status updated** to "approved-by-director"
10. **Notifications sent** to tutor and manager

### Rejection Flow

1. **On the report show page**, scroll to the reject form
2. **Enter rejection reason** (required)
3. **Click "Reject Report"**
4. **Confirm** the action
5. **Status updated** to "rejected"
6. **Director comment** saved with rejection reason

### Assessment Approval Flow

1. **Navigate to Assessments**: `/director/assessments`
2. **Click "Review"** on an assessment
3. **Review** strengths, weaknesses, recommendations
4. **Add director comment** (optional)
5. **Click "Approve Assessment"**
6. **Confirm** the action
7. **Status updated** to "approved-by-director"

## Signature Upload Storage

**File Storage**:
- Signatures are stored in `storage/app/public/signatures`
- File names are hashed for security
- Accepted formats: PNG, JPG, JPEG, WebP
- Maximum size: 2MB (configurable in controller)

**Database Field**:
- `director_signature` column stores the file path
- Display in views using `asset($report->director_signature)`

**To configure storage**:
1. Run `php artisan storage:link`
2. Ensure `public/signatures` directory is writable

## Accessibility Features

1. **ARIA Attributes**:
   - `role="alert"` on flash messages
   - `aria-live="polite"` on success messages
   - `aria-live="assertive"` on error messages
   - `aria-label` on icon buttons

2. **Keyboard Navigation**:
   - Tab order follows visual flow
   - Modal focus trap with Escape key close
   - Skip links for screen readers

3. **Color Contrast**:
   - All text meets WCAG AA standards
   - Status badges have sufficient contrast
   - Dark mode fully supported

4. **Responsive Design**:
   - Tables stack on mobile devices
   - Touch-friendly button sizes (min 44x44px)
   - Flexible layouts with breakpoints

## Design System

**Colors**:
- Primary Gradient: Royal Blue → Purple (`from-blue-500 to-purple-500`)
- Success: Green (`from-green-500 to-emerald-600`)
- Warning: Red (`from-red-500 to-red-600`)

**Typography**:
- Font Family: Inter, Plus Jakarta Sans
- Headings: Bold 700-800
- Body: Regular 400

**Components**:
- Glassmorphism: `backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10`
- Rounded corners: `rounded-2xl` (16px) or `rounded-xl` (12px)
- Hover effects: `transform hover:-translate-y-1 transition-all`

**Icons**:
- Heroicons (from Tailwind UI)
- Consistent 5x5 (w-5 h-5) for inline icons

## Performance Considerations

1. **Lazy Loading**:
   - Audit logs paginated (25 entries per page)
   - Images lazy-loaded with `loading="lazy"`

2. **Caching**:
   - View caching enabled in production
   - Route caching for faster lookups

3. **Database Queries**:
   - Eager loading of relationships (`.with()`)
   - Index on status columns
   - Pagination prevents memory issues

## Testing

**Run Feature Tests**:
```bash
php artisan test --filter DirectorReportPagesTest
php artisan test --filter DirectorAssessmentPagesTest
```

**Test Coverage**:
- Director can access index pages
- Director can view show pages
- Approval flow works correctly
- Rejection requires comment
- Unauthorized users blocked
- Status validation enforced

## Troubleshooting

**Issue**: Modal not opening
- **Solution**: Ensure Alpine.js is loaded (`@stack('scripts')` in layout)

**Issue**: Signature upload fails
- **Solution**: Check storage permissions, run `php artisan storage:link`

**Issue**: Status badge not showing colors
- **Solution**: Clear view cache with `php artisan view:clear`

**Issue**: Floating orbs not animating
- **Solution**: Ensure `@push('styles')` section is rendered

## Future Enhancements (Phase 5)

- Analytics charts (Chart.js integration)
- CSV export for activity logs
- Bulk approval functionality
- Email notification templates
- Real-time notifications with WebSockets

## Support

For issues or questions, please contact the development team or create an issue in the project repository.
