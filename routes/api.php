<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\StudentCourseController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get("category","API\\CategoryController@index");

Route::post('/student/login', [StudentAuthController::class, 'login']);
Route::post('/student/register', [StudentAuthController::class, 'register']);

Route::post('/teacher/login', [TeacherAuthController::class, 'login']);
Route::post('/teacher/register', [TeacherAuthController::class, 'register']);

Route::group(['middleware'=>'auth:teacher'],function(){
    Route::post('/teacher/add/course', [CourseController::class, 'addCourse'])->name('add_course');
    Route::post('/teacher/add/video', [VideoController::class, 'uploadVideo'])->name('add_video');
});
Route::get('/searchCource/{categoryId}', 'CourseController@searchCoursesbyCategoryId')->name('search');

Route::get('/course/{courseId}', [CourseController::class, 'getInfoCoursebyId']);
    
Route::get('/courses', [CourseController::class, 'getAllCourses']);
    
Route::put('/students/{id}', [StudentAuthController::class, 'update']);
Route::put('/teachers/{id}', [TeacherAuthController::class, 'update']);

Route::post('/students/{id}/add-money', [StudentAuthController::class, 'addMoneyToWallet']);
Route::post('/teachers/{id}/add-money', [TeacherAuthController::class, 'addMoneyToWallet']);


Route::post('/courses/{studentId}/{courseId}/subscribe', [StudentCourseController::class, 'subscribeToCourse']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', function(Request $request) {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        // Respond with a JSON message
        return response()->json(['message' => 'You have been successfully logged out!'], 200);
    });
});
Route::get('/teachers/search', 'TeacherAuthController@searchTeacherByFullName');
Route::get('/teachers/full-names', 'TeacherAuthController@getAllTeacherFullNames');
Route::get('/teachers/{teacherId}', 'TeacherAuthController@getInformationTeacherById');


Route::post('/students/{studentId}/transfer-to-teacher/{teacherId}', 'StudentWalletController@transferMoneyToTeacher');

Route::get('/students/{studentId}/wallet-value', 'StudentWalletController@getStudentWalletValue');
Route::get('/teachers/{teacherId}/wallet-value', 'TeacherWalletController@getStudentWalletValue');

Route::get('/test', function() {
    if(auth('teacher')->check() || auth('student')->check() )
        return response()->json(['status' => "passed"], 200);


    return response()->json(['status' => "Not Authorized"], 401);
});
