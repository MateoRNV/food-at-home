<?php

namespace App\Http\Controllers\API;


use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Order as OrderResource;
use App\Models\Order;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function info($id){

        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function me(Request $request){
        return $request->user();
    }

    public function create(CreateUserRequest $request){

        $user = new User;
        $validated = $request->validated();

        $user->fill($validated);
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['user' => $user], 201);
    }


    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        $user = User::findOrFail($id);
        $user->fill($validated);
        $user->save();


        return response()->json(null, 204);
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
