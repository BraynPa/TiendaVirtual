<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'api_response' => 'array'
    ];
    public function orderDetails(){
        return $this->hasMany(OrderDetail::class);
    }
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->uuid = str()->uuid();
        });
    }
}
