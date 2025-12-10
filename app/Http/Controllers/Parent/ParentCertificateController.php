<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ParentCertificateController extends Controller
{
    /**
     * Display all certifications for all children.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all children
        $children = $user->guardiansOf()->get();

        if ($children->isEmpty()) {
            return view('parent.no-children');
        }

        // Get student IDs
        $studentIds = $children->pluck('id');

        // Get selected child filter (optional)
        $selectedChildId = $request->input('student_id');

        // Build query
        $query = Certification::whereIn('student_id', $studentIds)
            ->where('status', 'active')
            ->with(['student']);

        if ($selectedChildId) {
            $query->where('student_id', $selectedChildId);
        }

        $certifications = $query->orderBy('issue_date', 'desc')->paginate(12);

        return view('parent.certifications.index', compact(
            'children',
            'certifications',
            'selectedChildId'
        ));
    }

    /**
     * Display a specific certification.
     */
    public function show(Certification $certification)
    {
        $user = Auth::user();

        // Ensure this certification belongs to one of the parent's children
        $student = Student::find($certification->student_id);

        abort_unless(
            $student && ($user->isGuardianOf($student) || $user->hasRole('admin')),
            403,
            'Unauthorized: You can only view certifications for your own children.'
        );

        $certification->load(['student', 'uploader']);

        return view('parent.certifications.show', compact('certification'));
    }

    /**
     * Download a certification file.
     */
    public function download(Certification $certification)
    {
        $user = Auth::user();

        // Ensure this certification belongs to one of the parent's children
        $student = Student::find($certification->student_id);

        abort_unless(
            $student && ($user->isGuardianOf($student) || $user->hasRole('admin')),
            403,
            'Unauthorized: You can only download certifications for your own children.'
        );

        // Check if file exists
        if (!Storage::disk('public')->exists($certification->file_path)) {
            abort(404, 'Certificate file not found.');
        }

        // Generate a meaningful filename
        $filename = 'Certificate_' . str_replace(' ', '_', $student->full_name) . '_' . $certification->certificate_id . '.' . $certification->file_type;

        return Storage::disk('public')->download($certification->file_path, $filename);
    }

    /**
     * Validate a certificate by its ID.
     */
    public function validate(Request $request)
    {
        $certificateId = $request->input('certificate_id');

        if (!$certificateId) {
            return response()->json([
                'valid' => false,
                'message' => 'Certificate ID is required.',
            ]);
        }

        $certification = Certification::where('certificate_id', $certificateId)->first();

        if (!$certification) {
            return response()->json([
                'valid' => false,
                'message' => 'Certificate not found.',
            ]);
        }

        if (!$certification->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Certificate has been revoked or has expired.',
            ]);
        }

        // Load student info
        $certification->load('student');

        return response()->json([
            'valid' => true,
            'message' => 'Certificate is valid.',
            'certificate' => [
                'id' => $certification->certificate_id,
                'title' => $certification->title,
                'student_name' => $certification->student->full_name ?? 'Unknown',
                'course_name' => $certification->course_name,
                'issue_date' => $certification->issue_date->format('F d, Y'),
                'expiry_date' => $certification->expiry_date ? $certification->expiry_date->format('F d, Y') : null,
            ],
        ]);
    }

    /**
     * View certificate (for viewing in browser).
     */
    public function view(Certification $certification)
    {
        $user = Auth::user();

        // Ensure this certification belongs to one of the parent's children
        $student = Student::find($certification->student_id);

        abort_unless(
            $student && ($user->isGuardianOf($student) || $user->hasRole('admin')),
            403,
            'Unauthorized: You can only view certifications for your own children.'
        );

        // Check if file exists
        if (!Storage::disk('public')->exists($certification->file_path)) {
            abort(404, 'Certificate file not found.');
        }

        // Get the file content
        $file = Storage::disk('public')->get($certification->file_path);
        $mimeType = Storage::disk('public')->mimeType($certification->file_path);

        return response($file)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }
}
