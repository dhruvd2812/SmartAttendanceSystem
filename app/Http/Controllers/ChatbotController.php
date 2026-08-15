<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function message(Request $request): JsonResponse
    {
        $question = $this->normalise(
            $request->validate([
                'message' => ['required', 'string', 'max:1000']
            ])['message']
        );

        $user = $request->user();
        $role = strtolower($user->role ?? '');

        /*
        |--------------------------------------------------------------------------
        | COMMON QUESTIONS
        |--------------------------------------------------------------------------
        */

        if ($this->contains($question, [
            'hello',
            'hi',
            'hey',
            'help'
        ])) {
            return $this->reply(
                "Hello! 👋 I'm your Smart Attendance Assistant.\n\n" .
                "You can ask me about attendance, QR, subjects, faculty, students or your department."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STUDENT CHATBOT
        |--------------------------------------------------------------------------
        */

        if ($role === 'student') {

            if ($this->contains($question, [
                'today attendance',
                'attendance today',
                'my attendance today'
            ])) {
                return $this->reply(
                    $this->todayStudentAttendance($user)
                );
            }

            if ($this->contains($question, [
                'my attendance',
                'attendance percentage',
                'what is my attendance',
                'show my attendance'
            ])) {
                $subject = $this->findSubject($question);

                return $this->reply(
                    $this->studentAttendance($user, $subject)
                );
            }

            if ($this->contains($question, [
                'who teaches',
                'who is faculty',
                'faculty for',
                'teacher'
            ])) {
                $subject = $this->findSubject($question);

                return $this->reply(
                    $this->facultyForSubject($subject)
                );
            }

            if ($this->contains($question, [
                'how many departments',
                'total departments',
                'list departments',
                'which departments'
            ])) {
                return $this->reply(
                    $this->departmentInformation($question)
                );
            }

            if ($this->contains($question, [
                'how many subjects',
                'total subjects',
                'list subjects',
                'what subjects',
                'which subjects'
            ])) {
                return $this->reply(
                    $this->subjectInformation($user, $question)
                );
            }

            if ($this->contains($question, [
                'qr',
                'scan qr',
                'scan'
            ])) {
                return $this->reply(
                    "📱 To mark attendance:\n\n" .
                    "1. Open Scan QR.\n" .
                    "2. Scan the faculty's active QR code.\n" .
                    "3. Allow GPS/location permission.\n" .
                    "4. System will verify the lecture and your location.\n" .
                    "5. Attendance will be marked automatically."
                );
            }

            return $this->reply(
                "I can help you with:\n\n" .
                "📊 My attendance\n" .
                "📅 Today's attendance\n" .
                "📚 Subject-wise attendance\n" .
                "👨‍🏫 Who teaches a subject?\n" .
                "📱 QR attendance\n" .
                "🏫 Departments and subjects"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FACULTY CHATBOT
        |--------------------------------------------------------------------------
        */

        if ($role === 'faculty') {

            if ($this->contains($question, [
                'my subjects',
                'my subject',
                'subjects i teach',
                'what subjects do i teach'
            ])) {
                return $this->reply(
                    $this->facultySubjects($user)
                );
            }

            if ($this->contains($question, [
                'today attendance',
                'attendance today',
                'today lecture'
            ])) {
                return $this->reply(
                    $this->facultyTodayAttendance($user)
                );
            }

            if ($this->contains($question, [
                'total students',
                'how many students',
                'number of students'
            ])) {
                return $this->reply(
                    $this->facultyStudentCount($user)
                );
            }

            if ($this->contains($question, [
                'generate qr',
                'create qr',
                'qr code'
            ])) {
                return $this->reply(
                    "📱 To generate attendance QR:\n\n" .
                    "1. Open QR Generator.\n" .
                    "2. Select subject.\n" .
                    "3. Start the lecture.\n" .
                    "4. Generate QR.\n\n" .
                    "The QR will be valid only for the active attendance session."
                );
            }

            if ($this->contains($question, [
                'attendance report',
                'report',
                'attendance'
            ])) {
                return $this->reply(
                    $this->facultyAttendanceSummary($user)
                );
            }

            return $this->reply(
                "I can help you with:\n\n" .
                "📚 My subjects\n" .
                "📊 Attendance\n" .
                "👨‍🎓 Total students\n" .
                "📱 Generate QR\n" .
                "📈 Attendance reports"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | HOD CHATBOT
        |--------------------------------------------------------------------------
        */

        if ($role === 'hod') {

            if ($this->contains($question, [
                'total students',
                'how many students',
                'number of students'
            ])) {
                return $this->reply(
                    $this->hodStudentCount($user)
                );
            }

            if ($this->contains($question, [
                'total faculty',
                'how many faculty',
                'number of faculty',
                'teachers'
            ])) {
                return $this->reply(
                    $this->hodFacultyCount($user)
                );
            }

            if ($this->contains($question, [
                'department attendance',
                'attendance percentage',
                'overall attendance',
                'department attendance percentage'
            ])) {
                return $this->reply(
                    $this->hodAttendance($user)
                );
            }

            if ($this->contains($question, [
                'subjects',
                'total subjects',
                'list subjects'
            ])) {
                return $this->reply(
                    $this->hodSubjects($user)
                );
            }

            if ($this->contains($question, [
                'faculty list',
                'list faculty',
                'my faculty'
            ])) {
                return $this->reply(
                    $this->hodFacultyList($user)
                );
            }

            return $this->reply(
                "I can help you with:\n\n" .
                "👨‍🎓 Department students\n" .
                "👨‍🏫 Department faculty\n" .
                "📚 Department subjects\n" .
                "📊 Department attendance\n" .
                "📈 Attendance reports"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN / UNKNOWN ROLE
        |--------------------------------------------------------------------------
        */

        if ($this->contains($question, [
            'how many departments',
            'total departments',
            'list departments'
        ])) {
            return $this->reply(
                $this->departmentInformation($question)
            );
        }

        if ($this->contains($question, [
            'how many students',
            'total students'
        ])) {
            return $this->reply(
                'There are currently ' . Student::count() . ' students.'
            );
        }

        if ($this->contains($question, [
            'how many faculty',
            'total faculty'
        ])) {
            return $this->reply(
                'There are currently ' . Faculty::count() . ' faculty members.'
            );
        }

        return $this->reply(
            "Sorry, I couldn't understand that. Please ask about attendance, QR, faculty, students, subjects or departments."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT FUNCTIONS
    |--------------------------------------------------------------------------
    */

    private function student($user): ?Student
    {
        if (!$user->student_id) {
            return null;
        }

        return Student::find($user->student_id);
    }

    private function studentAttendance($user, ?Subject $subject): string
    {
        $student = $this->student($user);

        if (!$student) {
            return "Your account is not linked to a student record.";
        }

        $sessions = DB::table('attendance_sessions')
            ->where('department_id', $student->department_id)
            ->where('semester', $student->semester);

        if ($subject) {
            $sessions->where('subject_id', $subject->id);
        }

        $sessionIds = (clone $sessions)->pluck('id');

        $total = $sessionIds->count();

        if ($total === 0) {
            return $subject
                ? "No attendance sessions are available for {$subject->subject_name} yet."
                : "No attendance sessions are available for you yet.";
        }

        $present = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->whereIn('attendance_session_id', $sessionIds)
            ->count();

        $percentage = ($present / $total) * 100;

        $name = $subject
            ? $subject->subject_name
            : 'all subjects';

        return "📊 Your {$name} attendance is " .
            number_format($percentage, 1) .
            "% ({$present} of {$total} lectures present).";
    }

    private function todayStudentAttendance($user): string
    {
        $student = $this->student($user);

        if (!$student) {
            return "Your account is not linked to a student record.";
        }

        $records = Attendance::query()
            ->join(
                'attendance_sessions',
                'attendances.attendance_session_id',
                '=',
                'attendance_sessions.id'
            )
            ->leftJoin(
                'subjects',
                'attendance_sessions.subject_id',
                '=',
                'subjects.id'
            )
            ->where('attendances.student_id', $student->id)
            ->whereDate(
                'attendance_sessions.session_date',
                Carbon::today()
            )
            ->orderBy('attendance_sessions.starts_at')
            ->get([
                'attendances.status',
                'subjects.subject_name'
            ]);

        if ($records->isEmpty()) {
            return "📅 No attendance records were found for you today.";
        }

        return "📅 Today's attendance:\n\n" .
            $records->map(function ($record) {
                return ($record->subject_name ?: 'Class') .
                    ': ' .
                    ucfirst($record->status);
            })->implode("\n");
    }


    /*
    |--------------------------------------------------------------------------
    | FACULTY FUNCTIONS
    |--------------------------------------------------------------------------
    */

    private function faculty($user): ?Faculty
    {
        if (!$user->faculty_id) {
            return null;
        }

        return Faculty::find($user->faculty_id);
    }

    private function facultySubjects($user): string
    {
        $faculty = $this->faculty($user);

        if (!$faculty) {
            return "Your account is not linked to a faculty record.";
        }

        $subjects = Subject::query()
            ->where('department_id', $faculty->department_id)
            ->orderBy('subject_name')
            ->get();

        if ($subjects->isEmpty()) {
            return "No subjects are available for your department.";
        }

        return "📚 Your department subjects:\n\n" .
            $subjects->map(function ($subject) {
                return $subject->subject_name .
                    ($subject->subject_code
                        ? " ({$subject->subject_code})"
                        : '');
            })->implode("\n");
    }

    private function facultyTodayAttendance($user): string
    {
        $faculty = $this->faculty($user);

        if (!$faculty) {
            return "Your account is not linked to a faculty record.";
        }

        $sessions = DB::table('attendance_sessions')
            ->where('faculty_id', $faculty->id)
            ->whereDate('session_date', Carbon::today())
            ->get();

        if ($sessions->isEmpty()) {
            return "📅 You have no attendance sessions today.";
        }

        $total = 0;

        foreach ($sessions as $session) {
            $total += Attendance::where(
                'attendance_session_id',
                $session->id
            )
                ->where('status', 'present')
                ->count();
        }

        return "📊 Today your lectures have recorded {$total} present attendance records.";
    }

    private function facultyStudentCount($user): string
    {
        $faculty = $this->faculty($user);

        if (!$faculty) {
            return "Your account is not linked to a faculty record.";
        }

        $count = Student::where(
            'department_id',
            $faculty->department_id
        )->count();

        return "👨‍🎓 There are currently {$count} students in your department.";
    }

    private function facultyAttendanceSummary($user): string
    {
        $faculty = $this->faculty($user);

        if (!$faculty) {
            return "Your account is not linked to a faculty record.";
        }

        $sessionIds = DB::table('attendance_sessions')
            ->where('faculty_id', $faculty->id)
            ->pluck('id');

        $total = Attendance::whereIn(
            'attendance_session_id',
            $sessionIds
        )->count();

        $present = Attendance::whereIn(
            'attendance_session_id',
            $sessionIds
        )
            ->where('status', 'present')
            ->count();

        if ($total === 0) {
            return "No attendance records are available yet.";
        }

        $percentage = ($present / $total) * 100;

        return "📈 Your attendance record summary is " .
            number_format($percentage, 1) .
            "% present ({$present} present out of {$total} records).";
    }


    /*
    |--------------------------------------------------------------------------
    | HOD FUNCTIONS
    |--------------------------------------------------------------------------
    */

    private function hodStudentCount($user): string
    {
        if (!$user->department_id) {
            return "Your account is not linked to a department.";
        }

        $count = Student::where(
            'department_id',
            $user->department_id
        )->count();

        return "👨‍🎓 Your department has {$count} students.";
    }

    private function hodFacultyCount($user): string
    {
        if (!$user->department_id) {
            return "Your account is not linked to a department.";
        }

        $count = Faculty::where(
            'department_id',
            $user->department_id
        )->count();

        return "👨‍🏫 Your department has {$count} faculty members.";
    }

    private function hodSubjects($user): string
    {
        if (!$user->department_id) {
            return "Your account is not linked to a department.";
        }

        $subjects = Subject::where(
            'department_id',
            $user->department_id
        )
            ->orderBy('subject_name')
            ->get();

        if ($subjects->isEmpty()) {
            return "No subjects are available in your department.";
        }

        return "📚 Department subjects:\n\n" .
            $subjects->map(function ($subject) {
                return $subject->subject_name .
                    ($subject->subject_code
                        ? " ({$subject->subject_code})"
                        : '');
            })->implode("\n");
    }

    private function hodFacultyList($user): string
    {
        if (!$user->department_id) {
            return "Your account is not linked to a department.";
        }

        $faculty = Faculty::where(
            'department_id',
            $user->department_id
        )
            ->orderBy('faculty_name')
            ->get();

        if ($faculty->isEmpty()) {
            return "No faculty members are available.";
        }

        return "👨‍🏫 Department faculty:\n\n" .
            $faculty->map(function ($person) {
                return $person->faculty_name .
                    " - " .
                    $person->email;
            })->implode("\n");
    }

    private function hodAttendance($user): string
    {
        if (!$user->department_id) {
            return "Your account is not linked to a department.";
        }

        $sessionIds = DB::table('attendance_sessions')
            ->where('department_id', $user->department_id)
            ->pluck('id');

        $total = Attendance::whereIn(
            'attendance_session_id',
            $sessionIds
        )->count();

        $present = Attendance::whereIn(
            'attendance_session_id',
            $sessionIds
        )
            ->where('status', 'present')
            ->count();

        if ($total === 0) {
            return "No attendance records are available for your department.";
        }

        $percentage = ($present / $total) * 100;

        return "📊 Department attendance is " .
            number_format($percentage, 1) .
            "% ({$present} present out of {$total} attendance records).";
    }


    /*
    |--------------------------------------------------------------------------
    | GENERAL FUNCTIONS
    |--------------------------------------------------------------------------
    */

    private function findSubject(string $question): ?Subject
    {
        return Subject::query()
            ->where(function ($query) use ($question) {
                $query
                    ->whereRaw(
                        'LOWER(subject_name) LIKE ?',
                        ['%' . strtolower($question) . '%']
                    )
                    ->orWhereRaw(
                        'LOWER(subject_code) LIKE ?',
                        ['%' . strtolower($question) . '%']
                    );
            })
            ->first();
    }

    private function facultyForSubject(?Subject $subject): string
    {
        if (!$subject) {
            return "Please include the subject name or code, for example: Who teaches DBMS?";
        }

        $facultyIds = DB::table('attendance_sessions')
            ->where('subject_id', $subject->id)
            ->whereNotNull('faculty_id')
            ->pluck('faculty_id')
            ->unique();

        if ($facultyIds->isEmpty()) {
            return "No faculty member is assigned to {$subject->subject_name} yet.";
        }

        $faculty = Faculty::whereIn('id', $facultyIds)
            ->get();

        return "👨‍🏫 Faculty for {$subject->subject_name}:\n\n" .
            $faculty->map(function ($person) {
                return $person->faculty_name;
            })->unique()->implode("\n");
    }

    private function departmentInformation(string $question): string
    {
        $items = Department::query()
            ->orderBy('department_name')
            ->pluck('department_name');

        if ($items->isEmpty()) {
            return "There are no departments available yet.";
        }

        if ($this->contains($question, ['list', 'which'])) {
            return "🏫 Available departments ({$items->count()}):\n\n" .
                $items->implode("\n");
        }

        return "🏫 There are currently {$items->count()} departments.";
    }

    private function subjectInformation($user, string $question): string
    {
        $query = Subject::query();

        if (($user->role ?? '') === 'student') {
            $student = $this->student($user);

            if ($student) {
                $query
                    ->where('department_id', $student->department_id)
                    ->where('semester', $student->semester);
            }
        }

        if (($user->role ?? '') === 'hod' && $user->department_id) {
            $query->where(
                'department_id',
                $user->department_id
            );
        }

        $items = $query
            ->orderBy('subject_name')
            ->get(['subject_name', 'subject_code']);

        if ($items->isEmpty()) {
            return "No subjects are available.";
        }

        return "📚 Available subjects ({$items->count()}):\n\n" .
            $items->map(function ($subject) {
                return $subject->subject_name .
                    ($subject->subject_code
                        ? " ({$subject->subject_code})"
                        : '');
            })->implode("\n");
    }

    private function normalise(string $value): string
    {
        $value = strtolower(trim($value));

        return preg_replace('/\s+/', ' ', $value);
    }

    private function contains(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, strtolower($needle))) {
                return true;
            }
        }

        return false;
    }

    private function reply(string $message): JsonResponse
    {
        return response()->json([
            'reply' => $message
        ]);
    }
}