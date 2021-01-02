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

        return response()->json(['message' => 'User created successfully.', 'user' => $user], 200);
    }


    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        $user = User::findOrFail($id);
        $userBackUp = User::findOrFail($id);
        $user->fill($validated);

        if($request->password == null){
            $value = 0;
            $user->password = $userBackUp->password;
        }else{
            $value = 1;
            $user->password = Hash::make($request->password);
        }

        $user->save();


        return response()->json(['message' => 'User updated successfully.', 'user' => $user], 200);
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->type == 'C') {
            $customer = Customer::findOrFail($id);
            $customer->delete();
        }
        
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.', 'user' => $user], 200);
    }

    public function block($id){
        
        $user = User::findOrFail($id);
        $user->blocked = 1;

        $user->save();

        return response()->json(['message' => 'User blocked successfully.', 'user' => $user], 200);
    }
    public function unblock($id){
        $user = User::findOrFail($id);
        $user->blocked = 0;

        $user->save();

        return response()->json(['message' => 'User unblocked successfully.', 'user' => $user], 200);
    }

    public function getEmployees(){
        return UserResource::collection(User::where('type', '!=', 'C')->get());
    }
}
