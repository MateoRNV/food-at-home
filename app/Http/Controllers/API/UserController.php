<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function info($id){

        $user = User::findOrFail($id);

        return $user;
    }

    public function me(Request $request){
        return $request->user();
    }
}
