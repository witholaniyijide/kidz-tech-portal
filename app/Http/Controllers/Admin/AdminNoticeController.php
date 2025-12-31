<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\ActivityLog;
use App\Models\ManagerNotification;
use App\Models\DirectorNotification;
use App\Models\TutorNotification;
use App\Models\User;
use App\Models\Tutor;
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

        // Calculate stats
        $stats = [
            'total' => Notice::count(),
            'published' => Notice::where('status', 'published')->count(),
            'draft' => Notice::where('status', 'draft')->count(),
            'high_priority' => Notice::where('priority', 'high')->count(),
        ];

        return view('admin.notices.index', compact('notices', 'stats'));
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

        $validated['posted_by'] = Auth::id();
        $visibleToRoles = $validated['visible_to'];
        // Note: Don't json_encode visible_to - the Notice model cast handles this automatically

        DB::transaction(function() use ($validated, $visibleToRoles) {
            $notice = Notice::create($validated);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'description' => "Created notice: {$validated['title']}",
                'model_type' => Notice::class,
                'model_id' => $notice->id,
            ]);

            // Create notifications if notice is published
            if ($validated['status'] === 'published') {
                $this->createNotificationsForRoles($notice, $visibleToRoles);
            }
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

        $visibleToRoles = $validated['visible_to'];
        // Note: Don't json_encode visible_to - the Notice model cast handles this automatically

        DB::transaction(function() use ($notice, $validated, $visibleToRoles) {
            $wasPublished = $notice->status === 'published';
            $notice->update($validated);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'description' => "Updated notice: {$notice->title}",
                'model_type' => Notice::class,
                'model_id' => $notice->id,
            ]);

            // Create notifications if notice is newly published
            if ($validated['status'] === 'published' && !$wasPublished) {
                $this->createNotificationsForRoles($notice, $visibleToRoles);
            }
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

    /**
     * Create notifications for the specified roles.
     */
    private function createNotificationsForRoles(Notice $notice, array $roles)
    {
        $notificationData = [
            'title' => 'New Notice: ' . $notice->title,
            'body' => \Str::limit(strip_tags($notice->content), 100),
            'type' => 'notice',
            'is_read' => false,
            'meta' => [
                'notice_id' => $notice->id,
                'link' => '#', // Will be updated per role
            ],
        ];

        // Send to managers
        if (in_array('manager', $roles)) {
            $managers = User::whereHas('roles', function($q) {
                $q->where('name', 'manager');
            })->get();

            foreach ($managers as $manager) {
                $data = $notificationData;
                $data['user_id'] = $manager->id;
                $data['meta']['link'] = route('manager.notices.index');
                ManagerNotification::create($data);
            }
        }

        // Send to directors
        if (in_array('director', $roles)) {
            $directors = User::whereHas('roles', function($q) {
                $q->where('name', 'director');
            })->get();

            foreach ($directors as $director) {
                $data = $notificationData;
                $data['user_id'] = $director->id;
                $data['meta']['link'] = route('director.notices.index');
                DirectorNotification::create($data);
            }
        }

        // Send to tutors
        if (in_array('tutor', $roles)) {
            $tutors = Tutor::all();

            foreach ($tutors as $tutor) {
                $data = $notificationData;
                $data['tutor_id'] = $tutor->id;
                $data['meta']['link'] = route('tutor.notices.index');
                TutorNotification::create($data);
            }
        }
    }
}
