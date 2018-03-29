<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiUserController extends Controller
{
    //
    public function login(Request $request){
        $rules = [
            'user_name'=>'required',
            'password'=>'required'
        ];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        try{
            if(!$token=JWTAuth::attempt($request->only(['user_name','password']))){
                return response()->json(['message'=>'Invalid user name or password'],401);
            }
        }catch (JWTException $e){
            return response()->json(['message'=>$e->getMessage()],500);
        }
        return response()->json(compact('token'));
    }
}
