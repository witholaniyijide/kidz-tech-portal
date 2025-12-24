<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentNoticeController extends Controller
{
    /**
     * Display all notices for parents.
     */
    public function index(Request $request)
    {
        $type = $request->get('type');

        // Get notices visible to parents
        $query = Notice::where(function ($q) {
                $q->where('audience', 'all')
                    ->orWhere('audience', 'parents')
                    ->orWhereJsonContains('audience', 'parent');
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

        return view('parent.notices.index', compact('notices', 'types', 'type'));
    }

    /**
     * Display a specific notice.
     */
    public function show(Notice $notice)
    {
        // Verify notice is visible to parents
        $isVisible = $notice->status === 'published'
            && ($notice->audience === 'all'
                || $notice->audience === 'parents'
                || (is_array($notice->audience) && in_array('parent', $notice->audience)));

        if (!$isVisible) {
            abort(404);
        }

        // Track view (optional)
        // $notice->increment('views');

        return view('parent.notices.show', compact('notice'));
    }

    /**
     * Mark a notice as read.
     */
    public function markAsRead(Notice $notice)
    {
        $user = Auth::user();

        // Store read status (could use a pivot table or JSON column)
        // For now, we'll use session
        $readNotices = session('read_notices', []);
        if (!in_array($notice->id, $readNotices)) {
            $readNotices[] = $notice->id;
            session(['read_notices' => $readNotices]);
        }

        return response()->json(['success' => true]);
    }
}
