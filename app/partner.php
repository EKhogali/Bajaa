<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class partner extends Model
{
    public function account(){
        return $this->belongsTo(account::class);
    }
}
