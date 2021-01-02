<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Product;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $orderItems = $this->orderItem;

        foreach($orderItems as $item){
            $productName = Product::withTrashed()->where('id', $item->product_id)->pluck('name')->first();
            $productPhoto = Product::withTrashed()->where('id', $item->product_id)->pluck('photo_url')->first();
            $item->setAttribute('product_name', $productName);
            $item->setAttribute('product_photo', $productPhoto);
        }

        return [
            'id' => $this->id,
            'status' => $this->status,
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer->user->name,
                'address' => $this->customer->address,
                'phone' => $this->customer->phone,
                'email' => $this->customer->user->email,
                'photo_url' => $this->customer->user->photo_url,
            ],
            'items' => $orderItems,
            'notes' => $this->notes,
            'total_price' => $this->total_price,
            'date' => $this->date,
            'prepared_by' => $this->prepared_by,
            'delivered_by' => $this->delivered_by,
            'deliveryman_name' => $this->deliveryman,
            'opened_at' => Carbon::parse($this->opened_at)->toTimeString(),
            'closed_at' => Carbon::parse($this->closed_at)->toTimeString(),
            'preparation_time' => $this->preparation_time,
            'delivery_time' => $this->delivery_time,
            'total_time' => $this->total_time,
            'current_status_at' => Carbon::parse($this->current_status_at)->toTimeString(),
            // 'current_status_at' => $this->current_status_at,
        ];
    }
}
