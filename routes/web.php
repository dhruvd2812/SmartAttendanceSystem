<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
<<<<<<< Updated upstream

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/
=======
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceSessionController;
>>>>>>> Stashed changes

Route::get('/', function () {
    return view('welcome');
});

// ===========================
// Department Routes
<<<<<<< Updated upstream
Route::get('/departments', [DepartmentController::class, 'index']);
Route::get('/departments/create', [DepartmentController::class, 'create']);
Route::post('/departments', [DepartmentController::class, 'store']);
=======
// ===========================
Route::get('/departments', [DepartmentController::class, 'index'])
    ->name('departments.index');

Route::get('/departments/create', [DepartmentController::class, 'create'])
    ->name('departments.create');

Route::post('/departments', [DepartmentController::class, 'store'])
    ->name('departments.store');

Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])
    ->name('departments.edit');

Route::put('/departments/{department}', [DepartmentController::class, 'update'])
    ->name('departments.update');

Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])
    ->name('departments.destroy');

// ===========================
// Student Routes
// ===========================
Route::resource('students', StudentController::class);

// ===========================
// Attendance Session Routes
// ===========================
Route::get('/attendance-sessions', [AttendanceSessionController::class, 'index'])
    ->name('attendance-sessions.index');

Route::get('/attendance-sessions/create', [AttendanceSessionController::class, 'create'])
    ->name('attendance-sessions.create');

Route::post('/attendance-sessions', [AttendanceSessionController::class, 'store'])
    ->name('attendance-sessions.store');
>>>>>>> Stashed changes
