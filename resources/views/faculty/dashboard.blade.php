@extends('layouts.app')

@section('title', 'Faculty Dashboard | Smart Attendance')

@section('content')
<div class="container-fluid py-4">
    <div class="app-hero p-4 p-md-5 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <p class="mb-2 opacity-75">Faculty Dashboard</p>
                <h1 class="display-6 mb-2">Hello, {{ $user->name ?? 'Faculty' }}</h1>
                <p class="mb-0">Manage your subjects, students, attendance sessions, and QR codes.</p>
            </div>
            <div class="mt-3 mt-md-0"><span class="badge bg-dark fs-6 px-3 py-2">Faculty</span></div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3"><div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted mb-1">My Subjects</p><h2 class="mb-0">{{ $subjectCount }}</h2></div><div class="fs-1">📚</div></div></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted mb-1">My Students</p><h2 class="mb-0">{{ $studentCount }}</h2></div><div class="fs-1">👨‍🎓</div></div></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted mb-1">Today's Classes</p><h2 class="mb-0">{{ $todayClasses }}</h2></div><div class="fs-1">🗓️</div></div></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="card shadow-sm border-0 h-100"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted mb-1">Attendance Sessions</p><h2 class="mb-0">{{ $attendanceSessionCount }}</h2></div><div class="fs-1">📊</div></div></div></div></div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Quick Actions</h4>
                <a href="{{ route('faculty.subjects.index') }}" class="btn btn-outline-primary">Manage Subjects</a>
            </div>
            <div class="row g-3">
                <div class="col-md-6 col-lg-4"><a href="{{ route('faculty.qr.index') }}" class="btn btn-primary w-100 py-3">📱<br>Generate QR Code</a></div>
                <div class="col-md-6 col-lg-4"><a href="{{ route('faculty.students.index') }}" class="btn btn-success w-100 py-3">👨‍🎓<br>View Students</a></div>
                <div class="col-md-6 col-lg-4"><a href="{{ route('faculty.chatbot.index') }}" class="btn btn-dark w-100 py-3">🤖<br>Open Chatbot</a></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center"><h5 class="mb-0">My Assigned Subjects</h5><span class="text-muted small">{{ $subjectCount }} assigned</span></div>
        <div class="card-body p-0">
            @if($subjects->isNotEmpty())
                <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Subject</th><th>Code</th><th>Semester</th><th>Enrolled Students</th></tr></thead><tbody>
                    @foreach($subjects as $subject)
                        <tr><td>{{ $subject->name }}</td><td>{{ $subject->code ?? '-' }}</td><td>{{ $subject->semester ?? '-' }}</td><td>{{ $subject->student_classes_count }}</td></tr>
                    @endforeach
                </tbody></table></div>
            @else
                <div class="p-4 text-center text-muted">No subjects have been assigned yet.</div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0"><div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3"><div><h5 class="mb-1">🤖 Smart Attendance AI</h5><p class="text-muted mb-0">Ask about your subjects, students, attendance, or QR codes.</p></div><a href="{{ route('faculty.chatbot.index') }}" class="btn btn-primary">Open Chatbot</a></div></div>
</div>
@endsection
