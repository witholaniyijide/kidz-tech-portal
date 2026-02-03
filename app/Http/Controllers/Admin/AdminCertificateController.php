<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminCertificateController extends Controller
{
    /**
     * Course codes and names mapping
     */
    protected $courses = [
        'ICS' => 'Introduction to Computer Science',
        'ICF' => 'Introduction to Coding & Fundamental Concepts',
        'SCR' => 'Introduction to Scratch Programming',
        'AI' => 'Introduction to Artificial Intelligence',
        'GD' => 'Introduction to Graphics Design',
        'GAME' => 'Game Development (Game Maker & Roblox)',
        'APP' => 'Mobile App Development',
        'WEB' => 'Website Development',
        'PYT' => 'Python Programming',
        'DLS' => 'Digital Literacy & Safety',
        'ML' => 'Machine Learning',
        'ROB' => 'Robotics',
    ];

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
     * Show the certificate creation form
     */
    public function index()
    {
        $students = Student::where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . ($student->other_name ? $student->other_name . ' ' : '') . $student->last_name,
                ];
            });

        return view('admin.certificates.index', [
            'students' => $students,
            'courses' => $this->courses,
        ]);
    }

    /**
     * Generate certificate and store in WordPress database
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_code' => 'required|in:' . implode(',', array_keys($this->courses)),
        ], [
            'student_id.required' => 'Please select a student.',
            'student_id.exists' => 'The selected student does not exist.',
            'course_code.required' => 'Please select a course.',
            'course_code.in' => 'The selected course is invalid.',
        ]);

        $student = Student::findOrFail($request->student_id);
        $courseCode = $request->course_code;
        $courseName = $this->courses[$courseCode];
        $studentName = trim($student->first_name . ' ' . ($student->other_name ? $student->other_name . ' ' : '') . $student->last_name);

        try {
            // Test WordPress database connection first
            DB::connection('wordpress')->getPdo();

            // Generate unique certificate ID
            $certificateId = $this->generateCertificateId($courseCode);

            // Insert into WordPress database
            DB::connection('wordpress')->table('wpnm_certificates')->insert([
                'certificate_id' => $certificateId,
                'student_name' => $studentName,
                'course_code' => $courseCode,
                'course_name' => $courseName,
                'issue_date' => now()->toDateString(),
                'status' => 'valid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Generate verification URL
            $verificationUrl = 'https://kidztech.edubeta.net.ng/verify/' . $certificateId;

            return redirect()->route('admin.certificates.index')
                ->with('success', 'Certificate created successfully!')
                ->with('certificate_id', $certificateId)
                ->with('verification_url', $verificationUrl)
                ->with('student_name', $studentName)
                ->with('course_name', $courseName);

        } catch (\PDOException $e) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'WordPress database connection failed. Please check your .env credentials (WP_DB_HOST, WP_DB_DATABASE, WP_DB_USERNAME, WP_DB_PASSWORD).')
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if it's a table not found error
            if (str_contains($e->getMessage(), "doesn't exist")) {
                return redirect()->route('admin.certificates.index')
                    ->with('error', 'The wpnm_certificates table does not exist in your WordPress database. Please create it first.')
                    ->withInput();
            }
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Database error: ' . $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Failed to create certificate: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate a unique certificate ID in format: KTCC-YYYY-COURSECODE-XXXX
     */
    protected function generateCertificateId(string $courseCode): string
    {
        $year = date('Y');
        $prefix = "KTCC-{$year}-{$courseCode}-";

        // Get the latest sequence number for this year and course code
        $latestCertificate = DB::connection('wordpress')
            ->table('wpnm_certificates')
            ->where('certificate_id', 'LIKE', $prefix . '%')
            ->orderBy('certificate_id', 'desc')
            ->first();

        if ($latestCertificate) {
            // Extract the sequence number from the last certificate
            $lastId = $latestCertificate->certificate_id;
            $lastSequence = (int) substr($lastId, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        // Pad to 4 digits
        $paddedSequence = str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return $prefix . $paddedSequence;
    }
}
