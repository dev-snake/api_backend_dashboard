<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Order extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'products',
        'methodPayment',
        'status',
        'totalOrder',
        'year',
        'month',
        "day",
        "week"
    ];
}
