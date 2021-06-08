<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
 /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json([
                'success'=> true,
                'message' => 'Display All The Users List',
                'data'  => $users

            ] , 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User',
            ] , 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'userid' => 'required|unique:users|numeric',
            'role' => 'required|numeric', 
            'password' =>'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'errors' => $validator->errors()
                ], 401);
        }

        try {
         $user = User::create([
                'userid' =>$request->userid,
                'fname' => $request->fname,
                'lname' => $request->lname,
                'role' => $request->role,
                'remember_token' => NULL,
                'password' => Hash::make( $request->password)
            ]);

            return response()->json([
                'success'=> true,
                'message' =>'User Created Successfully!!!',
                'data' => $user
                ], 200);
           
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' =>'Something Went Worng...!!!'
                ], 400);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($userid)
    {
        try {
            // $users = User::find($userid);
            $users = User::where('userid',$userid)->get();
            if(count($users) > 0){   
            return response()->json([
                'success'=> true,
                'message' => 'Display the specific User',
                'data'  => $users
            ] , 200);
            }
            else{
                return response()->json([
                    'success'=> false,
                    'message' => 'No User is available At that ID',
                ] , 400);
            }
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Unauthorized User !! Access Restricted!',
            ] , 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[

            //'userid' => 'required|unique:users|numeric',
            'role' => 'required|numeric', 
            'password' =>'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'errors' => $validator->errors()
                ], 401);
        }

        try {
            
             $user = User::findorfail($id);
            //$user = User::where('userid', $userid)->get();
            // dd($user);
            // $user->userid = $request->userid;
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->role = $request->role;
            $user->remember_token = NULL;
            $user->password = Hash::make( $request->password);
            $user->save();
    
            return response()->json([
                'success'=> true,
                'message' =>'User Data Updated Successfully!!!',
                'data' => $user
                ], 200);
           
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' =>'User Data Update failed!!!'
                ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userid)
    {   
        try {
            $users = User::where('userid',$userid)->delete();
            return response()->json([
                'success'=> true,
                'message' => 'User Deleted Successfully!',

            ] , 200);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Somthing Went Wrong...!!!',
            ] , 401);
        }
    }

   /**
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function login(Request $request){

        $credentials = $request->only('userid', 'password');
        $data = User::where('userid',$request->userid)->get();

        if ($token = auth()->attempt($credentials)) {
            return $this->respondWithToken($token , $data);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
      
    }

     /**
     * Get the token array structure.
     *
     * @param  string $token
     * @param  string $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token ,$data)
    {
        return response()->json([
            'success'=> true,
            'data'=> $data,
            'access_token' => $token,
            'token_type' => 'bearer',

            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

        /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }


        /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'],200);
    }

}