<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\User as UserResource;

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
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return new UserResource($user);
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
