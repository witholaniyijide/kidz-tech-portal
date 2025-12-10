<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    /**
     * Display a listing of notices visible to tutors.
     */
    public function index(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $query = Notice::with('author')
            ->where('status', 'published')
            ->where(function ($q) {
                // Filter notices where visible_to JSON array contains 'tutor'
                $q->whereJsonContains('visible_to', 'tutor');
            });

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search by title or content
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notices = $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Count unread notices (for tutors, we track via session since there's no pivot table)
        $readNoticeIds = session('read_notices', []);
        $unreadCount = Notice::where('status', 'published')
            ->whereJsonContains('visible_to', 'tutor')
            ->whereNotIn('id', $readNoticeIds)
            ->count();

        return view('tutor.notices.index', compact('notices', 'unreadCount'));
    }

    /**
     * Display the specified notice.
     */
    public function show(Notice $notice)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Verify notice is published and visible to tutors
        if ($notice->status !== 'published') {
            abort(404, 'Notice not found.');
        }

        $visibleTo = $notice->visible_to;
        if (is_string($visibleTo)) {
            $visibleTo = json_decode($visibleTo, true);
        }

        if (!is_array($visibleTo) || !in_array('tutor', $visibleTo)) {
            abort(403, 'You do not have access to this notice.');
        }

        $notice->load('author');

        // Mark as read in session
        $readNoticeIds = session('read_notices', []);
        if (!in_array($notice->id, $readNoticeIds)) {
            $readNoticeIds[] = $notice->id;
            session(['read_notices' => $readNoticeIds]);
        }

        // Get previous and next notices for navigation
        $previousNotice = Notice::where('status', 'published')
            ->whereJsonContains('visible_to', 'tutor')
            ->where(function ($q) use ($notice) {
                $q->where('published_at', '<', $notice->published_at)
                  ->orWhere(function ($q2) use ($notice) {
                      $q2->where('published_at', $notice->published_at)
                         ->where('id', '<', $notice->id);
                  });
            })
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $nextNotice = Notice::where('status', 'published')
            ->whereJsonContains('visible_to', 'tutor')
            ->where(function ($q) use ($notice) {
                $q->where('published_at', '>', $notice->published_at)
                  ->orWhere(function ($q2) use ($notice) {
                      $q2->where('published_at', $notice->published_at)
                         ->where('id', '>', $notice->id);
                  });
            })
            ->orderBy('published_at', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        return view('tutor.notices.show', compact('notice', 'previousNotice', 'nextNotice'));
    }

    /**
     * Mark a notice as read.
     */
    public function markAsRead(Notice $notice)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Verify notice is accessible to tutors
        $visibleTo = $notice->visible_to;
        if (is_string($visibleTo)) {
            $visibleTo = json_decode($visibleTo, true);
        }

        if (!is_array($visibleTo) || !in_array('tutor', $visibleTo)) {
            return response()->json(['error' => 'Notice not accessible'], 403);
        }

        // Mark as read in session
        $readNoticeIds = session('read_notices', []);
        if (!in_array($notice->id, $readNoticeIds)) {
            $readNoticeIds[] = $notice->id;
            session(['read_notices' => $readNoticeIds]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notice marked as read'
        ]);
    }
}
