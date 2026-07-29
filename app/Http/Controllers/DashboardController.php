<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();
        $departmentCount = Department::count();

        $recentStudents = Student::with('department')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'studentCount',
            'departmentCount',
            'recentStudents'
        ));
    }
}