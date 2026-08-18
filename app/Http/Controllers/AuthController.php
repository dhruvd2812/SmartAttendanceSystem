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
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],
        ]);


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


        $request->session()->regenerate();

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
     * Register Student.
     */
    public function register(Request $request)
    {
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
            | Create Student User
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

        $departments = Department::orderBy('department_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Faculty Registration View
        |--------------------------------------------------------------------------
        */

        return view(
            'auth.faculty-register',
            compact('departments')
        );
    }


    /**
     * Register Faculty.
     */
    public function facultyRegister(Request $request)
    {
        $validated = $request->validate([

            'faculty_name' => [
                'required',
                'string',
                'max:255',
            ],

            'employee_id' => [
                'required',
                'string',
                'max:50',
                'unique:faculties,employee_id',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:faculties,email',
                'unique:users,email',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        $user = DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | Create Faculty
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
            | Create Faculty User
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


            return $user;
        });


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Faculty registration successful. Please login.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN REGISTRATION
    |--------------------------------------------------------------------------
    */

    /**
     * Show Admin Registration page.
     *
     * IMPORTANT:
     * In a real production system, public admin registration
     * should normally be disabled.
     */
    public function showAdminRegister()
    {
        return view('auth.admin-register');
    }


    /**
     * Register Admin.
     */
    public function adminRegister(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        User::create([

            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                Hash::make($validated['password']),

            'role' =>
                'admin',

            'student_id' =>
                null,

            'faculty_id' =>
                null,
        ]);


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Admin account created successfully. Please login.'
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
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'You have been logged out.'
            );
    }
}