<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'notes'
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
}
