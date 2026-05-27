<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $fillable = ['name', 'price', 'image'];

    public function transactions() {

        return $this->hasMany(Transaction::class);
        
    }

    public function reservations() { 

    return $this->hasMany(Reservation::class);
    
    }

}
