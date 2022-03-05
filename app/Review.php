<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory;
    
    protected $dates = ['created_at','updated_at'];

    public function restorant()
    {
        return $this->belongsTo(\App\Restorant::class);
    }
    
    public function getCreatedAtAttribute($date){
        return Carbon::parse($date)->format('Y-m-d');
    }



}
