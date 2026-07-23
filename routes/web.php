<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Department Routes
Route::get('/departments', [DepartmentController::class, 'index']);
Route::get('/departments/create', [DepartmentController::class, 'create']);
Route::post('/departments', [DepartmentController::class, 'store']);