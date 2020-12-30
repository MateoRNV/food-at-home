<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'quantity', 'unit_price', 'subtotal_price'
    ];

    public function order(){
        return $this->belongsTo('App\Models\Order')->withTrashed();
    }

    public function product(){
        return $this->hasOne('App\Models\Product')->withTrashed();
    }
}
