<?php

namespace App\Http\Controllers;

use App\Http\Resources\MiscellaneousTeacherResource;
use App\Http\Resources\MiscellaneousClassResource;
use App\Models\MyClass;
use App\Models\User;
use Illuminate\Http\Request;

class MiscellaneousController extends Controller
{
    public function teacherlist()
    {
        try {
            $teacher_list = User::orderBy('id' , 'desc')->where('role' , 'teacher')->get();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Teacher Lists',
                'data'  => MiscellaneousTeacherResource::collection($teacher_list)

            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User'
            ] , 401);
        }
       
    } 

    public function classlist()
    {
        try {
            $class_list = MyClass::all();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Class Lists',
                'data'  => MiscellaneousClassResource::collection($class_list)

            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User'
            ] , 401);
        }
       
    } 

}
