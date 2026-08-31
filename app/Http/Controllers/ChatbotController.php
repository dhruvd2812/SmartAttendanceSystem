<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Notice;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\Timetable;
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

    /**
     * Main Conversational Dispatcher
     */
    public function message(Request $request): JsonResponse
    {
        $rawMessage = $request->validate([
            'message' => ['required', 'string', 'max:1000']
        ])['message'];

        $question = $this->normalise($rawMessage);
        $user = $request->user();
        $role = strtolower($user->role ?? '');

        // 1. Common Greetings & Bot Identity
        if ($this->isGreeting($question)) {
            $name = $user->display_name ?? 'there';
            return $this->reply(
                "👋 Hello {$name}! I'm your **Smart Attendance AI Assistant**.\n\n" .
                "I can assist you with:\n" .
                "• 📊 **Live Attendance & 75% Calculator**\n" .
                "• 📚 **Subject-wise Breakdown & Faculty Info**\n" .
                "• 🗓️ **Today's Class Timetable & Schedule**\n" .
                "• 📢 **Latest College Notices & Updates**\n" .
                "• 📷 **QR Scanner Guide & Troubleshooting**\n\n" .
                "How can I help you today?"
            );
        }

        if ($this->contains($question, ['who are you', 'what are you', 'your name', 'who made you', 'who created you'])) {
            return $this->reply(
                "🤖 I am the **Smart Attendance AI Copilot**, designed specifically for students, faculty, and administrators to make campus attendance seamless, transparent, and intelligent!"
            );
        }

        if ($this->contains($question, ['thank', 'thanks', 'awesome', 'great', 'good job', 'perfect', 'cool'])) {
            return $this->reply(
                "You're very welcome! 😊 Feel free to ask if you have any more questions about your attendance, subjects, or schedule."
            );
        }

        // 2. QR Scanner Help & Troubleshooting (Global for all roles)
        if ($this->contains($question, ['how to scan', 'qr scan', 'scan qr', 'scanner not working', 'camera issue', 'how does qr work', 'location issue', 'qr expired', 'camera permission'])) {
            return $this->reply($this->qrTroubleshootingGuide($question));
        }

        // 3. Notices & Announcements
        if ($this->contains($question, ['notice', 'notices', 'announcement', 'announcements', 'circular', 'news', 'update', 'updates'])) {
            return $this->reply($this->getNotices());
        }

        // 4. Role-Specific Dispatch
        if ($role === 'student') {
            return $this->handleStudentQuery($user, $question, $rawMessage);
        }

        if ($role === 'faculty') {
            return $this->handleFacultyQuery($user, $question, $rawMessage);
        }

        if ($role === 'hod') {
            return $this->handleHodQuery($user, $question, $rawMessage);
        }

        // Default Admin or General Queries
        return $this->handleAdminQuery($user, $question, $rawMessage);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT QUERY HANDLER
    |--------------------------------------------------------------------------
    */
    private function handleStudentQuery($user, string $question, string $rawMessage): JsonResponse
    {
        $student = $this->student($user);
        if (!$student) {
            return $this->reply("⚠️ Your user profile is not linked to a student enrollment record. Please contact your administrator or update your profile.");
        }

        // A. 75% Criteria / Bunk / Defaulter / Eligibility Calculator
        if ($this->contains($question, [
            '75%', '75 percent', 'bunk', 'miss class', 'skip class', 'safe to miss',
            'defaulter', 'shortage', 'eligibility', 'exam eligibility', 'criteria', 'how many classes to attend', 'how many lectures to attend'
        ])) {
            return $this->reply($this->calculateStudentEligibility($student));
        }

        // B. Today's Attendance Status
        if ($this->contains($question, ['today', 'present today', 'marked today', 'did i attend today', 'today attendance', 'attendance today', 'was i present'])) {
            return $this->reply($this->todayStudentAttendance($student));
        }

        // C. Specific Subject Attendance or Faculty
        $subject = $this->findSubject($question, $student->department_id, $student->semester);
        if ($subject) {
            if ($this->contains($question, ['who teach', 'who is faculty', 'teacher', 'professor', 'faculty for', 'taught by', 'sir', 'madam', 'mam'])) {
                return $this->reply($this->facultyForSubject($subject));
            }
            return $this->reply($this->studentSubjectAttendance($student, $subject));
        }

        // D. General Teacher / Faculty Inquiry
        if ($this->contains($question, ['who teach', 'teacher', 'faculty', 'professor', 'list teachers'])) {
            return $this->reply($this->listStudentFaculty($student));
        }

        // E. Timetable & Schedule
        if ($this->contains($question, ['timetable', 'time table', 'schedule', 'routine', 'lecture today', 'classes today', 'class today', 'next class', 'room'])) {
            return $this->reply($this->studentTimetable($student, $question));
        }

        // F. Subjects List
        if ($this->contains($question, ['my subjects', 'subjects', 'subject list', 'which subjects', 'what subjects', 'courses', 'enrolled'])) {
            return $this->reply($this->studentSubjectsList($student));
        }

        // G. Overall Attendance Summary
        if ($this->contains($question, ['attendance', 'percentage', 'present', 'stats', 'report', 'summary', 'record', 'score', 'status', 'check my attendance', 'my attendance'])) {
            return $this->reply($this->studentComprehensiveAttendance($student));
        }

        // H. Student Profile & Enrollment Details
        if ($this->contains($question, ['profile', 'enrollment', 'my details', 'semester', 'department', 'branch', 'roll no', 'who am i'])) {
            return $this->reply($this->studentProfileDetails($student));
        }

        // I. Smart Fallback with Intelligent Suggestions
        return $this->reply($this->smartStudentFallback($student, $question));
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY QUERY HANDLER
    |--------------------------------------------------------------------------
    */
    private function handleFacultyQuery($user, string $question, string $rawMessage): JsonResponse
    {
        $faculty = $this->faculty($user);
        if (!$faculty) {
            return $this->reply("⚠️ Your user profile is not linked to a faculty record. Please contact the administrator.");
        }

        // A. Today's Lectures & Sessions
        if ($this->contains($question, ['today', 'today lecture', 'today class', 'classes today', 'today attendance', 'marked today'])) {
            return $this->reply($this->facultyTodayAttendance($faculty));
        }

        // B. Assigned Subjects
        if ($this->contains($question, ['my subject', 'my subjects', 'subjects i teach', 'what do i teach', 'assigned subject', 'course'])) {
            return $this->reply($this->facultySubjects($faculty));
        }

        // C. QR Generator Instruction
        if ($this->contains($question, ['generate qr', 'create qr', 'start session', 'how to make qr', 'qr generator', 'projector'])) {
            return $this->reply(
                "📱 **Faculty QR Generation Guide**:\n\n" .
                "1. Navigate to **QR Generator** from your topbar or dashboard.\n" .
                "2. Choose the **Subject**, lecture topic name, date, and class time duration.\n" .
                "3. Click **Generate Live QR Code**.\n" .
                "4. *(Optional)* Click **Projector View** to display the QR in full-screen on the classroom smartboard.\n" .
                "5. The dynamic QR code has an active **2-minute countdown window** and changes per session to prevent proxy attendance."
            );
        }

        // D. Students Count & Department
        if ($this->contains($question, ['student', 'students', 'how many students', 'enrolled', 'roster', 'class size'])) {
            return $this->reply($this->facultyStudentCount($faculty));
        }

        // E. Overall Attendance Summary
        if ($this->contains($question, ['attendance', 'report', 'summary', 'percentage', 'stats', 'analytics', 'history'])) {
            return $this->reply($this->facultyAttendanceSummary($faculty));
        }

        // F. Timetable
        if ($this->contains($question, ['timetable', 'schedule', 'routine', 'slot', 'room'])) {
            return $this->reply($this->facultyTimetable($faculty));
        }

        return $this->reply(
            "👨‍🏫 **Faculty AI Assistant** — You can ask me:\n\n" .
            "• 📚 *\"What subjects am I teaching?\"*\n" .
            "• 🗓️ *\"What classes do I have today?\"*\n" .
            "• 📊 *\"Show attendance summary for my lectures\"*\n" .
            "• 👨‍🎓 *\"How many students are in my department?\"*\n" .
            "• 📱 *\"How do I generate a classroom QR code?\"*\n" .
            "• 📢 *\"Latest notices & announcements\"*"
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HOD QUERY HANDLER
    |--------------------------------------------------------------------------
    */
    private function handleHodQuery($user, string $question, string $rawMessage): JsonResponse
    {
        $deptId = $user->department_id;
        $department = $deptId ? Department::find($deptId) : null;

        if (!$department) {
            return $this->reply("⚠️ Your HOD account is not assigned to a department.");
        }

        if ($this->contains($question, ['student', 'students', 'total student'])) {
            $count = Student::where('department_id', $deptId)->count();
            return $this->reply("👨‍🎓 Department **{$department->department_name}** currently has **{$count} registered students**.");
        }

        if ($this->contains($question, ['faculty', 'teachers', 'professors', 'staff'])) {
            $faculties = Faculty::where('department_id', $deptId)->get();
            $list = $faculties->map(fn($f) => "• **{$f->faculty_name}** ({$f->email})")->implode("\n");
            return $this->reply("👨‍🏫 **Faculty in {$department->department_name} ({$faculties->count()})**:\n\n" . ($list ?: 'No faculty assigned yet.'));
        }

        if ($this->contains($question, ['subject', 'subjects', 'courses'])) {
            $subjects = Subject::where('department_id', $deptId)->get();
            $list = $subjects->map(fn($s) => "• **{$s->name}** (" . ($s->code ?: 'N/A') . ") — Sem {$s->semester}")->implode("\n");
            return $this->reply("📚 **Subjects in {$department->department_name} ({$subjects->count()})**:\n\n" . ($list ?: 'No subjects created yet.'));
        }

        if ($this->contains($question, ['attendance', 'percentage', 'rate', 'stats'])) {
            $sessionIds = AttendanceSession::whereHas('subject', fn($q) => $q->where('department_id', $deptId))->pluck('id');
            $total = Attendance::whereIn('attendance_session_id', $sessionIds)->count();
            $present = Attendance::whereIn('attendance_session_id', $sessionIds)->where('status', 'present')->count();
            $pct = $total > 0 ? number_format(($present / $total) * 100, 1) : 0;
            return $this->reply("📊 **{$department->department_name} Attendance Overview**:\n\n• Average Rate: **{$pct}%**\n• Present Entries: **{$present}**\n• Total Recorded: **{$total}**");
        }

        return $this->reply("🏢 **HOD Assistant for {$department->department_name}**:\n\nAsk about department students, faculty members, subjects, or overall attendance rates.");
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN / GENERAL QUERY HANDLER
    |--------------------------------------------------------------------------
    */
    private function handleAdminQuery($user, string $question, string $rawMessage): JsonResponse
    {
        // 1. Departments Information
        if ($this->contains($question, ['department', 'departments', 'branch', 'branches'])) {
            $depts = Department::all();
            if ($this->contains($question, ['list', 'which', 'all', 'show'])) {
                $list = $depts->map(fn($d) => "• **{$d->department_name}** " . ($d->department_code ? "({$d->department_code})" : ""))->implode("\n");
                return $this->reply("🏢 **University Departments ({$depts->count()})**:\n\n" . ($list ?: 'No departments found.'));
            }
            return $this->reply("🏢 The university has **{$depts->count()} academic departments** registered in the system.");
        }

        // 2. Student Count
        if ($this->contains($question, ['how many student', 'total student', 'student count', 'students count'])) {
            $total = Student::count();
            $active = Student::where('status', 1)->orWhereNull('status')->count();
            return $this->reply("👨‍🎓 Total Students: **{$total}** ({$active} active enrollments).");
        }

        // 3. Faculty Count
        if ($this->contains($question, ['how many faculty', 'total faculty', 'faculty count', 'teachers count', 'professors'])) {
            $count = Faculty::count();
            return $this->reply("👨‍🏫 Total Faculty Members: **{$count}** across all departments.");
        }

        // 4. Overall Attendance Stats
        if ($this->contains($question, ['attendance', 'stats', 'rate', 'percentage', 'system attendance'])) {
            $total = Attendance::count();
            $present = Attendance::where('status', 'present')->count();
            $pct = $total > 0 ? number_format(($present / $total) * 100, 1) : 0;
            return $this->reply(
                "📊 **Institution-Wide Attendance Summary**:\n\n" .
                "• Overall Average: **{$pct}%**\n" .
                "• Total Present Marks: **{$present}**\n" .
                "• Total Attendance Logs: **{$total}**\n" .
                "• Active Sessions: **" . AttendanceSession::count() . "**"
            );
        }

        // 5. QR System Info
        if ($this->contains($question, ['qr', 'generator', 'scanner'])) {
            return $this->reply($this->qrTroubleshootingGuide($question));
        }

        return $this->reply(
            "🎓 **Smart Attendance Admin Assistant**:\n\n" .
            "You can query live institutional analytics:\n" .
            "• 👨‍🎓 *\"Total student count\"*\n" .
            "• 👨‍🏫 *\"Faculty and staff overview\"*\n" .
            "• 🏢 *\"List all departments\"*\n" .
            "• 📊 *\"Overall university attendance rate\"*\n" .
            "• 📢 *\"Latest campus notices\"*\n" .
            "• 📱 *\"How QR session security works\"*"
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SMART NLP & STUDENT ANALYTICS ENGINES
    |--------------------------------------------------------------------------
    */

    /**
     * 75% Eligibility & Defaulter Calculator with Actionable Advice
     */
    private function calculateStudentEligibility(Student $student): string
    {
        $sessionIds = AttendanceSession::where('department_id', $student->department_id)
            ->where('semester', $student->semester)
            ->pluck('id');

        $totalSessions = $sessionIds->count();
        if ($totalSessions === 0) {
            return "ℹ️ No attendance sessions have been conducted for your semester yet. Your attendance will begin calculating once lectures commence.";
        }

        $presentCount = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->whereIn('attendance_session_id', $sessionIds)
            ->count();

        $absentCount = $totalSessions - $presentCount;
        $currentPct = ($presentCount / $totalSessions) * 100;
        $formattedPct = number_format($currentPct, 1);

        $response = "🎯 **75% Attendance & Exam Eligibility Calculator**\n";
        $response .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $response .= "• **Current Status**: **{$formattedPct}%** ({$presentCount} Present / {$totalSessions} Total Lectures)\n";
        $response .= "• **Absences**: {$absentCount} missed lectures\n\n";

        if ($currentPct >= 75.0) {
            // How many lectures can they safely miss?
            $safeBunks = (int) floor(($presentCount / 0.75) - $totalSessions);
            $response .= "🟢 **SAFE & ELIGIBLE**: Your attendance is well above the required 75% threshold!\n\n";
            if ($safeBunks > 0) {
                $response .= "💡 **Buffer Advice**: You can safely miss up to **{$safeBunks} more lecture" . ($safeBunks > 1 ? 's' : '') . "** without falling below 75%. Keep up the great attendance!";
            } else {
                $response .= "💡 **Buffer Advice**: You are right on the edge of 75%. Make sure you attend upcoming classes to build a comfortable buffer.";
            }
        } else {
            // How many consecutive lectures must they attend?
            $neededLectures = max(1, (3 * $totalSessions) - (4 * $presentCount));
            $response .= "🔴 **ATTENDANCE SHORTAGE WARNING**: Your attendance is currently below the 75% university eligibility requirement.\n\n";
            $response .= "⚠️ **Recovery Plan**: You must attend the next **{$neededLectures} consecutive lecture" . ($neededLectures > 1 ? 's' : '') . "** without missing any to restore your attendance back to **75.0%**!";
        }

        return $response;
    }

    /**
     * Comprehensive Student Attendance with Subject-by-Subject Breakdown
     */
    private function studentComprehensiveAttendance(Student $student): string
    {
        $sessionIds = AttendanceSession::where('department_id', $student->department_id)
            ->where('semester', $student->semester)
            ->pluck('id');

        $total = $sessionIds->count();
        if ($total === 0) {
            return "📊 No attendance sessions have been logged for Semester {$student->semester} yet.";
        }

        $present = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->whereIn('attendance_session_id', $sessionIds)
            ->count();

        $overallPct = ($present / $total) * 100;
        $statusEmoji = $overallPct >= 75 ? '🟢 Safe' : ($overallPct >= 65 ? '🟡 Warning' : '🔴 Critical Shortage');

        $response = "📊 **Your Attendance Overview**\n";
        $response .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $response .= "• **Overall Percentage**: **" . number_format($overallPct, 1) . "%** ({$present} / {$total} Present)\n";
        $response .= "• **Eligibility Status**: {$statusEmoji}\n\n";
        $response .= "📚 **Subject-wise Breakdown**:\n";

        $subjects = Subject::where('department_id', $student->department_id)
            ->where('semester', $student->semester)
            ->get();

        if ($subjects->isEmpty()) {
            $response .= "• No individual subjects mapped for this semester.";
        } else {
            foreach ($subjects as $sub) {
                $subSessionIds = AttendanceSession::where('subject_id', $sub->id)->pluck('id');
                $subTotal = $subSessionIds->count();

                if ($subTotal === 0) {
                    $response .= "• **{$sub->name}**: *No sessions held yet*\n";
                } else {
                    $subPresent = Attendance::where('student_id', $student->id)
                        ->where('status', 'present')
                        ->whereIn('attendance_session_id', $subSessionIds)
                        ->count();

                    $subPct = ($subPresent / $subTotal) * 100;
                    $badge = $subPct >= 75 ? '🟢' : ($subPct >= 60 ? '🟡' : '🔴');
                    $response .= "• {$badge} **{$sub->name}** (" . ($sub->code ?: 'N/A') . "): **" . number_format($subPct, 1) . "%** ({$subPresent}/{$subTotal})\n";
                }
            }
        }

        $response .= "\n💡 *Tip: Ask \"Can I bunk?\" or \"How to reach 75%?\" for personalized recovery advice.*";
        return $response;
    }

    /**
     * Single Subject Attendance Detail
     */
    private function studentSubjectAttendance(Student $student, Subject $subject): string
    {
        $sessionIds = AttendanceSession::where('subject_id', $subject->id)->pluck('id');
        $total = $sessionIds->count();

        if ($total === 0) {
            return "📚 **{$subject->name}** (" . ($subject->code ?: 'Course') . "):\n\nNo lectures have been conducted for this subject yet.";
        }

        $present = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->whereIn('attendance_session_id', $sessionIds)
            ->count();

        $absent = $total - $present;
        $pct = ($present / $total) * 100;
        $status = $pct >= 75 ? "🟢 Above 75% Criteria" : "🔴 Shortage (Below 75%)";

        $faculty = $subject->faculty ? $subject->faculty->faculty_name : 'Assigned Faculty';

        return "📚 **{$subject->name}** (" . ($subject->code ?: 'N/A') . ")\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "• **Instructor**: {$faculty}\n" .
            "• **Attendance**: **" . number_format($pct, 1) . "%** ({$present} Present / {$total} Conducted)\n" .
            "• **Missed Classes**: {$absent}\n" .
            "• **Status**: {$status}";
    }

    /**
     * Today's Attendance Logs for Student
     */
    private function todayStudentAttendance(Student $student): string
    {
        $today = Carbon::today()->toDateString();

        $sessions = AttendanceSession::whereDate('lecture_date', $today)
            ->where('department_id', $student->department_id)
            ->where('semester', $student->semester)
            ->with('subject')
            ->get();

        if ($sessions->isEmpty()) {
            return "📅 **Today's Attendance** (" . Carbon::today()->format('l, d M Y') . "):\n\nNo class attendance sessions have been logged for your department today.";
        }

        $response = "📅 **Today's Class Attendance** (" . Carbon::today()->format('d M Y') . "):\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        foreach ($sessions as $session) {
            $att = Attendance::where('student_id', $student->id)
                ->where('attendance_session_id', $session->id)
                ->first();

            $subName = $session->subject ? $session->subject->name : ($session->lecture_name ?: 'Lecture');
            $time = ($session->start_time && $session->end_time) ? " ({$session->start_time} - {$session->end_time})" : "";

            if ($att && $att->status === 'present') {
                $response .= "• 🟢 **{$subName}**{$time}: **PRESENT** (Recorded at " . ($att->marked_at ? $att->marked_at->format('H:i') : 'verified') . ")\n";
            } else {
                $response .= "• 🔴 **{$subName}**{$time}: **ABSENT / NOT MARKED**\n";
            }
        }

        return $response;
    }

    /**
     * Student Timetable Schedule (Today or Full Week)
     */
    private function studentTimetable(Student $student, string $question): string
    {
        $todayName = date('l');
        $query = Timetable::where('department_id', $student->department_id)
            ->where('semester', $student->semester)
            ->with(['subject', 'faculty']);

        if ($this->contains($question, ['today', 'today class', 'next class', 'room'])) {
            $slots = (clone $query)->where('day', $todayName)->orderBy('start_time')->get();
            if ($slots->isEmpty()) {
                return "🗓️ **Today's Schedule** ({$todayName}):\n\nNo scheduled timetable lectures for today! Enjoy your free study hours. ✨";
            }

            $res = "🗓️ **Today's Lecture Schedule ({$todayName})**:\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            foreach ($slots as $slot) {
                $sub = $slot->subject ? $slot->subject->name : 'Subject';
                $fac = $slot->faculty ? $slot->faculty->faculty_name : 'Faculty';
                $room = $slot->room ? " [Room: {$slot->room}]" : "";
                $res .= "• ⏰ **{$slot->start_time} - {$slot->end_time}**: {$sub} ({$fac}){$room}\n";
            }
            return $res;
        }

        // Full Weekly Timetable
        $allSlots = $query->orderBy('day')->orderBy('start_time')->get();
        if ($allSlots->isEmpty()) {
            return "🗓️ Timetable entries have not been published for Semester {$student->semester} yet.";
        }

        $res = "🗓️ **Weekly Timetable (Semester {$student->semester})**:\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $grouped = $allSlots->groupBy('day');
        foreach ($grouped as $day => $slots) {
            $res .= "\n**{$day}**:\n";
            foreach ($slots as $slot) {
                $sub = $slot->subject ? $slot->subject->name : 'Subject';
                $fac = $slot->faculty ? $slot->faculty->faculty_name : 'Staff';
                $res .= "  • {$slot->start_time} - {$slot->end_time}: {$sub} ({$fac})\n";
            }
        }
        return $res;
    }

    /**
     * Faculty info for a subject
     */
    private function facultyForSubject(Subject $subject): string
    {
        $assigned = $subject->faculty;
        if ($assigned) {
            return "👨‍🏫 **{$subject->name}** (" . ($subject->code ?: 'N/A') . ")\n\n" .
                "• **Faculty**: **{$assigned->faculty_name}**\n" .
                "• **Email**: {$assigned->email}\n" .
                "• **Phone**: " . ($assigned->phone ?: 'Department Office');
        }

        return "👨‍🏫 **{$subject->name}** is currently coordinated by the **" . ($subject->department ? $subject->department->department_name : 'Department') . "** faculty team.";
    }

    /**
     * List all Faculty teaching the student
     */
    private function listStudentFaculty(Student $student): string
    {
        $faculties = Faculty::where('department_id', $student->department_id)->get();
        if ($faculties->isEmpty()) {
            return "👨‍🏫 No faculty profiles are assigned to your department yet.";
        }

        $res = "👨‍🏫 **Faculty Members in Your Department**:\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($faculties as $f) {
            $res .= "• **{$f->faculty_name}** — {$f->email}\n";
        }
        return $res;
    }

    /**
     * List enrolled subjects
     */
    private function studentSubjectsList(Student $student): string
    {
        $subjects = Subject::where('department_id', $student->department_id)
            ->where('semester', $student->semester)
            ->with('faculty')
            ->get();

        if ($subjects->isEmpty()) {
            return "📚 No subjects registered for Semester {$student->semester} yet.";
        }

        $res = "📚 **Your Enrolled Subjects (Semester {$student->semester})**:\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($subjects as $s) {
            $teacher = $s->faculty ? " (Prof. {$s->faculty->faculty_name})" : "";
            $code = $s->code ? " [{$s->code}]" : "";
            $res .= "• **{$s->name}**{$code}{$teacher}\n";
        }
        return $res;
    }

    /**
     * Student Profile Summary
     */
    private function studentProfileDetails(Student $student): string
    {
        $dept = $student->department ? $student->department->department_name : 'Academic Dept';
        return "👨‍🎓 **Your Student Profile**\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "• **Name**: {$student->full_name}\n" .
            "• **Enrollment No**: **{$student->enrollment_no}**\n" .
            "• **Department**: {$dept}\n" .
            "• **Current Semester**: Semester {$student->semester}\n" .
            "• **Email**: {$student->email}\n" .
            "• **Status**: Active Student ✅";
    }

    /**
     * Smart Student Fallback with Fuzzy Recommendation
     */
    private function smartStudentFallback(Student $student, string $question): string
    {
        return "🤖 I'm not sure I understood: *\"{$question}\"*\n\n" .
            "Here are quick questions you can ask me:\n" .
            "• 📊 **\"What is my attendance?\"**\n" .
            "• 🎯 **\"Can I bunk today?\"** *(75% eligibility check)*\n" .
            "• 📅 **\"Did I attend any classes today?\"**\n" .
            "• 🗓️ **\"What lectures do I have today?\"**\n" .
            "• 📚 **\"What subjects do I have?\"**\n" .
            "• 📷 **\"How to scan QR code?\"**\n" .
            "• 📢 **\"Show latest notices\"**";
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY HELPER METHODS
    |--------------------------------------------------------------------------
    */
    private function facultyTodayAttendance(Faculty $faculty): string
    {
        $today = Carbon::today()->toDateString();
        $sessions = AttendanceSession::where('faculty_id', $faculty->id)
            ->whereDate('lecture_date', $today)
            ->with('subject')
            ->get();

        if ($sessions->isEmpty()) {
            return "📅 You have no attendance sessions conducted today (" . Carbon::today()->format('d M Y') . "). Click **Launch QR Session** to start marking attendance in class.";
        }

        $res = "📅 **Today's Conducted Sessions** (" . Carbon::today()->format('d M Y') . "):\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($sessions as $s) {
            $count = Attendance::where('attendance_session_id', $s->id)->where('status', 'present')->count();
            $sub = $s->subject ? $s->subject->name : ($s->lecture_name ?: 'Lecture');
            $res .= "• 🟢 **{$sub}** ({$s->start_time} - {$s->end_time}): **{$count} Students Present**\n";
        }
        return $res;
    }

    private function facultySubjects(Faculty $faculty): string
    {
        $subjects = Subject::where('faculty_id', $faculty->id)->get();
        if ($subjects->isEmpty()) {
            $subjects = Subject::where('department_id', $faculty->department_id)->get();
        }

        if ($subjects->isEmpty()) {
            return "📚 No subjects are currently assigned to your instructor profile.";
        }

        $res = "📚 **Your Teaching Subjects**:\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($subjects as $s) {
            $code = $s->code ? " [{$s->code}]" : "";
            $res .= "• **{$s->name}**{$code} — Semester {$s->semester}\n";
        }
        return $res;
    }

    private function facultyStudentCount(Faculty $faculty): string
    {
        $count = Student::where('department_id', $faculty->department_id)->count();
        return "👨‍🎓 There are **{$count} enrolled students** in your department ready for attendance tracking.";
    }

    private function facultyAttendanceSummary(Faculty $faculty): string
    {
        $sessionIds = AttendanceSession::where('faculty_id', $faculty->id)->pluck('id');
        $totalSessions = $sessionIds->count();

        if ($totalSessions === 0) {
            return "📊 You have not conducted any attendance sessions yet. Use the **QR Generator** to launch your first session.";
        }

        $totalPresent = Attendance::whereIn('attendance_session_id', $sessionIds)->where('status', 'present')->count();
        $avgStudentsPerClass = number_format($totalPresent / $totalSessions, 1);

        return "📈 **Faculty Attendance Analytics Summary**\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "• **Total Lecture Sessions Conducted**: **{$totalSessions}**\n" .
            "• **Total Present Attendances Logged**: **{$totalPresent}**\n" .
            "• **Average Turnout per Lecture**: **~{$avgStudentsPerClass} students**";
    }

    private function facultyTimetable(Faculty $faculty): string
    {
        $today = date('l');
        $slots = Timetable::where('faculty_id', $faculty->id)
            ->where('day', $today)
            ->with('subject')
            ->orderBy('start_time')
            ->get();

        if ($slots->isEmpty()) {
            return "🗓️ You have no timetable lectures scheduled for today ({$today}).";
        }

        $res = "🗓️ **Your Timetable Schedule ({$today})**:\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($slots as $slot) {
            $sub = $slot->subject ? $slot->subject->name : 'Lecture';
            $room = $slot->room ? " [Room: {$slot->room}]" : "";
            $res .= "• ⏰ **{$slot->start_time} - {$slot->end_time}**: {$sub} (Sem {$slot->semester}){$room}\n";
        }
        return $res;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERAL NOTICE & QR TROUBLESHOOTING HELPERS
    |--------------------------------------------------------------------------
    */
    private function getNotices(): string
    {
        $notices = Notice::where('is_active', true)->latest()->take(4)->get();
        if ($notices->isEmpty()) {
            return "📢 **Campus Notice Board**:\n\nThere are no new active announcements or circulars at this moment.";
        }

        $res = "📢 **Latest College Notices & Announcements**:\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach ($notices as $n) {
            $date = $n->created_at ? $n->created_at->format('d M Y') : 'Recent';
            $res .= "📌 **{$n->title}** *({$date})*\n{$n->description}\n\n";
        }
        return trim($res);
    }

    private function qrTroubleshootingGuide(string $question): string
    {
        return "📷 **Smart Attendance QR Guide & Troubleshooting**:\n\n" .
            "1. **How to Scan**:\n" .
            "   • Open **Live Attendance Scanner** on your phone.\n" .
            "   • Align the classroom projector QR code inside the cyan HUD box.\n" .
            "   • Hear the confirmation chime & celebrate your recorded attendance!\n\n" .
            "2. **Troubleshooting Tips**:\n" .
            "   • ⏳ **Expired QR**: QR codes refresh every 2 minutes for security. Ask your professor if expired.\n" .
            "   • 📷 **Camera Permission**: Allow browser camera access in settings, or switch between cameras using the dropdown.\n" .
            "   • 🖼️ **File Upload**: If camera is unavailable, click *Scan from Image File* to upload a photo of the QR code.";
    }

    /*
    |--------------------------------------------------------------------------
    | ENTITY RESOLUTION HELPERS (FUZZY MATCHING)
    |--------------------------------------------------------------------------
    */
    private function findSubject(string $question, $departmentId = null, $semester = null): ?Subject
    {
        $allSubjects = Subject::query();
        if ($departmentId) {
            $allSubjects->where('department_id', $departmentId);
        }
        if ($semester) {
            $allSubjects->where('semester', $semester);
        }
        $subjects = $allSubjects->get();

        $lowerQ = strtolower($question);

        // 1. Check exact/partial name or code in question
        foreach ($subjects as $s) {
            $name = strtolower($s->name);
            $code = strtolower($s->code ?? '');

            if (str_contains($lowerQ, $name) || ($code && str_contains($lowerQ, $code))) {
                return $s;
            }

            // Acronym check (e.g. "Operating Systems" -> "os", "Database Management Systems" -> "dbms")
            $words = explode(' ', $name);
            $acronym = '';
            foreach ($words as $w) {
                if (!empty($w)) $acronym .= $w[0];
            }
            if (strlen($acronym) >= 2 && preg_match('/\b' . preg_quote($acronym, '/') . '\b/i', $lowerQ)) {
                return $s;
            }

            // Check individual distinct key words (e.g. "python", "database", "java", "network")
            foreach ($words as $w) {
                if (strlen($w) >= 4 && str_contains($lowerQ, $w)) {
                    return $s;
                }
            }
        }

        // Global search if not found in student's semester
        $global = Subject::all();
        foreach ($global as $s) {
            $name = strtolower($s->name);
            $code = strtolower($s->code ?? '');
            if (str_contains($lowerQ, $name) || ($code && str_contains($lowerQ, $code))) {
                return $s;
            }
        }

        return null;
    }

    private function student($user): ?Student
    {
        if ($user->student) {
            return $user->student;
        }

        if ($user->student_id) {
            $student = Student::find($user->student_id);
            if ($student) return $student;
        }

        $student = Student::where('email', $user->email)->first();

        if (!$student && !empty($user->name)) {
            $student = Student::whereRaw(
                "LOWER(CONCAT(first_name, ' ', last_name)) = ?",
                [strtolower(trim($user->name))]
            )->first();
        }

        if ($student && !$user->student_id) {
            $user->forceFill(['student_id' => $student->id])->save();
        }

        return $student;
    }

    private function faculty($user): ?Faculty
    {
        if ($user->faculty) {
            return $user->faculty;
        }

        if ($user->faculty_id) {
            $faculty = Faculty::find($user->faculty_id);
            if ($faculty) return $faculty;
        }

        $faculty = Faculty::where('email', $user->email)->first();

        if (!$faculty && !empty($user->name)) {
            $faculty = Faculty::whereRaw("LOWER(faculty_name) = ?", [strtolower(trim($user->name))])->first();
        }

        if ($faculty && !$user->faculty_id) {
            $user->forceFill(['faculty_id' => $faculty->id])->save();
        }

        return $faculty;
    }

    private function isGreeting(string $q): bool
    {
        return $this->contains($q, ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'start', 'help me', 'menu']);
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