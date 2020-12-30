<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
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
        return [
            'id' => $this->id,
            'status' => $this->status,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer->user->name,
            'notes' => $this->notes,
            'total_price' => $this->total_price,
            'date' => $this->date,
            'prepared_by' => $this->prepared_by,
            'delivered_by' => $this->delivered_by,
            'opened_at' => Carbon::parse($this->opened_at)->toTimeString(),
            'closed_at' => Carbon::parse($this->closed_at)->toTimeString(),
            'preparation_time' => $this->preparation_time,
            'delivery_time' => $this->delivery_time,
            'total_time' => $this->total_time,
            'current_status_at' => Carbon::parse($this->current_status_at)->toTimeString(),
        ];
    }
}
