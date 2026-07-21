<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $departments = Department::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('department_name', 'like', "%{$search}%")
                        ->orWhere('department_code', 'like', "%{$search}%");
                });
            })
            ->orderBy('department_name')
            ->paginate(8)
            ->withQueryString();

        return view('departments.index', compact('departments', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:100'],
            'department_code' => ['required', 'string', 'max:10', 'unique:departments,department_code'],
        ]);

        Department::create($validated);

        return redirect('/departments')
            ->with('success', 'Department added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:100'],
            'department_code' => [
                'required', 'string', 'max:10',
                Rule::unique('departments', 'department_code')->ignore($department->id),
            ],
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
