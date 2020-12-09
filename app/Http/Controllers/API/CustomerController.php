<?php

namespace App\Http\Controllers\API;

use Throwable;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\    Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Resources\Customer as CustomerResource;

class CustomerController extends Controller
{
    public function index(){
        return CustomerResource::collection(Customer::all());
    }

    public function create(CreateCustomerRequest $request){

        $user = new User;
        $customer = new Customer;
        
        $user->fill($request->validated());
        $user->password = Hash::make($request->password);
        $user->save();

        try{
            $customer->fill($request->validated());
            $customer->id = $user->id;
            $customer->save();
        }catch(Throwable $error){
            $user->delete();
        }
        
        return response()->json(['user' => $user, 'customer' => $customer]);
    }
}
