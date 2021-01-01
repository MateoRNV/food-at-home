<?php

namespace App\Http\Controllers\API;

use Throwable;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use Faker\Provider\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\Customer as CustomerResource;
use App\Http\Resources\Order as OrderResource;

class CustomerController extends Controller
{
    public function index(){
        return CustomerResource::collection(Customer::all());
    }

     public function me($id){

         $customer = new Customer;
         $customer = Customer::findOrFail($id);

         return response()->json($customer);
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

    public function update(UpdateCustomerRequest $request, $id)
    {

        $user = new User;
        $customer = new Customer;

        $user = User::findOrFail($id);
        $customer = Customer::findOrFail($id);

        $user->type = 'C';

        $user->fill($request->validated());
        $user->password = Hash::make($request->password);
        //$user->save();


        try{
            $customer->fill($request->validated());

            $customer->save();

        }catch(Throwable $error){
            $user->getOriginal();
        }
        
        $user->save();

        return response()->json(['User updated successfully. ' . $user, 201]);
    }

    public function uploadPhoto(Request $request){
        $request->validate(['photo_file' => 'nullable|image|mimes:jpeg,png,jpg']);

        $path = Storage::putFile('public/fotos', $request->file('photo_file'));

        return response()->json(['location' => '/storage/fotos/'.$request->file('photo_file')->hashName(), 'filename' => $request->file('photo_file')->hashName()], 201);
    }

    public function getCustomerOrders($id){
        $customer = Customer::findOrFail($id);

        $orders = OrderResource::collection(Order::where('customer_id', '=', $customer->id)->get());

        return response()->json(['orders' => $orders], 200);
    }

}
