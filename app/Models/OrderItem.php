<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{

    use HasFactory;

    protected $fillable = [
        'quantity', 'unit_price', 'subtotal_price', 'product_id', 'order_id'
    ];

    public function order(){
        return $this->belongsTo('App\Models\Order')->withTrashed();
    }

    public function product(){
        return $this->hasOne('App\Models\Product')->withTrashed();
    }
}
