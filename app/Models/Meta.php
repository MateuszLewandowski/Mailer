<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    public $timestamps = false;

    protected $table = 'meta';

    protected $fillable = [
        'request_id', 'ip_address', 'user_agent', 'fingerprint', 'content_size',
    ];
}
