<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentSubjectController extends Controller
{
    /**
     * Display student's subjects.
     */
    public function index()
    {
        $user = Auth::user();

        // Check whether the logged-in account
        // is connected to a student record.
        if (!$user->student_id) {
            abort(403, 'No student profile is connected to this account.');
        }

        // Find the logged-in student's record.
        $student = Student::with([
            'studentClasses.subject'
        ])->findOrFail($user->student_id);

        // Get the student's class/subject records.
        $studentClasses = $student->studentClasses;

        return view('student.subjects', compact(
            'student',
            'studentClasses'
        ));
    }
}