<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id','address', 'phone', 'nif' 
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'id')->withTrashed();
    }

    public function order(){
        return $this->hasMany('App\Models\Order', 'customer_id')->withTrashed();
    }
}
