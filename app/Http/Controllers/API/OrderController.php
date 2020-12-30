<?php

namespace App\Http\Controllers\API;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order as OrderResource;
use App\Http\Requests\CreateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return OrderResource::collection(Order::all());
    }

    public function getStatus($status){
        switch(strtoupper($status)){
            case 'H':
            case 'P':
            case 'R':
            case 'T':
            case 'D':
            case 'C':
                return OrderResource::collection(Order::where('status', '=', $status)->get());
        }
        
        return response()->json(['message' => 'The status ' . strtoupper($status) . ' does not exist'], 404);
    }

    public function getOrdersByCook($id, $status){
        switch(strtoupper($status)){
            case 'H':
            case 'P':
            case 'R':
            case 'T':
            case 'D':
            case 'C':

                return OrderResource::collection(Order::where('status', '=', $status)
                                                        ->where('prepared_by','=',$id)->get());
        }
        
        return response()->json(['message' => 'The id of the cook ' . $id. ' or the status '. strtoupper($status) . 
                                                ' does not exist'], 404);
    }

    public function create(CreateOrderRequest $request){

        $orderItem = new OrderItem;
        $order = new Order;
        
        $order->fill($request->validated());

        $order->save();

        try{
            $orderItem->fill($request->validated());
            $orderItem->order_id = $order->id;
            $orderItem->save();
        }catch(Throwable $error){
            $order->delete();
        }
        
        return response()->json(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
