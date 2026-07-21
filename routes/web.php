<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Student Management Routes
|--------------------------------------------------------------------------
*/

// Display All Students
Route::get('/students', [StudentController::class, 'index'])
    ->name('students.index');

// Show Add Student Form
Route::get('/students/create', [StudentController::class, 'create'])
    ->name('students.create');

// Store New Student
Route::post('/students', [StudentController::class, 'store'])
    ->name('students.store');

// Show Student Details
Route::get('/students/{student}', [StudentController::class, 'show'])
    ->name('students.show');

// Show Edit Student Form
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
    ->name('students.edit');

// Update Student Details
Route::put('/students/{student}', [StudentController::class, 'update'])
    ->name('students.update');

// Delete Student
Route::delete('/students/{student}', [StudentController::class, 'destroy'])
    ->name('students.destroy');