<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorNotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        $tutorId = Auth::user()->tutor?->id;

        if (!$tutorId) {
            return redirect()->route('tutor.dashboard')->with('error', 'Tutor profile not found.');
        }

        $notifications = TutorNotification::where('tutor_id', $tutorId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = TutorNotification::where('tutor_id', $tutorId)
            ->where('is_read', false)
            ->count();

        return view('tutor.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(TutorNotification $notification)
    {
        $tutorId = Auth::user()->tutor?->id;

        if ($notification->tutor_id !== $tutorId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        $tutorId = Auth::user()->tutor?->id;

        if (!$tutorId) {
            return redirect()->back()->with('error', 'Tutor profile not found.');
        }

        TutorNotification::where('tutor_id', $tutorId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function destroy(TutorNotification $notification)
    {
        $tutorId = Auth::user()->tutor?->id;

        if ($notification->tutor_id !== $tutorId) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
