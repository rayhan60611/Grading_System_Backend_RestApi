<?php

use App\Http\Controllers\ClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\MiscellaneousController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\PupilController;
use App\Http\Controllers\AssignedClassController;



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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


//login & logout Crontrollers
Route::post('/login' , [UserController::class , 'login']);
Route::post('/logout' , [UserController::class , 'logout']);

//User Crontrollers
Route::resource('/users' , UserController::class);


//Class Crontrollers
Route::get('/class/index' , [ClassController::class , 'index']);
Route::get('/class/show/{name}' , [ClassController::class , 'show']);
Route::post('/class/create/store' , [ClassController::class , 'store']);
Route::post('/class/update/{id}' , [ClassController::class , 'update']);
Route::post('/class/delete/{id}' , [ClassController::class , 'delete']);


//Subject Crontrollers
Route::get('/subject/index' , [SubjectController::class , 'index']);
Route::get('/subject/show/{name}' , [SubjectController::class , 'show']);
Route::post('/subject/create/store' , [SubjectController::class , 'store']);
Route::post('/subject/update/{id}' , [SubjectController::class , 'update']);
Route::post('/subject/delete/{id}' , [SubjectController::class , 'delete']);


//Test Crontrollers
Route::get('/test/index' , [TestController::class , 'index']);
Route::get('/test/show/{name}' , [TestController::class , 'show']);
Route::post('/test/create/store' , [TestController::class , 'store']);
Route::post('/test/update/{id}' , [TestController::class , 'update']);
Route::post('/test/delete/{id}' , [TestController::class , 'delete']);


//Result Crontrollers
Route::get('/result/index' , [ResultController::class , 'index']);
Route::get('/result/avarage-grade-list' , [ResultController::class , 'avarageGradeList']);
Route::get('/result/show/{teacher_id}/{pupil_id}/{subject_id}' , [ResultController::class , 'show']);
Route::post('/result/create/store' , [ResultController::class , 'store']);
Route::post('/result/update/{id}' , [ResultController::class , 'update']);
Route::post('/result/delete/{id}' , [ResultController::class , 'delete']);
Route::post('/result/upload' , [ResultController::class , 'Upload']);


//Pupils Controllers

Route::get('/pupil/avarage-grade/{id}' , [PupilController::class , 'pupilIndividualAvarageGrade'])->middleware(['auth:api', 'pupil']);
Route::get('/pupil/subject-wise-test-grade/{id}/{subject_id}' , [PupilController::class , 'subjectWiseTestGrade'])->middleware(['auth:api', 'pupil']);


//AssignedClassController Crontrollers
Route::get('/AssignedClassController/index' , [AssignedClassController::class , 'index']);
// Route::get('/AssignedClassController/show/{name}' , [AssignedClassController::class , 'show']);
Route::post('/AssignedClassController/create/store' , [AssignedClassController::class , 'store']);
// Route::post('/AssignedClassController/update/{id}' , [AssignedClassController::class , 'update']);
// Route::post('/AssignedClassController/delete/{id}' , [AssignedClassController::class , 'delete']);

//Miscellaneous Controllers
Route::get('/teacher-list' , [MiscellaneousController::class , 'teacherlist']);
Route::get('/class-list' , [MiscellaneousController::class , 'classlist']);
Route::get('/teacher/assign/subject/{id}' , [MiscellaneousController::class , 'teacherAssignSubject']);
Route::get('/test-list' , [MiscellaneousController::class , 'testList']);

