<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class FacultyDashboardController extends Controller
{
    /**
     * Display the Faculty Dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Get Logged-in Faculty
        |--------------------------------------------------------------------------
        */

        $faculty = $user->faculty;

        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $subjects = collect();

        $subjectCount = 0;
        $studentCount = 0;
        $todayClasses = 0;
        $attendanceSessionCount = 0;

        /*
        |--------------------------------------------------------------------------
        | Faculty Dashboard Data
        |--------------------------------------------------------------------------
        */

        if ($faculty) {

            /*
            |--------------------------------------------------------------------------
            | Assigned Subjects
            |--------------------------------------------------------------------------
            */

            $subjects = Subject::where('faculty_id', $faculty->id)
                ->withCount('studentClasses')
                ->orderBy('semester')
                ->orderBy('name')
                ->get();

            $subjectCount = $subjects->count();


            /*
            |--------------------------------------------------------------------------
            | Total Students
            |--------------------------------------------------------------------------
            |
            | Count unique students enrolled in this faculty's subjects.
            |
            */

            $studentCount = Student::whereHas(
                'studentClasses',
                function ($query) use ($faculty) {

                    $query->whereHas(
                        'subject',
                        function ($subjectQuery) use ($faculty) {

                            $subjectQuery->where(
                                'faculty_id',
                                $faculty->id
                            );

                        }
                    );

                }
            )->count();


            /*
            |--------------------------------------------------------------------------
            | Today's Classes
            |--------------------------------------------------------------------------
            */

            $todayClasses = AttendanceSession::where(
                'faculty_id',
                $faculty->id
            )
                ->whereDate(
                    'lecture_date',
                    now()->toDateString()
                )
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Total Attendance Sessions
            |--------------------------------------------------------------------------
            */

            $attendanceSessionCount = AttendanceSession::where(
                'faculty_id',
                $faculty->id
            )->count();
        }


        /*
        |--------------------------------------------------------------------------
        | Return Faculty Dashboard
        |--------------------------------------------------------------------------
        */

        return view('faculty.dashboard', compact(
            'user',
            'faculty',
            'subjects',
            'subjectCount',
            'studentCount',
            'todayClasses',
            'attendanceSessionCount'
        ));
    }
}