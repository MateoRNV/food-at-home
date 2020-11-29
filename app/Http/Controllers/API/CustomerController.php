<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Customer as CustomerResource;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(){
        return CustomerResource::collection(Customer::all());
    }
}
