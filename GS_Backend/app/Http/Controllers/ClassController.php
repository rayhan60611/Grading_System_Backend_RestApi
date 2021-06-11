<?php

namespace App\Http\Controllers;
use App\Models\MyClass;
use App\Models\AssignedClassModel;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class ClassController extends Controller
{
    public function index()
    {
        try {
            $all_class_list = MyClass::orderBy('id' , 'desc')->get();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Classes List',
                'data'  => $all_class_list

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
            $class = MyClass::where('name', $name)->get();
            if(count($class) > 0){   
            return response()->json([
                'success'=> true,
                'message' => 'Display the specific Class',
                'data'  => $class
            ] , 302);
            }
            else{
                return response()->json([
                    'success'=> false,
                    'message' => 'No Class is available by that Name',
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

            'name' => 'required|unique:my_classes|string'
            // 'subject_id' => 'required|numeric', 
            // 'pupil_id' =>'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'errors' => $validator->errors()
                ], 422);
        }

        try {
                //send data to class table
                $classes = new MyClass;
                $classes->name = $request->name;
                $classes->save();

                // //send data to AssignedClassModel table
                // $classid = MyClass::orderBy('id' ,'desc')->where('name' , $request->name)->first();
                // $assigend_classes = new AssignedClassModel;
                // $assigend_classes->MyClass_id = $classid->id;
                // $assigend_classes->subject_id = $request->subject_id;
                // $assigend_classes->pupil_id = $request->pupil_id;
                // $assigend_classes->save();

            return response()->json([
                'success'=> true,
                'message' =>'Class Created Successfully!!!',
                'class_data' => $classes,
                // 'assigend_classes' => $assigend_classes
                ], 201);
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

        'name' => 'required|unique:my_classes|string'
       ]);

       if($validator->fails()){
           return response()->json([
               'success'=> false,
               'errors' => $validator->errors()
               ], 422);
       }

       try {
            $class = MyClass::findorfail($id);
            $class->name = $request->name;
            $class->save();
   
           return response()->json([
               'success'=> true,
               'message' =>'Class Data Updated Successfully!!!',
               'data' => $class
               ], 200);
          
       } 
       catch (\Throwable $th) {
           return response()->json([
               'success'=> false,
               'message' =>'Class Data Update failed!!!'
               ], 401);
       }
   }

   public function delete($id)
   {   
       try {
           $class = MyClass::where('id', $id)->delete();
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
