<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassResource;
use App\Models\MyClass;
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
    // public function index()
    // {
    //     try {
    //         $all_class_list = MyClass::orderBy('id' , 'desc')->get();
    //         return response()->json([
    //             'success'=> true,
    //             'message' => 'Display All The Classes List',
    //             'data'  => $all_class_list

    //         ] , 200);
    //     } 
    //     catch (\Throwable $th) {
    //         return response()->json([
    //             'success'=> false,
    //             'message' => 'Unauthorized User',
    //         ] , 401);
    //     }
       
    // }

    public function index()
    {
        try {
            $Myclass = DB::table('my_classes')
            // ->rightJoin('assigned_class_models', 'assigned_class_models.MyClass_id', '=','my_classes.id', )
            ->select('my_classes.name as class_name','my_classes.id as class_id')
            ->orderBy('class_id' , 'ASC')
            ->get();

            $Assigned_class = DB::table('my_classes')->distinct('id')
            ->rightjoin('assigned_class_models', 'assigned_class_models.MyClass_id', '=','my_classes.id', )
            ->select('my_classes.name as class_name','my_classes.id as class_id')
            ->orderBy('class_id' , 'ASC')
            ->get();

            // $result = $Myclass->diff($Assigned_class);

            $class_array = [];
            foreach($Myclass as $key => $value){
                $class_array[$key] =  $value;
            }

            $Assigned_class_array = [];
            foreach($Assigned_class as $key1 => $value1){
                $Assigned_class_array[$key1] = $value1;
            }

            //   $result = array_intersect( $class_array , $Assigned_class_array);
            //  dd($Assigned_class_array);
            // $Assigned_class = AssignedClassModel::get();
            // if ($Myclass->id ) {
            //     # code...
            // }
            
            // $class_list = DB::table('assigned_class_models')
            // ->join('users', 'users.id', '=', 'assigned_class_models.pupil_id')
            // ->join('my_classes', 'my_classes.id', '=', 'assigned_class_models.MyClass_id')
            // // ->select('users.userid','users.fname','users.lname','users.id as user_id','my_classes.name as class_name','my_classes.id as class_id')
            // ->select('users.userid','users.fname','users.lname','users.id as user_id','my_classes.name as class_name','my_classes.id as class_id')
            // // ->orderBy('assigned_class_models.MyClass_id')
            // // ->groupBy('assigned_class_models.MyClass_id')
            // ->get();

            //  $groupby_class_list = $class_list->groupBy('my_classes.id');

            //  $class_list = DB::table('my_classes')
            // ->join('assigned_class_models',  'users.id' , '=', 'assigned_class_models.pupil_id')
            // ->join('my_classes', 'my_classes.id', '=', 'assigned_class_models.MyClass_id')
            // ->select('users.userid','users.fname','users.lname','users.id as user_id','my_classes.name as class_name','my_classes.id as class_id')
            // ->get();
        // else{
            $class_list =AssignedClassModel::With(['User','MyClass'])->get();
            $class_list2 = $class_list->groupBy('MyClass_id');
            // $class_list3 = ClassResource::collection($class_list)->groupBy('myclass_name');
            // $class_list4 = $class_list3->groupBy('myclass_id');
            // echo var_dump($class_list3);

            //   $class_list =AssignedClassModel::With(['User','MyClass'])->groupBy('MyClass_id')->get();
             
            
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Pupil list Group by Class',
                'data'  =>$class_list2,
                // 'class'=>$result,
                'classArray'=>$class_array,
                'classAssignedArry'=>$Assigned_class_array
            

            ] , 200);
        } 
   
    //  } 
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

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(),[

    //         'name' => 'required|unique:my_classes|string'
    //         // 'subject_id' => 'required|numeric', 
    //         // 'pupil_id' =>'required|numeric'
    //     ]);

    //     if($validator->fails()){
    //         return response()->json([
    //             'success'=> false,
    //             'errors' => $validator->errors()
    //             ], 422);
    //     }

    //     try {
    //             //send data to class table
    //             $classes = new MyClass;
    //             $classes->name = $request->name;
    //             $classes->save();

    //             // //send data to AssignedClassModel table
    //             // $classid = MyClass::orderBy('id' ,'desc')->where('name' , $request->name)->first();
    //             // $assigend_classes = new AssignedClassModel;
    //             // $assigend_classes->MyClass_id = $classid->id;
    //             // $assigend_classes->subject_id = $request->subject_id;
    //             // $assigend_classes->pupil_id = $request->pupil_id;
    //             // $assigend_classes->save();

    //         return response()->json([
    //             'success'=> true,
    //             'message' =>'Class Created Successfully!!!',
    //             'class_data' => $classes,
    //             // 'assigend_classes' => $assigend_classes
    //             ], 201);
    //     } 
    //     catch (\Throwable $th) {
    //         return response()->json([
    //             'success'=> false,
    //             'message' =>'Something Went Worng...While creating the class!!!'
    //             ], 400);
    //     }
        
    // }

      public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'name' => 'required|unique:my_classes|string',
            'MyClass_id' => 'required|numeric',
            'pupil_id' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'errors' => $validator->errors()
                ], 422);
        }

        try {

            $AssignedPupilCheckValue = AssignedClassModel::orderBy('id' ,'desc')->where('pupil_id' ,$request->pupil_id)->get();
            if(count($AssignedPupilCheckValue) > 0)
            {
                $AssignedClassCheck = AssignedClassModel::where('id' , $AssignedPupilCheckValue[0]->id)->get();
                if($AssignedClassCheck[0]->MyClass_id == $request->MyClass_id )
                {
                    return response()->json([
                        'success'=> false,
                        'message' =>'Nothing to Change Pupil Already exists in the class!!!',
                    ], 400);
                }
                else
                {
                    $AssignedClassCheck[0]->MyClass_id = $request->MyClass_id;
                    $AssignedClassCheck[0]->save();
                    return response()->json([
                        'success'=> true,
                        'message' =>'Pupil is Assigned to a new class And disassign form the previous One!!!',
                        'data' => $AssignedClassCheck,
                    ], 201);

                }
                
            }

            else
                { //send data to class table
                    $classes = new MyClass;
                    $classes->name = $request->name;
                    $classes->save();

                    $AssignedClassModel = new AssignedClassModel;
                    $AssignedClassModel->MyClass_id = $request->MyClass_id;
                    $AssignedClassModel->pupil_id = $request->pupil_id;
                    $AssignedClassModel->save();
                    return response()->json([
                        'success'=> true,
                        'message' =>'Class and Assigned Class Created Successfully!!!',
                        'data' => $AssignedClassModel,
                        'class_data' => $classes,
                    ], 201);
                }     
               

                // //send data to AssignedClassModel table
                // $classid = MyClass::orderBy('id' ,'desc')->where('name' , $request->name)->first();
                // $assigend_classes = new AssignedClassModel;
                // $assigend_classes->MyClass_id = $classid->id;
                // $assigend_classes->subject_id = $request->subject_id;
                // $assigend_classes->pupil_id = $request->pupil_id;
                // $assigend_classes->save();

            // return response()->json([
            //     'success'=> true,
            //     'message' =>'Class Created Successfully!!!',
            //     'class_data' => $classes,
            //     // 'assigend_classes' => $assigend_classes
            //     ], 201);
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