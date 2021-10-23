<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class LoginController extends Controller
{
    public function Signup(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',            
            'password' => 'required',            
           
        ],
        [
            'email.unique' => 'Already a Registered User'
        ]);
        if($validator->fails()){
            $messages = $validator->errors()->all();
            return response()->json(['success'=> false,'message'=>$messages]);
        }
        $user = new User([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),             
        ]);
        $user->save();
        return response()->json(["success" => true,'message' => 'User has Registered'], 200);
    }

    public function login(Request $request)
       {

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $user->save();
            $email=$user->email;
            $success=User::where('email',$email)->get();
            return response()->json([ "success" => true, "message" => "You have logged in successfully"],200);
        }
        else{
            return response()->json([ "success" => false, "message" => "This data is not Registered"]);
        }
    }
}
