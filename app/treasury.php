<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class treasury extends Model
{
    public function account(){
        return $this->belongsTo(account::class);
    }
}
