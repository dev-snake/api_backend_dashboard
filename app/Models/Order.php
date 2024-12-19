<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Order extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        "customerId",
        'products',
        'product_id',
        'methodPayment',
        'orderStatus',
        'totalOrder',
        'year',
        'month',
        "day",
        "week"
    ];
    protected $attributes = [
        'orderStatus' => 'pending',
    ];
    public function User()
    {
        return $this->belongsTo(User::class, 'customerId', ownerKey: 'id');
    }
}
