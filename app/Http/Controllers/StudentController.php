<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Display Student List
    public function index()
    {
        $students = Student::with('department')->get();

        return view('students.index', compact('students'));
    }


    // Create Student Form
    public function create()
    {
        $departments = Department::all();

        return view('students.create', compact('departments'));
    }


    // Store Student Data
    public function store(Request $request)
    {
        $request->validate([
            'enrollment_no'=>'required|unique:students',
            'first_name'=>'required',
            'last_name'=>'required',
            'gender'=>'required',
            'department_id'=>'required',
            'semester'=>'required'
        ]);


        Student::create([

            'enrollment_no'=>$request->enrollment_no,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'gender'=>$request->gender,
            'dob'=>$request->dob,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
            'address'=>$request->address,
            'department_id'=>$request->department_id,
            'semester'=>$request->semester,
            'academic_year'=>$request->academic_year,
            'qr_unique_id'=>uniqid(),
            'status'=>'active'

        ]);


        return redirect()
        ->route('students.index')
        ->with('success','Student Added Successfully');
    }



    // Step 4.3: Edit Student Form
    public function edit($id)
    {
        $student = Student::findOrFail($id);

        $departments = Department::all();


        return view('students.edit', compact(
            'student',
            'departments'
        ));
    }



    // Step 4.4: Update Student Data
    public function update(Request $request, $id)
    {

        $student = Student::findOrFail($id);


        $request->validate([

            'first_name'=>'required',
            'last_name'=>'required',
            'gender'=>'required',
            'department_id'=>'required',
            'semester'=>'required'

        ]);



        $student->update([

            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'gender'=>$request->gender,
            'dob'=>$request->dob,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
            'address'=>$request->address,
            'department_id'=>$request->department_id,
            'semester'=>$request->semester,
            'academic_year'=>$request->academic_year

        ]);



        return redirect()
        ->route('students.index')
        ->with('success','Student Updated Successfully');

    }




    // Step 4.5: Delete Student
    public function destroy($id)
    {

        $student = Student::findOrFail($id);


        $student->delete();



        return redirect()
        ->route('students.index')
        ->with('success','Student Deleted Successfully');

    }

}