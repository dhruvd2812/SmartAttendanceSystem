<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Student Count
        |--------------------------------------------------------------------------
        */

        $studentCount = Student::count();


        /*
        |--------------------------------------------------------------------------
        | Department Count
        |--------------------------------------------------------------------------
        */

        $departmentCount = Department::count();


        /*
        |--------------------------------------------------------------------------
        | Faculty Count
        |--------------------------------------------------------------------------
        */

        $facultyCount = Faculty::count();


        /*
        |--------------------------------------------------------------------------
        | Recent Students
        |--------------------------------------------------------------------------
        */

        $recentStudents = Student::with('department')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        $departments = Department::latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Attendance Percentage
        |--------------------------------------------------------------------------
        */

        $totalAttendance = Attendance::count();

        $presentAttendance = Attendance::where(
            'status',
            'present'
        )->count();


        $attendancePercentage = $totalAttendance > 0
            ? ($presentAttendance / $totalAttendance) * 100
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard',
            compact(
                'studentCount',
                'departmentCount',
                'facultyCount',
                'attendancePercentage',
                'recentStudents',
                'departments'
            )
        );
    }
}