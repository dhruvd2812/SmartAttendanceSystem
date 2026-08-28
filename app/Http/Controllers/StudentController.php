<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StudentController extends Controller
{
    /**
     * Display students.
     *
     * Admin   -> All students
     * Faculty -> Students of their department
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Student::with('department')
            ->when(
                $request->filled('semester'),
                fn ($students) => $students->where('semester', $request->integer('semester'))
            )
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $students = $query->get();
        }

        /*
        |--------------------------------------------------------------------------
        | FACULTY
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'faculty') {

            /*
            | Faculty can see only students
            | belonging to their department.
            */

            $students = $query->get();
        }

        /*
        |--------------------------------------------------------------------------
        | OTHER USERS
        |--------------------------------------------------------------------------
        */

        else {

            abort(403, 'Unauthorized access.');
        }


        $semesterCounts = Student::selectRaw('semester, count(*) as total')
            ->whereBetween('semester', [1, 8])
            ->groupBy('semester')
            ->pluck('total', 'semester');

        return view(
            'students.index',
            compact('students', 'semesterCounts')
        );
    }


    /**
     * Show create student form.
     *
     * IMPORTANT:
     * Admin and Faculty can create students.
     * Student cannot access this page.
     */
    public function create()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'faculty'])) {

            abort(403, 'Students cannot create student records.');
        }


        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $departments = Department::orderBy('department_name')->get();
        }

        elseif ($user->role === 'faculty') {

            if (!$user->faculty || !$user->faculty->department_id) {

                abort(
                    403,
                    'Faculty department is not assigned.'
                );
            }

            $departments = Department::where(
                'id',
                $user->faculty->department_id
            )->get();
        }


        return view(
            'students.create',
            compact('departments')
        );
    }


    /**
     * Store student.
     *
     * Admin   -> Can create student in any department.
     * Faculty -> Can create student only in own department.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Role Check
        |--------------------------------------------------------------------------
        */

        if (!in_array($user->role, ['admin', 'faculty'])) {

            abort(
                403,
                'Students are not allowed to create student records.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'enrollment_no' => [
                'required',
                'string',
                'max:255',
                'unique:students,enrollment_no',
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email',
                'unique:users,email',
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:20',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:20',
            ],

            'dob' => [
                'nullable',
                'date',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'semester' => ['required', 'integer', 'between:1,8'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Faculty Department Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'faculty') {

            if (!$user->faculty) {

                abort(
                    403,
                    'Faculty profile not found.'
                );
            }

            if (
                $validated['department_id']
                != $user->faculty->department_id
            ) {

                abort(
                    403,
                    'You can only create students in your department.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create Student + User
        |--------------------------------------------------------------------------
        */

        $photoName = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            File::ensureDirectoryExists(public_path('uploads/students'));
            $photo->move(public_path('uploads/students'), $photoName);
        }

        DB::transaction(function () use ($validated, $photoName) {

            /*
            |--------------------------------------------------------------------------
            | Create Student
            |--------------------------------------------------------------------------
            */

            $student = Student::create([

                'enrollment_no' => $validated['enrollment_no'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
                'dob' => $validated['dob'] ?? null,
                'mobile' => $validated['mobile'] ?? null,
                'address' => $validated['address'] ?? null,
                'department_id' => $validated['department_id'],
                'semester' => $validated['semester'],
                'academic_year' => $validated['academic_year'] ?? null,
                'photo' => $photoName,
                'status' => $validated['status'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Student Login Account
            |--------------------------------------------------------------------------
            */

            User::create([

                'name' => $student->full_name,

                'email' => $student->email,

                /*
                | Default password.
                | You can change this later.
                */

                'password' => Hash::make($validated['password']),

                'student_id' =>
                    $student->id,

                'faculty_id' =>
                    null,

                'role' =>
                    'student',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Redirect According To Role
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()
                ->route('admin.students.index')
                ->with(
                    'success',
                    'Student created successfully.'
                );
        }


        return redirect()
            ->route('faculty.students.index')
            ->with(
                'success',
                'Student created successfully.'
            );
    }


    /**
     * Display student details.
     *
     * Admin   -> Any student
     * Faculty -> Own department student
     */
    public function show(Student $student)
    {
        /* Faculty may view the institute-wide student register, but changes
         * remain restricted to students in the faculty's own department. */
        if (Auth::user()->role !== 'faculty') {
            $this->authorizeStudentAccess($student);
        }

        $student->load('department');

        return view(
            'students.show',
            compact('student')
        );
    }


    /**
     * Show edit form.
     *
     * Admin   -> Any student
     * Faculty -> Own department student
     */
    public function edit(Student $student)
    {
        $user = Auth::user();

        $this->authorizeStudentAccess($student);


        /*
        |--------------------------------------------------------------------------
        | Department List
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $departments =
                Department::orderBy('department_name')->get();
        }

        elseif ($user->role === 'faculty') {

            if (!$user->faculty) {

                abort(
                    403,
                    'Faculty profile not found.'
                );
            }

            $departments =
                Department::where(
                    'id',
                    $user->faculty->department_id
                )->get();
        }

        else {

            abort(
                403,
                'Unauthorized access.'
            );
        }


        return view(
            'students.edit',
            compact(
                'student',
                'departments'
            )
        );
    }


    /**
     * Update student.
     *
     * Admin   -> Full update
     * Faculty -> Own department only
     */
    public function update(
        Request $request,
        Student $student
    ) {

        $user = Auth::user();

        $this->authorizeStudentAccess($student);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'enrollment_no' => [
                'required',
                'string',
                'max:255',
                'unique:students,enrollment_no,' . $student->id,
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email,' . $student->id,
                'unique:users,email,' .
                    optional($student->user)->id,
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:20',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:20',
            ],

            'dob' => [
                'nullable',
                'date',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'semester' => ['required', 'integer', 'between:1,8'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Faculty Department Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'faculty') {

            if (
                $validated['department_id']
                != $user->faculty->department_id
            ) {

                abort(
                    403,
                    'Faculty cannot move students to another department.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update Student + User
        |--------------------------------------------------------------------------
        */

        $photoName = $student->photo;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            File::ensureDirectoryExists(public_path('uploads/students'));
            $photo->move(public_path('uploads/students'), $photoName);
        }

        DB::transaction(function () use (
            $validated,
            $student,
            $photoName
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update Student
            |--------------------------------------------------------------------------
            */

            $student->update([

                'enrollment_no' => $validated['enrollment_no'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
                'dob' => $validated['dob'] ?? null,
                'mobile' => $validated['mobile'] ?? null,
                'address' => $validated['address'] ?? null,
                'department_id' => $validated['department_id'],
                'semester' => $validated['semester'],
                'academic_year' => $validated['academic_year'] ?? null,
                'photo' => $photoName,
                'status' => $validated['status'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Login User
            |--------------------------------------------------------------------------
            */

            if ($student->user) {

                $student->user->update([

                    'name' => $student->full_name,

                    'email' =>
                        $student->email,
                ]);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()
                ->route('admin.students.index')
                ->with(
                    'success',
                    'Student updated successfully.'
                );
        }


        return redirect()
            ->route('faculty.students.index')
            ->with(
                'success',
                'Student updated successfully.'
            );
    }


    /**
     * Delete student.
     *
     * Admin   -> Can delete
     * Faculty -> Can delete own department student
     */
    public function destroy(Student $student)
    {
        $user = Auth::user();

        $this->authorizeStudentAccess($student);


        DB::transaction(function () use ($student) {

            /*
            |--------------------------------------------------------------------------
            | Delete User Account
            |--------------------------------------------------------------------------
            */

            if ($student->user) {

                $student->user->delete();
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Student
            |--------------------------------------------------------------------------
            */

            $student->delete();
        });


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()
                ->route('admin.students.index')
                ->with(
                    'success',
                    'Student deleted successfully.'
                );
        }


        return redirect()
            ->route('faculty.students.index')
            ->with(
                'success',
                'Student deleted successfully.'
            );
    }


    /**
     * Check whether current user can access student.
     */
    private function authorizeStudentAccess(
        Student $student
    ) {

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | FACULTY
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'faculty') {

            if (!$user->faculty) {

                abort(
                    403,
                    'Faculty profile not found.'
                );
            }


            if (
                $student->department_id
                != $user->faculty->department_id
            ) {

                abort(
                    403,
                    'You are not allowed to access this student.'
                );
            }


            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | STUDENT / OTHER
        |--------------------------------------------------------------------------
        */

        abort(
            403,
            'Students cannot manage other student records.'
        );
    }
}
