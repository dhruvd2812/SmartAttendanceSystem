<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Department;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    /**
     * Show Login page.
     */
    public function showLogin()
    {
        return view('auth.login');
    }


    /**
     * Login user.
     *
     * Student -> Student Dashboard
     * Faculty -> Faculty Dashboard
     * Admin   -> Admin Dashboard
     */
    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Login
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Attempt Login
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {

            return back()
                ->withErrors([
                    'email' => 'The provided email or password is incorrect.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Get Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'student') {

            return redirect()
                ->route('student.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | FACULTY
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'faculty') {

            return redirect()
                ->route('faculty.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()
                ->route('dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | INVALID ROLE
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Your account does not have a valid role.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT REGISTRATION
    |--------------------------------------------------------------------------
    */

    /**
     * Show Student Registration page.
     */
    public function showRegister()
    {
        return view('auth.register');
    }


    /**
     * Register a new Student.
     *
     * Registration Flow:
     *
     * Student Form
     *      ↓
     * Validate
     *      ↓
     * Create Student
     *      ↓
     * Create User
     *      ↓
     * student_id = student.id
     *      ↓
     * role = student
     *      ↓
     * Login
     *      ↓
     * Student Dashboard
     */
    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Student Registration
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'enrollment_no' => [
                'required',
                'string',
                'max:100',
                'unique:students,enrollment_no',
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'gender' => [
                'required',
                'in:Male,Female',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'semester' => [
                'required',
                'integer',
                'min:1',
                'max:8',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Student + User
        |--------------------------------------------------------------------------
        */

        $user = DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | Create Student
            |--------------------------------------------------------------------------
            */

            $student = Student::create([

                'enrollment_no' =>
                    $validated['enrollment_no'],

                'first_name' =>
                    $validated['first_name'],

                'last_name' =>
                    $validated['last_name'],

                'gender' =>
                    $validated['gender'],

                'email' =>
                    $validated['email'],

                'department_id' =>
                    $validated['department_id'],

                'semester' =>
                    $validated['semester'],

                'status' =>
                    'active',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            */

            $user = User::create([

                'name' =>
                    $validated['first_name']
                    . ' '
                    . $validated['last_name'],

                'email' =>
                    $validated['email'],

                'password' =>
                    Hash::make($validated['password']),

                'role' =>
                    'student',

                'student_id' =>
                    $student->id,

                'faculty_id' =>
                    null,
            ]);


            return $user;
        });


        /*
        |--------------------------------------------------------------------------
        | Automatic Login
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Student Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('student.dashboard')
            ->with(
                'success',
                'Student account created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FACULTY REGISTRATION
    |--------------------------------------------------------------------------
    */

    /**
     * Show Faculty Registration page.
     */
    public function showFacultyRegister()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Departments
        |--------------------------------------------------------------------------
        */

        $departments = Department::orderBy('department_name')->get();


        /*
        |--------------------------------------------------------------------------
        | Show Faculty Registration View
        |--------------------------------------------------------------------------
        */

        return view(
            'auth.faculty-register',
            compact('departments')
        );
    }


    /**
     * Register a new Faculty.
     *
     * Registration Flow:
     *
     * Faculty Form
     *      ↓
     * Validate
     *      ↓
     * Create Faculty
     *      ↓
     * Create User
     *      ↓
     * faculty_id = faculty.id
     *      ↓
     * role = faculty
     *      ↓
     * Login Page
     */
    public function facultyRegister(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Faculty Registration
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Faculty Name
            |--------------------------------------------------------------------------
            */

            'faculty_name' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Employee ID
            |--------------------------------------------------------------------------
            */

            'employee_id' => [
                'required',
                'string',
                'max:50',
                'unique:faculties,employee_id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Email
            |--------------------------------------------------------------------------
            */

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:faculties,email',
                'unique:users,email',
            ],


            /*
            |--------------------------------------------------------------------------
            | Phone
            |--------------------------------------------------------------------------
            */

            'phone' => [
                'required',
                'string',
                'max:20',
            ],


            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            */

            'department_id' => [
                'required',
                'exists:departments,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Faculty + User
        |--------------------------------------------------------------------------
        */

        $user = DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | 1. Create Faculty
            |--------------------------------------------------------------------------
            */

            $faculty = Faculty::create([

                'faculty_name' =>
                    $validated['faculty_name'],

                'employee_id' =>
                    $validated['employee_id'],

                'email' =>
                    $validated['email'],

                'phone' =>
                    $validated['phone'],

                'department_id' =>
                    $validated['department_id'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | 2. Create Faculty User
            |--------------------------------------------------------------------------
            */

            $user = User::create([

                'name' =>
                    $validated['faculty_name'],

                'email' =>
                    $validated['email'],

                'password' =>
                    Hash::make($validated['password']),

                'role' =>
                    'faculty',

                'student_id' =>
                    null,

                'faculty_id' =>
                    $faculty->id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Return User
            |--------------------------------------------------------------------------
            */

            return $user;
        });


        /*
        |--------------------------------------------------------------------------
        | Faculty Registration Complete
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Faculty registration successful. Please login.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    /**
     * Logout authenticated user.
     */
    public function logout(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Auth::logout();


        /*
        |--------------------------------------------------------------------------
        | Invalidate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();


        /*
        |--------------------------------------------------------------------------
        | Regenerate CSRF Token
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | Redirect Login
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'success',
                'You have been logged out.'
            );
    }
}