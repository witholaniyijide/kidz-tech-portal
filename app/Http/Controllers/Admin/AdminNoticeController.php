<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminNoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of notices.
     */
    public function index(Request $request)
    {
        $query = Notice::with('author');

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $notices = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.notices.index', compact('notices'));
    }

    /**
     * Show the form for creating a new notice.
     */
    public function create()
    {
        return view('admin.notices.create');
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
            'visible_to.*' => 'in:director,manager,admin,tutor',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['author_id'] = Auth::id();
        $validated['visible_to'] = json_encode($validated['visible_to']);

        DB::transaction(function() use ($validated) {
            $notice = Notice::create($validated);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'description' => "Created notice: {$validated['title']}",
                'model_type' => Notice::class,
                'model_id' => $notice->id,
            ]);
        });

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    /**
     * Display the specified notice.
     */
    public function show(Notice $notice)
    {
        $notice->load('author');
        return view('admin.notices.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified notice.
     */
    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
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
            'visible_to.*' => 'in:director,manager,admin,tutor',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['visible_to'] = json_encode($validated['visible_to']);

        DB::transaction(function() use ($notice, $validated) {
            $notice->update($validated);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'description' => "Updated notice: {$notice->title}",
                'model_type' => Notice::class,
                'model_id' => $notice->id,
            ]);
        });

        return redirect()
            ->route('admin.notices.show', $notice)
            ->with('success', 'Notice updated successfully.');
    }

    /**
     * Remove the specified notice.
     */
    public function destroy(Notice $notice)
    {
        DB::transaction(function() use ($notice) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'description' => "Deleted notice: {$notice->title}",
                'model_type' => Notice::class,
                'model_id' => $notice->id,
            ]);

            $notice->delete();
        });

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }
}
