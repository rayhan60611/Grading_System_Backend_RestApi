<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function index()
    {
        try {
            $test_list = Test::orderBy('id' , 'desc')->get();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Test List',
                'data'  => $test_list

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
            $test = test::where('name', $name)->get();
            if(count($test) > 0){   
            return response()->json([
                'success'=> true,
                'message' => 'Display the specific test by Name',
                'data'  => $test
            ] , 302);
            }
            else{
                return response()->json([
                    'success'=> false,
                    'message' => 'No test is available by that Name',
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

            // 'test_class' =>'unique:tests|string',
            'name' => 'unique:tests|required|string',
            'teacher_id' => 'required|numeric', 
            'subject_id' => 'required|numeric', 
            'test_date' =>'required|date'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'errors' => $validator->errors()
                ], 422);
        }

        try {
            
                $test = new Test;
                $test->name = $request->name;
                $test->teacher_id = $request->teacher_id;
                $test->subject_id = $request->subject_id;
                $test->test_date = $request->test_date;
                $test->save();
                return response()->json([
                    'success'=> true,
                    'message' =>'Test Created Successfully!!!',
                    'data' => $test,
                ], 201);

                

        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' =>'Something Went Worng...While creating the Test!!!'
                ], 400);
        }
        
    }

   public function update(Request $request, $id)
   {
    $validator = Validator::make($request->all(),[

        'name' => 'unique:tests|required|string',
        'teacher_id' => 'required|numeric', 
        'subject_id' => 'required|numeric', 
        'test_date' =>'required|date'
    ]);

    if($validator->fails()){
        return response()->json([
            'success'=> false,
            'errors' => $validator->errors()
            ], 422);
    }

       try {
           $test = Test::where('id',$id)->first();
           if(!$test)
           {
            return response()->json([
                'success'=> false,
                'message' =>'Nothing Found!!!'
                ], 404);
           }
                $test->name = $request->name;
                $test->teacher_id = $request->teacher_id;
                $test->subject_id = $request->subject_id;
                $test->test_date = $request->test_date;
                $test->save();

                return response()->json([
                'success'=> true,
                'message' =>'Test Updated Successfully!!!',
                'data' => $test,
                ], 200);
            }       
    
       catch (\Throwable $th) {
           return response()->json([
               'success'=> false,
               'message' =>'Test Data Update failed!!!'
               ], 401);
       }
   }

   public function delete($id)
   {   
       try 
       {
            $test = Test::where('id', $id)->first();
           if(!$test){
            return response()->json([
                'success'=> false,
                'message' =>'Nothing Found!!!'
                ], 404);
           }
           $testdata = Result::where('test_id' ,$id)->get();
            if (count($testdata) > 0) 
            {
                foreach ($testdata as $value) 
                {
                    $value->delete();
                }
                $test->delete();
                return response()->json([
                    'success'=> true,
                    'message' => 'Test Along with All Results Deleted Successfully!',
                ] , 200);
            }
            else
            {
                $test->delete();
                return response()->json([
                    'success'=> true,
                    'message' => 'Test Deleted Successfully!',
                ] , 200);

            }
           
       } 
       catch (\Throwable $th) {
           return response()->json([
               'success'=> false,
               'message' => 'Somthing Went Wrong...While deleting the Test !!!',
           ] , 401);
       }
   }   
}
