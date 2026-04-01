<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TutorAccountWelcomeMail;
use App\Models\Role;
use App\Models\Tutor;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use App\Rules\NigerianPhone;

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
        // Count only active students (inactive students excluded from count)
        $query = Tutor::withCount(['students' => fn($q) => $q->where('status', 'active')]);

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

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');

        // Validate sort parameters
        $allowedSorts = ['first_name', 'last_name', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        // Order so resigned tutors appear at the end
        $tutors = $query->orderByRaw("FIELD(status, 'active', 'inactive', 'on_leave', 'resigned')")
            ->orderBy($sortBy, $sortDir)
            ->paginate(20)
            ->withQueryString();

        // Statistics (total excludes resigned tutors)
        try {
            $stats = [
                'total' => Tutor::where('status', '!=', 'resigned')->count(),
                'active' => Tutor::where('status', 'active')->count(),
                'inactive' => Tutor::where('status', 'inactive')->count(),
                'on_leave' => Tutor::where('status', 'on_leave')->count(),
                'resigned' => Tutor::where('status', 'resigned')->count(),
            ];
        } catch (\Exception $e) {
            // Fallback if 'resigned' status doesn't exist yet (migration not run)
            $stats = [
                'total' => Tutor::where('status', '!=', 'resigned')->count(),
                'active' => Tutor::where('status', 'active')->count(),
                'inactive' => Tutor::where('status', 'inactive')->count(),
                'on_leave' => Tutor::where('status', 'on_leave')->count(),
                'resigned' => 0,
            ];
        }

        return view('admin.tutors.index', compact('tutors', 'stats', 'sortBy', 'sortDir'));
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
        try {
            $validated = $request->validate([
                // Personal Info
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:tutors,email',
                'phone' => ['required', 'string', new NigerianPhone()],
                'gender' => 'required|in:male,female',
                'date_of_birth' => 'required|date|before:today',
                'hire_date' => 'required|date',
                'location' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:255',
                'bio' => 'nullable|string|max:2000',
                'profile_photo' => 'nullable|image|max:2048',

                // Emergency Contact
                'contact_person_name' => 'nullable|string|max:255',
                'contact_person_relationship' => 'nullable|string|max:100',
                'contact_person_phone' => ['nullable', 'string', new NigerianPhone()],

                // Payment Details
                'bank_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:50',
                'account_name' => 'nullable|string|max:255',

                // Status
                'status' => 'nullable|in:active,inactive,on_leave,resigned',
            ]);

            // Set default status if not provided
            $validated['status'] = $validated['status'] ?? 'active';

            $tutor = null;
            $user = null;
            $defaultPassword = 'password123';

            DB::transaction(function() use ($validated, $request, $defaultPassword, &$tutor, &$user) {
                // Handle profile photo upload
                if ($request->hasFile('profile_photo')) {
                    $validated['profile_photo'] = $request->file('profile_photo')->store('tutors/photos', 'public');
                }

                // Generate unique tutor ID
                $validated['tutor_id'] = 'TUT-' . strtoupper(uniqid());

                $tutor = Tutor::create($validated);

                // Create associated user account with default password
                $user = User::create([
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($defaultPassword),
                    'email_verified_at' => now(), // Auto-verify since admin is creating the account
                    'status' => 'active',
                    'password_change_required' => true,
                    'phone' => $validated['phone'] ?? null,
                ]);

                // Assign tutor role via role_user pivot table
                $tutorRole = Role::where('name', 'tutor')->first();
                if ($tutorRole) {
                    $user->roles()->attach($tutorRole->id);
                }

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

        // Send welcome email to tutor
        $emailSent = false;
        if ($user && $tutor && !empty($user->email)) {
            try {
                $loginUrl = secure_url('/login');
                Mail::to($user->email)->send(new TutorAccountWelcomeMail(
                    user: $user,
                    tutor: $tutor,
                    password: $defaultPassword,
                    loginUrl: $loginUrl
                ));

                $emailSent = true;
                Log::info("Successfully sent tutor welcome email", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'tutor_id' => $tutor->id,
                    'tutor_name' => $tutor->first_name . ' ' . $tutor->last_name
                ]);
            } catch (\Exception $e) {
                Log::error("FAILED to send tutor welcome email", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'tutor_id' => $tutor->id,
                    'error' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't throw - email failure shouldn't break account creation
            }
        } else {
            Log::warning("Skipped sending tutor welcome email - missing data", [
                'has_user' => (bool)$user,
                'has_tutor' => (bool)$tutor,
                'has_email' => !empty($user->email ?? null)
            ]);
        }

        $successMessage = 'Tutor created successfully. ';
        if ($emailSent) {
            $successMessage .= 'A welcome email with login credentials has been sent to ' . $validated['email'] . '. Default password: password123';
        } else {
            $successMessage .= 'Please note: Welcome email could not be sent. Default password is: password123. Please share this with the tutor.';
        }

        return redirect()
            ->route('admin.tutors.index')
            ->with('success', $successMessage);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so they display properly
            throw $e;
        } catch (QueryException $e) {
            // Handle database-specific errors
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry')) {
                Log::warning("Duplicate tutor email attempted", [
                    'email' => $request->email,
                    'error' => $e->getMessage()
                ]);

                return back()
                    ->withInput()
                    ->with('error', 'This email address is already in use. Please use a different email.');
            }

            // Log other database errors with full details
            Log::error("Database error creating tutor in Admin portal", [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ]);

            // Show actual error to user for debugging
            return back()
                ->withInput()
                ->with('error', 'Database error: ' . $e->getMessage());

        } catch (\Exception $e) {
            // Keep existing generic handler for non-database errors
            Log::error("Failed to create tutor in Admin portal", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified tutor.
     */
    public function show(Tutor $tutor)
    {
        $tutor->load(['students', 'attendances', 'user', 'availabilities']);
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
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_relationship' => 'nullable|string|max:100',
            'contact_person_phone' => ['nullable', 'string', new NigerianPhone()],
            
            // Payment Details
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            
            // Status
            'status' => 'required|in:active,inactive,on_leave,resigned',
        ]);

        $previousStatus = $tutor->status;
        $studentsUnassigned = 0;

        DB::transaction(function() use ($tutor, $validated, $request, $previousStatus, &$studentsUnassigned) {
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($tutor->profile_photo) {
                    Storage::disk('public')->delete($tutor->profile_photo);
                }
                $validated['profile_photo'] = $request->file('profile_photo')->store('tutors/photos', 'public');
            }

            // If status is changing to resigned, set resigned_at and unassign students
            if ($validated['status'] === 'resigned' && $previousStatus !== 'resigned') {
                $validated['resigned_at'] = now();
                $studentsUnassigned = $tutor->students()->count();
                $tutor->students()->update(['tutor_id' => null]);
            }

            // If status is changing FROM resigned to something else, clear resigned_at
            if ($validated['status'] !== 'resigned' && $previousStatus === 'resigned') {
                $validated['resigned_at'] = null;
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
