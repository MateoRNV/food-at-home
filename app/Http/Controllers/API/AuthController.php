<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
            return Auth::user();
        }else{
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }

    public function logout(){
        // Auth::logout();
        Auth::guard('web')->logout();
        return response()->json(['message' => 'User session closed'], 200);
    }
}
