<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassResource;
use App\Models\MyClass;
use App\Models\User;
use App\Models\AssignedClassModel;
use App\Models\Subject;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Assign;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        try {
            $all_class_list = MyClass::orderBy('id', 'desc')->with('assignClass.pupil')->get();
            return response()->json([
                'success' => true,
                'message' => 'Display All The Classes List',
                'data'  => $all_class_list

            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 401);
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



            return response()->json([
                'success'=> true,
                'message' =>'Class Created Successfully!!!',
                'class_data' => $classes,
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'pupil_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $class = MyClass::findorfail($id);
            $isExistName = MyClass::where([
                ['id', "!=", $id],
                ['name', "=", $request->name]
            ])->first();

            if ($isExistName) {
                return response()->json([
                    'success' => false,
                    'message' => 'This name already exist to another class',
                ], 422);
            }

            if ($class->name !== $request->name) {
                $class->name = $request->name;
            }

            if (!User::find($request->pupil_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This pupil does no exist!',
                ], 404);
            }
            // check pupil already exist 
            $is_assigned = AssignedClassModel::where([
                ['pupil_id', "=", $request->pupil_id]
            ])->first();

            $assign_class = new AssignedClassModel;
            $assign_class->MyClass_id = $id;
            $assign_class->pupil_id = $request->pupil_id;

            DB::beginTransaction();
            $class->save();
            if ($is_assigned) {
                $is_assigned->delete();
            }
            $assign_class->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Class update and pupil assigned Successfully!!!',
                'data' => MyClass::orderBy('id', 'desc')->with('assignClass.pupil')->get(),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Class update and pupil assigning failed!!!',
                'error' => $th
            ], 400);
        }
    }
//    public function delete($id)
//    {   
//        try {

//            $class = MyClass::where('id', $id)->delete();
//            return response()->json([
//                'success'=> true,
//                'message' => 'Class Deleted Successfully!',
//            ] , 200);
//        } 
//        catch (\Throwable $th) {
//            return response()->json([
//                'success'=> false,
//                'message' => 'Somthing Went Wrong...!!!',
//            ] , 401);
//        }
//    }
// }


public function delete($id)
    {   $classDetails = MyClass::where('id', $id)->first();
        //    dd(count($classDetails));
        try {
            if ($classDetails === null) {
                return response()->json([
                    'success'=> false,
                    'message' => 'Nothing to Delete...!!!'
                ] , 404);
            }

            $AssignSubjects = Subject::where('class_name' ,$classDetails->name)->where('status' , 1)->get();
            // dd($AssignSubjects);
            $AssignPupils = AssignedClassModel::where('MyClass_id' ,$id)->get();
            // dd(count($AssignPupils));
            if(count($AssignSubjects) > 0)
            {
                foreach ($AssignSubjects as $AssignSubjects)
                    {
                        $AssignSubjects->delete();
                    }

                if(count($AssignPupils) > 0)
                {
                    foreach ($AssignPupils as $AssignPupils)
                    {
                        $AssignPupils->delete();
                    }
                }
                $classDetails->delete();
                return response()->json([
                    'success'=> true,
                    'message' => 'Class with its all Subject and Assign pupils are Deleted Successfully!',
                ] , 200);
            }
            else
            {
                $classDetails->delete();
                return response()->json([
                    'success'=> true,
                    'message' => 'Class Deleted Successfully!',
                ] , 200);
            }
            
            
      
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Somthing Went Wrong...!!!',
            ] , 401);
        }
    }
}