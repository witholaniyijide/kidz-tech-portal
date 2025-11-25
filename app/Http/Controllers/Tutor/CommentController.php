<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\StoreReportCommentRequest;
use App\Models\TutorReport;
use App\Models\TutorReportComment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment on a report.
     */
    public function store(StoreReportCommentRequest $request, TutorReport $report)
    {
        // Get the authenticated tutor
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify report belongs to this tutor
        if ($report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Create comment
        TutorReportComment::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'role' => 'tutor',
        ]);

        // If AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully!',
            ]);
        }

        return redirect()
            ->route('tutor.reports.show', $report)
            ->with('success', 'Comment added successfully!');
    }
}
