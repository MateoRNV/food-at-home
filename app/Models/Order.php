<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'status', 'note', 'total_price', 'date', 'prepared_by', 'delivered_by', 'opened_at', 'closed_at', 'current_status_at', 'preparation_time', 'delivery_time', 'total_time'
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
