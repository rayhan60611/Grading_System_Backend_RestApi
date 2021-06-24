<?php

namespace App\Http\Controllers;

use App\Http\Resources\MiscellaneousTeacherResource;
use App\Http\Resources\MiscellaneousClassResource;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function teacherAssignSubject($id){
        try {
            $teacher_assign_subject_list = Subject::orderBy('id' , 'desc')->where('teacher_id' , $id)->get();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Assign subject Lists of this teacher',
                'data'  =>  $teacher_assign_subject_list
            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User'
            ] , 401);
        }

    }

    public function testList()
    {
        try {
            $test_list = Test::orderBy('id' , 'desc')->get();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Test Lists',
                'data'  => $test_list

            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User'
            ] , 401);
        }
       
    }

    public function teacherAvarageGrade($techer_id ,$subject_id)
    {
        try {
            // $pupilIndividualAvarageGrade = Result::orderBy('id' , 'desc')->avg('grade')->groupBy('pupil_id')->with('user')->get();
    
            $Teacher_Avarage_Grade = DB::table('results')
            ->join('users', 'users.id', '=', 'results.pupil_id')
            ->select('users.userid','users.fname','users.lname','users.id as user_id', DB::raw('AVG(grade) as AvaregeGrade'))
            ->where('teacher_id' , $techer_id)
            ->where('subject_id' , $subject_id)
            ->groupBy('pupil_id')
            ->get();

            return response()->json([
                'success'=> true,
                'message' => 'Display All The Test avagrage Result by Subject and teacher Avarage Grade',
                'data'  => $Teacher_Avarage_Grade

            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User',
            ] , 401);
        }
       
    }

}
