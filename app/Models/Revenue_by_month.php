<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Revenue_by_month extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'year', 'month', 'totalRevenue' 
    ];
}
