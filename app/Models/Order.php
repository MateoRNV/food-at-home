<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{


    protected $fillable = [
        'notes', 'customer_id', 'total_price'
    ];

    public function customer(){
        return $this->belongsTo('App\Models\Customer')->withTrashed();
    }

    public function orderItem(){
        return $this->hasMany('App\Models\OrderItem', 'order_id');
    }

    public function deliveryman(){
        return $this->hasOne('App\Models\User', 'id');
    }
    
}
