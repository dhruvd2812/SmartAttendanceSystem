<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display Department Page
     */
    public function index()
    {
        return view('departments.index');
    }

    /**
     * Show Add Department Form
     */
    public function create()
    {
        //
    }

    /**
     * Store Department
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Edit Department
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update Department
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Delete Department
     */
    public function destroy($id)
    {
        //
    }
}