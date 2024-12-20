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
    public function getTotal()
    {
        return $this->orderDetails->reduce(function ($carry, $item) {
            return $carry + $item->price;
        }, 0);
    }
    public function hasInvoice()
    {
        return ! is_null($this->factura_id);
    }
    public function scopeActives($query){
        return $query->whereNotNull('api_response');
    }
    public function downloadInvoice()
    {
        /*Mail::to($this->user)->send(new SendInvoice($this->user, $this));
        if ($this->pdf_file) {
            return $this->pdf_file;
        }*/
        define('INVOICE_PATH', 'storage/');

        $filePath = public_path(INVOICE_PATH . $this->pdf_file);
        return $filePath;
    }
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->uuid = str()->uuid();
        });
        static::updating(function (Order $order) {
            if ($order->isDirty('facturama_id')) {
                //Mail::to($order->user)->send(new NoticeInvoiceCreated($order->user));
            }
        });
    }
}
