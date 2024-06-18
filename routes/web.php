<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\usercController;



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
    return view('welcome');
});

Route::get('logout', 'Auth\LoginController@logout');



Route::get('role', [roleController::class, 'index'])->name('role.index');
Route::get('role/create',[roleController::class,'create']);
Route::post('role/store', [roleController::class, 'store'])->name('role.store');
Route::get('role/{id}/edit', [roleController::class, 'edit'])->name('role.edit');
Route::put('role/{id}/update', [roleController::class, 'update'])->name('role.update');
Route::get('pagination-role', [roleController::class, 'page'])->name('page');
Route::get('role/{id}/deleted',[roleController::class, 'deleted'])->name('deleted');


    
Route::get('user', [userController::class, 'index'])->name('user.index');
Route::get('user/create',[userController::class,'create']);
Route::post('user/store', [userController::class, 'store'])->name('user.store');
Route::get('user/{id}/edit', [userController::class, 'edit'])->name('user.edit');
Route::put('user/{id}/update', [userController::class, 'update'])->name('user.update');
Route::get('pagination-user', [userController::class, 'page'])->name('page');
Route::get('user/{id}/deleted',[userController::class, 'deleted'])->name('deleted');


   
    Auth::routes();
    Route::get('teacher', [TeacherAuthController::class, 'index']);
    Route::get('pagination-teacher', [TeacherAuthController::class, 'page']);
    
    Route::get('student', [StudentAuthController::class, 'index']);
    Route::get('pagination-student', [StudentAuthController::class, 'page']);



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
