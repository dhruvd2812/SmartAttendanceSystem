<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceSessionController extends Controller
{
    /**
     * Display all attendance sessions.
     */
    public function index()
    {
        $sessions = AttendanceSession::latest()->get();

        return view('attendance_sessions.index', compact('sessions'));
    }

    /**
     * Show the form to create a new attendance session.
     */
    public function create()
    {
        $departments = Department::all();
        $faculties = Faculty::all();
        $subjects = Subject::all();

        return view('attendance_sessions.create', compact(
            'departments',
            'faculties',
            'subjects'
        ));
    }

    /**
     * Store a new attendance session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'faculty_id' => 'required',
            'department_id' => 'required',
            'subject_id' => 'required',
            'semester' => 'required',
            'lecture_name' => 'required',
        ]);

        AttendanceSession::create([
            'faculty_id' => $request->faculty_id,
            'department_id' => $request->department_id,
            'subject_id' => $request->subject_id,
            'semester' => $request->semester,
            'lecture_name' => $request->lecture_name,
            'session_token' => Str::random(20),
            'expires_at' => now()->addMinutes(2),
            'status' => 'active',
        ]);

        return redirect()
            ->route('attendance-sessions.index')
            ->with('success', 'Attendance session created successfully.');
    }
}