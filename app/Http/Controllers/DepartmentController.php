<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $departments = Department::query()
            ->when($search, fn ($query) => $query->where('department_name', 'like', "%{$search}%")
                ->orWhere('department_code', 'like', "%{$search}%"))
            ->orderBy('department_name')
            ->paginate(8)
            ->withQueryString();

        return view('departments.index', compact('departments', 'search'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:100'],
            'department_code' => ['required', 'string', 'max:10', 'unique:departments,department_code'],
        ]);
        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department added successfully.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'department_name' => ['required', 'string', 'max:100'],
            'department_code' => ['required', 'string', 'max:10', Rule::unique('departments', 'department_code')->ignore($department)],
        ]);
        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
