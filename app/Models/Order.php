<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{


    protected $fillable = [
        'notes', 'customer_id', 'total_price'
    ];

    public function statusToStr(){
        switch($this->status){
            case 'H':
                return 'Holding';
            case 'P':
                return 'Preparing';
            case 'R':
                return 'Ready';
            case 'T':
                return 'Transit';
            case 'D':
                return 'Delivered';
            case 'C':
                return 'Cancelled';
        }

        return 'Unknown';
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }

    public function orderItem(){
        return $this->hasMany('App\Models\OrderItem', 'order_id');
        // return $this->hasMany('App\Models\OrderItem', 'order_id')->withTrashed();
    }
}
