<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyAttendanceController extends Controller
{
    /** Display attendance for lectures conducted by the logged-in faculty. */
    public function index(Request $request)
    {
        $faculty = Auth::user()?->faculty;

        if (!$faculty) {
            return redirect()->route('faculty.dashboard')
                ->with('error', 'Your faculty profile is not linked to this account.');
        }

        $subjects = Subject::where('faculty_id', $faculty->id)
            ->orderBy('name')
            ->get();

        $sessions = AttendanceSession::where('faculty_id', $faculty->id)
            ->when($request->filled('subject_id'), fn ($query) => $query->where('subject_id', $request->subject_id))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('lecture_date', $request->date))
            ->with([
                'subject' => fn ($query) => $query->withCount('studentClasses'),
                'attendances.student',
            ])
            ->withCount([
                'attendances as present_count' => fn ($query) => $query->where('status', 'present'),
                'attendances as late_count' => fn ($query) => $query->where('status', 'late'),
            ])
            ->latest('lecture_date')
            ->latest('start_time')
            ->get();

        return view('faculty.attendance.index', compact('subjects', 'sessions'));
    }
}
