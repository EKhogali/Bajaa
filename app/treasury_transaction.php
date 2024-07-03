<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class treasury_transaction extends Model
{
    public function account(){
        return $this->belongsTo(account::class);
    }
    public function treasury(){
        return $this->belongsTo(treasury::class);
    }
}
