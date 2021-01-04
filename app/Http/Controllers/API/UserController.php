<?php

namespace App\Http\Controllers\API;


use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Order as OrderResource;

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
            $user->password = $userBackUp->password;
        }else{
            $user->password = Hash::make($request->password);
        }

        if($request->photo_url == null){
            $user->photo_url = $userBackUp->photo_url;
        }else{
            $user->photo_url = $request->photo_url;
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
    
    public function uploadPhoto(Request $request){
        $request->validate(['photo_file' => 'image|mimes:jpeg,png,jpg']);

        $path = Storage::putFile('public/fotos', $request->file('photo_file'));

        return response()->json(['location' => '/storage/fotos/'.$request->file('photo_file')->hashName(), 
                                 'filename' => $request->file('photo_file')->hashName()], 201);
    }

    public function updatePhoto(Request $request, $id){
        $request->validate(['photo_file' => 'image|mimes:jpeg,png,jpg']);
        
        $user = User::findOrFail($id);

        // Delete previous photo
        Storage::disk('public')->delete('fotos/'.$user->photo_url);

        $path = Storage::putFile('public/fotos', $request->file('photo_file'));

        return response()->json(['location' => '/storage/fotos/'.$request->file('photo_file')->hashName(), 'filename' => $request->file('photo_file')->hashName()], 201);
    }

    public function getCurrentOrder($user){
        $user = User::findOrFail($user);

        if($user->type == 'EC'){
            return OrderResource::collection(Order::where([['status', '=', 'P'], ['prepared_by', '=', $user->id]])->get());
        }else if($user->type == 'ED'){
            return OrderResource::collection(Order::where([['status', '=', 'T'], ['delivered_by', '=', $user->id]])->get());
        }

        return response()->json(['message' => 'Type not found'], 404);
    }
}
