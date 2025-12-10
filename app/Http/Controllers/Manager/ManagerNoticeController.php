<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\StoreNoticeRequest;
use App\Http\Requests\UpdateNoticeRequest;

class ManagerNoticeController extends Controller
{
    /**
     * Display a listing of notices.
     */
    public function index(Request $request)
    {
        $query = Notice::with('author')
            ->where(function($q) {
                // Show notices visible to managers or all
                $q->whereJsonContains('visible_to', 'manager')
                  ->orWhereJsonContains('visible_to', 'all');
            });

        // Filter by priority if provided
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $notices = $query->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get statistics
        $stats = [
            'total' => Notice::whereJsonContains('visible_to', 'manager')->count(),
            'important' => Notice::whereJsonContains('visible_to', 'manager')
                ->where('priority', 'important')->count(),
            'general' => Notice::whereJsonContains('visible_to', 'manager')
                ->where('priority', 'general')->count(),
            'reminder' => Notice::whereJsonContains('visible_to', 'manager')
                ->where('priority', 'reminder')->count(),
        ];

        return view('manager.notices.index', compact('notices', 'stats'));
    }

    /**
     * Show the form for creating a new notice.
     */
    public function create()
    {
        return view('manager.notices.create');
    }

    /**
     * Store a newly created notice in storage.
     */
    public function store(StoreNoticeRequest $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:important,general,reminder',
            'visible_to' => 'required|array',
            'visible_to.*' => 'in:all,admin,manager,tutor,parent',
            'status' => 'required|in:draft,published',
        ]);

        // Manager CANNOT create notices visible to directors
        // Enforce: visible_to must not include "director"
        $visibleTo = $request->visible_to;

        if (in_array('director', $visibleTo)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Managers cannot create notices for Directors. Please contact an Admin or Director.');
        }

        // Create the notice
        $notice = new Notice();
        $notice->title = $request->title;
        $notice->content = $request->content;
        $notice->priority = $request->priority;
        $notice->visible_to = $visibleTo;
        $notice->status = $request->status;
        $notice->posted_by = Auth::id();

        if ($request->status === 'published') {
            $notice->published_at = Carbon::now();
        }

        $notice->save();

        return redirect()
            ->route('manager.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    /**
     * Display the specified notice.
     */
    public function show(Notice $notice)
    {
        // Check if manager can view this notice
        $visibleTo = $notice->visible_to ?? [];

        if (!in_array('manager', $visibleTo) && !in_array('all', $visibleTo)) {
            abort(403, 'You do not have permission to view this notice.');
        }

        $notice->load('author');

        return view('manager.notices.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified notice.
     * Only allow editing if the manager created it.
     */
    public function edit(Notice $notice)
    {
        // Check if current user is the author
        if ($notice->posted_by !== Auth::id()) {
            return redirect()
                ->route('manager.notices.index')
                ->with('error', 'You can only edit notices you created.');
        }

        return view('manager.notices.edit', compact('notice'));
    }

    /**
     * Update the specified notice in storage.
     * Only allow editing if the manager created it.
     */
    public function update(UpdateNoticeRequest $request, Notice $notice)
    {
        // Check if current user is the author
        if ($notice->posted_by !== Auth::id()) {
            return redirect()
                ->route('manager.notices.index')
                ->with('error', 'You can only edit notices you created.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:important,general,reminder',
            'visible_to' => 'required|array',
            'visible_to.*' => 'in:all,admin,manager,tutor,parent',
            'status' => 'required|in:draft,published',
        ]);

        // Manager CANNOT create notices visible to directors
        $visibleTo = $request->visible_to;

        if (in_array('director', $visibleTo)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Managers cannot create notices for Directors.');
        }

        // Update the notice
        $notice->title = $request->title;
        $notice->content = $request->content;
        $notice->priority = $request->priority;
        $notice->visible_to = $visibleTo;
        $notice->status = $request->status;

        if ($request->status === 'published' && !$notice->published_at) {
            $notice->published_at = Carbon::now();
        }

        $notice->save();

        return redirect()
            ->route('manager.notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    /**
     * Remove the specified notice from storage.
     * Only allow deletion if the manager created it.
     */
    public function destroy(Notice $notice)
    {
        // Check if current user is the author
        if ($notice->posted_by !== Auth::id()) {
            return redirect()
                ->route('manager.notices.index')
                ->with('error', 'You can only delete notices you created.');
        }

        $notice->delete();

        return redirect()
            ->route('manager.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }
}
