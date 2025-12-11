<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Student;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorMessageController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth', 'verified']);
        $this->notificationService = $notificationService;
    }

    /**
     * Display inbox messages.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $messages = Message::where('recipient_id', $user->id)
            ->whereNull('parent_id') // Only parent messages, not replies
            ->with(['sender', 'student', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('director.messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Display sent messages.
     */
    public function sent()
    {
        $user = Auth::user();
        
        $messages = Message::where('sender_id', $user->id)
            ->whereNull('parent_id')
            ->with(['recipient', 'student', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('director.messages.sent', compact('messages'));
    }

    /**
     * Show compose form.
     */
    public function create(Request $request)
    {
        // Get all parents for recipient dropdown
        $parents = User::whereHas('roles', function($q) {
            $q->where('name', 'parent');
        })->orderBy('name')->get();
        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        
        $replyTo = null;
        if ($request->has('reply_to')) {
            $replyTo = Message::with(['sender', 'student'])->find($request->reply_to);
        }

        return view('director.messages.create', compact('parents', 'students', 'replyTo'));
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'student_id' => 'nullable|exists:students,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'parent_id' => 'nullable|exists:messages,id',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $validated['recipient_id'],
            'student_id' => $validated['student_id'] ?? null,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'parent_id' => $validated['parent_id'] ?? null,
            'is_read' => false,
        ]);

        // Send notification to recipient
        $recipient = User::find($validated['recipient_id']);
        $this->notificationService->sendMessage(
            Auth::user(),
            $recipient,
            $validated['subject'],
            $validated['body'],
            ['message_id' => $message->id]
        );

        return redirect()
            ->route('director.messages.index')
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Show a specific message thread.
     */
    public function show(Message $message)
    {
        $user = Auth::user();

        // Check if user is sender or recipient
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403);
        }

        // Mark as read if recipient
        if ($message->recipient_id === $user->id && !$message->is_read) {
            $message->markAsRead();
        }

        // Get all replies
        $message->load(['sender', 'recipient', 'student', 'replies.sender']);

        return view('director.messages.show', compact('message'));
    }

    /**
     * Reply to a message.
     */
    public function reply(Request $request, Message $message)
    {
        $user = Auth::user();

        // Check if user can reply
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        // Determine recipient (opposite of current user)
        $recipientId = $message->sender_id === $user->id 
            ? $message->recipient_id 
            : $message->sender_id;

        $reply = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'student_id' => $message->student_id,
            'subject' => 'Re: ' . $message->subject,
            'body' => $validated['body'],
            'parent_id' => $message->parent_id ?? $message->id, // Link to original thread
            'is_read' => false,
        ]);

        // Send notification
        $recipient = User::find($recipientId);
        $this->notificationService->sendMessage(
            Auth::user(),
            $recipient,
            'Re: ' . $message->subject,
            $validated['body'],
            ['message_id' => $reply->id, 'thread_id' => $message->id]
        );

        return redirect()
            ->route('director.messages.show', $message)
            ->with('success', 'Reply sent successfully.');
    }

    /**
     * Delete a message.
     */
    public function destroy(Message $message)
    {
        $user = Auth::user();

        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403);
        }

        $message->delete();

        return redirect()
            ->route('director.messages.index')
            ->with('success', 'Message deleted.');
    }

    /**
     * Mark message as read via AJAX.
     */
    public function markAsRead(Message $message)
    {
        if ($message->recipient_id === Auth::id()) {
            $message->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
