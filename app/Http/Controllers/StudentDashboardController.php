<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Display the logged-in student's dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $student = $this->studentForUser($user);

        if (!$student) {
            abort(403, 'No student profile is connected to this account.');
        }

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
        $student = $this->studentForUser($user);

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

    private function studentForUser($user): ?Student
    {
        if ($user->student) {
            return $user->student;
        }

        $student = Student::where('email', $user->email)->first();

        if (!$student) {
            $student = Student::whereRaw(
                "LOWER(CONCAT(first_name, ' ', last_name)) = ?",
                [strtolower(trim($user->name))]
            )->first();
        }

        if ($student) {
            $user->forceFill(['student_id' => $student->id])->save();
        }

        return $student;
    }

    /**
     * Admin can preview a student dashboard.
     */
    public function adminView()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Only admin can access this preview.');
        }

        $student = Student::with('department')->first();

        if (!$student) {
            abort(404, 'No student found for dashboard preview.');
        }

        return view('student.dashboard', compact(
            'user',
            'student',
        ));
    }

    /**
     * Faculty can preview a student dashboard from their department.
     */
    public function facultyView()
    {
        $user = Auth::user();

        if ($user->role !== 'faculty') {
            abort(403, 'Only faculty can access this preview.');
        }

        if (!$user->faculty || !$user->faculty->department_id) {
            abort(403, 'Faculty department is not assigned.');
        }

        $student = Student::with('department')
            ->where('department_id', $user->faculty->department_id)
            ->first();

        if (!$student) {
            abort(404, 'No student found in your department.');
        }

        return view('student.dashboard', compact(
            'user',
            'student',
        ));
    }
}