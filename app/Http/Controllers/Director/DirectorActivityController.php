<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\DirectorActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorActivityController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('director') && !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of director activity logs.
     */
    public function index(Request $request)
    {
        // Authorize
        $this->authorize('viewAny', DirectorActivityLog::class);

        $query = DirectorActivityLog::with(['director'])
            ->orderBy('created_at', 'desc');

        // Filter by director (if admin is viewing)
        if ($request->filled('director_id') && Auth::user()->hasRole('admin')) {
            $query->where('director_id', $request->director_id);
        } elseif (Auth::user()->hasRole('director')) {
            // Directors can only see their own activity
            $query->where('director_id', Auth::id());
        }

        // Filter by action type
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(20);

        // Get unique action types for filter
        $actionTypes = DirectorActivityLog::select('action_type')
            ->distinct()
            ->pluck('action_type');

        // Get unique model types for filter
        $modelTypes = DirectorActivityLog::select('model_type')
            ->whereNotNull('model_type')
            ->distinct()
            ->pluck('model_type');

        return view('director.activity-logs.index', compact(
            'logs',
            'actionTypes',
            'modelTypes'
        ));
    }
}
