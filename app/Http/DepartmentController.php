<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|max:100',
            'department_code' => 'required|max:10',
        ]);

        Department::create($request->all());

        return redirect('/departments')
            ->with('success', 'Department Added Successfully');
    }
}