<?php

namespace App\Http\Controllers;

use App\Models\Notice;

class StudentNoticeController extends Controller
{
    /**
     * Display notices for students.
     */
    public function index()
    {
        $notices = Notice::where('is_active', true)
            ->latest()
            ->get();

        return view('student.notices', compact('notices'));
    }
}