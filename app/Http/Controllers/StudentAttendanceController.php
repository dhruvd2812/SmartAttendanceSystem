<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends Controller
{
    /**
     * Display student attendance page.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged In User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }


        /*
        |--------------------------------------------------------------------------
        | Check Student Connection
        |--------------------------------------------------------------------------
        */

        if (!$user->student_id) {
            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'No student profile is connected to this account.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Student
        |--------------------------------------------------------------------------
        */

        $student = Student::find($user->student_id);

        if (!$student) {
            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'Student profile not found.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Attendance Query
        |--------------------------------------------------------------------------
        |
        | Eager load attendance session, subject and faculty.
        |
        */

        $attendanceQuery = Attendance::with([
            'attendanceSession.subject',
            'attendanceSession.faculty',
        ])
        ->where(
            'student_id',
            $student->id
        );


        /*
        |--------------------------------------------------------------------------
        | Date Filter - From Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $attendanceQuery->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filter - To Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('to_date')) {

            $attendanceQuery->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Subject Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('subject_id')) {

            $subjectId = $request->subject_id;

            $attendanceQuery->whereHas(
                'attendanceSession',
                function ($query) use ($subjectId) {

                    $query->where(
                        'subject_id',
                        $subjectId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Attendance Records
        |--------------------------------------------------------------------------
        */

        $attendances = $attendanceQuery
            ->orderBy(
                'created_at',
                'desc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Attendance Counts
        |--------------------------------------------------------------------------
        */

        $totalCount = $attendances->count();


        $presentCount = $attendances
            ->filter(function ($attendance) {

                return strtolower(
                    trim((string) $attendance->status)
                ) === 'present';

            })
            ->count();


        $absentCount = $attendances
            ->filter(function ($attendance) {

                return strtolower(
                    trim((string) $attendance->status)
                ) === 'absent';

            })
            ->count();


        $lateCount = $attendances
            ->filter(function ($attendance) {

                return strtolower(
                    trim((string) $attendance->status)
                ) === 'late';

            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Compatibility Variables
        |--------------------------------------------------------------------------
        */

        $totalClasses = $totalCount;

        $presentClasses = $presentCount;

        $absentClasses = $absentCount;


        /*
        |--------------------------------------------------------------------------
        | Attendance Percentage
        |--------------------------------------------------------------------------
        */

        $attendancePercentage = $totalCount > 0
            ? round(
                ($presentCount / $totalCount) * 100,
                2
            )
            : 0;


        $percentage = $attendancePercentage;


        /*
        |--------------------------------------------------------------------------
        | Overall Attendance Status
        |--------------------------------------------------------------------------
        |
        | Used by the badge at the top of attendance.blade.php.
        |
        */

        if ($attendancePercentage >= 75) {

            $attendanceStatus = 'Good';

        } elseif ($attendancePercentage >= 60) {

            $attendanceStatus = 'Warning';

        } else {

            $attendanceStatus = 'Poor';
        }


        /*
        |--------------------------------------------------------------------------
        | Today's Attendance
        |--------------------------------------------------------------------------
        */

        $todayAttendance = Attendance::with([
            'attendanceSession.subject',
            'attendanceSession.faculty',
        ])
        ->where(
            'student_id',
            $student->id
        )
        ->whereDate(
            'created_at',
            today()
        )
        ->latest()
        ->first();


        /*
        |--------------------------------------------------------------------------
        | Today's Attendance Status
        |--------------------------------------------------------------------------
        |
        | This variable is available if you need it later.
        |
        */

        $todayAttendanceStatus = $todayAttendance
            ? ucfirst(
                strtolower(
                    trim((string) $todayAttendance->status)
                )
            )
            : 'Not Marked';


        /*
        |--------------------------------------------------------------------------
        | Get Student Subjects
        |--------------------------------------------------------------------------
        */

        $subjectsQuery = Subject::query();


        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($student->department_id)) {

            $subjectsQuery->where(
                'department_id',
                $student->department_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Semester Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($student->semester)) {

            $subjectsQuery->where(
                'semester',
                $student->semester
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Subjects
        |--------------------------------------------------------------------------
        */

        $subjects = $subjectsQuery
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Subject-wise Attendance
        |--------------------------------------------------------------------------
        */

        $subjectAttendance = collect();


        /*
        |--------------------------------------------------------------------------
        | Group Attendance By Subject
        |--------------------------------------------------------------------------
        */

        $groupedAttendance = $attendances->groupBy(function ($attendance) {

            return optional(
                $attendance->attendanceSession
            )->subject_id;

        });


        /*
        |--------------------------------------------------------------------------
        | Build Subject Attendance Data
        |--------------------------------------------------------------------------
        */

        foreach ($groupedAttendance as $subjectId => $records) {

            /*
            |------------------------------------------------------------------
            | Skip if subject is not available
            |------------------------------------------------------------------
            */

            if (!$subjectId) {
                continue;
            }


            /*
            |------------------------------------------------------------------
            | Get Subject
            |------------------------------------------------------------------
            */

            $subject = $records
                ->first()
                ->attendanceSession
                ->subject;


            if (!$subject) {
                continue;
            }


            /*
            |------------------------------------------------------------------
            | Subject Counts
            |------------------------------------------------------------------
            */

            $total = $records->count();


            $present = $records
                ->filter(function ($attendance) {

                    return strtolower(
                        trim((string) $attendance->status)
                    ) === 'present';

                })
                ->count();


            $absent = $records
                ->filter(function ($attendance) {

                    return strtolower(
                        trim((string) $attendance->status)
                    ) === 'absent';

                })
                ->count();


            $late = $records
                ->filter(function ($attendance) {

                    return strtolower(
                        trim((string) $attendance->status)
                    ) === 'late';

                })
                ->count();


            /*
            |------------------------------------------------------------------
            | Subject Percentage
            |------------------------------------------------------------------
            */

            $subjectPercentage = $total > 0
                ? round(
                    ($present / $total) * 100,
                    2
                )
                : 0;


            /*
            |------------------------------------------------------------------
            | Subject Status
            |------------------------------------------------------------------
            */

            if ($subjectPercentage >= 75) {

                $subjectStatus = 'Good';

            } elseif ($subjectPercentage >= 60) {

                $subjectStatus = 'Warning';

            } else {

                $subjectStatus = 'Poor';
            }


            /*
            |------------------------------------------------------------------
            | Add Subject Data
            |------------------------------------------------------------------
            */

            $subjectAttendance->push([

                'subject_id' => $subject->id,

                'subject_name' => $subject->name,

                'subject_code' => $subject->code ?? '-',

                'total' => $total,

                'present' => $present,

                'absent' => $absent,

                'late' => $late,

                'percentage' => $subjectPercentage,

                'status' => $subjectStatus,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Add Subjects With No Attendance
        |--------------------------------------------------------------------------
        |
        | This makes all student's subjects visible, even when no attendance
        | has been marked yet.
        |
        */

        foreach ($subjects as $subject) {

            $alreadyExists = $subjectAttendance
                ->contains(
                    'subject_id',
                    $subject->id
                );


            if (!$alreadyExists) {

                $subjectAttendance->push([

                    'subject_id' => $subject->id,

                    'subject_name' => $subject->name,

                    'subject_code' => $subject->code ?? '-',

                    'total' => 0,

                    'present' => 0,

                    'absent' => 0,

                    'late' => 0,

                    'percentage' => 0,

                    'status' => 'Poor',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Sort Subject Attendance
        |--------------------------------------------------------------------------
        */

        $subjectAttendance = $subjectAttendance
            ->sortBy('subject_name')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'student.attendance',
            compact(

                'student',

                'attendances',

                'subjects',

                'subjectAttendance',

                // Attendance statistics
                'totalCount',

                'presentCount',

                'absentCount',

                'lateCount',

                'attendancePercentage',

                'percentage',

                // Attendance status
                'attendanceStatus',

                'todayAttendance',

                'todayAttendanceStatus',

                // Compatibility
                'totalClasses',

                'presentClasses',

                'absentClasses'
            )
        );
    }


    /**
     * Display attendance history.
     */
    public function history()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged In User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Please login first.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Student Connection
        |--------------------------------------------------------------------------
        */

        if (!$user->student_id) {

            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'No student profile is connected to this account.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Student
        |--------------------------------------------------------------------------
        */

        $student = Student::find(
            $user->student_id
        );


        if (!$student) {

            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'Student profile not found.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Attendance History
        |--------------------------------------------------------------------------
        */

        $attendances = Attendance::with([
            'attendanceSession.subject',
            'attendanceSession.faculty',
        ])
        ->where(
            'student_id',
            $student->id
        )
        ->orderBy(
            'created_at',
            'desc'
        )
        ->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | Return History View
        |--------------------------------------------------------------------------
        */

        return view(
            'student.attendance-history',
            compact(
                'student',
                'attendances'
            )
        );
    }
}