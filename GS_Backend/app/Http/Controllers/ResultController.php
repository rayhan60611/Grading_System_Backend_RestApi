<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Excel;
use App\Imports\ResultImport;


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

    public function avarageGradeList()
    {
        try {
            // $avarge_grade_list_by_pupils = Result::orderBy('id' , 'desc')->avg('grade')->groupBy('pupil_id')->with('user')->get();
    
            $avarge_grade_list_by_pupils = DB::table('results')
            ->join('users', 'users.id', '=', 'results.pupil_id')
            ->select('users.userid','users.fname','users.lname','users.id', DB::raw('AVG(grade) as AvaregeGrade'))
            ->groupBy('pupil_id')
            ->get();

            return response()->json([
                'success'=> true,
                'message' => 'Display All The Test Result by Pupils Avarage Grade',
                'data'  => $avarge_grade_list_by_pupils

            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User',
            ] , 401);
        }
       
    }

    public function show( $teacher_id ,$pupil_id , $subject_id)
    {
        try {
            $specific_result = Result::where('pupil_id', $pupil_id)
            ->where('teacher_id', $teacher_id)
            ->where('subject_id', $subject_id)->with('user')->get();
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[ 
            'test_id' => 'required|numeric', 
            'teacher_id' => 'required|numeric', 
            'pupil_id' => 'required|numeric', 
            'subject_id' => 'required|numeric', 
            'grade' => 'required|numeric',         
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'errors' => $validator->errors()
                ], 422);
        }
      
        try {
                    $result = new Result;
                    $result->test_id = $request->test_id;
                    $result->teacher_id = $request->teacher_id;
                    $result->pupil_id = $request->pupil_id;
                    $result->subject_id = $request->subject_id;
                    $result->grade = $request->grade;
                    $result->save();
                return response()->json([
                    'success'=> true,
                    'message' =>'Result Created Successfully!!!',
                    'data' => $result,
                    ], 201);

        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' =>'Something Went Worng...While creating the Result!!!'
                ], 400);
        }
        
    }


    public function update(Request $request, $id)
   {  
       $validator = Validator::make($request->all(),[ 
            'test_id' => 'required|numeric', 
            'teacher_id' => 'required|numeric', 
            'pupil_id' => 'required|numeric', 
            'subject_id' => 'required|numeric', 
            'grade' => 'required|numeric',         
    ]);

    if($validator->fails()){
        return response()->json([
            'success'=> false,
            'errors' => $validator->errors()
            ], 422);
    }

       try {
           $result = Result::where('id',$id)->first();
           if(!$result)
           {
            return response()->json([
                'success'=> false,
                'message' =>'Nothing Found!!!'
                ], 404);
           }
                $result->test_id = $request->test_id;
                $result->teacher_id = $request->teacher_id;
                $result->pupil_id = $request->pupil_id;
                $result->subject_id = $request->subject_id;
                $result->grade = $request->grade;
                $result->save();

                return response()->json([
                'success'=> true,
                'message' =>'Result Updated Successfully!!!',
                'data' => $result,
                ], 200);
            }       
    
       catch (\Throwable $th) {
           return response()->json([
               'success'=> false,
               'message' =>'Result Data Update failed!!!'
               ], 401);
       }
   }


   public function delete($id)
   {   
       try {
            $result = Result::where('id', $id)->first();
           if(!$result){
            return response()->json([
                'success'=> false,
                'message' =>'Nothing Found!!!'
                ], 404);
           }
           $result->delete();
           return response()->json([
               'success'=> true,
               'message' => 'Result Deleted Successfully!',
           ] , 200);
       } 
       catch (\Throwable $th) {
           return response()->json([
               'success'=> false,
               'message' => 'Somthing Went Wrong...While deleting the Result !!!',
           ] , 401);
       }
   }
   
   
//    public function csvUpload(Request $request){

//         $upload = $request->file('csvfile');

//         $filepath = $upload->getRealPath();
//         $file = fopen($filepath ,'r');
//         $header = fgetcsv($file);

//         //trim Header
//         $escapedheader =[];

//         foreach ($header as $key => $value) {
//             $lowerCaseHeader = strtolower($value);
//             $escapeItem = preg_replace('/[^a-z_]/', '', $lowerCaseHeader );
//             array_push( $escapedheader , $escapeItem);
//         }


//         $coloum = fgetcsv($file);
//         while ($coloum){
//             if ($coloum[0] == ''){
//                 continue;
//             }

//             //trim data
//             foreach ($coloum as $key => &$value) {
//                 $value = preg_replace('/\D/' ,'' ,$value);
                
//             }
            
            
//             $data =array_combine($escapedheader , $coloum);

//             //setting data type
//             foreach ($data as $key => &$value) {
//                 $value = ($key == "grade" ? (float)$value : (integer)$value);
//             }

//             //table update
//             $test_id =$data['test_id'];
//             $teacher_id =$data['teacher_id'];
//             $pupil_id =$data['pupil_id'];
//             $subject_id =$data['subject_id'];
//             $grade =$data['grade'];

//             $result = Result::firstorNew(['test_id'=> $test_id]);
//             // $result->test_id = $test_id;
//             $result->teacher_id = $teacher_id;
//             $result->pupil_id = $pupil_id;
//             $result->subject_id = $subject_id;
//             $result->grade = $grade;
//             // dd($result);
//             // $result->save();
//             return response()->json([
//                 'success'=> true,
//                 'message' =>'Result Created Successfully!!!',
//                 'data' => $result,
//                 ], 201);
//         }

//    }


    public function Upload(Request $request){

        $request->validate([
            'file' => 'required|file|mimes:csv,txt,ods',
        ]);

        try {
      
        if($request->hasFile('file')){
            $filepath = $request->file('file')->getRealPath();

            Excel::import(new ResultImport, $request->file('file'));

            return response()->json([
                'success'=> true,
                'message' =>'CSV/ODS Result Uploded Successfully!!!',
                ], 201);

        }
    }

        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Somthing Went Wrong...While Uploading the CSV/ODS!!!',
            ] , 401);
        }
        

    }


    public function pupilIndividualAvarageGrade()
    {
        try {
            // $pupilIndividualAvarageGrade = Result::orderBy('id' , 'desc')->avg('grade')->groupBy('pupil_id')->with('user')->get();
    
            $avarge_grade_by_individual_pupils = DB::table('results')
            ->join('users', 'users.id', '=', 'results.pupil_id')
            ->select('users.userid','users.fname','users.lname','users.id', DB::raw('AVG(grade) as AvaregeGrade'))
            ->groupBy('pupil_id')
            ->get();

            return response()->json([
                'success'=> true,
                'message' => 'Display All The Test Result by Pupils Avarage Grade',
                'data'  => $avarge_grade_list_by_pupils

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


