<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Display the logged-in student's dashboard.
     */
    public function index()
    {
        // Get currently authenticated user
        $user = Auth::user();

        // Get the student connected to this user
        $student = $user->student;

        // Safety check
        if (!$student) {
            abort(403, 'No student profile is connected to this account.');
        }

        // Get student's department
        $department = $student->department;

        return view('student.dashboard', compact(
            'user',
            'student',
            'department'
        ));
    }
    public function profile()
{
    $user = Auth::user();

    $student = $user->student;

    if (!$student) {
        abort(403, 'No student profile is connected to this account.');
    }

    $department = $student->department;

    return view('student.profile', compact(
        'user',
        'student',
        'department'
    ));
}
}