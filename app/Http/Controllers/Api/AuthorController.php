<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function register(Request $request)
    {
        //validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:authors',
            'password' => 'required| confirmed', 
            'phone_no' => 'required'
        ]);

        //create data 
        $author = new Author();

        $author->name = $request->name;
        $author->email = $request->email;
        $author->phone_no = $request->phone_no;
        $author->password = bcrypt($request->password);
        //save data and send response
        $author->save();

        return response()->json([
            'status'=>1,
            'message'=>'successfully created'
        ]);

    }
    public function login(Request $request)
    {

      //validation
      $login_data = $request->validate([
          'email' => 'required',
          'password' => 'required'
      ]);

      //validate author data
      if(!auth()->attempt($login_data)){
          
          return response()->json([
              'status'=>0,
              'message'=>'invalid credetials'
          ]);
      }else{
      //token
      $token = auth()->user()->createToken("auth_token");
      
      //response
      return response()->json([
          'status' => 1,
          'message' => 'succefully loged in',
          'access_token' => $token
      ]);
    }

    }

    public function profile()
    {
       $user_data = auth()->user();

       return response()->json([
        'status' => true,
        'message' => 'user profile data',
        'data' => $user_data
       ]);
    }
    public function logout(Request $request)
    {
    //  get user token value
       $token = $request->user()->token();
       $token->revoke();

       return response()->json([
           'status'=>true,
           'message'=>'successfully logged out'
       ]);

    }
}

