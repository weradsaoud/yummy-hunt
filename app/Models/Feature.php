<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    public function restorants()
    {
        return $this->belongsToMany(\App\Restorant::class, \App\Models\RestorantHasFeature::class);
    }
}
