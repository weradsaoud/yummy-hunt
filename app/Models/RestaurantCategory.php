<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantCategory extends Model
{
    use HasFactory;


    public function restorants()
    {
        return $this->hasMany(\App\Restorant::class);
    }
  
}
