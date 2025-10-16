<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSession extends Model
{
    //

    protected $fillable =
    [
        'user_id',
        'task_name',
        'start_time',
        'end_time',
        'duration'
    ];


    protected $casts =
    [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'duration'   => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
