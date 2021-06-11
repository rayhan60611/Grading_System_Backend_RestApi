<?php

use App\Http\Controllers\ClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\MiscellaneousController;

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



//Miscellaneous Controllers
Route::get('/teacher-list' , [MiscellaneousController::class , 'teacherlist']);
Route::get('/class-list' , [MiscellaneousController::class , 'classlist']);
