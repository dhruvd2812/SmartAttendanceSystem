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
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');


/*
|--------------------------------------------------------------------------
| Student Registration
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');


/*
|--------------------------------------------------------------------------
| Faculty Registration
|--------------------------------------------------------------------------
*/

Route::get('/faculty/register', [AuthController::class, 'showFacultyRegister'])
    ->name('faculty.register');

Route::post('/faculty/register', [AuthController::class, 'facultyRegister'])
    ->name('faculty.register.store');


/*
|--------------------------------------------------------------------------
| Admin Registration
|--------------------------------------------------------------------------
*/

Route::get('/admin/register', [AuthController::class, 'showAdminRegister'])
    ->name('admin.register');

Route::post('/admin/register', [AuthController::class, 'adminRegister'])
    ->name('admin.register.store');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
|
| Admin has full system access.
|
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Admin Students
    |--------------------------------------------------------------------------
    */

    Route::resource('/admin/students', StudentController::class)
        ->names('admin.students');


    /*
    |--------------------------------------------------------------------------
    | Departments
    |--------------------------------------------------------------------------
    */

    Route::resource('/departments', DepartmentController::class);


    /*
    |--------------------------------------------------------------------------
    | Faculties
    |--------------------------------------------------------------------------
    */

    Route::resource('/faculties', FacultyController::class);


    /*
    |--------------------------------------------------------------------------
    | Admin QR Generator
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/qr-generator', [QrController::class, 'index'])
        ->name('admin.qr.index');

    Route::post('/admin/qr-generator', [QrController::class, 'generate'])
        ->name('admin.qr.generate');


    /*
    |--------------------------------------------------------------------------
    | Admin Chatbot
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/chatbot', [ChatbotController::class, 'index'])
        ->name('admin.chatbot.index');

    Route::post('/admin/chatbot/message', [ChatbotController::class, 'message'])
        ->name('admin.chatbot.message');
});


/*
|--------------------------------------------------------------------------
| FACULTY ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:faculty'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Faculty Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/faculty/dashboard', [FacultyDashboardController::class, 'index'])
        ->name('faculty.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Faculty Students
    |--------------------------------------------------------------------------
    */

    Route::resource('/faculty/students', StudentController::class)
        ->names('faculty.students');


    /*
    |--------------------------------------------------------------------------
    | Faculty QR Generator
    |--------------------------------------------------------------------------
    */

    Route::get('/faculty/qr-generator', [QrController::class, 'index'])
        ->name('faculty.qr.index');

    Route::post('/faculty/qr-generator', [QrController::class, 'generate'])
        ->name('faculty.qr.generate');


    /*
    |--------------------------------------------------------------------------
    | Faculty Chatbot
    |--------------------------------------------------------------------------
    */

    Route::get('/faculty/chatbot', [ChatbotController::class, 'index'])
        ->name('faculty.chatbot.index');

    Route::post('/faculty/chatbot/message', [ChatbotController::class, 'message'])
        ->name('faculty.chatbot.message');
});


/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Student Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Student Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/student/profile', [StudentDashboardController::class, 'profile'])
        ->name('student.profile');


    /*
    |--------------------------------------------------------------------------
    | Student Attendance
    |--------------------------------------------------------------------------
    */

    Route::get('/student/attendance', [StudentAttendanceController::class, 'index'])
        ->name('student.attendance');


    /*
    |--------------------------------------------------------------------------
    | Student QR Scanner
    |--------------------------------------------------------------------------
    */

    Route::get('/student/scan-qr', [QrController::class, 'scan'])
        ->name('student.scan-qr');
});