<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //

    protected $fillable =
    [

    'buyer_id',
    'seller_id',
    'product_id',
    'quantity',
    'total_price',
    'status'
];

    protected $casts =
    [
        'total_price' => 'decimal:2',
    ];


    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }


    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
