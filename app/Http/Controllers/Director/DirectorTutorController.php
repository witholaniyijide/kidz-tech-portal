<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Student;
use App\Models\Report;
use App\Models\AttendanceRecord;
use App\Http\Requests\StoreTutorRequest;
use App\Http\Requests\UpdateTutorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DirectorTutorController extends Controller
{
    /**
     * Display a listing of tutors.
     */
    public function index(Request $request)
    {
        $query = Tutor::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('tutor_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by location
        if ($request->has('location') && $request->location) {
            $query->where('location', $request->location);
        }

        $tutors = $query->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(15);

        // Get statistics
        $stats = [
            'total' => Tutor::count(),
            'active' => Tutor::where('status', 'active')->count(),
            'inactive' => Tutor::where('status', 'inactive')->count(),
            'on_leave' => Tutor::where('status', 'on_leave')->count(),
        ];

        // Get unique locations for filter dropdown
        $locations = Tutor::select('location')
            ->distinct()
            ->whereNotNull('location')
            ->orderBy('location')
            ->pluck('location');

        return view('director.tutors.index', compact('tutors', 'stats', 'locations'));
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
    public function store(StoreTutorRequest $request)
    {
        $data = $request->validated();

        // Generate tutor ID
        $data['tutor_id'] = $this->generateTutorId();

        // Set default status if not provided
        $data['status'] = $data['status'] ?? 'active';

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'tutor_' . Str::slug($data['email']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            $data['profile_photo'] = $path;
        }

        $tutor = Tutor::create($data);

        // Optional: create user account when checkbox is checked
        $tempPassword = null;
        if ($request->boolean('create_user_account')) {
            $tempPassword = 'KidzTech2025';

            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($tempPassword),
                'profile_photo' => $data['profile_photo'] ?? null,
            ]);

            // Assign tutor role
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('tutor');
            }

            // Flash temp password for one-time display
            session()->flash('temp_password', $tempPassword);
            session()->flash('temp_password_email', $data['email']);
        }

        return redirect()->route('director.tutors.show', $tutor)
            ->with('success', 'Tutor created successfully!' . ($tempPassword ? ' User account created with temporary password.' : ''));
    }

    /**
     * Display the specified tutor.
     */
    public function show(Tutor $tutor)
    {
        // Load assigned students
        $students = Student::where('tutor_id', $tutor->id)
            ->orderBy('first_name')
            ->get();

        // Get tutor's recent reports
        $recentReports = Report::where('instructor_id', $tutor->id)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get tutor's recent attendance records
        $recentAttendance = AttendanceRecord::where('tutor_id', $tutor->id)
            ->with('student')
            ->orderBy('class_date', 'desc')
            ->limit(10)
            ->get();

        // Calculate tutor performance metrics
        $metrics = [
            'total_students' => Student::where('tutor_id', $tutor->id)->count(),
            'active_students' => Student::where('tutor_id', $tutor->id)
                ->where('status', 'active')
                ->count(),
            'total_reports' => Report::where('instructor_id', $tutor->id)->count(),
            'reports_this_month' => Report::where('instructor_id', $tutor->id)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
            'total_classes' => AttendanceRecord::where('tutor_id', $tutor->id)
                ->where('status', 'approved')
                ->count(),
            'classes_this_month' => AttendanceRecord::where('tutor_id', $tutor->id)
                ->whereYear('class_date', Carbon::now()->year)
                ->whereMonth('class_date', Carbon::now()->month)
                ->where('status', 'approved')
                ->count(),
        ];

        return view('director.tutors.show', compact('tutor', 'students', 'recentReports', 'recentAttendance', 'metrics'));
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
    public function update(UpdateTutorRequest $request, Tutor $tutor)
    {
        $data = $request->validated();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($tutor->profile_photo && Storage::disk('public')->exists($tutor->profile_photo)) {
                Storage::disk('public')->delete($tutor->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = 'tutor_' . Str::slug($data['email']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            $data['profile_photo'] = $path;
        }

        $tutor->update($data);

        return redirect()->route('director.tutors.show', $tutor)
            ->with('success', 'Tutor updated successfully!');
    }

    /**
     * Remove the specified tutor.
     */
    public function destroy(Tutor $tutor)
    {
        // Check if tutor has assigned students
        $studentCount = Student::where('tutor_id', $tutor->id)->count();

        if ($studentCount > 0) {
            return redirect()->route('director.tutors.index')
                ->with('error', "Cannot delete tutor. They have {$studentCount} assigned student(s). Please reassign the students first.");
        }

        // Delete profile photo if exists
        if ($tutor->profile_photo && Storage::disk('public')->exists($tutor->profile_photo)) {
            Storage::disk('public')->delete($tutor->profile_photo);
        }

        $tutor->delete();

        return redirect()->route('director.tutors.index')
            ->with('success', 'Tutor deleted successfully!');
    }

    /**
     * Generate unique tutor ID.
     */
    private function generateTutorId()
    {
        $lastTutor = Tutor::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastTutor ? intval(substr($lastTutor->tutor_id, 2)) + 1 : 1;

        return 'TT' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
