<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ParentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentNotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated parent.
     */
    public function index()
    {
        $user = Auth::user();

        // Get all notifications for this parent
        $notifications = ParentNotification::where('parent_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Authorize that the user can view notifications
        $this->authorize('viewAny', ParentNotification::class);

        return view('parent.notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(ParentNotification $notification)
    {
        // Authorize that the user can mark this notification as read
        $this->authorize('markAsRead', $notification);

        $notification->markAsRead();

        // Return JSON for AJAX calls
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.'
            ]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read for the current parent.
     */
    public function markAllRead()
    {
        $user = Auth::user();

        // Authorize
        $this->authorize('viewAny', ParentNotification::class);

        // Mark all notifications as read
        ParentNotification::where('parent_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Return JSON for AJAX calls
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.'
            ]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
