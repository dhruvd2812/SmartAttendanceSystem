<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculty = $this->faculty();
        $subjects = Subject::where('faculty_id', $faculty->id)
            ->withCount('studentClasses')
            ->orderBy('semester')
            ->orderBy('name')
            ->get();

        return view('faculty.subjects.index', compact('subjects', 'faculty'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('faculty.subjects.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $faculty = $this->faculty();

        Subject::create([
            ...$this->validateSubject($request),
            'faculty_id' => $faculty->id,
            'department_id' => $faculty->department_id,
        ]);

        return redirect()->route('faculty.subjects.index')
            ->with('success', 'Subject added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $this->authorizeSubject($subject);

        return view('faculty.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $faculty = $this->faculty();
        $this->authorizeSubject($subject, $faculty->id);

        $subject->update([
            ...$this->validateSubject($request, $subject),
            'department_id' => $faculty->department_id,
        ]);

        return redirect()->route('faculty.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $this->authorizeSubject($subject);
        $subject->delete();

        return redirect()->route('faculty.subjects.index')
            ->with('success', 'Subject removed successfully.');
    }

    private function faculty()
    {
        $faculty = Auth::user()->faculty;

        if (!$faculty) {
            abort(403, 'Faculty profile not found.');
        }

        return $faculty;
    }

    private function authorizeSubject(Subject $subject, ?int $facultyId = null): void
    {
        $facultyId ??= $this->faculty()->id;

        if ($subject->faculty_id !== $facultyId) {
            abort(403, 'You are not allowed to manage this subject.');
        }
    }

    private function validateSubject(Request $request, ?Subject $subject = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects', 'code')->ignore($subject?->id)],
            'semester' => ['nullable', 'integer', 'min:1', 'max:12'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
