<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\AssignStudentsController;

use App\Models\AssignStudentToTeacher;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	$teachers = AssignStudentToTeacher::with('subject','teacher')->get();
    return view('index',compact('teachers'));
});

Route::resource('subjects-crud',SubjectsController::class);
Route::resource('students-crud',StudentsController::class);
Route::resource('teachers-crud',TeachersController::class);
Route::resource('assign-student-crud',AssignStudentsController::class);
