<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends Controller
{
    /**
     * Display the student's attendance dashboard.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get logged-in student
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        $student = Student::where('id', $user->student_id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Safety check
        |--------------------------------------------------------------------------
        */

        if (!$student) {
            abort(404, 'Student profile not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance query
        |--------------------------------------------------------------------------
        */

        $attendanceQuery = Attendance::query()
            ->where('student_id', $student->id)
            ->with([
                'attendanceSession.subject',
                'attendanceSession.faculty',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Date filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {
            $attendanceQuery->whereHas(
                'attendanceSession',
                function ($query) use ($request) {
                    $query->whereDate(
                        'lecture_date',
                        '>=',
                        $request->from_date
                    );
                }
            );
        }

        if ($request->filled('to_date')) {
            $attendanceQuery->whereHas(
                'attendanceSession',
                function ($query) use ($request) {
                    $query->whereDate(
                        'lecture_date',
                        '<=',
                        $request->to_date
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Subject filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('subject_id')) {
            $attendanceQuery->whereHas(
                'attendanceSession',
                function ($query) use ($request) {
                    $query->where(
                        'subject_id',
                        $request->subject_id
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get attendance records
        |--------------------------------------------------------------------------
        */

        $attendances = $attendanceQuery
            ->orderByDesc('marked_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Attendance counts
        |--------------------------------------------------------------------------
        */

        $totalClasses = $attendances->count();

        $presentCount = $attendances
            ->where('status', 'present')
            ->count();

        $absentCount = $attendances
            ->where('status', 'absent')
            ->count();

        $lateCount = $attendances
            ->where('status', 'late')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Attendance percentage
        |--------------------------------------------------------------------------
        */

        $attendancePercentage = $totalClasses > 0
            ? round(($presentCount / $totalClasses) * 100, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Attendance status
        |--------------------------------------------------------------------------
        */

        if ($attendancePercentage >= 75) {
            $attendanceStatus = 'Good';
        } elseif ($attendancePercentage >= 60) {
            $attendanceStatus = 'Warning';
        } else {
            $attendanceStatus = 'Critical';
        }

        /*
        |--------------------------------------------------------------------------
        | Subject-wise attendance
        |--------------------------------------------------------------------------
        */

        $subjectAttendance = $attendances
            ->groupBy(function ($attendance) {
                return $attendance->attendanceSession?->subject?->id;
            })
            ->map(function ($records) {

                $firstRecord = $records->first();

                $subject = $firstRecord
                    ->attendanceSession
                    ?->subject;

                $total = $records->count();

                $present = $records
                    ->where('status', 'present')
                    ->count();

                $percentage = $total > 0
                    ? round(($present / $total) * 100, 2)
                    : 0;

                if ($percentage >= 75) {
                    $status = 'Good';
                } elseif ($percentage >= 60) {
                    $status = 'Warning';
                } else {
                    $status = 'Critical';
                }

                return [
                    'subject_id' => $subject?->id,
                    'subject_name' => $subject?->name ?? 'Unknown Subject',
                    'subject_code' => $subject?->code ?? '-',
                    'total' => $total,
                    'present' => $present,
                    'absent' => $records
                        ->where('status', 'absent')
                        ->count(),
                    'late' => $records
                        ->where('status', 'late')
                        ->count(),
                    'percentage' => $percentage,
                    'status' => $status,
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Subjects for filter dropdown
        |--------------------------------------------------------------------------
        */

        $subjects = $student->department
            ? $student->department
                ->subjects()
                ->where('semester', $student->semester)
                ->orderBy('name')
                ->get()
            : collect();

        /*
        |--------------------------------------------------------------------------
        | Return attendance page
        |--------------------------------------------------------------------------
        */

        return view(
            'student.attendance',
            compact(
                'student',
                'attendances',
                'subjects',
                'totalClasses',
                'presentCount',
                'absentCount',
                'lateCount',
                'attendancePercentage',
                'attendanceStatus',
                'subjectAttendance'
            )
        );
    }
}