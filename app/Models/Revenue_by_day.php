<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Revenue_by_day extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'date','month', 'year', 'totalRevenue' 
    ];
}
