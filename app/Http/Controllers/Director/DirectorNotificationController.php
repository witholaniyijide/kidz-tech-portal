<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\DirectorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:director']);
    }

    /**
     * Display all notifications for the authenticated director.
     */
    public function index()
    {
        $notifications = DirectorNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = DirectorNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('director.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(DirectorNotification $notification)
    {
        abort_unless(
            $notification->user_id === Auth::id(),
            403,
            'Unauthorized: You can only manage your own notifications.'
        );

        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        DirectorNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
            ]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function destroy(DirectorNotification $notification)
    {
        abort_unless(
            $notification->user_id === Auth::id(),
            403,
            'Unauthorized: You can only delete your own notifications.'
        );

        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted.',
            ]);
        }

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    /**
     * Get unread notification count (for AJAX polling).
     */
    public function unreadCount()
    {
        $count = DirectorNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
