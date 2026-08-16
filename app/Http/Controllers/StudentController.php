<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display all students.
     */
    public function index()
    {
        $students = Student::with('department')
            ->latest()
            ->get();

        return view('students.index', compact('students'));
    }

    /**
     * Show Add Student form.
     */
    public function create()
    {
        $departments = Department::orderBy('department_name')->get();

        return view('students.create', compact('departments'));
    }

    /**
     * Store a new student and create the student's login account.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Student + Login Information
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'enrollment_no' => [
                'required',
                'max:50',
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

            'dob' => [
                'nullable',
                'date',
            ],

            'mobile' => [
                'nullable',
                'digits_between:10,15',
            ],

            /*
            |--------------------------------------------------------------------------
            | Login Email
            |--------------------------------------------------------------------------
            */

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email',
                'unique:users,email',
            ],

            /*
            |--------------------------------------------------------------------------
            | Login Password
            |--------------------------------------------------------------------------
            */

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
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

            'academic_year' => [
                'nullable',
                'string',
                'max:20',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        |
        | Student and User account are created together.
        |
        | If User creation fails, Student creation is also rolled back.
        |
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Upload Student Photo
            |--------------------------------------------------------------------------
            */

            $photoName = null;

            if ($request->hasFile('photo')) {

                $uploadPath = public_path('uploads/students');

                /*
                | Create directory if it does not exist.
                */

                if (!File::exists($uploadPath)) {
                    File::makeDirectory(
                        $uploadPath,
                        0755,
                        true
                    );
                }

                $photoName = time()
                    . '_' .
                    uniqid()
                    . '.' .
                    $request->photo->extension();

                $request->photo->move(
                    $uploadPath,
                    $photoName
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Create Student
            |--------------------------------------------------------------------------
            */

            $student = Student::create([
                'enrollment_no' => $validated['enrollment_no'],

                'first_name' => $validated['first_name'],

                'last_name' => $validated['last_name'],

                'gender' => $validated['gender'],

                'dob' => $validated['dob'] ?? null,

                'mobile' => $validated['mobile'] ?? null,

                'email' => $validated['email'],

                'address' => $validated['address'] ?? null,

                'department_id' => $validated['department_id'],

                'semester' => $validated['semester'],

                'academic_year' => $validated['academic_year'] ?? null,

                'photo' => $photoName,

                'qr_unique_id' => uniqid('STD-'),

                'status' => $validated['status'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Student Login User
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'name' => $student->first_name . ' ' . $student->last_name,

                'email' => $student->email,

                /*
                | Hash password before storing.
                */

                'password' => Hash::make(
                    $validated['password']
                ),

                /*
                | Important role.
                */

                'role' => 'student',

                /*
                | Connect User with Student.
                */

                'student_id' => $student->id,

                /*
                | Faculty must remain null.
                */

                'faculty_id' => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit Transaction
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('students.index')
                ->with(
                    'success',
                    'Student and Student Login Account created successfully.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback Database Changes
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Delete Uploaded Photo if Database Failed
            |--------------------------------------------------------------------------
            */

            if (
                isset($photoName) &&
                $photoName &&
                File::exists(
                    public_path(
                        'uploads/students/' . $photoName
                    )
                )
            ) {
                File::delete(
                    public_path(
                        'uploads/students/' . $photoName
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Return Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                        'Student account could not be created. ' .
                        $e->getMessage(),
                ]);
        }
    }

    /**
     * Display a student.
     */
    public function show($id)
    {
        $student = Student::with('department')
            ->findOrFail($id);

        return view(
            'students.show',
            compact('student')
        );
    }

    /**
     * Show Edit Student form.
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);

        $departments = Department::orderBy(
            'department_name'
        )->get();

        return view(
            'students.edit',
            compact(
                'student',
                'departments'
            )
        );
    }

    /**
     * Update Student.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'enrollment_no' => [
                'required',
                'max:50',
                'unique:students,enrollment_no,' . $student->id,
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

            'dob' => [
                'nullable',
                'date',
            ],

            'mobile' => [
                'nullable',
                'digits_between:10,15',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email,' . $student->id,
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
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

            'academic_year' => [
                'nullable',
                'string',
                'max:20',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Photo
        |--------------------------------------------------------------------------
        */

        $photoName = $student->photo;

        if ($request->hasFile('photo')) {

            if (
                $student->photo &&
                File::exists(
                    public_path(
                        'uploads/students/' .
                        $student->photo
                    )
                )
            ) {
                File::delete(
                    public_path(
                        'uploads/students/' .
                        $student->photo
                    )
                );
            }

            $uploadPath = public_path(
                'uploads/students'
            );

            if (!File::exists($uploadPath)) {
                File::makeDirectory(
                    $uploadPath,
                    0755,
                    true
                );
            }

            $photoName = time()
                . '_'
                . uniqid()
                . '.'
                . $request->photo->extension();

            $request->photo->move(
                $uploadPath,
                $photoName
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Student
        |--------------------------------------------------------------------------
        */

        $student->update([
            'enrollment_no' => $request->enrollment_no,

            'first_name' => $request->first_name,

            'last_name' => $request->last_name,

            'gender' => $request->gender,

            'dob' => $request->dob,

            'mobile' => $request->mobile,

            'email' => $request->email,

            'address' => $request->address,

            'department_id' => $request->department_id,

            'semester' => $request->semester,

            'academic_year' => $request->academic_year,

            'photo' => $photoName,

            'status' => $request->status,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Connected User Email/Name
        |--------------------------------------------------------------------------
        */

        if ($student->user) {

            $student->user->update([
                'name' =>
                    $student->first_name .
                    ' ' .
                    $student->last_name,

                'email' => $student->email,
            ]);
        }


        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Student Updated Successfully.'
            );
    }

    /**
     * Delete Student and connected User account.
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Photo
        |--------------------------------------------------------------------------
        */

        if (
            $student->photo &&
            File::exists(
                public_path(
                    'uploads/students/' .
                    $student->photo
                )
            )
        ) {
            File::delete(
                public_path(
                    'uploads/students/' .
                    $student->photo
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Connected User
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


        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Student and Login Account Deleted Successfully.'
            );
    }
}