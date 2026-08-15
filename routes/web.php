<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Departments
    |--------------------------------------------------------------------------
    */

    Route::resource('departments', DepartmentController::class);


    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    Route::resource('students', StudentController::class);


    /*
    |--------------------------------------------------------------------------
    | Faculties
    |--------------------------------------------------------------------------
    */

    Route::resource('faculties', FacultyController::class);


    /*
    |--------------------------------------------------------------------------
    | QR Generator
    |--------------------------------------------------------------------------
    */

    Route::get('/qr-generator', [QrController::class, 'index'])
        ->name('qr.index');

    Route::post('/qr-generator', [QrController::class, 'generate'])
        ->name('qr.generate');


    /*
    |--------------------------------------------------------------------------
    | AI Chatbot
    |--------------------------------------------------------------------------
    */

    Route::get('/chatbot', [ChatbotController::class, 'index'])
        ->name('chatbot.index');

    Route::post('/chatbot/message', [ChatbotController::class, 'message'])
        ->name('chatbot.message');

});