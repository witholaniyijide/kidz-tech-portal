<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Mail\TutorAccountWelcomeMail;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Rules\NigerianPhone;

class DirectorTutorController extends Controller
{
    /**
     * Display a listing of tutors.
     */
    public function index(Request $request)
    {
        $query = Tutor::with(['user', 'students']);

        // Filter by search (name, email, tutor_id)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('tutor_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get counts for cards
        $totalTutors = Tutor::count();
        $activeTutors = Tutor::where('status', 'active')->count();
        $inactiveTutors = Tutor::where('status', 'inactive')->count();
        $resignedTutors = Tutor::where('status', 'resigned')->count();

        $tutors = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('director.tutors.index', compact(
            'tutors',
            'totalTutors',
            'activeTutors',
            'inactiveTutors',
            'resignedTutors'
        ));
    }

    /**
     * Show the form for creating a new tutor.
     */
    public function create()
    {
        return view('director.tutors.create');
    }

    /**
     * Store a newly created tutor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tutors,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,resigned',
            'hire_date' => 'nullable|date',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'hourly_rate' => 'nullable|numeric|min:0',

            // Emergency Contact
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_relationship' => 'nullable|string|max:100',
            'contact_person_phone' => ['nullable', 'string', new NigerianPhone()],
        ]);

        $defaultPassword = 'password123';
        $tutor = null;
        $user = null;

        DB::beginTransaction();
        try {
            // Generate tutor ID
            $validated['tutor_id'] = 'TUT-' . strtoupper(uniqid());

            // Create tutor record first
            $tutor = Tutor::create($validated);

            // Always create user account with default password
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($defaultPassword),
                'email_verified_at' => now(), // Auto-verify since director is creating the account
                'status' => 'active',
                'password_change_required' => true,
                'phone' => $validated['phone'] ?? null,
            ]);

            // Assign tutor role
            $tutorRole = Role::where('name', 'tutor')->first();
            if ($tutorRole) {
                $user->roles()->attach($tutorRole->id);
            }

            // Link user to tutor
            $tutor->update(['user_id' => $user->id]);

            DB::commit();

            // Send welcome email to tutor
            $emailSent = false;
            if ($user && $tutor && !empty($user->email)) {
                try {
                    $loginUrl = config('app.url') . '/login';
                    Mail::to($user->email)->send(new TutorAccountWelcomeMail(
                        user: $user,
                        tutor: $tutor,
                        password: $defaultPassword,
                        loginUrl: $loginUrl
                    ));

                    $emailSent = true;
                    Log::info("Successfully sent tutor welcome email from Director portal", [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'tutor_id' => $tutor->id,
                        'tutor_name' => $tutor->first_name . ' ' . $tutor->last_name
                    ]);
                } catch (\Exception $e) {
                    Log::error("FAILED to send tutor welcome email from Director portal", [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'tutor_id' => $tutor->id,
                        'error' => $e->getMessage(),
                        'error_code' => $e->getCode(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Don't throw - email failure shouldn't break account creation
                }
            }

            $successMessage = 'Tutor created successfully. ';
            if ($emailSent) {
                $successMessage .= 'A welcome email with login credentials has been sent to ' . $validated['email'] . '. Default password: password123';
            } else {
                $successMessage .= 'Please note: Welcome email could not be sent. Default password is: password123. Please share this with the tutor.';
            }

            return redirect()->route('director.tutors.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Failed to create tutor in Director portal", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', 'Failed to create tutor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified tutor.
     */
    public function show(Tutor $tutor)
    {
        $tutor->load(['user', 'students', 'reports', 'availabilities']);
        
        // Get tutor statistics
        $totalStudents = $tutor->students()->count();
        $totalReports = $tutor->reports()->count();
        $pendingReports = $tutor->reports()->where('status', 'pending')->count();
        
        return view('director.tutors.show', compact('tutor', 'totalStudents', 'totalReports', 'pendingReports'));
    }

    /**
     * Show the form for editing the specified tutor.
     */
    public function edit(Tutor $tutor)
    {
        return view('director.tutors.edit', compact('tutor'));
    }

    /**
     * Update the specified tutor.
     */
    public function update(Request $request, Tutor $tutor)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('tutors')->ignore($tutor->id)],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,resigned',
            'hire_date' => 'nullable|date',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'hourly_rate' => 'nullable|numeric|min:0',

            // Emergency Contact
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_relationship' => 'nullable|string|max:100',
            'contact_person_phone' => ['nullable', 'string', new NigerianPhone()],
        ]);

        DB::beginTransaction();
        try {
            $tutor->update($validated);

            // Update linked user if exists
            if ($tutor->user) {
                $tutor->user->update([
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'email' => $validated['email'],
                ]);
            }

            DB::commit();

            return redirect()->route('director.tutors.index')
                ->with('success', 'Tutor updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update tutor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified tutor.
     */
    public function destroy(Tutor $tutor)
    {
        try {
            // Check if tutor has active students
            if ($tutor->students()->where('status', 'active')->exists()) {
                return back()->with('error', 'Cannot delete tutor with active students. Please reassign students first.');
            }

            // Store user reference before deleting tutor
            $user = $tutor->user;

            // Force delete tutor (permanently remove from database so email can be reused)
            $tutor->forceDelete();

            // Also delete associated user account if it exists
            if ($user) {
                $user->delete();
            }

            return redirect()->route('director.tutors.index')
                ->with('success', 'Tutor deleted successfully. Email can now be reused.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete tutor: ' . $e->getMessage());
        }
    }
}
