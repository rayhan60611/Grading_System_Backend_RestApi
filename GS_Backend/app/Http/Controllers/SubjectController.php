<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        try {
            $all_subject_list = Subject::orderBy('id' , 'desc')->get();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Subject List',
                'data'  => $all_subject_list

            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User',
            ] , 401);
        }
       
    }


    public function show($name)
    {
        try {
            $subject = Subject::where('name', $name)->get();
            if(count($subject) > 0){   
            return response()->json([
                'success'=> true,
                'message' => 'Display the specific Subject by Name',
                'data'  => $subject
            ] , 302);
            }
            else{
                return response()->json([
                    'success'=> false,
                    'message' => 'No subject is available by that Name',
                ] , 404);
            }
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User !! Access Restricted!',
            ] , 401);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[

            // 'subject_class' =>'unique:subjects|string',
            'name' => 'required|string',
            'teacher_id' => 'required|numeric', 
            // 'status' =>'required|numeric',
            'class_name' =>'required|string'
            
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'errors' => $validator->errors()
                ], 422);
        }

        $subject_class = $request->name.'-'.$request->class_name;
        
        try {
             $db_subject_class = Subject::where('subject_class' , $subject_class)->get();
                if(count($db_subject_class) > 0){

                    return response()->json([
                        'success'=> false,
                        'message' =>'This Subject Class already taken!!! Try a New One'
                        ], 422);
                }
                else
                {
                    $subject = new Subject;
                    $subject->subject_class = $subject_class;
                    $subject->name = $request->name;
                    $subject->teacher_id = $request->teacher_id;
                    $subject->status = 1;
                    $subject->class_name = $request->class_name;
                    $subject->save();
                return response()->json([
                    'success'=> true,
                    'message' =>'Subject Created Successfully!!!',
                    'data' => $subject,
                    ], 201);

                }

        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' =>'Something Went Worng...While creating the class!!!'
                ], 400);
        }
        
    }


   public function update(Request $request, $id)
   {
    $validator = Validator::make($request->all(),[

        // 'subject_class' =>'unique:subjects|string',
        'name' => 'string',
        'teacher_id' => 'numeric', 
        // 'status' =>'required|numeric',
        'class_name' =>'string'
        
    ]);

    if($validator->fails()){
        return response()->json([
            'success'=> false,
            'errors' => $validator->errors()
            ], 422);
    }

    $subject_class = $request->name.'-'.$request->class_name;
       try {
            $subject = Subject::findorfail($id);
            $specific_subject_data= DB::table('subjects')->select('subject_class' , 'name','teacher_id' ,'status','class_name')->where('id' , $id)->first();
             //dd($subject_class === $specific_subject_data->subject_class);
            if($subject_class === $specific_subject_data->subject_class){
                $subject->teacher_id = $request->teacher_id;
                $subject->save();
                return response()->json([
                    'success'=> true,
                    'message' =>'Subject Updated Successfully!!!',
                    'data' => $subject,
                    ], 204);

                // return response()->json([
                //     'success'=> false,
                //     'message' =>'Nothing To Change!!!'
                //     ], 204);
          
            }
            else
            {
                // $db_subject_class= DB::table('subjects')->select('subject_class')->where('subject_class' , $subject_class)->first();
                $db_subject_class = Subject::where('subject_class' , $subject_class)->get();
                dd( $db_subject_class);
                if(count($db_subject_class) > 0){

                    return response()->json([
                        'success'=> false,
                        'message' =>'This Subject Class already taken!!! Try a New One'
                        ], 422);
                }

                else{
                    $subject->subject_class = $subject_class;
                    $subject->name = $request->name;
                    $subject->teacher_id = $request->teacher_id;
                    $subject->class_name = $request->class_name;
                    $subject->save();

                return response()->json([
                'success'=> true,
                'message' =>'Subject Updated Successfully!!!',
                'data' => $subject,
                ], 204);

                }
        

            }       
          
       } 
       catch (\Throwable $th) {
           return response()->json([
               'success'=> false,
               'message' =>'Subject Data Update failed!!!'
               ], 401);
       }
   }

   public function delete($id)
   {   
       try {
           $class = Subject::where('id', $id)->delete();
           return response()->json([
               'success'=> true,
               'message' => 'Class Deleted Successfully!',
           ] , 200);
       } 
       catch (\Throwable $th) {
           return response()->json([
               'success'=> false,
               'message' => 'Somthing Went Wrong...!!!',
           ] , 401);
       }
   }
}
