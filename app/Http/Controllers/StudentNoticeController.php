<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentNoticeController extends Controller
{
    /**
     * Display notices for students.
     */
    public function index()
    {
        return view('student.notices');
    }
}