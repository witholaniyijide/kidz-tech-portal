<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutor\StoreReportRequest;
use App\Http\Requests\Tutor\UpdateReportRequest;
use App\Models\Student;
use App\Models\TutorNotification;
use App\Models\TutorReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Skills database for the report system.
     */
    protected $skillsDatabase;

    public function __construct()
    {
        $this->skillsDatabase = $this->getSkillsDatabase();
    }

    /**
     * Display a listing of the tutor's reports.
     */
    public function index(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $query = TutorReport::where('tutor_id', $tutor->id)
            ->with(['student', 'author']);

        // Apply filters
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $reports = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->except('page'));

        // Get unique months for filter dropdown
        $months = TutorReport::where('tutor_id', $tutor->id)
            ->select('month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        // Get students for filter
        $students = $tutor->students()->active()->get();

        // Stats
        $stats = [
            'total' => TutorReport::where('tutor_id', $tutor->id)->count(),
            'draft' => TutorReport::where('tutor_id', $tutor->id)->where('status', 'draft')->count(),
            'submitted' => TutorReport::where('tutor_id', $tutor->id)->where('status', 'submitted')->count(),
            'approved' => TutorReport::where('tutor_id', $tutor->id)->whereIn('status', ['approved', 'director_approved'])->count(),
            'returned' => TutorReport::where('tutor_id', $tutor->id)->where('status', 'returned')->count(),
        ];

        return view('tutor.reports.index', compact('reports', 'months', 'students', 'stats'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $students = $tutor->students()->active()->get();
        $courses = $this->getCourses();
        $skillsDatabase = $this->skillsDatabase;

        // Check for artifact import via URL parameter
        $importData = null;
        if ($request->has('import')) {
            try {
                $encoded = $request->get('import');
                $decoded = urldecode(base64_decode($encoded));
                $importData = json_decode($decoded, true);
            } catch (\Exception $e) {
                // Invalid import data, ignore
            }
        }

        // Pre-select student if passed
        $selectedStudentId = $request->get('student_id');

        return view('tutor.reports.create', compact(
            'students', 
            'courses', 
            'skillsDatabase', 
            'importData',
            'selectedStudentId'
        ));
    }

    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        // Validate the request
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|string',
            'year' => 'required|string|size:4',
            'courses' => 'nullable|array',
            'skills_mastered' => 'nullable|array',
            'new_skills' => 'nullable|array',
            'projects' => 'nullable|array',
            'areas_for_improvement' => 'nullable|string',
            'goals_next_month' => 'nullable|string',
            'assignments' => 'nullable|string',
            'comments_observation' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted',
        ]);

        // Verify student belongs to this tutor
        $student = Student::findOrFail($validated['student_id']);

        if ($student->tutor_id !== $tutor->id) {
            abort(403, 'You can only create reports for your assigned students.');
        }

        // Create report
        $report = TutorReport::create([
            'student_id' => $validated['student_id'],
            'tutor_id' => $tutor->id,
            'created_by' => Auth::id(),
            'title' => $student->first_name . ' ' . $student->last_name . ' - ' . $validated['month'] . ' ' . $validated['year'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'courses' => $validated['courses'] ?? [],
            'skills_mastered' => $validated['skills_mastered'] ?? [],
            'new_skills' => $validated['new_skills'] ?? [],
            'projects' => $validated['projects'] ?? [],
            'areas_for_improvement' => $validated['areas_for_improvement'] ?? null,
            'goals_next_month' => $validated['goals_next_month'] ?? null,
            'assignments' => $validated['assignments'] ?? null,
            'comments_observation' => $validated['comments_observation'] ?? null,
            'status' => $validated['status'] ?? 'draft',
            'imported_from_artifact' => $request->boolean('imported_from_artifact', false),
            'artifact_export_date' => $request->get('artifact_export_date'),
        ]);

        // If submitted, update timestamp and notify
        if ($report->status === 'submitted') {
            $report->update(['submitted_at' => now()]);

            TutorNotification::create([
                'tutor_id' => $tutor->id,
                'title' => 'Report Submitted',
                'body' => "Report for {$student->first_name} {$student->last_name} ({$report->month} {$report->year}) has been submitted for review.",
                'type' => 'system',
                'is_read' => false,
                'meta' => ['report_id' => $report->id],
            ]);
        }

        $message = $report->status === 'draft'
            ? 'Report saved as draft successfully!'
            : 'Report submitted successfully! Awaiting manager review.';

        return redirect()
            ->route('tutor.reports.show', $report)
            ->with('success', $message);
    }

    /**
     * Display the specified report.
     */
    public function show(TutorReport $report)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        if ($report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Load relationships - only show director comments to tutor (hide manager comments)
        $report->load(['student', 'comments' => function($query) {
            // Only show director comments or comments made by the tutor themselves
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->whereHas('roles', function($roleQuery) {
                        $roleQuery->where('name', 'director');
                    });
                })->orWhere('user_id', Auth::id());
            });
        }, 'comments.user']);

        return view('tutor.reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(TutorReport $report)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        if ($report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access to this report.');
        }

        if (!$report->canEdit()) {
            return redirect()
                ->route('tutor.reports.show', $report)
                ->with('error', 'Only draft or returned reports can be edited.');
        }

        $students = $tutor->students()->active()->get();
        $courses = $this->getCourses();
        $skillsDatabase = $this->skillsDatabase;

        return view('tutor.reports.edit', compact('report', 'students', 'courses', 'skillsDatabase'));
    }

    /**
     * Update the specified report in storage.
     */
    public function update(Request $request, TutorReport $report)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!$report->canEdit()) {
            return redirect()
                ->route('tutor.reports.show', $report)
                ->with('error', 'Only draft or returned reports can be edited.');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|string',
            'year' => 'required|string|size:4',
            'courses' => 'nullable|array',
            'skills_mastered' => 'nullable|array',
            'new_skills' => 'nullable|array',
            'projects' => 'nullable|array',
            'areas_for_improvement' => 'nullable|string',
            'goals_next_month' => 'nullable|string',
            'assignments' => 'nullable|string',
            'comments_observation' => 'nullable|string',
        ]);

        $student = Student::findOrFail($validated['student_id']);

        $report->update([
            'student_id' => $validated['student_id'],
            'title' => $student->first_name . ' ' . $student->last_name . ' - ' . $validated['month'] . ' ' . $validated['year'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'courses' => $validated['courses'] ?? [],
            'skills_mastered' => $validated['skills_mastered'] ?? [],
            'new_skills' => $validated['new_skills'] ?? [],
            'projects' => $validated['projects'] ?? [],
            'areas_for_improvement' => $validated['areas_for_improvement'],
            'goals_next_month' => $validated['goals_next_month'],
            'assignments' => $validated['assignments'],
            'comments_observation' => $validated['comments_observation'],
        ]);

        return redirect()
            ->route('tutor.reports.edit', $report)
            ->with('success', 'Report updated successfully!');
    }

    /**
     * Remove the specified report from storage.
     */
    public function destroy(TutorReport $report)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        if ($report->status !== 'draft') {
            return redirect()
                ->route('tutor.reports.index')
                ->with('error', 'Only draft reports can be deleted.');
        }

        $report->delete();

        return redirect()
            ->route('tutor.reports.index')
            ->with('success', 'Report deleted successfully!');
    }

    /**
     * Submit a draft report for review.
     */
    public function submit(Request $request, TutorReport $report)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!in_array($report->status, ['draft', 'returned'])) {
            return redirect()
                ->route('tutor.reports.show', $report)
                ->with('error', 'Only draft or returned reports can be submitted.');
        }

        // Validate report has required content
        $issues = [];
        if (empty($report->skills_mastered) || count($report->skills_mastered) === 0) {
            $issues[] = 'At least one skill mastered is required';
        }
        if (empty($report->areas_for_improvement)) {
            $issues[] = 'Areas for improvement is required';
        }
        if (empty($report->goals_next_month)) {
            $issues[] = 'Goals for next month is required';
        }
        if (empty($report->comments_observation)) {
            $issues[] = 'Comments/Observation is required';
        }

        if (count($issues) > 0) {
            return redirect()
                ->route('tutor.reports.edit', $report)
                ->with('error', 'Please complete all required sections before submitting: ' . implode(', ', $issues));
        }

        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        TutorNotification::create([
            'tutor_id' => $tutor->id,
            'title' => 'Report Submitted',
            'body' => "Report for {$report->student->first_name} {$report->student->last_name} ({$report->month} {$report->year}) has been submitted for review.",
            'type' => 'system',
            'is_read' => false,
            'meta' => ['report_id' => $report->id],
        ]);

        return redirect()
            ->route('tutor.reports.show', $report)
            ->with('success', 'Report submitted successfully! Awaiting manager review.');
    }

    /**
     * Import report from Claude Artifact JSON.
     */
    public function importFromArtifact(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            abort(403, 'You do not have a tutor profile.');
        }

        $validated = $request->validate([
            'json_data' => 'required|string',
        ]);

        try {
            $data = json_decode($validated['json_data'], true);

            if (!$data || !isset($data['studentName']) || !isset($data['month'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid report data format.',
                ], 400);
            }

            // Find student by name (partial match)
            $studentName = trim($data['studentName']);
            $student = $tutor->students()
                ->where(function($q) use ($studentName) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$studentName}%"])
                      ->orWhere('first_name', 'LIKE', "%{$studentName}%");
                })
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => "Student '{$studentName}' not found in your assigned students.",
                    'available_students' => $tutor->students()->active()->get()->map(fn($s) => $s->first_name . ' ' . $s->last_name),
                ], 404);
            }

            // Redirect to create page with pre-filled data
            $importData = base64_encode(urlencode(json_encode($data)));

            return response()->json([
                'success' => true,
                'redirect' => route('tutor.reports.create', [
                    'import' => $importData,
                    'student_id' => $student->id,
                ]),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to parse report data: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Export report as PDF.
     */
    public function exportPdf(TutorReport $report)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        $report->load(['student', 'tutor']);

        $pdf = Pdf::loadView('tutor.reports.pdf', compact('report'));

        $filename = 'report_' . $report->student->first_name . '_' . $report->student->last_name . '_' . $report->month . '_' . $report->year . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export report as JSON for WhatsApp.
     */
    public function exportWhatsApp(TutorReport $report)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        $report->load(['student', 'tutor']);

        $projectsList = collect($report->projects ?? [])
            ->filter(fn($p) => !empty($p['title']))
            ->map(fn($p, $i) => "Project " . ($i + 1) . ": " . $p['title'] . ($p['link'] ? " – " . $p['link'] : ''))
            ->implode("\n");

        $text = "*Kidz Tech Coding Club: Monthly Progress Report*\n\n"
            . "*Student:* " . $report->student->first_name . " " . $report->student->last_name . "\n"
            . "*Month:* " . $report->month . " " . $report->year . "\n"
            . "*Instructor:* " . $report->tutor->first_name . " " . $report->tutor->last_name . "\n"
            . "*Course(s):* " . implode(', ', $report->courses ?? []) . "\n\n"
            . "*1. Progress Overview:*\n"
            . "*Skills Mastered:* " . implode(', ', $report->skills_mastered ?? []) . "\n"
            . "*New Skills:* " . (count($report->new_skills ?? []) > 0 ? implode(', ', $report->new_skills) : 'N/A') . "\n\n"
            . "*2. Projects/Activities Completed:*\n" . $projectsList . "\n\n"
            . "*3. Areas for Improvement:*\n" . ($report->areas_for_improvement ?? 'N/A') . "\n\n"
            . "*4. Goals for Next Month:*\n" . ($report->goals_next_month ?? 'N/A') . "\n\n"
            . "*5. Assignment/Projects during the month:*\n" . ($report->assignments ?? 'N/A') . "\n\n"
            . "*6. Comments/Observation:*\n" . ($report->comments_observation ?? 'N/A');

        return response()->json(['text' => $text]);
    }

    /**
     * Display printable view of report.
     */
    public function print(TutorReport $report)
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor || $report->tutor_id !== $tutor->id) {
            abort(403, 'Unauthorized access.');
        }

        $report->load(['student', 'tutor']);

        return view('tutor.reports.print', compact('report'));
    }

    /**
     * Get all available courses.
     */
    protected function getCourses(): array
    {
        return [
            'Introduction to Computer Science',
            'Introduction to Coding & Fundamental Concepts of Coding',
            'Introduction to Scratch Programming',
            'Introduction to Artificial Intelligence',
            'Introduction to Graphic Design',
            'Game Development',
            'Mobile App Development',
            'Website Development',
            'Python Programming',
            'Digital Literacy & Safety',
            'Machine Learning',
            'Robotics',
        ];
    }

    /**
     * Get the skills database.
     */
    protected function getSkillsDatabase(): array
    {
        return [
            'Introduction to Computer Science' => [
                'Computer hardware components (monitor, keyboard, mouse, CPU)',
                'Operating system basics (Windows/Mac navigation)',
                'File management (creating, saving, organizing folders)',
                'Basic typing and keyboard shortcuts',
                'Using the mouse (clicking, dragging, right-click)',
                'Desktop navigation and icons',
                'Computer software vs hardware understanding',
                'Internet safety and digital citizenship',
                'Modern types of computers',
            ],
            'Introduction to Coding & Fundamental Concepts of Coding' => [
                'Understanding what coding/programming is',
                'Algorithm concepts and creation',
                'Sequences (step-by-step instructions)',
                'Loops (repetition)',
                'Debugging basics',
                'Functions and procedures',
                'Coordinates and positioning',
                'Binary concepts',
                'Arrays and data organization',
                'Programming language types and uses',
                'Careers in coding',
            ],
            'Introduction to Scratch Programming' => [
                'Scratch interface navigation',
                'Sprite creation and customization',
                'Backdrop design and selection',
                'Motion blocks (move, glide, turn)',
                'Looks blocks (costume changes, effects)',
                'Sound integration and music',
                'Event blocks (when clicked, when key pressed)',
                'Basic animation techniques',
                'Loops in Scratch (repeat, forever)',
                'Conditionals in Scratch (if/then/else)',
                'Variables in Scratch',
                'Broadcast messages for sprite communication',
                'Pen extension for drawing shapes',
                'Cloning sprites',
                'Story creation and narrative design',
                'Game mechanics (score keeping, collision detection)',
                'Motion sensing and arrow keys control',
                'Creating animations with multiple costumes',
                'Food truck and moving city animations',
                'Pen drawing (basic and complex shapes)',
                'Story telling with sound effects',
                'Solar system projects with music',
                'Game design - Ping Pong, Pop the balloon',
                'Game design - Obstacle, Fruit Catcher, Cheese Eater',
                'Game design - Math Quiz, Maze, Soccer',
                'Game design - Memory, Space Shooter, Flappy Bird',
                'Game design - Platformer games',
            ],
            'Introduction to Artificial Intelligence' => [
                'What is AI and machine learning',
                'AI in everyday life (assistants, recommendations)',
                'Possibilities and limitations of AI',
                'Ethics of AI',
                'Prompt engineering - using the right words',
                'Image generation with AI',
                'Creating comic books with AI',
                'Story book creation with speech bubbles',
                'Landscape and illustration prompting',
                'Pencil sketches generation',
                'AI for food, people, and objects',
                'Color book illustrations',
                'Book cover design with AI',
                'Stickers and T-shirt designs',
                'Musical sounds and melodies with AI',
                'Text-to-Speech (TTS) using AI',
                'Comical animations using AI',
                'Editing AI-generated images',
            ],
            'Introduction to Graphic Design' => [
                'Fundamentals and principles of design',
                'Color theory and color harmony',
                'Typography basics',
                'Canvas/workspace navigation (Canva)',
                'Creating flyers and posters',
                'Greeting card design',
                'Illustrations of people and animals',
                'GIF creation and animation',
                'Book cover design',
                'Logo design',
                'Introduction to Figma interface',
                'UI (User Interface) design basics',
                'UX (User Experience) principles',
                'Design composition and layout',
                'Image manipulation and editing',
            ],
            'Game Development' => [
                'Game design concepts (rules, objectives, challenges)',
                'Game Maker Studio interface navigation',
                'Sprite creation and animation',
                'Collision detection',
                'Score and lives system',
                'Level design basics',
                'Arcade Space Shooter mechanics',
                'Action Adventure game elements',
                'Platformer game design',
                'Escape game logic',
                'Brick Breaker mechanics',
                'Tank game development',
                'Multiplayer game concepts',
                'Roblox Studio interface navigation',
                'Obstacle course creation',
                'Maze design and logic',
                'Treasure hunt mechanics',
                'Food Frenzy game design',
                'Space Explorer game',
                'Chase game mechanics',
                'Shooter game development',
            ],
            'Mobile App Development' => [
                'Kodular Creator interface navigation',
                'Mobile app design principles',
                'Screen navigation and multi-screen apps',
                'Button and touch interactions',
                'Text input and forms',
                'Portfolio app creation (About Me)',
                'Brand app development',
                'Side menu components',
                'Calculator app development',
                'Flashlight app',
                'Countdown timer app',
                'Calendar app',
                'Speech-to-text functionality',
                'Text-to-speech functionality',
                'Timetable app',
                'Unit converter app',
                'Camera app integration',
                'Music player app',
                'OCR (Optical Character Recognition)',
                'Video player app',
                'Radio app development',
                'ChatGPT integration',
                'Translator app',
                'Educational app development',
                'Website to app conversion',
                'E-Commerce app basics',
                'Puzzle game app',
                'Pop the balloon game',
                'Math Quiz app',
                'Trivia Quiz app',
                'Paint book app',
                'Tic-tac-toe game',
                'Memory card game',
                'Car race game',
                'Testing and debugging apps',
            ],
            'Website Development' => [
                'Website design fundamentals',
                'Web server, domain, and hosting concepts',
                'HTML structure and tags',
                'Headings, paragraphs, links, and images in HTML',
                'CSS basics (colors, fonts, backgrounds)',
                'CSS selectors and properties',
                'Edublocks interface and block-based coding',
                'Portfolio website creation',
                'Google Sites website building',
                'Bootstrap with Google Sites',
                'VS Code setup and usage',
                'HTML & CSS portfolio website',
                'Multi-page website structure',
                'Navigation menus',
                'Website responsiveness',
                'JavaScript basics (variables, loops, conditionals)',
                'Adding interactivity to web pages',
                'JavaScript web apps (To-Do List, Timer)',
                'Random color generator',
                'Calculator web app',
                'WordPress introduction and interface',
                'WordPress on localhost setup',
                'Posts, pages, and media in WordPress',
                'WordPress themes, plugins, and widgets',
                'Customizing website layout and design',
            ],
            'Python Programming' => [
                'Python syntax and structure',
                'Basic data types (strings, integers, floats)',
                'Variables and assignment',
                'Print statements and output',
                'Input and user interaction',
                'Conditionals (if/elif/else)',
                'Loops (for loops, while loops)',
                'Functions and parameters',
                'Modules and libraries',
                'Lists, arrays, and dictionaries',
                'Working with data structures',
                'Turtle graphics for visual projects',
                'Problem-solving with Python',
                'Debugging techniques',
                'Game development with Python',
            ],
            'Digital Literacy & Safety' => [
                'Digital citizenship basics',
                'Digital footprints and online reputation',
                'Digital safety and security terms',
                'Online privacy and personal information protection',
                'Strong password creation',
                'Privacy settings and protection',
                'Identifying scams and online threats',
                'Cyberbullying awareness and prevention',
                'How to stay protected online',
                'Word processing tools and formatting',
                'Word processing online (Google Docs)',
                'Data entry tools and formatting',
                'Formulas and variables in spreadsheets',
                'Collecting data through forms and surveys',
                'Data privacy policies',
                'Presentation tools and best practices',
                'Working with media files',
                'Creating presentation slides',
                'Digital branding and online presence',
            ],
            'Machine Learning' => [
                'Machine learning fundamentals',
                'ML vs traditional programming',
                'Basic concepts: patterns, predictions, decisions',
                'Training data and datasets',
                'Image and pattern recognition',
                'Classification and categorization',
                'Speech assistants and voice recognition',
                'Model training process',
                'Building simple ML models',
                'Natural language processing (NLP)',
                'Computer vision with AI',
                'Machine learning in gaming (sorting, quizzes)',
                'Model accuracy and evaluation',
                'Data collection and preparation',
                'Ethical considerations in ML',
                'Responsible AI development',
            ],
            'Robotics' => [
                'Introduction to robotics',
                'Basic robotic concepts (sensors, control systems)',
                'Types of robots and their applications',
                'Virtual robotics environments',
                'Robot programming basics',
                'Building virtual robots',
                'Programming robots for specific tasks',
                'Virtual Reality (VR) in robotics',
                'Augmented Reality (AR) in robotics',
                'VR applications and gaming platforms',
                'Robotics gaming challenges',
                'Ethical considerations in robotics',
                'Responsible robotics development',
            ],
        ];
    }
}
