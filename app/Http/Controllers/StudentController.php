<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display all students
     */
    public function index()
    {
        $students = Student::with('department')->latest()->get();

        return view('students.index', compact('students'));
    }

    /**
     * Show Add Student Form
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('students.create', compact('departments'));
    }

    /**
     * Store Student
     */
    public function store(Request $request)
    {
        $request->validate([
            'enrollment_no' => 'required|unique:students,enrollment_no',
            'first_name'    => 'required|max:100',
            'last_name'     => 'required|max:100',
            'gender'        => 'required',
            'dob'           => 'nullable|date',
            'mobile'        => 'nullable|max:15',
            'email'         => 'nullable|email|unique:students,email',
            'address'       => 'nullable',
            'department_id' => 'required|exists:departments,id',
            'semester'      => 'required|integer',
            'academic_year' => 'nullable|max:20',
        ]);

        Student::create([
            'enrollment_no' => $request->enrollment_no,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'gender'        => $request->gender,
            'dob'           => $request->dob,
            'mobile'        => $request->mobile,
            'email'         => $request->email,
            'address'       => $request->address,
            'department_id' => $request->department_id,
            'semester'      => $request->semester,
            'academic_year' => $request->academic_year,
            'photo'         => null,
            'qr_unique_id'  => uniqid('STD-'),
            'status'        => 'active',
        ]);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student Added Successfully.');
    }

    /**
     * Display Single Student
     */
    public function show($id)
    {
        $student = Student::with('department')->findOrFail($id);

        return view('students.show', compact('student'));
    }

    /**
     * Show Edit Form
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);

        $departments = Department::orderBy('name')->get();

        return view('students.edit', compact('student', 'departments'));
    }

    /**
     * Update Student
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'enrollment_no' => 'required|unique:students,enrollment_no,' . $student->id,
            'first_name'    => 'required|max:100',
            'last_name'     => 'required|max:100',
            'gender'        => 'required',
            'dob'           => 'nullable|date',
            'mobile'        => 'nullable|max:15',
            'email'         => 'nullable|email|unique:students,email,' . $student->id,
            'address'       => 'nullable',
            'department_id' => 'required|exists:departments,id',
            'semester'      => 'required|integer',
            'academic_year' => 'nullable|max:20',
            'status'        => 'required',
        ]);

        $student->update([
            'enrollment_no' => $request->enrollment_no,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'gender'        => $request->gender,
            'dob'           => $request->dob,
            'mobile'        => $request->mobile,
            'email'         => $request->email,
            'address'       => $request->address,
            'department_id' => $request->department_id,
            'semester'      => $request->semester,
            'academic_year' => $request->academic_year,
            'status'        => $request->status,
        ]);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student Updated Successfully.');
    }

    /**
     * Delete Student
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student Deleted Successfully.');
    }
}