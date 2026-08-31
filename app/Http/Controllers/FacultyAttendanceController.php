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
         * Get students:
         * 1) Match by semester (e.g. 5th sem) or student_classes mapping
         * 2) Fallback to department or all active students so faculty is never blocked
         */
        $studentsQuery = Student::query();

        $hasClasses = DB::table('student_classes')->where('subject_id', $subject->id)->exists();

        if ($hasClasses) {
            $studentsQuery->where(function ($q) use ($subject) {
                $q->whereHas('studentClasses', function ($sq) use ($subject) {
                    $sq->where('subject_id', $subject->id);
                });
                if ($subject->semester) {
                    $q->orWhere('semester', $subject->semester);
                }
            });
        } elseif ($subject->semester) {
            $studentsQuery->where('semester', $subject->semester);
            if ($subject->department_id) {
                $studentsQuery->where(function ($q) use ($subject) {
                    $q->where('department_id', $subject->department_id)->orWhereNull('department_id');
                });
            }
        } elseif ($subject->department_id || $faculty->department_id) {
            $depId = $subject->department_id ?: $faculty->department_id;
            $studentsQuery->where('department_id', $depId);
        }

        $students = $studentsQuery
            ->orderBy('semester')
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

        // Fallback: If no students found by exact match, fetch department students or all students
        if ($students->isEmpty()) {
            $students = Student::query()
                ->when($faculty->department_id, function ($q, $dId) {
                    $q->where('department_id', $dId);
                })
                ->orderBy('semester')
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
        }

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
         * Validate that every submitted student exists in the database.
         */
        $studentIds = collect($validated['attendance'])
            ->pluck('student_id')
            ->unique()
            ->values();

        $validCount = Student::whereIn('id', $studentIds)->count();

        if ($validCount !== $studentIds->count()) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'One or more invalid students selected.'
                );
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

                'status' => 'closed',
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


    /**
     * Return sessions on a given date (any faculty in same department)
     * so faculty can copy attendance from a previous lecture.
     */
    public function sessionsOnDate(Request $request)
    {
        $faculty = Auth::user()?->faculty;

        if (!$faculty) {
            return response()->json(['message' => 'Faculty not found.'], 403);
        }

        $request->validate([
            'date' => ['required', 'date'],
        ]);

        // Get all sessions on that date for the same department or faculty
        $sessionsQuery = AttendanceSession::whereDate('lecture_date', $request->date);

        if ($faculty->department_id) {
            $sessionsQuery->where(function ($q) use ($faculty) {
                $q->whereHas('subject', function ($sq) use ($faculty) {
                    $sq->where('department_id', $faculty->department_id)->orWhereNull('department_id');
                })->orWhere('faculty_id', $faculty->id);
            });
        }

        $sessionsList = $sessionsQuery
            ->with([
                'subject:id,name,code,department_id,semester',
                'faculty:id,faculty_name',
                'attendances' => function ($q) {
                    $q->with('student:id,first_name,last_name,enrollment_no');
                },
            ])
            ->orderBy('start_time')
            ->latest('id')
            ->get();

        // Fallback: If department query returned empty, get all sessions on that date
        if ($sessionsList->isEmpty()) {
            $sessionsList = AttendanceSession::whereDate('lecture_date', $request->date)
                ->with([
                    'subject:id,name,code,department_id,semester',
                    'faculty:id,faculty_name',
                    'attendances' => function ($q) {
                        $q->with('student:id,first_name,last_name,enrollment_no');
                    },
                ])
                ->orderBy('start_time')
                ->latest('id')
                ->get();
        }

        $sessions = $sessionsList
            ->map(function ($session) {
                $presentCount = $session->attendances->where('status', 'present')->count();
                $absentCount = $session->attendances->where('status', 'absent')->count();
                $lateCount = $session->attendances->where('status', 'late')->count();
                $totalCount = $session->attendances->count();

                return [
                    'id'            => $session->id,
                    'subject'       => $session->subject?->name . ($session->subject?->code ? ' (' . $session->subject->code . ')' : ''),
                    'subject_id'    => $session->subject_id,
                    'semester'      => $session->subject?->semester,
                    'faculty'       => $session->faculty?->faculty_name ?? 'Faculty',
                    'lecture_name'  => $session->lecture_name ?: 'Regular Lecture',
                    'start_time'    => $session->start_time ? date('h:i A', strtotime($session->start_time)) : null,
                    'end_time'      => $session->end_time ? date('h:i A', strtotime($session->end_time)) : null,
                    'raw_start'     => $session->start_time,
                    'raw_end'       => $session->end_time,
                    'status'        => $session->status,
                    'present_count' => $presentCount,
                    'absent_count'  => $absentCount,
                    'late_count'    => $lateCount,
                    'total_count'   => $totalCount,
                    'students'      => $session->attendances->map(function ($att) {
                        return [
                            'student_id'    => $att->student_id,
                            'name'          => trim(($att->student?->first_name ?? '') . ' ' . ($att->student?->last_name ?? '')),
                            'enrollment_no' => $att->student?->enrollment_no,
                            'status'        => $att->status,
                            'remarks'       => $att->remarks,
                        ];
                    })->values(),
                ];
            });

        return response()->json(['sessions' => $sessions]);
    }
}
