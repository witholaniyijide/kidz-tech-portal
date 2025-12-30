<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentMessageController extends Controller
{
    /**
     * Display inbox messages.
     */
    public function index()
    {
        $user = Auth::user();

        // Get received messages (from Director only)
        $messages = Message::where('recipient_id', $user->id)
            ->with(['sender'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Message::where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('parent.messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Display sent messages.
     */
    public function sent()
    {
        $user = Auth::user();

        // Get sent messages
        $messages = Message::where('sender_id', $user->id)
            ->with(['recipient'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('parent.messages.sent', compact('messages'));
    }

    /**
     * Show form to compose a new message.
     */
    public function create()
    {
        // Parents can only message the Director (using roles relationship)
        $directors = User::whereHas('roles', function ($query) {
            $query->where('name', 'director');
        })->get();

        return view('parent.messages.create', compact('directors'));
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Verify recipient is a director (using roles relationship)
        $recipient = User::findOrFail($request->recipient_id);
        $isDirector = $recipient->roles()->where('name', 'director')->exists();
        if (!$isDirector) {
            return back()->with('error', 'You can only send messages to the Director.');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return redirect()->route('parent.messages.show', $message)
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Display a specific message.
     */
    public function show(Message $message)
    {
        $user = Auth::user();

        // Verify user is sender or recipient
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Mark as read if user is recipient
        if ($message->recipient_id === $user->id && !$message->read_at) {
            $message->update(['read_at' => now()]);
        }

        $message->load(['sender', 'recipient']);

        // Get thread (replies)
        $thread = Message::where(function ($query) use ($message) {
                $query->where('parent_id', $message->id)
                    ->orWhere('id', $message->parent_id ?? $message->id);
            })
            ->orWhere('id', $message->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('parent.messages.show', compact('message', 'thread'));
    }

    /**
     * Reply to a message.
     */
    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $user = Auth::user();

        // Verify user is part of this conversation
        if ($message->sender_id !== $user->id && $message->recipient_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Determine recipient (the other party in the conversation)
        $recipientId = $message->sender_id === $user->id
            ? $message->recipient_id
            : $message->sender_id;

        $reply = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'parent_id' => $message->parent_id ?? $message->id,
            'subject' => 'Re: ' . $message->subject,
            'body' => $request->body,
        ]);

        return redirect()->route('parent.messages.show', $message)
            ->with('success', 'Reply sent successfully.');
    }
}
