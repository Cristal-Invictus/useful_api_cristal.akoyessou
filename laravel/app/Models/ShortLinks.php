<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortLinks extends Model
{
    //

    protected $fillable = [
        'user_id',
        'original_url',
        'code',
        'clicks',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
