<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    /**
     * Display a listing of notices
     */
    public function index()
    {
        $notices = Notice::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('noticeboard.index', compact('notices'));
    }

    /**
     * Show the form for creating a new notice
     */
    public function create()
    {
        return view('noticeboard.create');
    }

    /**
     * Store a newly created notice in storage
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

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Notice::create($validated);

        return redirect()->route('noticeboard.index')
            ->with('success', 'Notice created successfully!');
    }

    /**
     * Display the specified notice
     */
    public function show(Notice $noticeboard)
    {
        $noticeboard->load('author');
        return view('noticeboard.show', compact('noticeboard'));
    }

    /**
     * Show the form for editing the specified notice
     */
    public function edit(Notice $noticeboard)
    {
        return view('noticeboard.edit', compact('noticeboard'));
    }

    /**
     * Update the specified notice in storage
     */
    public function update(Request $request, Notice $noticeboard)
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
        if ($validated['status'] === 'published' && $noticeboard->status !== 'published') {
            $validated['published_at'] = now();
        }

        $noticeboard->update($validated);

        return redirect()->route('noticeboard.index')
            ->with('success', 'Notice updated successfully!');
    }

    /**
     * Remove the specified notice from storage
     */
    public function destroy(Notice $noticeboard)
    {
        $noticeboard->delete();

        return redirect()->route('noticeboard.index')
            ->with('success', 'Notice deleted successfully!');
    }
}
