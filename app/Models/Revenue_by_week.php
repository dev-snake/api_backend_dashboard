<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Revenue_by_week extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'year', 'week', 'totalRevenue' 
    ];
}
