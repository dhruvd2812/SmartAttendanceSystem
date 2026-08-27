@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Manual Attendance
            </h2>

            <p class="text-muted mb-0">
                Manually mark attendance for your lecture.
            </p>
        </div>

        <a href="{{ route('faculty.attendance.index') }}"
           class="btn btn-secondary">

            ← Back to Attendance

        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <strong>Success!</strong>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Error!</strong>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

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


    <form method="POST"
          action="{{ route('faculty.attendance.manual.store') }}"
          id="attendanceForm">

        @csrf


        {{-- LECTURE INFORMATION --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    Lecture Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- SUBJECT --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Subject <span class="text-danger">*</span>
                        </label>

                        <select name="subject_id"
                                id="subject_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Select Subject --
                            </option>

                            @foreach($subjects as $subject)

                                <option value="{{ $subject->id }}">

                                    {{ $subject->name }}

                                    @if($subject->code)
                                        ({{ $subject->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- DATE --}}
                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Lecture Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="lecture_date"
                               class="form-control"
                               value="{{ old('lecture_date', date('Y-m-d')) }}"
                               required>

                    </div>


                    {{-- LECTURE NAME --}}
                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Lecture Name
                        </label>

                        <input type="text"
                               name="lecture_name"
                               class="form-control"
                               placeholder="Example: Unit 3">

                    </div>


                    {{-- START TIME --}}
                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Start Time
                        </label>

                        <input type="time"
                               name="start_time"
                               class="form-control">

                    </div>


                    {{-- END TIME --}}
                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            End Time
                        </label>

                        <input type="time"
                               name="end_time"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- STUDENTS --}}
        <div class="card shadow-sm">

            <div class="card-header
                        d-flex
                        justify-content-between
                        align-items-center">

                <h5 class="mb-0">
                    Student Attendance
                </h5>

                <span id="studentCount"
                      class="badge bg-primary">

                    0 Students

                </span>

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

                    <table class="table
                                  table-bordered
                                  table-hover
                                  align-middle">

                        <thead class="table-light">

                            <tr>

                                <th style="width:60px;">
                                    #
                                </th>

                                <th>
                                    Enrollment No
                                </th>

                                <th>
                                    Student Name
                                </th>

                                <th>
                                    Semester
                                </th>

                                <th>
                                    Attendance
                                </th>

                                <th>
                                    Remarks
                                </th>

                            </tr>

                        </thead>

                        <tbody id="studentTableBody">

                        </tbody>

                    </table>

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

                            Save Attendance

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const subjectSelect =
            document.getElementById('subject_id');

        const studentTableBody =
            document.getElementById(
                'studentTableBody'
            );

        const studentTableContainer =
            document.getElementById(
                'studentTableContainer'
            );

        const studentCount =
            document.getElementById(
                'studentCount'
            );

        const loading =
            document.getElementById(
                'loading'
            );

        const selectSubjectMessage =
            document.getElementById(
                'selectSubjectMessage'
            );

        const noStudents =
            document.getElementById(
                'noStudents'
            );

        const actionButtons =
            document.getElementById(
                'actionButtons'
            );


        /*
        |--------------------------------------------------------------------------
        | SUBJECT CHANGE
        |--------------------------------------------------------------------------
        */

        subjectSelect.addEventListener(
            'change',
            function () {

                const subjectId = this.value;


                /*
                 * Reset screen.
                 */

                studentTableBody.innerHTML = '';

                studentTableContainer.style.display =
                    'none';

                noStudents.style.display =
                    'none';

                actionButtons.style.display =
                    'none';

                loading.style.display =
                    'none';

                studentCount.textContent =
                    '0 Students';


                /*
                 * Nothing selected.
                 */

                if (!subjectId) {

                    selectSubjectMessage.style.display =
                        'block';

                    return;
                }


                selectSubjectMessage.style.display =
                    'none';

                loading.style.display =
                    'block';


                /*
                 * Get students.
                 */

                fetch(
                    "{{ route('faculty.attendance.students') }}?subject_id="
                    + encodeURIComponent(subjectId),

                    {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'
                        }
                    }
                )

                .then(function (response) {

                    if (!response.ok) {

                        throw new Error(
                            'Unable to load students.'
                        );
                    }

                    return response.json();

                })

                .then(function (data) {

                    loading.style.display =
                        'none';


                    const students =
                        data.students || [];


                    /*
                     * No students.
                     */

                    if (students.length === 0) {

                        noStudents.style.display =
                            'block';

                        return;
                    }


                    /*
                     * Student count.
                     */

                    studentCount.textContent =
                        students.length
                        + ' Students';


                    /*
                     * Create rows.
                     */

                    students.forEach(
                        function (
                            student,
                            index
                        ) {

                            const row =
                                document.createElement(
                                    'tr'
                                );


                            const fullName =
                                (
                                    student.first_name
                                    || ''
                                )
                                + ' '
                                +
                                (
                                    student.last_name
                                    || ''
                                );


                            row.innerHTML = `

                                <td>
                                    ${index + 1}
                                </td>


                                <td>
                                    <strong>
                                        ${escapeHtml(
                                            student.enrollment_no
                                            || '-'
                                        )}
                                    </strong>
                                </td>


                                <td>
                                    ${escapeHtml(
                                        fullName.trim()
                                    )}
                                </td>


                                <td>
                                    ${escapeHtml(
                                        student.semester
                                        || '-'
                                    )}
                                </td>


                                <td>

                                    <input
                                        type="hidden"
                                        name="attendance[${index}][student_id]"
                                        value="${student.id}"
                                    >


                                    <div
                                        class="btn-group"
                                        role="group"
                                    >


                                        {{-- PRESENT --}}

                                        <input
                                            type="radio"
                                            class="btn-check"
                                            name="attendance[${index}][status]"
                                            id="present_${student.id}"
                                            value="present"
                                            checked
                                        >

                                        <label
                                            class="btn btn-outline-success"
                                            for="present_${student.id}"
                                        >

                                            Present

                                        </label>


                                        {{-- ABSENT --}}

                                        <input
                                            type="radio"
                                            class="btn-check"
                                            name="attendance[${index}][status]"
                                            id="absent_${student.id}"
                                            value="absent"
                                        >

                                        <label
                                            class="btn btn-outline-danger"
                                            for="absent_${student.id}"
                                        >

                                            Absent

                                        </label>


                                        {{-- LATE --}}

                                        <input
                                            type="radio"
                                            class="btn-check"
                                            name="attendance[${index}][status]"
                                            id="late_${student.id}"
                                            value="late"
                                        >

                                        <label
                                            class="btn btn-outline-warning"
                                            for="late_${student.id}"
                                        >

                                            Late

                                        </label>

                                    </div>

                                </td>


                                <td>

                                    <input
                                        type="text"
                                        name="attendance[${index}][remarks]"
                                        class="form-control"
                                        placeholder="Optional"
                                    >

                                </td>

                            `;


                            studentTableBody.appendChild(
                                row
                            );

                        }
                    );


                    studentTableContainer.style.display =
                        'block';

                    actionButtons.style.display =
                        'block';

                })

                .catch(function (error) {

                    loading.style.display =
                        'none';

                    alert(
                        error.message
                        ||
                        'Unable to load students.'
                    );

                });

            }
        );


        /*
        |--------------------------------------------------------------------------
        | MARK ALL PRESENT
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('markAllPresent')
            .addEventListener(
                'click',
                function () {

                    document
                        .querySelectorAll(
                            'input[type="radio"][value="present"]'
                        )
                        .forEach(
                            function (radio) {

                                radio.checked =
                                    true;

                            }
                        );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | MARK ALL ABSENT
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('markAllAbsent')
            .addEventListener(
                'click',
                function () {

                    document
                        .querySelectorAll(
                            'input[type="radio"][value="absent"]'
                        )
                        .forEach(
                            function (radio) {

                                radio.checked =
                                    true;

                            }
                        );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | HTML ESCAPE
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {

            const div =
                document.createElement(
                    'div'
                );

            div.textContent =
                value;

            return div.innerHTML;
        }

    }
);

</script>

@endsection