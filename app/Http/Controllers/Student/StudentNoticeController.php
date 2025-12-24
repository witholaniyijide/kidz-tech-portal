<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentNoticeController extends Controller
{
    /**
     * Display all notices for students.
     */
    public function index(Request $request)
    {
        $type = $request->get('type');

        // Get notices visible to students
        $query = Notice::where(function ($q) {
                $q->where('audience', 'all')
                    ->orWhere('audience', 'students')
                    ->orWhereJsonContains('audience', 'student');
            })
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        $notices = $query->paginate(15);

        // Get notice types for filter
        $types = Notice::distinct()->pluck('type')->filter();

        return view('student.notices.index', compact('notices', 'types', 'type'));
    }

    /**
     * Display a specific notice.
     */
    public function show(Notice $notice)
    {
        // Verify notice is visible to students
        $isVisible = $notice->status === 'published'
            && ($notice->audience === 'all'
                || $notice->audience === 'students'
                || (is_array($notice->audience) && in_array('student', $notice->audience)));

        if (!$isVisible) {
            abort(404);
        }

        return view('student.notices.show', compact('notice'));
    }
}
