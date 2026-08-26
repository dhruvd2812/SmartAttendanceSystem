<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FacultyDashboardController extends Controller
{
    /**
     * Display the logged-in Faculty Dashboard.
     *
     * Allowed role:
     * faculty
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

            $subjects = Subject::where(
                'faculty_id',
                $faculty->id
            )
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
        | Faculty Dashboard View
        |--------------------------------------------------------------------------
        */

        return view(
            'faculty.dashboard',
            compact(
                'user',
                'faculty',
                'subjects',
                'subjectCount',
                'studentCount',
                'todayClasses',
                'attendanceSessionCount'
            )
        );
    }

    /** Show the faculty's account settings. */
    public function editProfile()
    {
        $user = Auth::user();

        return view('faculty.profile', [
            'user' => $user,
            'faculty' => $user->faculty,
            'departments' => Department::orderBy('department_name')->get(),
        ]);
    }

    /** Allow a faculty member to complete their own profile and change password. */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'faculty_name' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'string', 'max:50', 'unique:faculties,employee_id,' . Auth::user()->faculty_id],
            'phone' => ['required', 'string', 'max:20'],
            'department_id' => ['required', 'exists:departments,id'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $faculty = $user->faculty;

        if (!$faculty) {
            return redirect()->route('faculty.dashboard')
                ->with('error', 'Your faculty profile could not be found.');
        }

        DB::transaction(function () use ($validated, $user, $faculty) {
            $faculty->update([
                'faculty_name' => $validated['faculty_name'],
                'employee_id' => $validated['employee_id'],
                'phone' => $validated['phone'],
                'department_id' => $validated['department_id'],
            ]);

            $user->name = $validated['faculty_name'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();
        });

        return redirect()->route('faculty.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN → FACULTY DASHBOARD VIEW
    |--------------------------------------------------------------------------
    |
    | Admin can view Faculty Dashboard information.
    |
    | IMPORTANT:
    | This is a separate method because Auth::user() is ADMIN here,
    | not Faculty.
    |
    */


    public function adminView()
    {
        /*
        |--------------------------------------------------------------------------
        | Get All Faculties
        |--------------------------------------------------------------------------
        */

        $faculties = Faculty::with('department')
            ->orderBy('faculty_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Admin Faculty View
        |--------------------------------------------------------------------------
        */

        return view(
            'faculty.admin-view',
            compact('faculties')
        );
    }
}
