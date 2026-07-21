<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StudentController extends Controller
{
    /**
     * Display Student List
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
            'enrollment_no' => 'required|max:50|unique:students,enrollment_no',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'gender'        => 'required|in:Male,Female',
            'dob'           => 'nullable|date',
            'mobile'        => 'nullable|digits_between:10,15',
            'email'         => 'nullable|email|unique:students,email',
            'address'       => 'nullable|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'semester'      => 'required|integer|min:1|max:8',
            'academic_year' => 'nullable|string|max:20',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoName = null;

        if ($request->hasFile('photo')) {

            $photoName = time() . '.' . $request->photo->extension();

            $request->photo->move(
                public_path('uploads/students'),
                $photoName
            );
        }

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
            'photo'         => $photoName,
            'qr_unique_id'  => uniqid('STD-'),
            'status'        => 'active',
        ]);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student Added Successfully.');
    }

    /**
     * Show Single Student
     */
    public function show($id)
    {
        $student = Student::with('department')->findOrFail($id);

        return view('students.show', compact('student'));
    }

    /**
     * Edit Student Form
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
            'enrollment_no' => 'required|max:50|unique:students,enrollment_no,' . $student->id,
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'gender'        => 'required|in:Male,Female',
            'dob'           => 'nullable|date',
            'mobile'        => 'nullable|digits_between:10,15',
            'email'         => 'nullable|email|unique:students,email,' . $student->id,
            'address'       => 'nullable|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'semester'      => 'required|integer|min:1|max:8',
            'academic_year' => 'nullable|string|max:20',
            'status'        => 'required|in:active,inactive',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoName = $student->photo;

        if ($request->hasFile('photo')) {

            if ($student->photo && File::exists(public_path('uploads/students/' . $student->photo))) {

                File::delete(public_path('uploads/students/' . $student->photo));
            }

            $photoName = time() . '.' . $request->photo->extension();

            $request->photo->move(
                public_path('uploads/students'),
                $photoName
            );
        }

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
            'photo'         => $photoName,
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

        if ($student->photo && File::exists(public_path('uploads/students/' . $student->photo))) {

            File::delete(public_path('uploads/students/' . $student->photo));
        }

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student Deleted Successfully.');
    }
}