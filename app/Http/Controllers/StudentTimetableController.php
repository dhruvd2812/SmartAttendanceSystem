<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;

class StudentTimetableController extends Controller
{
    /**
     * Display the logged-in student's timetable.
     */
    public function index()
    {
        // Get currently logged-in user
        $user = Auth::user();

        // Check whether the user has a connected student profile
        if (!$user || !$user->student_id) {
            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'No student profile is connected to this account.'
                );
        }

        // Get student profile
        $student = Student::with('department')
            ->find($user->student_id);

        // Check whether student profile exists
        if (!$student) {
            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'Student profile not found.'
                );
        }

        // Get timetable for student's department and semester
        $timetables = Timetable::with([
            'subject',
            'faculty',
            'department'
        ])
        ->where('department_id', $student->department_id)
        ->where('semester', $student->semester)
        ->orderByRaw("
            CASE day
                WHEN 'Monday' THEN 1
                WHEN 'Tuesday' THEN 2
                WHEN 'Wednesday' THEN 3
                WHEN 'Thursday' THEN 4
                WHEN 'Friday' THEN 5
                WHEN 'Saturday' THEN 6
                WHEN 'Sunday' THEN 7
                ELSE 8
            END
        ")
        ->orderBy('start_time')
        ->get();

        // Return timetable page
        return view(
            'student.timetable',
            compact(
                'student',
                'timetables'
            )
        );
    }
}