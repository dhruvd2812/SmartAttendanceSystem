<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * Display Faculty List
     */
    public function index()
    {
        $faculties = Faculty::with('department')->latest()->get();

        return view('faculties.index', compact('faculties'));
    }

    /**
     * Show Add Faculty Form
     */
    public function create()
    {
        $departments = Department::all();

        return view('faculties.create', compact('departments'));
    }

    /**
     * Store Faculty
     */
public function store(Request $request)
{
    $request->validate([
        'faculty_name'  => 'required',
        'employee_id'   => 'required|unique:faculties',
        'email'         => 'required|email|unique:faculties',
        'phone'         => 'required',
        'department_id' => 'required|exists:departments,id',
    ]);

    Faculty::create([
        'faculty_name'  => $request->faculty_name,
        'employee_id'   => $request->employee_id,
        'email'         => $request->email,
        'phone'         => $request->phone,
        'department_id' => $request->department_id,
    ]);

    return redirect()->route('faculties.index')
                     ->with('success', 'Faculty Added Successfully');
}

    /**
     * Show Edit Faculty Form
     */
    public function edit(Faculty $faculty)
    {
        $departments = Department::all();

        return view('faculties.edit', compact('faculty', 'departments'));
    }

    /**
     * Update Faculty
     */
    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'faculty_name'  => 'required|string|max:100',
            'employee_id'   => 'required|string|max:20|unique:faculties,employee_id,' . $faculty->id,
            'email'         => 'required|email|unique:faculties,email,' . $faculty->id,
            'phone'         => 'required|string|max:15',
            'department_id' => 'required|exists:departments,id',
        ]);

        $faculty->update([
            'faculty_name'  => $request->faculty_name,
            'employee_id'   => $request->employee_id,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty Updated Successfully.');
    }

    /**
     * Delete Faculty
     */
    public function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty Deleted Successfully.');
    }
}