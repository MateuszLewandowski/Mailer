<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Captcha extends Model
{
    public $timestamps = false;

    protected $table = 'captcha';

    public const ADDITION = 1;

    public const SUBTRACTION = 2;

    public const DIVISION = 3;

    public const MULTIPLICATION = 4;

    protected $fillable = [
        'request_id', 'operation_id', 'x', 'y', 'result',
    ];
}
