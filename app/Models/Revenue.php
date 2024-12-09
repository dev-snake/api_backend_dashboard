<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Revenue extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'day',
        'month',
        'year',
        'week',
        'totalRevenue'
    ];
}
