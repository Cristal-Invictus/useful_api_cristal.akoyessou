<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserModule extends Model
{
    //

    protected $fillable =
    [
        'user_id',
        'module_id',
        'active'

    ];


    protected $table = 'user_modules';

    public $timestamps = false;


}
