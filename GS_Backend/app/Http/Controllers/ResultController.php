<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        try {
            $result_list = Result::orderBy('id' , 'desc')->with('user')->get();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Test Result List',
                'data'  => $result_list

            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User',
            ] , 401);
        }
       
    }



    public function show($pupil_id , $id)
    {
        try {
            $specific_result = Result::where('pupil_id', $pupil_id)->where('id', $id)->with('user')->get();
            if(count($specific_result) > 0){   
            return response()->json([
                'success'=> true,
                'message' => 'Display the specific test result by Name',
                'data'  => $specific_result
            ] , 302);
            }
            else{
                return response()->json([
                    'success'=> false,
                    'message' => 'No test result is available by that Name',
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
}


