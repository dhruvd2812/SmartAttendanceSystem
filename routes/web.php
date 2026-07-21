<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/departments', function () {
    return "Department Module Working Successfully!";
});