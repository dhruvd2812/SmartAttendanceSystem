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
| AUTHENTICATION
|--------------------------------------------------------------------------
*/


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
|
| Public Student Registration
|
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
|
| Public Faculty Registration
|
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
| ADMIN REGISTRATION
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| Admin Registration has been completely removed.
|
| There is NO:
|
| /admin/register
| admin.register
| admin.register.store
|
| Admin accounts must be created manually/securely.
|
|--------------------------------------------------------------------------
*/


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
|
| Admin permissions:
|
| ✅ Admin Dashboard
| ✅ Faculty Dashboard View
| ✅ Student Dashboard View
| ✅ All Students
| ✅ Departments
| ✅ Faculties
| ✅ QR Generator
| ✅ Chatbot
|
| ❌ Public Admin Registration
|
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
    |
    | This route will be enabled after adminView()
    | is added to FacultyDashboardController.
    |
    */

    Route::get('/admin/view/faculty-dashboard', [
        FacultyDashboardController::class,
        'adminView'
    ])->name('admin.view.faculty.dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN → STUDENT DASHBOARD
    |--------------------------------------------------------------------------
    |
    | This route will be enabled after adminView()
    | is added to StudentDashboardController.
    |
    */

    Route::get('/admin/view/student-dashboard', [
        StudentDashboardController::class,
        'adminView'
    ])->name('admin.view.student.dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN → STUDENTS
    |--------------------------------------------------------------------------
    |
    | Admin can manage all students.
    |
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

});


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| FACULTY ROUTES
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
| Faculty permissions:
|
| ❌ Admin Dashboard
| ❌ Departments
| ❌ Faculties
| ❌ Admin Registration
|
| ✅ Faculty Dashboard
| ✅ Student Dashboard View
| ✅ Student List
| ✅ Student Details
| ✅ QR Generator
| ✅ Chatbot
|
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
    | FACULTY → STUDENT LIST
    |--------------------------------------------------------------------------
    |
    | Faculty can see students.
    |
    */

    Route::get('/faculty/students', [
        StudentController::class,
        'index'
    ])->name('faculty.students.index');


    /*
    |--------------------------------------------------------------------------
    | FACULTY → STUDENT DETAILS
    |--------------------------------------------------------------------------
    */

    Route::get('/faculty/students/{student}', [
        StudentController::class,
        'show'
    ])->name('faculty.students.show');


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

});


/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|
| Student permissions:
|
| ❌ Admin Dashboard
| ❌ Faculty Dashboard
| ❌ Faculty List
| ❌ Department List
| ❌ Other Student Pages
|
| ✅ Own Dashboard
| ✅ Own Profile
| ✅ Own Attendance
| ✅ QR Scanner
|
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
    | STUDENT ATTENDANCE
    |--------------------------------------------------------------------------
    */

    Route::get('/student/attendance', [
        StudentAttendanceController::class,
        'index'
    ])->name('student.attendance');


    /*
    |--------------------------------------------------------------------------
    | STUDENT QR SCANNER
    |--------------------------------------------------------------------------
    */

    Route::get('/student/scan-qr', [
        QrController::class,
        'scan'
    ])->name('student.scan-qr');

});