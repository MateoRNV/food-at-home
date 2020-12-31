<?php

namespace App\Http\Controllers\API;


use App\Models\OrderItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOrderItemRequest;

class OrderItemController extends Controller
{
    public function create(CreateOrderItemRequest $request){

        $orderItem = new OrderItem;
        //$validated = $request->validated();


        //$orderItem->fill($validated);

        //$orderItem->save();
       // $orderItem->id = '1';

        return response()->json(200);
    }

}
