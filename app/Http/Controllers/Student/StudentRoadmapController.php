<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentRoadmapController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('student') && !Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    /**
     * Get the student associated with the authenticated user.
     */
    protected function getAuthenticatedStudent()
    {
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            // Try by email as fallback
            $student = Student::where('email', Auth::user()->email)->first();
        }

        if (!$student) {
            abort(404, 'Student profile not found. Please contact administration.');
        }

        return $student;
    }

    /**
     * Display the full roadmap with stages and student progress summary.
     */
    public function full()
    {
        $student = $this->getAuthenticatedStudent();

        // Define curriculum stages
        $stages = $this->getCurriculumStages();

        // Calculate progress metrics
        $currentStage = $student->roadmap_stage ?? 'Intro to CS';
        $currentStageIndex = $this->getStageIndex($currentStage, $stages);
        $completedStages = max(0, $currentStageIndex);
        $remainingStages = count($stages) - $completedStages - 1;

        // Get milestone completion counts
        $totalMilestones = $student->progress()->count();
        $completedMilestones = $student->progress()->where('completed', true)->count();

        return view('student.roadmap.full', compact(
            'student',
            'stages',
            'currentStage',
            'completedStages',
            'remainingStages',
            'totalMilestones',
            'completedMilestones'
        ));
    }

    /**
     * Display milestones for a specific stage.
     */
    public function stageShow($stageSlug)
    {
        $student = $this->getAuthenticatedStudent();

        // Convert slug to stage name
        $stageName = str_replace('-', ' ', $stageSlug);
        $stageName = ucwords($stageName);

        // Get milestones for this stage
        $milestones = StudentProgress::where('student_id', $student->id)
            ->where('milestone_code', 'like', $stageName . '%')
            ->orWhere('title', 'like', '%' . $stageName . '%')
            ->orderBy('completed', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $stages = $this->getCurriculumStages();
        $stageData = collect($stages)->firstWhere('name', $stageName);

        return view('student.roadmap.stage', compact(
            'student',
            'stageName',
            'stageData',
            'milestones'
        ));
    }

    /**
     * Get curriculum stages array.
     */
    protected function getCurriculumStages()
    {
        return [
            [
                'name' => 'Intro to CS',
                'slug' => 'intro-to-cs',
                'duration' => '4 weeks',
                'prerequisites' => 'None',
                'outcomes' => ['Understanding of basic computer concepts', 'Introduction to algorithms', 'Problem-solving basics'],
                'color' => 'indigo',
                'code' => 'ICS'
            ],
            [
                'name' => 'Scratch Beginner',
                'slug' => 'scratch-beginner',
                'duration' => '8 weeks',
                'prerequisites' => 'Intro to CS',
                'outcomes' => ['Block-based programming', 'Creating simple animations', 'Interactive storytelling'],
                'color' => 'purple',
                'code' => 'SCR1'
            ],
            [
                'name' => 'Scratch Intermediate',
                'slug' => 'scratch-intermediate',
                'duration' => '10 weeks',
                'prerequisites' => 'Scratch Beginner',
                'outcomes' => ['Advanced Scratch blocks', 'Game mechanics', 'Variables and lists'],
                'color' => 'pink',
                'code' => 'SCR2'
            ],
            [
                'name' => 'Scratch Advanced',
                'slug' => 'scratch-advanced',
                'duration' => '12 weeks',
                'prerequisites' => 'Scratch Intermediate',
                'outcomes' => ['Complex game development', 'Broadcasting and cloning', 'Advanced algorithms'],
                'color' => 'rose',
                'code' => 'SCR3'
            ],
            [
                'name' => 'Game Dev',
                'slug' => 'game-dev',
                'duration' => '12 weeks',
                'prerequisites' => 'Scratch Advanced',
                'outcomes' => ['Game design principles', '2D game development', 'Physics and collision detection'],
                'color' => 'orange',
                'code' => 'GD'
            ],
            [
                'name' => 'App Dev',
                'slug' => 'app-dev',
                'duration' => '14 weeks',
                'prerequisites' => 'Game Dev',
                'outcomes' => ['Mobile app basics', 'UI/UX design', 'App deployment'],
                'color' => 'amber',
                'code' => 'AD'
            ],
            [
                'name' => 'Web Dev',
                'slug' => 'web-dev',
                'duration' => '16 weeks',
                'prerequisites' => 'App Dev',
                'outcomes' => ['HTML, CSS, JavaScript', 'Responsive design', 'Web hosting'],
                'color' => 'emerald',
                'code' => 'WD'
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'duration' => '16 weeks',
                'prerequisites' => 'Web Dev',
                'outcomes' => ['Python syntax', 'Data structures', 'Object-oriented programming'],
                'color' => 'blue',
                'code' => 'PY'
            ],
            [
                'name' => 'Java',
                'slug' => 'java',
                'duration' => '18 weeks',
                'prerequisites' => 'Python',
                'outcomes' => ['Java fundamentals', 'Advanced OOP', 'Software design patterns'],
                'color' => 'red',
                'code' => 'JV'
            ],
            [
                'name' => 'Data Structures',
                'slug' => 'data-structures',
                'duration' => '20 weeks',
                'prerequisites' => 'Java',
                'outcomes' => ['Arrays and linked lists', 'Trees and graphs', 'Sorting algorithms'],
                'color' => 'cyan',
                'code' => 'DS'
            ]
        ];
    }

    /**
     * Get the index of a stage in the stages array.
     */
    protected function getStageIndex($stageName, $stages)
    {
        foreach ($stages as $index => $stage) {
            if ($stage['name'] === $stageName) {
                return $index;
            }
        }
        return 0;
    }
}
