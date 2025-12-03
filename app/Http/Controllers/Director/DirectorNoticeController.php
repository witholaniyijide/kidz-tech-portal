<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Notice;
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

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Search by title or content
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $notices = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total' => Notice::count(),
            'published' => Notice::where('status', 'published')->count(),
            'draft' => Notice::where('status', 'draft')->count(),
            'high_priority' => Notice::where('priority', 'high')->orWhere('priority', 'urgent')->count(),
        ];

        return view('director.notices.index', compact('notices', 'stats'));
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
            'visible_to' => 'required|array',
            'visible_to.*' => 'in:admin,manager,tutor,parent,all',
            'status' => 'required|in:draft,published',
        ]);

        $validated['posted_by'] = Auth::id();

        // Set published_at if status is published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $notice = Notice::create($validated);

        return redirect()->route('director.notices.show', $notice)
            ->with('success', 'Notice created successfully!');
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
            'visible_to' => 'required|array',
            'visible_to.*' => 'in:admin,manager,tutor,parent,all',
            'status' => 'required|in:draft,published',
        ]);

        // Set published_at if status is changing to published
        if ($validated['status'] === 'published' && $notice->status !== 'published') {
            $validated['published_at'] = now();
        }

        $notice->update($validated);

        return redirect()->route('director.notices.show', $notice)
            ->with('success', 'Notice updated successfully!');
    }

    /**
     * Remove the specified notice.
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('director.notices.index')
            ->with('success', 'Notice deleted successfully!');
    }

    /**
     * Publish a draft notice.
     */
    public function publish(Notice $notice)
    {
        if ($notice->status === 'draft') {
            $notice->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

            return back()->with('success', 'Notice published successfully!');
        }

        return back()->with('info', 'Notice is already published.');
    }

    /**
     * Unpublish a published notice.
     */
    public function unpublish(Notice $notice)
    {
        if ($notice->status === 'published') {
            $notice->update([
                'status' => 'draft',
            ]);

            return back()->with('success', 'Notice unpublished successfully!');
        }

        return back()->with('info', 'Notice is already a draft.');
    }
}
