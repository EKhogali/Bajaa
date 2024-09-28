<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class estimated_expense extends Model
{
    public function account(){
        return $this->belongsTo(account::class);
    }
}
