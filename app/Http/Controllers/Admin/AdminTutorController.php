<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminTutorController extends Controller
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
     * Display a listing of tutors.
     */
    public function index(Request $request)
    {
        $query = Tutor::withCount('students');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $tutors = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => Tutor::count(),
            'active' => Tutor::where('status', 'active')->count(),
            'inactive' => Tutor::where('status', 'inactive')->count(),
            'on_leave' => Tutor::where('status', 'on_leave')->count(),
            'resigned' => Tutor::where('status', 'resigned')->count(),
        ];

        return view('admin.tutors.index', compact('tutors', 'stats'));
    }

    /**
     * Show the form for creating a new tutor.
     */
    public function create()
    {
        return view('admin.tutors.create');
    }

    /**
     * Store a newly created tutor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Personal Info
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tutors,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'profile_photo' => 'nullable|image|max:2048',
            
            // Emergency Contact
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            
            // Payment Details
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            
            // Status
            'status' => 'required|in:active,inactive,on_leave,resigned',
        ]);

        DB::transaction(function() use ($validated, $request) {
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $request->file('profile_photo')->store('tutors/photos', 'public');
            }

            $tutor = Tutor::create($validated);

            // Create associated user account
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make('password123'), // Default password
                'role' => 'tutor',
            ]);

            $tutor->update(['user_id' => $user->id]);

            // Log the action
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'description' => "Created tutor: {$tutor->first_name} {$tutor->last_name}",
                'model_type' => Tutor::class,
                'model_id' => $tutor->id,
            ]);
        });

        return redirect()
            ->route('admin.tutors.index')
            ->with('success', 'Tutor created successfully. Default password: password123');
    }

    /**
     * Display the specified tutor.
     */
    public function show(Tutor $tutor)
    {
        $tutor->load(['students', 'attendances', 'user']);
        return view('admin.tutors.show', compact('tutor'));
    }

    /**
     * Show the form for editing the specified tutor.
     */
    public function edit(Tutor $tutor)
    {
        return view('admin.tutors.edit', compact('tutor'));
    }

    /**
     * Update the specified tutor.
     */
    public function update(Request $request, Tutor $tutor)
    {
        $validated = $request->validate([
            // Personal Info
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tutors,email,' . $tutor->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'profile_photo' => 'nullable|image|max:2048',
            
            // Emergency Contact
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            
            // Payment Details
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            
            // Status
            'status' => 'required|in:active,inactive,on_leave,resigned',
        ]);

        DB::transaction(function() use ($tutor, $validated, $request) {
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($tutor->profile_photo) {
                    Storage::disk('public')->delete($tutor->profile_photo);
                }
                $validated['profile_photo'] = $request->file('profile_photo')->store('tutors/photos', 'public');
            }

            $tutor->update($validated);

            // Update associated user if exists
            if ($tutor->user) {
                $tutor->user->update([
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'email' => $validated['email'],
                ]);
            }

            // Log the action
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'description' => "Updated tutor: {$tutor->first_name} {$tutor->last_name}",
                'model_type' => Tutor::class,
                'model_id' => $tutor->id,
            ]);
        });

        return redirect()
            ->route('admin.tutors.show', $tutor)
            ->with('success', 'Tutor updated successfully.');
    }

    // Note: Admin cannot delete tutors per specification
}
