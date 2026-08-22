<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\FacultyDashboardController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\StudentTimetableController;
use App\Http\Controllers\StudentNoticeController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\SubjectController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', [
    AuthController::class,
    'showLogin'
])->name('login');

Route::post('/login', [
    AuthController::class,
    'login'
])->name('login.store');


/*
|--------------------------------------------------------------------------
| STUDENT REGISTRATION
|--------------------------------------------------------------------------
*/

Route::get('/register', [
    AuthController::class,
    'showRegister'
])->name('register');

Route::post('/register', [
    AuthController::class,
    'register'
])->name('register.store');


/*
|--------------------------------------------------------------------------
| FACULTY REGISTRATION
|--------------------------------------------------------------------------
*/

Route::get('/faculty/register', [
    AuthController::class,
    'showFacultyRegister'
])->name('faculty.register');

Route::post('/faculty/register', [
    AuthController::class,
    'facultyRegister'
])->name('faculty.register.store');


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [
    AuthController::class,
    'logout'
])->name('logout');


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN → FACULTY DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/view/faculty-dashboard', [
        FacultyDashboardController::class,
        'adminView'
    ])->name('admin.view.faculty.dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN → STUDENT DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/view/student-dashboard', [
        StudentDashboardController::class,
        'adminView'
    ])->name('admin.view.student.dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN → STUDENTS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/admin/students',
        StudentController::class
    )->names('admin.students');


    /*
    |--------------------------------------------------------------------------
    | ADMIN → DEPARTMENTS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/departments',
        DepartmentController::class
    );


    /*
    |--------------------------------------------------------------------------
    | ADMIN → FACULTIES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/faculties',
        FacultyController::class
    );


    /*
    |--------------------------------------------------------------------------
    | ADMIN → QR GENERATOR
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/qr-generator', [
        QrController::class,
        'index'
    ])->name('admin.qr.index');

    Route::post('/admin/qr-generator', [
        QrController::class,
        'generate'
    ])->name('admin.qr.generate');


    /*
    |--------------------------------------------------------------------------
    | ADMIN → CHATBOT
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/chatbot', [
        ChatbotController::class,
        'index'
    ])->name('admin.chatbot.index');

    Route::post('/admin/chatbot/message', [
        ChatbotController::class,
        'message'
    ])->name('admin.chatbot.message');


    /*
    |--------------------------------------------------------------------------
    | ADMIN → NOTICES
    |--------------------------------------------------------------------------
    */

    Route::get('/notices', [
        NoticeController::class,
        'index'
    ])->name('notices.index');

    Route::get('/notices/create', [
        NoticeController::class,
        'create'
    ])->name('notices.create');

    Route::post('/notices', [
        NoticeController::class,
        'store'
    ])->name('notices.store');

    Route::delete('/notices/{notice}', [
        NoticeController::class,
        'destroy'
    ])->name('notices.destroy');

});


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| FACULTY ROUTES
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:faculty'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | FACULTY DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/faculty/dashboard', [
        FacultyDashboardController::class,
        'index'
    ])->name('faculty.dashboard');

    Route::resource(
        '/faculty/subjects',
        SubjectController::class
    )->except(['show', 'create'])->names('faculty.subjects');


    /*
    |--------------------------------------------------------------------------
    | FACULTY → STUDENT DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/faculty/view/student-dashboard', [
        StudentDashboardController::class,
        'facultyView'
    ])->name('faculty.view.student.dashboard');


    /*
    |--------------------------------------------------------------------------
    | FACULTY → STUDENTS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/faculty/students',
        StudentController::class
    )->names('faculty.students');


    /*
    |--------------------------------------------------------------------------
    | FACULTY → QR GENERATOR
    |--------------------------------------------------------------------------
    */

    Route::get('/faculty/qr-generator', [
        QrController::class,
        'index'
    ])->name('faculty.qr.index');

    Route::post('/faculty/qr-generator', [
        QrController::class,
        'generate'
    ])->name('faculty.qr.generate');


    /*
    |--------------------------------------------------------------------------
    | FACULTY → CHATBOT
    |--------------------------------------------------------------------------
    */

    Route::get('/faculty/chatbot', [
        ChatbotController::class,
        'index'
    ])->name('faculty.chatbot.index');

    Route::post('/faculty/chatbot/message', [
        ChatbotController::class,
        'message'
    ])->name('faculty.chatbot.message');


    /*
    |--------------------------------------------------------------------------
    | FACULTY → NOTICES
    |--------------------------------------------------------------------------
    */

    Route::get('/notices', [
        NoticeController::class,
        'index'
    ])->name('notices.index');

    Route::get('/notices/create', [
        NoticeController::class,
        'create'
    ])->name('notices.create');

    Route::post('/notices', [
        NoticeController::class,
        'store'
    ])->name('notices.store');

    Route::delete('/notices/{notice}', [
        NoticeController::class,
        'destroy'
    ])->name('notices.destroy');

});


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:student'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | STUDENT DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/student/dashboard', [
        StudentDashboardController::class,
        'index'
    ])->name('student.dashboard');


    /*
    |--------------------------------------------------------------------------
    | STUDENT PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/student/profile', [
        StudentDashboardController::class,
        'profile'
    ])->name('student.profile');


    /*
    |--------------------------------------------------------------------------
    | STUDENT → MY SUBJECTS
    |--------------------------------------------------------------------------
    */

    Route::get('/student/subjects', [
        StudentSubjectController::class,
        'index'
    ])->name('student.subjects');


    /*
    |--------------------------------------------------------------------------
    | STUDENT → TIMETABLE
    |--------------------------------------------------------------------------
    */

    Route::get('/student/timetable', [
        StudentTimetableController::class,
        'index'
    ])->name('student.timetable');


    /*
    |--------------------------------------------------------------------------
    | STUDENT → NOTICES
    |--------------------------------------------------------------------------
    */

    Route::get('/student/notices', [
        StudentNoticeController::class,
        'index'
    ])->name('student.notices');


    /*
    |--------------------------------------------------------------------------
    | STUDENT → MY ATTENDANCE
    |--------------------------------------------------------------------------
    */

    Route::get('/student/attendance', [
        StudentAttendanceController::class,
        'index'
    ])->name('student.attendance');


    /*
    |--------------------------------------------------------------------------
    | STUDENT → ATTENDANCE HISTORY
    |--------------------------------------------------------------------------
    */

    Route::get('/student/attendance/history', [
        StudentAttendanceController::class,
        'history'
    ])->name('student.attendance.history');


    /*
    |--------------------------------------------------------------------------
    | STUDENT → SCAN QR CODE
    |--------------------------------------------------------------------------
    */

    Route::get('/student/scan-qr', [
        QrController::class,
        'scan'
    ])->name('student.scan-qr');

});
