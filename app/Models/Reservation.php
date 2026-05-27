<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = 
    ['user_id', 
    'table_number', 
    'reservation_time', 
    'dish_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}
