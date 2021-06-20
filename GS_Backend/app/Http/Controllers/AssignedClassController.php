<?php

namespace App\Http\Controllers;

use App\Models\AssignedClassModel;
use Illuminate\Http\Request;

class AssignedClassController extends Controller
{
    public function index()
    {
        try {
            $AssignedClassController = AssignedClassModel::orderBy('id' , 'desc')->get();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Pupil and their Class List',
                'data'  => $AssignedClassController

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
