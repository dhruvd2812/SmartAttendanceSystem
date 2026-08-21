<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MY ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Check Student Profile
        |--------------------------------------------------------------------------
        */

        $student = Student::find($user->student_id);

        if (!$student) {
            abort(
                403,
                'No student profile is connected to this account.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Attendance Records
        |--------------------------------------------------------------------------
        */

        $attendances = Attendance::where(
            'student_id',
            $student->id
        )
        ->latest()
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Attendance Statistics
        |--------------------------------------------------------------------------
        */

        $totalClasses = $attendances->count();

        $presentClasses = $attendances
            ->where('status', 'present')
            ->count();

        $absentClasses = $attendances
            ->where('status', 'absent')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Attendance Percentage
        |--------------------------------------------------------------------------
        */

        $attendancePercentage = $totalClasses > 0
            ? round(($presentClasses / $totalClasses) * 100, 2)
            : 0;


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
                'totalClasses',
                'presentClasses',
                'absentClasses',
                'attendancePercentage'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE HISTORY
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Check Student Profile
        |--------------------------------------------------------------------------
        */

        $student = Student::find($user->student_id);

        if (!$student) {
            abort(
                403,
                'No student profile is connected to this account.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Complete Attendance History
        |--------------------------------------------------------------------------
        */

        $attendances = Attendance::where(
            'student_id',
            $student->id
        )
        ->latest()
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Attendance History View
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


    /*
    |--------------------------------------------------------------------------
    | STUDENT TIMETABLE
    |--------------------------------------------------------------------------
    */

    public function timetable()
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Check Student Profile
        |--------------------------------------------------------------------------
        */

        $student = Student::find($user->student_id);

        if (!$student) {
            abort(
                403,
                'No student profile is connected to this account.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Return Timetable View
        |--------------------------------------------------------------------------
        */

        return view(
            'student.timetable',
            compact(
                'student'
            )
        );
    }
}