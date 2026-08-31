@extends('layouts.faculty')

@section('title', 'Manual Attendance | Smart Attendance')
@section('page-title', 'Manual Attendance')

@section('content')

<div class="container-fluid py-2">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="font-size: 1.5rem;">
                <i class="bi bi-clipboard-check text-primary me-2"></i>Manual Attendance
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Mark attendance manually or <strong>auto-fill / copy from any previous faculty lecture</strong>.
            </p>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" id="toggleCopyPanelBtn">
                <i class="bi bi-copy me-1"></i> Copy from Other Lecture
            </button>
            <a href="{{ route('faculty.attendance.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    {{-- COPY ATTENDANCE HELPER CARD --}}
    <div class="card shadow-sm border-0 mb-4 bg-light" id="copyAttendancePanel" style="border-left: 4px solid var(--color-primary) !important;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary p-2 rounded-circle">
                    <i class="bi bi-lightning-charge-fill"></i>
                </span>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Quick Copy: Fill Attendance from Any Previous Lecture</h6>
                    <small class="text-muted">Did another faculty take a lecture before yours? Pick the date and 1-click copy their attendance data!</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-sm" id="closeCopyPanelBtn" aria-label="Close"></button>
        </div>

        <div class="card-body">
            <div class="row g-3 align-items-end mb-3">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label fw-semibold text-muted small mb-1">
                        <i class="bi bi-calendar-event me-1"></i>Select Date to Lookup Lectures:
                    </label>
                    <input type="date" id="copySearchDate" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <button type="button" id="searchLecturesBtn" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Find Lectures
                    </button>
                </div>
                <div class="col-md-5">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle text-primary me-1"></i>
                        You can select today's date or 5-6 days ago to copy from past lectures.
                    </div>
                </div>
            </div>

            {{-- SEARCHING SPINNER --}}
            <div id="copySearchLoading" class="text-center py-3" style="display:none;">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <span class="ms-2 text-muted small">Fetching lectures on this date...</span>
            </div>

            {{-- LECTURES LIST CONTAINER --}}
            <div id="copyLecturesContainer">
                <div class="alert alert-info py-2 px-3 mb-0 small" id="copyDefaultHint">
                    <i class="bi bi-lightbulb me-1"></i> Click <strong>"Find Lectures"</strong> to see all sessions conducted by any faculty on the selected date.
                </div>
            </div>

            {{-- SUCCESS COPY BANNER --}}
            <div id="copiedSuccessAlert" class="alert alert-success mt-3 py-2 px-3 small d-flex align-items-center justify-content-between" style="display:none !important;">
                <div id="copiedSuccessText">
                    <i class="bi bi-check-circle-fill me-2"></i> Attendance copied successfully!
                </div>
                <span class="badge bg-success">Copied & Applied</span>
            </div>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('faculty.attendance.manual.store') }}" id="attendanceForm">
        @csrf

        {{-- LECTURE INFORMATION --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-semibold" style="font-size: 1.1rem;">
                    <i class="bi bi-journal-text me-2"></i>Lecture Information
                </h5>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    {{-- SUBJECT --}}
                    <div class="col-md-5">
                        <label class="form-label fw-bold">
                            Your Subject <span class="text-danger">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" class="form-select" required>
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }}
                                    @if($subject->code) ({{ $subject->code }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Choose your subject for which attendance will be recorded.</div>
                    </div>

                    {{-- DATE --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            Lecture Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="lecture_date" id="lecture_date" class="form-control" value="{{ old('lecture_date', date('Y-m-d')) }}" required>
                    </div>

                    {{-- LECTURE NAME --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Lecture Name / Topic</label>
                        <input type="text" name="lecture_name" id="lecture_name" class="form-control" placeholder="Example: Unit 3 / Chapter 4">
                    </div>

                    {{-- START TIME --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control">
                    </div>

                    {{-- END TIME --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        {{-- STUDENTS --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-dark" style="font-size: 1.1rem;">
                    <i class="bi bi-people-fill text-primary me-2"></i>Student Attendance List
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <span id="studentCount" class="badge bg-primary px-3 py-2">0 Students</span>
                </div>
            </div>

            <div class="card-body">


                {{-- SELECT SUBJECT --}}
                <div id="selectSubjectMessage"
                     class="text-center text-muted py-5">

                    <h5>
                        Select a subject
                    </h5>

                    <p class="mb-0">
                        Students assigned to the subject
                        will appear here.
                    </p>

                </div>


                {{-- LOADING --}}
                <div id="loading"
                     class="text-center py-5"
                     style="display:none;">

                    <div class="spinner-border text-primary"
                         role="status">

                        <span class="visually-hidden">
                            Loading...
                        </span>

                    </div>

                    <p class="mt-3">
                        Loading students...
                    </p>

                </div>


                {{-- NO STUDENTS --}}
                <div id="noStudents"
                     class="alert alert-warning"
                     style="display:none;">

                    <strong>No students found.</strong>

                    <br>

                    There are no students assigned
                    to this subject.

                </div>


                {{-- STUDENT TABLE --}}
                <div id="studentTableContainer"
                     class="table-responsive"
                     style="display:none;">

                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <div id="attendanceModeBadge">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                <i class="bi bi-list-check me-1"></i> Attendance Roster
                            </span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="loadAllSemesterStudentsBtn" style="display:none;">
                            <i class="bi bi-people me-1"></i> Load All Semester Students
                        </button>
                    </div>

                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px;" class="text-center">#</th>
                                <th style="width:160px;">Enrollment No</th>
                                <th>Student Name</th>
                                <th style="width:100px;">Semester</th>
                                <th style="width:230px;">Attendance Status</th>
                                <th>Remarks</th>
                                <th style="width:60px;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                        </tbody>
                    </table>

                    {{-- PRESENT ALL BUTTON BAR (RIGHT ABOVE ADD STUDENT) --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-2 flex-wrap gap-2 p-2 bg-white border rounded-3">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" id="markAllPresentQuick" class="btn btn-success fw-bold px-3">
                                <i class="bi bi-check-all me-1"></i> ✓ Present All (Mark All Present)
                            </button>
                            <button type="button" id="markAllAbsentQuick" class="btn btn-outline-danger btn-sm">
                                ✕ Mark All Absent
                            </button>
                        </div>
                        <span class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i> 1-click will mark all listed/copied students as <strong>Present</strong>!
                        </span>
                    </div>

                    {{-- ADD EXTRA / LATE ARRIVAL STUDENT BAR (WITH SEARCH) --}}
                    <div class="card bg-light border p-3 rounded-3" id="addExtraStudentBar">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-semibold small text-dark">
                                <i class="bi bi-person-plus-fill text-success me-1"></i>Add Another Student (Late Arrival / Walk-in):
                            </span>
                            <div class="input-group input-group-sm" style="max-width: 250px;">
                                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="extraStudentSearch" class="form-control" placeholder="Search student name / enrollment...">
                            </div>
                            <select id="extraStudentSelect" class="form-select form-select-sm" style="max-width: 320px;">
                                <option value="">-- Choose Student to Add --</option>
                            </select>
                            <button type="button" id="addExtraStudentBtn" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-lg me-1"></i> Add Student
                            </button>
                        </div>
                    </div>

                </div>


                {{-- ACTION BUTTONS --}}
                <div id="actionButtons"
                     class="mt-4"
                     style="display:none;">

                    <div class="d-flex
                                justify-content-between
                                align-items-center
                                flex-wrap
                                gap-2">

                        <div>
                            <button type="button"
                                    id="markAllPresent"
                                    class="btn btn-outline-success">
                                ✓ Mark All Present
                            </button>

                            <button type="button"
                                    id="markAllAbsent"
                                    class="btn btn-outline-danger">
                                ✕ Mark All Absent
                            </button>
                        </div>

                        <button type="submit"
                                class="btn btn-primary btn-lg">
                            <i class="bi bi-check2-circle me-1"></i> Save Attendance
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const subjectSelect             = document.getElementById('subject_id');
    const lectureDateInput          = document.getElementById('lecture_date');
    const copySearchDateInput       = document.getElementById('copySearchDate');
    const searchLecturesBtn         = document.getElementById('searchLecturesBtn');
    const copyLecturesContainer     = document.getElementById('copyLecturesContainer');
    const copySearchLoading         = document.getElementById('copySearchLoading');
    const copyAttendancePanel       = document.getElementById('copyAttendancePanel');
    const toggleCopyPanelBtn        = document.getElementById('toggleCopyPanelBtn');
    const closeCopyPanelBtn         = document.getElementById('closeCopyPanelBtn');
    const copiedSuccessAlert        = document.getElementById('copiedSuccessAlert');
    const copiedSuccessText         = document.getElementById('copiedSuccessText');

    const studentTableBody          = document.getElementById('studentTableBody');
    const studentTableContainer     = document.getElementById('studentTableContainer');
    const studentCount              = document.getElementById('studentCount');
    const loading                   = document.getElementById('loading');
    const selectSubjectMessage      = document.getElementById('selectSubjectMessage');
    const noStudents                = document.getElementById('noStudents');
    const actionButtons             = document.getElementById('actionButtons');
    const attendanceModeBadge       = document.getElementById('attendanceModeBadge');
    const loadAllSemesterStudentsBtn= document.getElementById('loadAllSemesterStudentsBtn');
    const extraStudentSearch        = document.getElementById('extraStudentSearch');
    const extraStudentSelect        = document.getElementById('extraStudentSelect');
    const addExtraStudentBtn        = document.getElementById('addExtraStudentBtn');
    const markAllPresentQuick       = document.getElementById('markAllPresentQuick');
    const markAllAbsentQuick        = document.getElementById('markAllAbsentQuick');

    let currentStudents = [];
    let allSemesterStudents = [];
    let isCopiedMode = false;
    let lastCopiedSession = null;

    // Quick Mark All Present/Absent handlers (Right above Add Student bar)
    if (markAllPresentQuick) {
        markAllPresentQuick.addEventListener('click', function() {
            document.querySelectorAll('input[type="radio"][value="present"]').forEach(r => r.checked = true);
            currentStudents.forEach(s => s.status = 'present');
        });
    }

    if (markAllAbsentQuick) {
        markAllAbsentQuick.addEventListener('click', function() {
            document.querySelectorAll('input[type="radio"][value="absent"]').forEach(r => r.checked = true);
            currentStudents.forEach(s => s.status = 'absent');
        });
    }

    // Toggle Copy Panel
    if (toggleCopyPanelBtn) {
        toggleCopyPanelBtn.addEventListener('click', function() {
            if (copyAttendancePanel.style.display === 'none') {
                copyAttendancePanel.style.display = 'block';
                copyAttendancePanel.scrollIntoView({ behavior: 'smooth' });
                searchLecturesOnDate(copySearchDateInput.value);
            } else {
                copyAttendancePanel.style.display = 'none';
            }
        });
    }

    if (closeCopyPanelBtn) {
        closeCopyPanelBtn.addEventListener('click', function() {
            copyAttendancePanel.style.display = 'none';
        });
    }

    // Sync lecture date change with copy search date
    lectureDateInput.addEventListener('change', function() {
        if (this.value) {
            copySearchDateInput.value = this.value;
            searchLecturesOnDate(this.value);
        }
    });

    // Search lectures button
    searchLecturesBtn.addEventListener('click', function() {
        const date = copySearchDateInput.value;
        if (!date) {
            alert('Please select a date.');
            return;
        }
        searchLecturesOnDate(date);
    });

    // Auto-search on page load
    if (copySearchDateInput.value) {
        searchLecturesOnDate(copySearchDateInput.value);
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH LECTURES ON GIVEN DATE
    |--------------------------------------------------------------------------
    */
    function searchLecturesOnDate(date) {
        copySearchLoading.style.display = 'block';
        copyLecturesContainer.innerHTML = '';

        fetch("{{ route('faculty.attendance.sessions-on-date') }}?date=" + encodeURIComponent(date), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            copySearchLoading.style.display = 'none';
            const sessions = data.sessions || [];

            if (sessions.length === 0) {
                copyLecturesContainer.innerHTML = `
                    <div class="alert alert-warning py-2 px-3 mb-0 small">
                        <i class="bi bi-exclamation-triangle me-1"></i> No recorded lectures found for <strong>${escapeHtml(date)}</strong>. Try selecting another date above.
                    </div>
                `;
                return;
            }

            let html = `<div class="row g-2">`;
            sessions.forEach(session => {
                html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border shadow-xs p-3 position-relative" style="background: #ffffff; border-radius: 10px;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-primary text-white mb-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-book me-1"></i>${escapeHtml(session.subject)}
                                    </span>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">
                                        ${escapeHtml(session.lecture_name)}
                                    </h6>
                                </div>
                            </div>
                            <div class="small text-muted mb-2">
                                <div><i class="bi bi-person-fill text-secondary me-1"></i>Faculty: <strong>${escapeHtml(session.faculty)}</strong></div>
                                ${session.start_time ? `<div><i class="bi bi-clock text-secondary me-1"></i>Time: ${escapeHtml(session.start_time)} - ${escapeHtml(session.end_time || '')}</div>` : ''}
                            </div>
                            <div class="d-flex gap-1 mb-3 flex-wrap">
                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                                    ✓ ${session.present_count} Present
                                </span>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.72rem;">
                                    ✕ ${session.absent_count} Absent
                                </span>
                                <span class="badge bg-light text-muted border ms-auto" style="font-size: 0.72rem;">
                                    ${session.total_count} Total
                                </span>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary w-100 copy-session-btn"
                                    data-session='${JSON.stringify(session).replace(/'/g, "&apos;")}'>
                                <i class="bi bi-copy me-1"></i> Copy This Attendance
                            </button>
                        </div>
                    </div>
                `;
            });
            html += `</div>`;
            copyLecturesContainer.innerHTML = html;

            // Bind click handlers to copy buttons
            document.querySelectorAll('.copy-session-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const sessionData = JSON.parse(this.getAttribute('data-session').replace(/&apos;/g, "'"));
                    applyCopiedAttendance(sessionData);
                });
            });
        })
        .catch(err => {
            copySearchLoading.style.display = 'none';
            copyLecturesContainer.innerHTML = `
                <div class="alert alert-danger py-2 px-3 mb-0 small">
                    <i class="bi bi-x-circle me-1"></i> Failed to fetch lectures. Please try again.
                </div>
            `;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | APPLY COPIED ATTENDANCE (SHOW ONLY WHO ATTENDED PREVIOUS LECTURE)
    |--------------------------------------------------------------------------
    */
    function applyCopiedAttendance(sessionData) {
        lastCopiedSession = sessionData;
        isCopiedMode = true;

        // Auto select subject if only 1 option
        if (!subjectSelect.value) {
            if (subjectSelect.options.length === 2) {
                subjectSelect.selectedIndex = 1;
            } else {
                alert('Please select YOUR Subject from the dropdown first to apply this attendance.');
                subjectSelect.focus();
                return;
            }
        }

        // Auto fill time if empty
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput   = document.getElementById('end_time');
        if (!startTimeInput.value && sessionData.raw_start) {
            startTimeInput.value = sessionData.raw_start.substring(0, 5);
        }
        if (!endTimeInput.value && sessionData.raw_end) {
            endTimeInput.value = sessionData.raw_end.substring(0, 5);
        }

        if (copySearchDateInput.value) {
            lectureDateInput.value = copySearchDateInput.value;
        }

        // Fetch full semester students pool in background for the "+ Add student" dropdown
        fetchSemesterStudents(subjectSelect.value);

        // Filter ONLY the students who were PRESENT or LATE in the previous lecture
        const attendedStudents = (sessionData.students || []).filter(s => s.status === 'present' || s.status === 'late');

        if (attendedStudents.length === 0) {
            // If none marked present, use whatever students exist in that session
            currentStudents = (sessionData.students || []).map(s => ({
                id: s.student_id,
                enrollment_no: s.enrollment_no,
                first_name: s.name,
                last_name: '',
                semester: sessionData.semester || '5',
                status: 'present',
                remarks: s.remarks || `Copied from ${sessionData.subject}`
            }));
        } else {
            currentStudents = attendedStudents.map(s => ({
                id: s.student_id,
                enrollment_no: s.enrollment_no,
                first_name: s.name,
                last_name: '',
                semester: sessionData.semester || '5',
                status: s.status || 'present',
                remarks: s.remarks || `Copied from ${sessionData.subject}`
            }));
        }

        // Render table with ONLY attended students
        selectSubjectMessage.style.display = 'none';
        noStudents.style.display = 'none';
        studentCount.textContent = currentStudents.length + ' Student' + (currentStudents.length === 1 ? '' : 's');
        
        attendanceModeBadge.innerHTML = `
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                <i class="bi bi-check2-circle me-1"></i> Copied: Showing ${currentStudents.length} Attended Student(s) from Previous Lecture
            </span>
        `;
        loadAllSemesterStudentsBtn.style.display = 'inline-block';

        renderStudentTable(currentStudents);
        updateExtraStudentsDropdown();

        // Show success alert
        copiedSuccessAlert.style.setProperty('display', 'flex', 'important');
        copiedSuccessText.innerHTML = `
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            Copied attendance from <strong>${escapeHtml(sessionData.faculty)}'s ${escapeHtml(sessionData.subject)}</strong> lecture!
            <strong>${currentStudents.length}</strong> student(s) who attended are added. If another student arrives, use the "+ Add Student" bar below.
        `;

        studentTableContainer.scrollIntoView({ behavior: 'smooth' });
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH ALL SEMESTER STUDENTS (FOR ADD-STUDENT DROPDOWN)
    |--------------------------------------------------------------------------
    */
    function fetchSemesterStudents(subjectId) {
        fetch("{{ route('faculty.attendance.students') }}?subject_id=" + encodeURIComponent(subjectId), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            allSemesterStudents = data.students || [];
            updateExtraStudentsDropdown();
        })
        .catch(err => {});
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE THE "ADD EXTRA STUDENT" DROPDOWN (WITH SEARCH FILTER)
    |--------------------------------------------------------------------------
    */
    function updateExtraStudentsDropdown(filterQuery = '') {
        if (!extraStudentSelect) return;
        extraStudentSelect.innerHTML = '<option value="">-- Choose Student to Add --</option>';

        const currentIds = new Set(currentStudents.map(s => String(s.id)));
        let available = allSemesterStudents.filter(s => !currentIds.has(String(s.id)));

        if (filterQuery) {
            const q = filterQuery.toLowerCase().trim();
            available = available.filter(s => {
                const fullName = ((s.first_name || '') + ' ' + (s.last_name || '')).toLowerCase();
                const enroll   = (s.enrollment_no || '').toLowerCase();
                return fullName.includes(q) || enroll.includes(q);
            });
        }

        if (available.length === 0) {
            const opt = document.createElement('option');
            opt.disabled = true;
            opt.textContent = filterQuery ? 'No matching students found' : 'All semester students already added';
            extraStudentSelect.appendChild(opt);
            return;
        }

        available.forEach((s, idx) => {
            const name = ((s.first_name || '') + ' ' + (s.last_name || '')).trim();
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = `${s.enrollment_no ? s.enrollment_no + ' - ' : ''}${name} (Sem ${s.semester || '-'})`;
            // Auto select first match if searching
            if (filterQuery && idx === 0) {
                opt.selected = true;
            }
            extraStudentSelect.appendChild(opt);
        });
    }

    // Live search listener
    if (extraStudentSearch) {
        extraStudentSearch.addEventListener('input', function() {
            updateExtraStudentsDropdown(this.value);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ADD EXTRA STUDENT BUTTON HANDLER
    |--------------------------------------------------------------------------
    */
    if (addExtraStudentBtn) {
        addExtraStudentBtn.addEventListener('click', function() {
            const studentId = extraStudentSelect.value;
            if (!studentId) {
                alert('Please select a student from the dropdown first.');
                return;
            }

            const studentToAdd = allSemesterStudents.find(s => String(s.id) === String(studentId));
            if (!studentToAdd) return;

            // Add student as present
            currentStudents.push({
                id: studentToAdd.id,
                enrollment_no: studentToAdd.enrollment_no,
                first_name: studentToAdd.first_name,
                last_name: studentToAdd.last_name,
                semester: studentToAdd.semester,
                status: 'present',
                remarks: 'Late arrival / added manually'
            });

            studentCount.textContent = currentStudents.length + ' Student' + (currentStudents.length === 1 ? '' : 's');
            renderStudentTable(currentStudents);
            
            // Clear search and refresh dropdown
            if (extraStudentSearch) extraStudentSearch.value = '';
            updateExtraStudentsDropdown();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | BUTTON TO SHOW ALL SEMESTER STUDENTS
    |--------------------------------------------------------------------------
    */
    if (loadAllSemesterStudentsBtn) {
        loadAllSemesterStudentsBtn.addEventListener('click', function() {
            if (subjectSelect.value) {
                isCopiedMode = false;
                loadStudents(subjectSelect.value);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SUBJECT CHANGE HANDLER (FRESH LOAD)
    |--------------------------------------------------------------------------
    */
    subjectSelect.addEventListener('change', function () {
        const subjectId = this.value;
        if (!subjectId) {
            studentTableBody.innerHTML = '';
            studentTableContainer.style.display = 'none';
            noStudents.style.display = 'none';
            actionButtons.style.display = 'none';
            loading.style.display = 'none';
            selectSubjectMessage.style.display = 'block';
            studentCount.textContent = '0 Students';
            currentStudents = [];
            allSemesterStudents = [];
            return;
        }

        if (!isCopiedMode) {
            loadStudents(subjectId);
        } else {
            fetchSemesterStudents(subjectId);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | LOAD STUDENTS FUNCTION
    |--------------------------------------------------------------------------
    */
    function loadStudents(subjectId, callback) {
        selectSubjectMessage.style.display = 'none';
        studentTableContainer.style.display = 'none';
        noStudents.style.display = 'none';
        actionButtons.style.display = 'none';
        loading.style.display = 'block';

        fetch("{{ route('faculty.attendance.students') }}?subject_id=" + encodeURIComponent(subjectId), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Unable to load students.');
            return response.json();
        })
        .then(data => {
            loading.style.display = 'none';
            allSemesterStudents = data.students || [];
            currentStudents = [...allSemesterStudents];

            if (currentStudents.length === 0) {
                noStudents.style.display = 'block';
                studentCount.textContent = '0 Students';
                return;
            }

            studentCount.textContent = currentStudents.length + ' Students';
            attendanceModeBadge.innerHTML = `
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                    <i class="bi bi-people me-1"></i> All Students in Semester (${currentStudents.length})
                </span>
            `;
            loadAllSemesterStudentsBtn.style.display = 'none';

            renderStudentTable(currentStudents);
            updateExtraStudentsDropdown();

            if (typeof callback === 'function') {
                callback(currentStudents);
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            alert(error.message || 'Unable to load students.');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER STUDENT TABLE
    |--------------------------------------------------------------------------
    */
    function renderStudentTable(students) {
        studentTableBody.innerHTML = '';

        students.forEach(function (student, index) {
            const row = document.createElement('tr');
            const fullName = ((student.first_name || '') + ' ' + (student.last_name || '')).trim();
            const studentStatus = student.status || 'present';

            row.innerHTML = `
                <td class="text-center text-muted fw-semibold">
                    ${index + 1}
                </td>
                <td>
                    <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.85rem;">
                        ${escapeHtml(student.enrollment_no || '-')}
                    </span>
                </td>
                <td>
                    <strong class="text-dark">${escapeHtml(fullName || 'Student #' + student.id)}</strong>
                </td>
                <td>
                    <span class="badge bg-secondary-subtle text-secondary">
                        Sem ${escapeHtml(student.semester || '-')}
                    </span>
                </td>
                <td>
                    <input type="hidden" name="attendance[${index}][student_id]" value="${student.id}">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="attendance[${index}][status]" id="present_${student.id}" value="present" ${studentStatus === 'present' ? 'checked' : ''}>
                        <label class="btn btn-sm btn-outline-success" for="present_${student.id}">
                            <i class="bi bi-check-lg me-1"></i>Present
                        </label>

                        <input type="radio" class="btn-check" name="attendance[${index}][status]" id="absent_${student.id}" value="absent" ${studentStatus === 'absent' ? 'checked' : ''}>
                        <label class="btn btn-sm btn-outline-danger" for="absent_${student.id}">
                            <i class="bi bi-x-lg me-1"></i>Absent
                        </label>

                        <input type="radio" class="btn-check" name="attendance[${index}][status]" id="late_${student.id}" value="late" ${studentStatus === 'late' ? 'checked' : ''}>
                        <label class="btn btn-sm btn-outline-warning" for="late_${student.id}">
                            <i class="bi bi-clock me-1"></i>Late
                        </label>
                    </div>
                </td>
                <td>
                    <input type="text" name="attendance[${index}][remarks]" class="form-control form-control-sm" value="${escapeHtml(student.remarks || '')}" placeholder="Optional remark">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 remove-student-btn" data-index="${index}" title="Remove from this lecture">
                        <i class="bi bi-trash3"></i>
                    </button>
                </td>
            `;

            studentTableBody.appendChild(row);
        });

        // Bind remove student button
        document.querySelectorAll('.remove-student-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = parseInt(this.getAttribute('data-index'), 10);
                currentStudents.splice(idx, 1);
                studentCount.textContent = currentStudents.length + ' Student' + (currentStudents.length === 1 ? '' : 's');
                renderStudentTable(currentStudents);
                updateExtraStudentsDropdown();
            });
        });

        studentTableContainer.style.display = 'block';
        actionButtons.style.display = 'block';
    }

    /*
    |--------------------------------------------------------------------------
    | MARK ALL PRESENT / ABSENT
    |--------------------------------------------------------------------------
    */
    document.getElementById('markAllPresent').addEventListener('click', function () {
        document.querySelectorAll('input[type="radio"][value="present"]').forEach(r => r.checked = true);
    });

    document.getElementById('markAllAbsent').addEventListener('click', function () {
        document.querySelectorAll('input[type="radio"][value="absent"]').forEach(r => r.checked = true);
    });

    function escapeHtml(value) {
        if (!value) return '';
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }
});
</script>

@endsection