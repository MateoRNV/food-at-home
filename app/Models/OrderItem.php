<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    protected $fillable = [
        'quantity', 'unit_price', 'subtotal_price'
    ];

    public function order(){
        return $this->belongsTo('App\Models\Order')->withTrashed();
    }
}
