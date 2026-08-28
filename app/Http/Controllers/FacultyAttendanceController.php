<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacultyAttendanceController extends Controller
{
    /**
     * Display faculty attendance sessions.
     */
    public function index(Request $request)
    {
        $faculty = Auth::user()?->faculty;

        if (!$faculty) {
            return redirect()
                ->route('faculty.dashboard')
                ->with('error', 'Your faculty profile is not linked to this account.');
        }

        $subjects = Subject::where('faculty_id', $faculty->id)
            ->orderBy('name')
            ->get();

        $sessions = AttendanceSession::where('faculty_id', $faculty->id)
            ->when(
                $request->filled('subject_id'),
                fn ($query) =>
                    $query->where('subject_id', $request->subject_id)
            )
            ->when(
                $request->filled('date'),
                fn ($query) =>
                    $query->whereDate('lecture_date', $request->date)
            )
            ->with([
                'subject',
                'attendances.student',
            ])
            ->withCount([
                'attendances as present_count' => function ($query) {
                    $query->where('status', 'present');
                },

                'attendances as late_count' => function ($query) {
                    $query->where('status', 'late');
                },

                'attendances as absent_count' => function ($query) {
                    $query->where('status', 'absent');
                },
            ])
            ->latest('lecture_date')
            ->latest('start_time')
            ->get();

        return view(
            'faculty.attendance.index',
            compact('subjects', 'sessions')
        );
    }

    /** Display the semester-wise attendance muster for this faculty. */
    public function muster(Request $request)
    {
        $faculty = Auth::user()?->faculty;

        if (!$faculty) {
            return redirect()->route('faculty.dashboard')
                ->with('error', 'Your faculty profile is not linked to this account.');
        }

        $sessionForFaculty = fn ($query) => $query->where('faculty_id', $faculty->id);

        $students = Student::with('department')
            ->when($request->filled('semester'), fn ($query) => $query->where('semester', $request->integer('semester')))
            ->withCount([
                'attendances as total_lectures' => fn ($query) => $query->whereHas('attendanceSession', $sessionForFaculty),
                'attendances as present_lectures' => fn ($query) => $query->whereIn('status', ['present', 'late'])->whereHas('attendanceSession', $sessionForFaculty),
                'attendances as absent_lectures' => fn ($query) => $query->where('status', 'absent')->whereHas('attendanceSession', $sessionForFaculty),
            ])
            ->orderBy('semester')->orderBy('first_name')->orderBy('last_name')
            ->get();

        $semesterCounts = Student::selectRaw('semester, count(*) as total')
            ->whereBetween('semester', [1, 8])
            ->groupBy('semester')
            ->pluck('total', 'semester');

        return view('faculty.attendance.muster', compact('students', 'semesterCounts'));
    }


    /**
     * Show manual attendance form.
     */
    public function manual()
    {
        $faculty = Auth::user()?->faculty;

        if (!$faculty) {
            return redirect()
                ->route('faculty.dashboard')
                ->with(
                    'error',
                    'Your faculty profile is not linked to this account.'
                );
        }

        /*
         * Only show subjects assigned to this faculty.
         */
        $subjects = Subject::where('faculty_id', $faculty->id)
            ->orderBy('name')
            ->get();

        return view(
            'faculty.attendance.manual',
            compact('subjects')
        );
    }


    /**
     * Return students assigned to a subject.
     */
    public function students(Request $request)
    {
        $faculty = Auth::user()?->faculty;

        if (!$faculty) {
            return response()->json([
                'message' => 'Faculty profile not found.'
            ], 403);
        }

        $request->validate([
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id'
            ],
        ]);

        /*
         * Make sure the selected subject belongs
         * to the logged-in faculty.
         */
        $subject = Subject::where('id', $request->subject_id)
            ->where('faculty_id', $faculty->id)
            ->first();

        if (!$subject) {
            return response()->json([
                'message' => 'This subject is not assigned to you.'
            ], 403);
        }

        /*
         * Get students through student_classes.
         */
        $students = Student::whereHas('studentClasses', function ($query) use ($subject) {
            $query->where('subject_id', $subject->id);
        })
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get([
            'id',
            'enrollment_no',
            'first_name',
            'last_name',
            'email',
            'semester',
            'status',
        ]);

        return response()->json([
            'students' => $students,
        ]);
    }


    /**
     * Save manual attendance.
     */
    public function storeManual(Request $request)
    {
        $faculty = Auth::user()?->faculty;

        if (!$faculty) {
            return redirect()
                ->route('faculty.dashboard')
                ->with(
                    'error',
                    'Your faculty profile is not linked to this account.'
                );
        }

        /*
         * Validate basic lecture information.
         */
        $validated = $request->validate([
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id'
            ],

            'lecture_date' => [
                'required',
                'date'
            ],

            'start_time' => [
                'nullable',
                'date_format:H:i'
            ],

            'end_time' => [
                'nullable',
                'date_format:H:i'
            ],

            'lecture_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'attendance' => [
                'required',
                'array',
                'min:1'
            ],

            'attendance.*.student_id' => [
                'required',
                'integer',
                'exists:students,id'
            ],

            'attendance.*.status' => [
                'required',
                'in:present,absent,late'
            ],

            'attendance.*.remarks' => [
                'nullable',
                'string',
                'max:500'
            ],
        ]);


        /*
         * Make sure subject belongs to this faculty.
         */
        $subject = Subject::where('id', $validated['subject_id'])
            ->where('faculty_id', $faculty->id)
            ->first();

        if (!$subject) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'You are not assigned to this subject.'
                );
        }


        /*
         * Validate that every submitted student
         * actually belongs to this subject.
         */
        $studentIds = collect($validated['attendance'])
            ->pluck('student_id')
            ->unique()
            ->values();

        $validStudentIds = Student::whereIn('id', $studentIds)
            ->whereHas('studentClasses', function ($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            })
            ->pluck('id');


        foreach ($studentIds as $studentId) {

            if (!$validStudentIds->contains($studentId)) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'One or more students are not assigned to this subject.'
                    );
            }
        }


        /*
         * Prevent duplicate manual attendance
         * for the same faculty + subject + date + time.
         */
        $existingSession = AttendanceSession::where(
            'faculty_id',
            $faculty->id
        )
        ->where(
            'subject_id',
            $subject->id
        )
        ->whereDate(
            'lecture_date',
            $validated['lecture_date']
        )
        ->when(
            !empty($validated['start_time']),
            function ($query) use ($validated) {
                $query->where('start_time', $validated['start_time']);
            }
        )
        ->first();


        if ($existingSession) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Attendance for this subject and lecture already exists.'
                );
        }


        /*
         * Save everything in one database transaction.
         */
        DB::transaction(function () use (
            $validated,
            $faculty,
            $subject
        ) {

            /*
             * Create attendance session.
             *
             * QR fields are NULL because this is
             * manual attendance.
             */
            $session = AttendanceSession::create([
                'subject_id' => $subject->id,
                'faculty_id' => $faculty->id,
                'lecture_date' => $validated['lecture_date'],
                'start_time' => $validated['start_time'] ?? null,
                'end_time' => $validated['end_time'] ?? null,
                'lecture_name' =>
                    $validated['lecture_name']
                    ?? 'Manual Attendance',

                'qr_token' => null,
                'qr_expires_at' => null,

                'status' => 'completed',
            ]);


            /*
             * Create attendance record
             * for every selected student.
             */
            foreach ($validated['attendance'] as $studentAttendance) {

                Attendance::create([
                    'student_id' =>
                        $studentAttendance['student_id'],

                    'attendance_session_id' =>
                        $session->id,

                    'status' =>
                        $studentAttendance['status'],

                    'marked_at' => now(),

                    'remarks' =>
                        $studentAttendance['remarks'] ?? null,
                ]);
            }
        });


        return redirect()
            ->route('faculty.attendance.index')
            ->with(
                'success',
                'Manual attendance saved successfully.'
            );
    }
}
