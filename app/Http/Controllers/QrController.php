<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QrController extends Controller
{
    /**
     * Faculty QR Generator Page
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->faculty_id) {
            return redirect()
                ->route('login')
                ->with('error', 'Faculty account not found.');
        }

        $faculty = $user->faculty;

        if (!$faculty) {
            return redirect()
                ->route('login')
                ->with('error', 'Faculty profile not found.');
        }

        $subjects = Subject::where('faculty_id', $faculty->id)
            ->orderBy('semester')
            ->orderBy('name')
            ->get();

        return view('qr.index', compact(
            'faculty',
            'subjects'
        ));
    }


    /**
     * Generate Attendance Session + QR
     */
    public function generate(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->faculty_id) {
            return redirect()
                ->route('login')
                ->with('error', 'Faculty account not found.');
        }

        $faculty = $user->faculty;

        if (!$faculty) {
            return redirect()
                ->route('login')
                ->with('error', 'Faculty profile not found.');
        }

        $validated = $request->validate([
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],

            'lecture_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'lecture_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Verify Subject Belongs To Faculty
        |--------------------------------------------------------------------------
        */

        $subject = Subject::where('id', $validated['subject_id'])
            ->where('faculty_id', $faculty->id)
            ->first();

        if (!$subject) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected subject is not assigned to you.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Close Previous Active Sessions
        |--------------------------------------------------------------------------
        |
        | Existing TiDB schema:
        | status = enum('active', 'closed')
        |
        */

        AttendanceSession::where('faculty_id', $faculty->id)
            ->where('status', 'active')
            ->update([
                'status' => 'closed',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Secure Token
        |--------------------------------------------------------------------------
        */

        $qrToken = Str::random(64);

        /*
        |--------------------------------------------------------------------------
        | QR Expiry
        |--------------------------------------------------------------------------
        */

        $expiresAt = now()->addMinutes(2);

        /*
        |--------------------------------------------------------------------------
        | Create Attendance Session
        |--------------------------------------------------------------------------
        */

        $session = AttendanceSession::create([
            'faculty_id' => $faculty->id,
            'subject_id' => $subject->id,
            'lecture_date' => $validated['lecture_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'lecture_name' => $validated['lecture_name']
                ?? $subject->name,
            'qr_token' => $qrToken,
            'qr_expires_at' => $expiresAt,
            'status' => 'active',
        ]);

        /*
        |--------------------------------------------------------------------------
        | QR Data
        |--------------------------------------------------------------------------
        */

        $qrData = json_encode([
            'type' => 'attendance',
            'session_id' => $session->id,
            'qr_token' => $qrToken,
        ]);

        /*
        |--------------------------------------------------------------------------
        | QR Image
        |--------------------------------------------------------------------------
        */

        $qr = 'https://api.qrserver.com/v1/create-qr-code/?size=350x350&data='
            . urlencode($qrData);

        return view('qr.index', [
            'faculty' => $faculty,

            'subjects' => Subject::where(
                'faculty_id',
                $faculty->id
            )
                ->orderBy('semester')
                ->orderBy('name')
                ->get(),

            'subject' => $subject,

            'session' => $session,

            'qr' => $qr,

            'qrData' => $qrData,
        ]);
    }


    /**
     * Student QR Scanner Page
     */
    public function scan()
    {
        $user = Auth::user();

        if (!$user || !$user->student_id) {
            return redirect()
                ->route('login')
                ->with('error', 'Student account not found.');
        }

        $student = $user->student;

        if (!$student) {
            return redirect()
                ->route('login')
                ->with('error', 'Student profile not found.');
        }

        return view('student.scan-qr', compact('student'));
    }


    /**
     * Verify QR and Mark Student Attendance
     */
    public function markAttendance(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Logged-in Student
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user || !$user->student_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student account not found.',
            ], 401);
        }

        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student profile not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate QR Data
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'session_id' => [
                'required',
                'integer',
            ],

            'qr_token' => [
                'required',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find Attendance Session
        |--------------------------------------------------------------------------
        */

        $session = AttendanceSession::with([
            'subject',
            'faculty',
        ])
            ->where('id', $validated['session_id'])
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Session Status
        |--------------------------------------------------------------------------
        */

        if ($session->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This attendance QR code is no longer active.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check QR Expiry
        |--------------------------------------------------------------------------
        */

        if (
            !$session->qr_expires_at ||
            now()->greaterThan($session->qr_expires_at)
        ) {
            /*
            | Existing database enum only supports:
            | active / closed
            */
            $session->update([
                'status' => 'closed',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'This QR code has expired.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Student Status
        |--------------------------------------------------------------------------
        */

        if ($student->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your student account is not active.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Department
        |--------------------------------------------------------------------------
        |
        | Student and subject should belong to same department.
        |
        */

        if (
            $student->department_id &&
            $session->subject &&
            $session->subject->department_id &&
            $student->department_id != $session->subject->department_id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'You are not eligible for this subject.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Semester
        |--------------------------------------------------------------------------
        |
        | If both values exist, they should match.
        |
        */

        if (
            $student->semester &&
            $session->subject &&
            $session->subject->semester &&
            $student->semester != $session->subject->semester
        ) {
            return response()->json([
                'success' => false,
                'message' => 'You are not eligible for this semester.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Attendance
        |--------------------------------------------------------------------------
        */

        $alreadyMarked = Attendance::where(
            'student_id',
            $student->id
        )
            ->where(
                'attendance_session_id',
                $session->id
            )
            ->exists();

        if ($alreadyMarked) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance is already marked for this lecture.',
            ], 409);
        }

        /*
        |--------------------------------------------------------------------------
        | Mark Attendance
        |--------------------------------------------------------------------------
        */

        $attendance = Attendance::create([
            'student_id' => $student->id,

            'attendance_session_id' => $session->id,

            'status' => 'present',

            'marked_at' => now(),

            'remarks' => 'QR attendance',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Success Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'message' => 'Attendance marked successfully.',

            'attendance' => [
                'id' => $attendance->id,

                'student_name' => $student->full_name,

                'enrollment_no' => $student->enrollment_no,

                'subject' => $session->subject?->name,

                'subject_code' => $session->subject?->code,

                'faculty' => $session->faculty?->faculty_name,

                'status' => 'Present',

                'marked_at' => $attendance->marked_at?->toDateTimeString(),
            ],
        ]);
    }
}