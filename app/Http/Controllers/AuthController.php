<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Show login page.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login user.
     *
     * Student  -> Student Dashboard
     * Faculty  -> Faculty Dashboard
     * Admin    -> Admin Dashboard
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'The provided email or password is incorrect.'
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // STUDENT
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        // FACULTY
        if ($user->role === 'faculty') {
            return redirect()->route('faculty.dashboard');
        }

        // ADMIN
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        // INVALID ROLE
        Auth::logout();

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Your account does not have a valid role.'
            ]);
    }

    /**
     * Show registration page.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Register a new student.
     *
     * Registration flow:
     *
     * Register Form
     *      ↓
     * Create Student
     *      ↓
     * Create User
     *      ↓
     * user.student_id = student.id
     *      ↓
     * role = student
     *      ↓
     * Automatic Login
     *      ↓
     * Student Dashboard
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
                'confirmed',
                'min:8',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Student + User together
        |--------------------------------------------------------------------------
        |
        | If anything fails, both records are rolled back.
        |
        */

        $user = DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | 1. Create Student Profile
            |--------------------------------------------------------------------------
            */

            $student = Student::create([
                'enrollment_no' => $validated['enrollment_no'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'department_id' => $validated['department_id'],
                'semester' => $validated['semester'],
                'status' => 'active',
            ]);

            /*
            |--------------------------------------------------------------------------
            | 2. Create User Login Account
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'student_id' => $student->id,
                'faculty_id' => null,
            ]);

            return $user;
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Automatically Login Student
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | 4. Redirect to Student Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('student.dashboard')
            ->with('success', 'Student account created successfully.');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out.');
    }
}