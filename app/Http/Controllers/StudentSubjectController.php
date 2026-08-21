<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StudentSubjectController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Get Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Get Student Profile
        |--------------------------------------------------------------------------
        */

        $student = $user->student;


        /*
        |--------------------------------------------------------------------------
        | Check Student Profile
        |--------------------------------------------------------------------------
        */

        if (!$student) {

            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'No student profile is connected to this account.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Student Classes
        |--------------------------------------------------------------------------
        */

        $studentClasses = $student
            ->studentClasses()
            ->with([
                'subject',
                'subject.faculty',
                'subject.department',
            ])
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Get Subjects
        |--------------------------------------------------------------------------
        */

        $subjects = $studentClasses
            ->pluck('subject')
            ->filter()
            ->unique('id')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Send Data To View
        |--------------------------------------------------------------------------
        */

        return view(
            'student.subjects',
            compact(
                'subjects'
            )
        );
    }
}