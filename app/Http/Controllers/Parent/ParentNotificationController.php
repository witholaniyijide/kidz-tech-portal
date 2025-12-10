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
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get unread count
        $unreadCount = ParentNotification::where('parent_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('parent.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(ParentNotification $notification)
    {
        // Ensure this notification belongs to the logged-in parent
        abort_unless(
            $notification->parent_id === Auth::id(),
            403,
            'Unauthorized: You can only manage your own notifications.'
        );

        $notification->markAsRead();

        // Return JSON for AJAX calls
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
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

        // Mark all notifications as read
        ParentNotification::where('parent_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Return JSON for AJAX calls
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
    public function destroy(ParentNotification $notification)
    {
        // Ensure this notification belongs to the logged-in parent
        abort_unless(
            $notification->parent_id === Auth::id(),
            403,
            'Unauthorized: You can only delete your own notifications.'
        );

        $notification->delete();

        // Return JSON for AJAX calls
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
        $user = Auth::user();

        $count = ParentNotification::where('parent_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown/header).
     */
    public function recent()
    {
        $user = Auth::user();

        $notifications = ParentNotification::where('parent_id', $user->id)
            ->with(['student'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'is_read' => !$notification->isUnread(),
                    'student_name' => $notification->student ? $notification->student->full_name : null,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        $unreadCount = ParentNotification::where('parent_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }
}
