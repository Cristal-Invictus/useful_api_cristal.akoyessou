<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $fillable =
    [
        'user_id',
        'name',
        'price',
        'stock',
        'min_stock'
    ];


    protected $casts =
    [
        'price' => 'decimal:2',
    ];


    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
