<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'uuid',
    ];
}
