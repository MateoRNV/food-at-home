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
            if(Auth::user()->blocked){
                return response()->json(['message' => 'User account is blocked. Please contact us'], 403);
            }

            // Register the time at which the user logged
            Auth::user()->logged_at = now();
            Auth::user()->available_at = now();
            Auth::user()->save();

            return Auth::user();
        }else{
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }

    public function logout(){
        // Clean user logged at (meaning it's not currently active)
        $user = Auth::guard('web')->user();
        $user->logged_at = null;
        $user->available_at = null;
        $user->save();

        Auth::guard('web')->logout();
        return response()->json(['message' => 'User session closed'], 200);
    }
}
