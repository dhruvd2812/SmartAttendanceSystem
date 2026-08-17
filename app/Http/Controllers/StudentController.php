<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display student list.
     */
    public function index()
    {
        $students = Student::with('department')
            ->latest()
            ->get();

        return view('students.index', compact('students'));
    }


    /**
     * Show create student form.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('students.create', compact('departments'));
    }


    /**
     * Store student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email',
                'unique:users,email',
            ],

            'phone' => 'nullable|string|max:20',

            'gender' => 'nullable|string|max:20',

            'date_of_birth' => 'nullable|date',

            'department_id' => [
                'required',
                'exists:departments,id',
            ],
        ]);


        DB::transaction(function () use ($validated) {

            $student = Student::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'department_id' => $validated['department_id'],
            ]);


            User::create([
                'name' => $student->name,
                'email' => $student->email,
                'password' => Hash::make('12345678'),
                'student_id' => $student->id,
                'role' => 'student',
            ]);
        });


        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }


    /**
     * Display student details.
     */
    public function show(Student $student)
    {
        $student->load('department');

        return view('students.show', compact('student'));
    }


    /**
     * Show edit form.
     */
    public function edit(Student $student)
    {
        $departments = Department::orderBy('name')->get();

        return view(
            'students.edit',
            compact('student', 'departments')
        );
    }


    /**
     * Update student.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email,' . $student->id,
                'unique:users,email,' . optional($student->user)->id,
            ],

            'phone' => 'nullable|string|max:20',

            'gender' => 'nullable|string|max:20',

            'date_of_birth' => 'nullable|date',

            'department_id' => [
                'required',
                'exists:departments,id',
            ],
        ]);


        DB::transaction(function () use (
            $validated,
            $student
        ) {

            $student->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'department_id' => $validated['department_id'],
            ]);


            if ($student->user) {

                $student->user->update([
                    'name' => $student->name,
                    'email' => $student->email,
                ]);

            }
        });


        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }


    /**
     * Delete student.
     */
    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {

            if ($student->user) {
                $student->user->delete();
            }

            $student->delete();
        });


        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}