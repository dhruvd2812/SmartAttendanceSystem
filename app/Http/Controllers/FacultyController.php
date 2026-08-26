<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FacultyController extends Controller
{
    /**
     * Display Faculty List
     */
    public function index()
    {
        $faculties = Faculty::with('user')->latest()->get();

        return view('faculties.index', compact('faculties'));
    }

    /**
     * Show Add Faculty Form
     */
    public function create()
    {
        return view('faculties.create');
    }

    /**
     * Store Faculty
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email|unique:faculties,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($validated) {
            $faculty = Faculty::create([
                'faculty_name' => $validated['email'],
                'email' => $validated['email'],
            ]);

            User::create([
                'name' => $validated['email'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'faculty',
                'faculty_id' => $faculty->id,
            ]);
        });

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty login created successfully.');
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

        $this->linkFacultyLogin($faculty);

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty Updated Successfully.');
    }

    /**
     * Delete Faculty
     */
    public function destroy(Faculty $faculty)
    {
        DB::transaction(function () use ($faculty) {
            $faculty->user?->delete();
            $faculty->delete();
        });

        return redirect()->route('faculties.index')
            ->with('success', 'Faculty Deleted Successfully.');
    }

    /** Keep legacy faculty records linked when an admin edits them. */
    private function linkFacultyLogin(Faculty $faculty): void
    {
        User::where('role', 'faculty')
            ->where('email', $faculty->email)
            ->update(['faculty_id' => $faculty->id]);
    }

}
