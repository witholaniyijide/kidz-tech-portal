<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\DirectorActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorNoticeController extends Controller
{
    /**
     * Display a listing of notices.
     */
    public function index(Request $request)
    {
        $query = Notice::with('author');

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notices = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('director.notices.index', compact('notices'));
    }

    /**
     * Show the form for creating a new notice.
     */
    public function create()
    {
        return view('director.notices.create');
    }

    /**
     * Store a newly created notice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:draft,published,archived',
            'visible_to' => 'required|array|min:1',
            'visible_to.*' => 'in:admin,manager,tutor,parent,student',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ]);

        try {
            $validated['author_id'] = Auth::id();
            $validated['posted_by'] = Auth::id();
            // Note: visible_to is already cast as array in model, no need to json_encode

            $notice = Notice::create($validated);

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'notice_created',
                'model_type' => 'Notice',
                'model_id' => $notice->id,
                'payload' => json_encode([
                    'title' => $validated['title'],
                    'priority' => $validated['priority'],
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('director.notices.index')
                ->with('success', 'Notice created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create notice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified notice.
     */
    public function show(Notice $notice)
    {
        $notice->load('author');
        
        return view('director.notices.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified notice.
     */
    public function edit(Notice $notice)
    {
        return view('director.notices.edit', compact('notice'));
    }

    /**
     * Update the specified notice.
     */
    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:draft,published,archived',
            'visible_to' => 'required|array|min:1',
            'visible_to.*' => 'in:admin,manager,tutor,parent,student',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ]);

        try {
            // Note: visible_to is already cast as array in model, no need to json_encode

            $notice->update($validated);

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'notice_updated',
                'model_type' => 'Notice',
                'model_id' => $notice->id,
                'payload' => json_encode([
                    'title' => $validated['title'],
                    'changes' => $notice->getChanges(),
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('director.notices.index')
                ->with('success', 'Notice updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update notice: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified notice.
     */
    public function destroy(Notice $notice)
    {
        try {
            $noticeId = $notice->id;
            $noticeTitle = $notice->title;
            
            $notice->delete();

            // Log director activity
            DirectorActivityLog::create([
                'director_id' => Auth::id(),
                'action_type' => 'notice_deleted',
                'model_type' => 'Notice',
                'model_id' => $noticeId,
                'payload' => json_encode([
                    'title' => $noticeTitle,
                ]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->route('director.notices.index')
                ->with('success', 'Notice deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete notice: ' . $e->getMessage());
        }
    }
}
