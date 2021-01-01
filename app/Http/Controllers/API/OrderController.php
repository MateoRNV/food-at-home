<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\Order as OrderResource;

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
                return OrderResource::collection(Order::where('status', '=', $status)->get())->sortBy('current_status_at')->values()->all();
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

                return OrderResource::collection(Order::where('status', '=', $status)->where('prepared_by','=',$id)->get());
        }
        
        return response()->json(['message' => 'The id of the cook ' . $id. ' or the status '. strtoupper($status) . ' does not exist'], 404);
    }

    public function getOrderById($id){
        return OrderResource::collection(Order::where('id', '=', $id)->get());
    }

    public function setOrderStatus($id, $status){
        $order = Order::findOrFail($id);

        switch($status){
            case 'H':
            case 'P':
            case 'R':
            case 'T':
            case 'D':
            case 'C':
                $oldStatus = $order->status;
                $status = strtoupper($status);
                $order->status = $status;
                $order->save();
                return response()->json(['Order status updated. Order went from '. $oldStatus . ' to ' . $order->status, $order], 200);
        }

        return response()->json('Status ' . $status . ' doesn\'t exist', 404);
        
    }

    /*INSERT INTO `orders` 
    (`id`, `status`, `customer_id`, `notes`, `total_price`, `date`, `prepared_by`, `delivered_by`, 
    `opened_at`, `current_status_at`, `closed_at`, `preparation_time`, 
    `delivery_time`, `total_time`, `created_at`, `updated_at`) 

    VALUES ('1934', 'H', '22', NULL, '20', '2020-12-28', NULL, NULL, 
    '2020-12-28 00:00:00', '2020-12-28 00:00:00', NULL, NULL, 
    NULL, NULL, NULL, NULL);*/ 

    public function create(CreateOrderRequest $request){

        $order = new Order;

        $current_date = Carbon::now()->toDateString();
        $current_date_time = Carbon::now()->toDateTimeString();

        $order->status = 'H';

        $order->date = $current_date;
        $order->opened_at = $current_date_time;
        $order->current_status_at = $current_date_time;


        //dd($order);
        
        $order->fill($request->validated());
        
        $order->save();

       /*$productsList = $request->products;
       $size = count($productsList);
       
       $orderItemList = array();

      //  for ($i=0; $i < $size; $i++) { 
            $orderItem = new OrderItem();

            $orderItem->id = 10561;
            $orderItem->order_id = 1960;
            $orderItem->product_id = 3;//$productsList[0]["id"];
            $orderItem->quantity = 2;//$productsList[0]["quantity"];
            $orderItem->unit_price = 1.00;//$productsList[0]["price"];
            $orderItem->sub_total_price = 2.00;//$productsList[0]["totalPrice"];

            $orderItem->save();

            //array_push($orderItemList, $orderItem);

       // };*/
        
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
