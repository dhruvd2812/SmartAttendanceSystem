<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrController extends Controller
{
    /**
     * Display QR generator page.
     */
    public function index()
    {
        return view('qr.index');
    }

    /**
     * Generate QR Code.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'faculty' => 'required',
            'department' => 'required',
            'subject' => 'required',
        ]);

        $data = json_encode([
            "session_id" => rand(1000, 9999),
            "faculty" => $request->faculty,
            "department" => $request->department,
            "subject" => $request->subject,
            "date" => now()->format('d-m-Y'),
            "time" => now()->format('h:i A'),
            "expires" => now()->addMinutes(2)->format('h:i A')
        ]);

        $qr = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data="
            . urlencode($data);

        return view('qr.index', compact('qr', 'data'));
    }

    /**
     * Display QR scanner page for students.
     */
    public function scan()
    {
        return view('student.scan-qr');
    }
}