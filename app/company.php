<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    public function financial_year(){
        return $this->hasMany(financial_year::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
