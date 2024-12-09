<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'productName',
        'productPrice',
        'imageUrl',
        'storage',
        "type"
    ];
}
