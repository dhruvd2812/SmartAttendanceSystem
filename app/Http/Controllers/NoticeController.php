<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::latest()->get();

        return view('notices.index', compact('notices'));
    }

    public function create()
    {
        return view('notices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $user = auth()->user();

        Notice::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'posted_by' => $user->display_name,
            'role' => $user->role ?? null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('notices.index')
            ->with('success', 'Notice added successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()
            ->route('notices.index')
            ->with('success', 'Notice deleted successfully.');
    }
}